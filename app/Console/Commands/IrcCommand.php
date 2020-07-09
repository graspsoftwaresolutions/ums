<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use App\Mail\SendIRCMailable;
use App\Helpers\CommonHelper;

class IrcCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'irc:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send mail to hq of irc detail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $mail_data = array(
        //      'name' => $member_name,
        //      'email' => $member_email,
        //      'password' => $randompassone,
        //      'site_url' => URL::to("/"),
        //     );
        $irccount =  DB::table('irc_confirmation as irc')
                   ->select('irc.resignedmembername','m.member_number','irc.resignedmembericno as icno','irc.resignedmemberbankname as bankname','irc.resignedmemberbranchname as branchname','s.status_name')
                   ->leftjoin('membership as m','m.id','=','irc.resignedmemberno')
                   ->leftjoin('status as s','s.id','=','m.status_id')
                   ->where('irc.irc_status','=',1)
                   ->where('irc.status','=',1)
                   ->where('irc.mail_status','=',0)->count();

        if($irccount>0){
            $cc_mail = CommonHelper::getCCTestMail();
            $union_email = 'murugan.bizsoft@gmail.com';
            $status = Mail::to($union_email)->cc([$cc_mail])->send(new SendIRCMailable());
            
            if( count(Mail::failures()) == 0 ) {
                DB::table('irc_confirmation as irc')
                       ->select('irc.resignedmembername','m.member_number','irc.resignedmembericno as icno','irc.resignedmemberbankname as bankname','irc.resignedmemberbranchname as branchname','s.status_name')
                       ->leftjoin('membership as m','m.id','=','irc.resignedmemberno')
                       ->leftjoin('status as s','s.id','=','m.status_id')
                       ->where('irc.irc_status','=',1)
                       ->where('irc.status','=',1)
                       ->where('irc.mail_status','=',0)->update(['mail_status' => 1]);
            }
            //$userscount = DB::table('users')->where('id','>', 1000)->count();
            $this->info('Mail sent successfully!');
        }else{
            $this->info('No ircs to send mail!');
        }
       
    }
}

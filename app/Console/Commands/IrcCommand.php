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
        $cc_mail = CommonHelper::getCCTestMail();
        $union_email = 'murugan.bizsoft@gmail.com';
        $status = Mail::to($union_email)->cc([$cc_mail])->send(new SendIRCMailable());
        
        if( count(Mail::failures()) > 0 ) {

        }
        //$userscount = DB::table('users')->where('id','>', 1000)->count();
        $this->info('All inactive users are deleted successfully!');
    }
}

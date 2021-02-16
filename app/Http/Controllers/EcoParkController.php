<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;

use App\Model\Membership;
use DB;
use View;
use Mail;
use URL;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Log;
use Auth;
use PDF;
use Artisan;
use Facades\App\Repository\CacheMembers;
use DateTime;
use App\Imports\SubsheetImport;


class EcoParkController extends Controller
{
	public function __construct() {
		$this->middleware('auth');
        ini_set('memory_limit', -1);
		ini_set('max_execution_time', 0);
	}
	
    public function FileUpload() {
        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->slug;
        $user_id = Auth::user()->id;
      
        $data['date'] = date('Y-m-01');

        //isset($data['member_stat']) ? $data['member_stat'] : "";       
        return view('eco_park.file_upload')->with('data', $data);
    }   
	
	public function EcoParkUpdate($lang, Request $request){
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', '8000');
         $rules = array(
                    'file' => 'required|mimes:xls,xlsx',
                );
        $validator = Validator::make(Input::all(), $rules);
        if($validator->fails())
        {
            //return 1;
            return back()->withErrors($validator);
        }
        else
        {
        
            if(Input::hasFile('file')){
                $data['entry_date'] = $request->entry_date;
                $entry_date = $request->entry_date;
                $batch_type = $request->input('batch_type');

                $datearr = explode("/",$entry_date);  
                $monthname = $datearr[0];
                $year = $datearr[1];
                $full_date = date('Ymdhis',strtotime('01-'.$monthname.'-'.$year));

                $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
                $form_datefull = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year)).' '.date('h:i:s');
                $others = '';
                

                $file_name = 'ecopark_'.$full_date;
            
                $file = $request->file('file')->storeAs('ecopark', $file_name.'.xlsx'  ,'local');

                 $subsdata = (new SubsheetImport)->toArray('storage/app/ecopark/'.$file_name.'.xlsx');
                 $firstrow = $subsdata[0][2];
                 dd($subsdata[0]);
                 //dd($firstrow);
                
                if($firstrow[0]!='No.' || $firstrow[1]!='Privilege Card NO'){
                    return  redirect('en/ecopark/fileupload')->with('error', 'Wrong excel sheet');
                }

                $subscription_qry = DB::table('tdf_date')->where('Date','=',$form_date)->where('company_id','=',$sub_company);
                $subscription_count = $subscription_qry->count();
                if($subscription_count>0){
                    $subscription_month = $subscription_qry->get();
                    $date_auto_id = $subscription_month[0]->id;
                }else{
                    $subscription_month = [];
                    $subscription_month['Date'] = $form_date;
                    $subscription_month['company_id'] = $sub_company;
                    $subscription_month['created_by'] = Auth::user()->id;
                    $subscription_month['created_on'] = date('Y-m-d');
                    
                    $date_auto_id = DB::table('tdf_date')->insertGetId($subscription_month);
                }

                $firstsheet = $subsdata[0];
                $bulkedata = [];
                for ($i=3; $i < count($firstsheet); $i++) { 
                    if($firstsheet[$i][1]!=''){
                        $memberno = $firstsheet[$i][1];
                        //dd($memberno);
                        $membername = $firstsheet[$i][2];
                        $memberic = $firstsheet[$i][3];
                        $amount = $firstsheet[$i][4];
                        $chequeno = $firstsheet[$i][5];
                        $paiddate = $firstsheet[$i][6];

                        $paydate = '';

                        if($paiddate!=''){
                          $UNIX_DATE = ($paiddate - 25569) * 86400;
                          $paydate = gmdate("Y-m-d", $UNIX_DATE);
                        }

                        $insertdata = [];
                        $insertdata['member_number'] = $memberno;
                        $insertdata['paid_date'] = $paydate;
                        $insertdata['tdf_date_id'] = $date_auto_id;
                        $insertdata['name'] = $membername;
                        $insertdata['amount'] = $amount;
                        $insertdata['icno'] = $memberic;
                        $insertdata['cheque_no'] = $chequeno;
                        $insertdata['status'] = 0;
                        $insertdata['created_by'] = Auth::user()->id;
                        $insertdata['created_at'] = date('Y-m-d h:i:s');

                        $bulkedata[] = $insertdata;

                    }
                }
                $savesal = DB::table('tdf_updation_temp')->insert($bulkedata);
                //dd($bulkedata);
                return redirect($lang.'/latesttdf_process?date='.strtotime($form_datefull))->with('message','Tdf file uploaded successfully');

            }else{
                return redirect($lang.'/tdf_upload')->with('message','please select file');
            }
            
        }
    }
}

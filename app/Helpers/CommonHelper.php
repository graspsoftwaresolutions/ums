<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use DB;
use Carbon\Carbon;
use App\Model\FormType;
use App\Model\AppForm;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Reason;
use App\Model\UnionBranch;
use App\Model\CompanyBranch;
use App\Model\Membership;
use App\Model\MonthlySubscriptionMember;
use App\Model\Company;
use App\Model\Status;
use App\Model\MonthlySubscriptionCompany;
use App\Model\Irc;
use App\Model\Resignation;
use App\Model\Fee;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Log;
use Facades\App\Repository\CacheMonthEnd;
use DateTime;

class CommonHelper
{
	public function __construct() {
        $this->membermonthendstatus_table = "membermonthendstatus";
    }
	
    public static function encryption(string $string)
    {
        return strtoupper($string);
    } 
	
	public static function decryption(string $string)
    {
        return strtoupper($string);
    }
	
	public static function random_password($length,$alpha=false)
    {
		$random = str_shuffle('1234567890');
		if($alpha){
			$random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ1234567890!$%^&!$%^&');
		}
		
		$password = substr($random, 0, 10);

        return $password;
    }

    public static function get_member_status_name($status_id){
       $status_data = DB::table('status')->where('status.id','=',$status_id)->pluck('status_name')->first();
       return $status_data;
    }

    public static function get_branch_company_id($branch_id){
        $company_data = DB::table('company_branch')->where('company_branch.id','=',$branch_id)->pluck('company_id');
        if(!empty($company_data)){
            return $company_data[0];
        }
        return '';
     }

     public static function calculate_age($userDob){
        return $years = Carbon::parse($userDob)->age;
     }

     public static function get_relationship_name($relationship_id){
		if($relationship_id!=""){
			$status_data = DB::table('relation')->where('relation.id','=',$relationship_id)->pluck('relation_name');
			if(!empty($status_data)){
				return $status_data[0];
			}
		}
        return '';
     }

     public static function get_fee_name($fee_id){
        $status_data = DB::table('fee')->where('fee.id','=',$fee_id)->pluck('fee_name');
        if(!empty($status_data)){
            return $status_data[0];
        }
        return '';
     }

     public static function convert_date_database($date){
        $date_array = explode("/",$date);           							
        $date_format = $date_array[2]."-".$date_array[1]."-".$date_array[0];
        $converted_date = date('Y-m-d', strtotime($date_format));
        return $converted_date;
     }

    public static function convert_date_datepicker($date){
        return date('d/M/Y', strtotime($date));
    }

    public static function get_auto_member_number(){
        $last_no = DB::table('membership')->orderBy('member_number', 'desc')->limit(1)->pluck('member_number');
       
        if(count($last_no)>0){
            $last_no =  $last_no[0];
            return is_numeric($last_no) ? $last_no+1 : 1000;
        }
        return 1000;
    }

    public static function get_company_branch($company_id){
        $branch_list = DB::table('company_branch')->where('company_id', $company_id)->get();
       
        return $branch_list;
    }

    public static function getaccountStatus($user_id){
       $status_data = DB::table('membership')->where('user_id', $user_id)->pluck('status_id');
        if(count($status_data)>0){
            return $status_data[0];
        }
        return '';
    }

    public static function getStatus()
    {
        $status = DB::table('status')->where('status','=','1')->pluck('status_name');
        return $status;
    }


    public static function getapprovedStatus($user_id){
        $status_data = DB::table('membership')->where('user_id', $user_id)->pluck('is_request_approved');
         if(count($status_data)>0){
             return $status_data[0];
         }
         return '';
     }

    public static function getloginmemberStatus($user_id){
         $status_data = DB::table('membership as m')
         ->leftjoin('status as s','s.id','=','m.status_id')
         ->where('m.user_id', $user_id)->pluck('s.status_name')->first();
         
         return $status_data;
     }

    
    public static function get_branch_by_unionbranch($branch_id){
        DB::connection()->enableQueryLog();
        $branch_list = DB::table('company_branch')->where('union_branch_id', $branch_id)->get();
        //$queries = DB::getQueryLog();
        // dd($queries);
        return $branch_list;
    }
	
	public static function getFormTypes($status=false){
		return $status==false ? FormType::all()->orderBy('orderno', 'ASC') :  FormType::where('status',$status)->orderBy('orderno', 'ASC')->get();
	}
	
	public static function getSubForms($formtype_id){
		$appforms = FormType::find($formtype_id)->appforms;
		return $appforms;
    }
    
	public static function getSingleMenus(){
		$appforms = AppForm::where('formtype_id',NULL)->orderBy('orderno', 'ASC')->get();
		return $appforms;
    }
    
    public static function getFormTypeName($type_id){
        $form_name = FormType::where('id',$type_id)->pluck('formname');
        if(count($form_name)>0){
            return $form_name[0];
        }
        return '';
    }
    public static function getStatusName($statusid){
        $status_data = DB::table('status')->where('id', $statusid)->pluck('status_name');
         if(count($status_data)>0){
             return $status_data[0];
         }
         return '';
     }
     public static function getExistingCountry($countryname)
     {
         $country_exists = Country::where('country_name','=',$countryname)->count();    
         return  $country_exists;
     }

     public static function DefaultCountry()
     {
         return 130;
     }
	 
	 public static function getUnionBranchID($user_id){
		 $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id');
		 if(count($union_branch_id)>0){
				return $union_branch_id[0];
		 }
		 return false;
	 }
	 
	 public static function getUnionCompanyList($union_branch_id){
		$rawQuery = "SELECT c.id, c.company_name from company_branch as b left join company as c on b.company_id=c.id where b.union_branch_id=$union_branch_id ";
		//DB::select( DB::raw('set sql_mode='''));
		return $results = DB::table('company_branch as b')
							->selectRaw('c.id, c.company_name')
							->join('company as c','c.id','=','b.company_id')
							->where('b.union_branch_id','=',$union_branch_id)
							->groupBy('c.id')
							->get();
	 }
	 
	 public static function getCompanyID($user_id){
		 $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id');
		 if(count($company_id)>0){
			return $company_id[0];
		 }
		 return false;
	 }
	 
	 public static function getCompanyList($companyid){
		//$rawQuery = "SELECT c.id, c.company_name from company_branch as b left join company as c on b.company_id=c.id where b.union_branch_id=$union_branch_id ";
		//DB::select( DB::raw('set sql_mode='''));
		return $results = Company::where('id',$companyid)->get();
     }
     
     public static function getCompanyBranchList($companyid,$branchid=false,$unionbranchid=false){
		//$rawQuery = "SELECT c.id, c.company_name from company_branch as b left join company as c on b.company_id=c.id where b.union_branch_id=$union_branch_id ";
		//DB::select( DB::raw('set sql_mode='''));
        $companyBranch = CompanyBranch::where('company_id',$companyid);
        if($branchid!=false && $branchid!=''){
            $companyBranch->where('id',$branchid);
        }
        if($unionbranchid!=false && $unionbranchid!=''){
            $companyBranch->where('union_branch_id',$unionbranchid);
        }
        $companyBranch->where('status',1);
                              
        return  $companyBranch->get();
     }
     
     public static function getCompanyBranchID($user_id){
        $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id');
        if(count($branch_id)>0){
               return $branch_id[0];
        }
        return false;
    }

    public static function checkModuleAccess($module){
		$access_count = 0;
		if(auth()->check()) :
		$get_roles = auth()->user()->roles; 
		$login_role = $get_roles[0]->id;
		//$module = str_replace("'", "", $module);
		$project_modules = DB::table('form_type')->where('status',1)->where('module',$module)->get(); 
			$role_models = DB::table('roles_modules')->where('role_id',$login_role)->get();
			if(!empty($project_modules)) :
				$module_id =$project_modules[0]->id;
				$access_count = DB::table('roles_modules')->where('role_id',$login_role)->where('module_id',$module_id)->count();
			endif; 
		endif;
		return $access_count;
	}
	
	public static function getBranchName($branchid){
		$branch_name = CompanyBranch::where('id',$branchid)->pluck('branch_name');
        if(count($branch_name)>0){
            return $branch_name[0];
        }
        return false;
    }
    
    public static function getStateList($countryid){
        return DB::table('state')->select('id','state_name')->where('status','=','1')->where('country_id','=',$countryid)->get();
    }

    public static function getCompanyListAll(){
		return $results = Company::where('status',1)->get();
    }

    public static function getComapnyName($companyid){
		$company_name = Company::where('id',$companyid)->pluck('company_name');
        if(count($company_name)>0){
            return $company_name[0];
        }
        return false;
    }
    public static function getCCTestMail(){
        $ccmail = env("MAIL_CC",'membership@gmail.com');
        return $ccmail;
    }
	
	public static function getmemberid_bycode($membercode){
		$autoid = Membership::where('member_number',$membercode)->pluck('id');
        if(count($autoid)>0){
            return $autoid[0];
        }
        return false;
    }
	
	public static function getcompanyidbyBranchid($branchid){
		$company_id = CompanyBranch::where('id',$branchid)->pluck('company_id');
        if(count($company_id)>0){
            return $company_id[0];
        }
        return false;
    }
	
	public static function getcompanyidOfsubscribeCompanyid($companyid){
		$company_id = MonthlySubscriptionCompany::where('id',$companyid)->pluck('CompanyCode');
        if(count($company_id)>0){
            return $company_id[0];
        }
        return false;
    }
	
	public static function statusMembersCount($status_id, $user_role, $user_id){
		if($user_role=='union'){
			$members_count = DB::table('membership as m')->where('status_id','=',$status_id)->count();
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_count = DB::table('membership as m')
								->join('company_branch as c','c.id','=','m.branch_id')
								->where('c.union_branch_id','=',$union_branch_id)
								->where('status_id','=',$status_id)->count();
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
        }
		return $members_count;
    }
    
    public static function mastersMembersCount($table,$autoid,$user_role,$user_id)
    {
        $members_count=0;
        //dd($country_id);
        if($user_role=='union')
        {
            if($table=="country"){
                $members_count = DB::table('membership as m')->where('country_id','=',$autoid)->count();
				//Log::debug('An informational message.');
            }
            else if($table=="state")
            {
                $members_count = DB::table('membership as m')
                    ->where('state_id','=',$autoid)->count();
            }
            else if($table=="city")
            {
                $members_count = DB::table('membership as m')
                    ->where('city_id','=',$autoid)->count();
            }
            else if($table=="company")
            {
                $members_count = DB::table('membership as m')
                ->join('company_branch as cb','cb.id','=','m.branch_id')
                ->leftjoin('company as c','c.id','=','cb.company_id')
                ->where('cb.company_id','=',$autoid)->count();
            }
            else if($table=="company_branch")
            {
                $members_count = DB::table('membership as m')
                ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                ->leftjoin('company as c','c.id','=','cb.company_id')
                ->where('cb.id','=',$autoid)->count();
            }
            else if($table=="designation")
            {
                $members_count = DB::table('membership as m')
                    ->where('designation_id','=',$autoid)->count();
            }
            else if($table=="race")
            {
                $members_count = DB::table('membership as m')
                    ->where('race_id','=',$autoid)->count();
            }
            else if($table=="fee")
            {
                $members_count = DB::table('membership as m')
                    ->leftjoin('member_fee as f','m.id','=','f.member_id')
                    ->where('f.fee_id','=',$autoid)->count();
            }
            else if($table=="reason")
            {
                $members_count = DB::table('membership as m')
                ->leftjoin('irc_confirmation as irc','m.id','=','irc.resignedmemberno')
                ->leftjoin('reason as r','r.id','=','irc.resignedreason')
                ->where('r.id','=',$autoid)->count();
            }
            else if($table=="union_branch")
            {
              //  echo "hiii";die;
                $members_count = DB::table('membership as m')
                 ->join('company_branch as c','c.id','=','m.branch_id')
                                 ->where('c.union_branch_id','=',$autoid)->count();
            }
        }
        else if($user_role=='union-branch')
        {
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            if($table=="country"){   
                $members_count = DB::table('membership as m')
                                    ->join('company_branch as c','c.id','=','m.branch_id')
                                    ->where('c.union_branch_id','=',$union_branch_id)
                                    ->where('m.country_id','=',$autoid)->count();
            }
            else if($table=="state")
            {
                $members_count = DB::table('membership as m')
                                    ->join('company_branch as c','c.id','=','m.branch_id')
                                    ->where('c.union_branch_id','=',$union_branch_id)
                                    ->where('m.state_id','=',$autoid)->count();
            }
            else if($table=="city")
            {
                $members_count = DB::table('membership as m')
                                    ->join('company_branch as c','c.id','=','m.branch_id')
                                    ->where('c.union_branch_id','=',$union_branch_id)
                                    ->where('m.city_id','=',$autoid)->count();
            }
            else if($table=="company")
            {
                $members_count = DB::table('membership as m')
                                    ->join('company_branch as c','c.id','=','m.branch_id')
                                    ->where('c.union_branch_id','=',$union_branch_id)
                                    ->where('c.company_id','=',$autoid)->count();
            }
            else if($table=="company_branch")
            {
                $members_count = DB::table('membership as m')
                                ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                                ->leftjoin('company as c','c.id','=','cb.company_id')
                                ->where('cb.union_branch_id','=',$union_branch_id)
                                ->where('cb.id','=',$autoid)->count();
            }
            else if($table=="designation")
            {
                $members_count = DB::table('membership as m')
                        ->join('company_branch as c','c.id','=','m.branch_id')
                        ->where('c.union_branch_id','=',$union_branch_id)
                        ->where('m.designation_id','=',$autoid)->count();
            }
            else if($table=="race")
            {
                $members_count = DB::table('membership as m')
                        ->join('company_branch as c','c.id','=','m.branch_id')
                        ->where('c.union_branch_id','=',$union_branch_id)
                        ->where('m.race_id','=',$autoid)->count();
            }
            else if($table=="fee")
            {
                $members_count = DB::table('membership as m')
                        ->join('company_branch as c','c.id','=','m.branch_id')
                        ->leftjoin('member_fee as f','m.id','=','f.member_id')                     
                        ->where('c.union_branch_id','=',$union_branch_id)
                        ->where('f.fee_id','=',$autoid)->count();
            }
            else if($table=="reason")
            {
                $members_count = DB::table('membership as m')
                ->join('company_branch as c','c.id','=','m.branch_id')
                ->leftjoin('irc_confirmation as irc','m.id','=','irc.resignedmemberno')
                ->leftjoin('reason as r','r.id','=','irc.resignedreason')
                ->where('c.union_branch_id','=',$union_branch_id)
                ->where('r.id','=',$autoid)->count();
            }
        }
        else if($user_role=='company')
        {
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            if($table=="country"){
                $members_count = DB::table('membership as m')
                ->join('company_branch as cb','cb.id','=','m.branch_id')
                ->leftjoin('company as c','c.id','=','cb.company_id')
                ->where('cb.company_id','=',$company_id)
                ->where('m.country_id','=',$autoid)->count();
            } 
            else if($table=="state")
            {
                $members_count = DB::table('membership as m')
                        ->join('company_branch as cb','cb.id','=','m.branch_id')
                        ->leftjoin('company as c','c.id','=','cb.company_id')
                        ->where('cb.company_id','=',$company_id)
                        ->where('m.state_id','=',$autoid)->count();
            }
            else if($table=="city")
            {
                $members_count = DB::table('membership as m')
                        ->join('company_branch as cb','cb.id','=','m.branch_id')
                        ->leftjoin('company as c','c.id','=','cb.company_id')
                        ->where('cb.company_id','=',$company_id)
                        ->where('m.city','=',$autoid)->count();
            }
            else if($table=="company")
            {
                $members_count = DB::table('membership as m')
                        ->join('company_branch as cb','cb.id','=','m.branch_id')
                        ->leftjoin('company as c','c.id','=','cb.company_id')
                        ->where('cb.company_id','=',$company_id)
                        ->where('cb.company_id','=',$autoid)->count();
            }
            else if($table=="company_branch")
            {
                $members_count = DB::table('membership as m')
                        ->join('company_branch as cb','cb.id','=','m.branch_id')
                        ->leftjoin('company as c','c.id','=','cb.company_id')
                        ->where('cb.company_id','=',$company_id)
                        ->where('cb.id','=',$autoid)->count();
            }
            else if($table=="designation")
            {
                $members_count = DB::table('membership as m')
                ->join('company_branch as cb','cb.id','=','m.branch_id')
                ->leftjoin('company as c','c.id','=','cb.company_id')
                ->where('cb.company_id','=',$company_id)
                ->where('m.designation_id','=',$autoid)->count();
            }
            else if($table=="race")
            {
                $members_count = DB::table('membership as m')
                ->join('company_branch as cb','cb.id','=','m.branch_id')
                ->leftjoin('company as c','c.id','=','cb.company_id')
                ->where('cb.company_id','=',$company_id)
                ->where('m.race_id','=',$autoid)->count();
            }
            else if($table=="fee")
            {
                $members_count = DB::table('membership as m')
                    ->leftjoin('member_fee as f','m.id','=','f.member_id')
                    ->join('company_branch as cb','cb.id','=','m.branch_id')
                    ->leftjoin('company as c','c.id','=','cb.company_id')
                    ->where('cb.company_id','=',$company_id)
                    ->where('f.fee_id','=',$autoid)->count();
            } 
            else if($table=="reason")
            {
                $members_count = DB::table('membership as m')
                ->join('company_branch as cb','cb.id','=','m.branch_id')
                ->leftjoin('company as c','c.id','=','cb.company_id')
                ->leftjoin('irc_confirmation as irc','m.id','=','irc.resignedmemberno')
                ->leftjoin('reason as r','r.id','=','irc.resignedreason')
                ->where('cb.company_id','=',$company_id)
                ->where('r.id','=',$autoid)->count();
            }   
        }
        else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();

            if($table=="country"){
            $members_count = DB::table('membership as m')
                            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id') 
                            ->where('cb.id','=',$branch_id)
                            ->where('m.country_id','=',$autoid)->count();
            }
            else if($table=="state")
            {
                $members_count = DB::table('membership as m')
                            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id') 
                            ->where('cb.id','=',$branch_id)
                            ->where('m.state_id','=',$autoid)->count();
            }
            else if($table=="city")
            {
                $members_count = DB::table('membership as m')
                            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id') 
                            ->where('cb.id','=',$branch_id)
                            ->where('m.city','=',$autoid)->count();
            }
            else if($table=="company")
            {
                $members_count = DB::table('membership as m')
                        ->join('company_branch as cb','cb.id','=','m.branch_id')
                        ->leftjoin('company as c','c.id','=','cb.company_id')
                        ->where('cb.id','=',$branch_id)
                        ->where('cb.company_id','=',$autoid)->count();
            }
            else if($table=="company_branch")
            {
                $members_count = DB::table('membership as m')
                ->join('company_branch as cb','cb.id','=','m.branch_id')
                ->leftjoin('company as c','c.id','=','cb.company_id')
                ->where('cb.id','=',$branch_id)
                ->where('cb.id','=',$autoid)->count(); 
            }
            else if($table=="designation")
            {
                $members_count = DB::table('membership as m')
                            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id') 
                            ->where('cb.id','=',$branch_id)
                            ->where('m.designation_id','=',$autoid)->count();
            }
            else if($table=="race")
            {
                $members_count = DB::table('membership as m')
                ->leftjoin('company_branch as cb','cb.id','=','m.branch_id') 
                ->where('cb.id','=',$branch_id)
                ->where('m.race_id','=',$autoid)->count();
            }
            else if($table=="fee")
            {
                $members_count = DB::table('membership as m')
                ->leftjoin('member_fee as f','m.id','=','f.member_id')
                ->leftjoin('company_branch as cb','cb.id','=','m.branch_id') 
                ->where('cb.id','=',$branch_id)
                ->where('f.fee_id','=',$autoid)->count();
            }
            else if($table=="reason")
            {
                $members_count = DB::table('membership as m')
                                ->leftjoin('company_branch as cb','cb.id','=','m.branch_id') 
                                ->leftjoin('irc_confirmation as irc','m.id','=','irc.resignedmemberno')
                                ->leftjoin('reason as r','r.id','=','irc.resignedreason')
                                ->where('cb.id','=',$branch_id)
                                ->where('r.id','=',$autoid)->count();               
            }
        }
        return $members_count;
    }
	
	public static function statusSubsMembersCount($status_id, $user_role, $user_id, $monthyear=false){
		if($monthyear==false){
			$monthyear=date('Y-m-01');
		}
		if($user_role=='union'){
			$members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
        }
		return $members_count;
	}

    public static function statusSubsMembersNotDojCount($status_id, $user_role, $user_id, $monthyear=false){
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }

     public static function statusSubsMembersNotDojActCount($status_id, $user_role, $user_id, $monthyear=false){
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND sc.banktype<>1 AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND sc.banktype<>1 AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }
	
	public static function statusMembersAmount($status_id, $user_role, $user_id, $monthyear=false){
		if($monthyear==false){
			$monthyear=date('Y-m-01');
		}
		if($user_role=='union'){
			$members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'"'));
			$members_amount = $members_qry[0]->amount;
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_amount = $members_qry[0]->amount;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_amount = $members_qry[0]->amount;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_amount = $members_qry[0]->amount;
        }
		return $members_amount;
    }

    public static function statusMembersNotDojAmount($status_id, $user_role, $user_id, $monthyear=false){
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_amount = $members_qry[0]->amount;
        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_amount = $members_qry[0]->amount;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_amount = $members_qry[0]->amount;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_amount = $members_qry[0]->amount;
        }
        return $members_amount;
    }

    public static function statusMembersNotDojActAmount($status_id, $user_role, $user_id, $monthyear=false){
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND sc.banktype<>1 AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_amount = $members_qry[0]->amount;
        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_amount = $members_qry[0]->amount;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_amount = $members_qry[0]->amount;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_amount = $members_qry[0]->amount;
        }
        return $members_amount;
    }
    
    public static function statusSubsMembersCompanyCount($status_id, $user_role, $user_id,$company_id,$monthyear=false)
    {
        if($monthyear==false){
			$monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }
        else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }
		return $members_count;
    }

    public static function statusSubsMembersNotDOJCompanyCount($status_id, $user_role, $user_id,$company_id,$monthyear=false)
    {
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }
        else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $scompany_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$scompany_id.'") AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
           
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }

    public static function statusMembersCompanyAmount($status_id, $user_role, $user_id,$company_id, $monthyear=false)
    {
        if($monthyear==false){
			$monthyear=date('Y-m-01');
        }

        if($user_role=='union'){
			$members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'" '));
            $members_amount = $members_qry[0]->amount;
        }
        else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_amount = $members_qry[0]->amount;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_amount = $members_qry[0]->amount;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where m.StatusId="'.$status_id.'" AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_amount = $members_qry[0]->amount;
        }
        return $members_amount;
    }

    public static function statusMembersNotDojCompanyAmount($status_id, $user_role, $user_id,$company_id, $monthyear=false)
    {
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }

        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'" '));
            $members_amount = $members_qry[0]->amount;
        }
        else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_amount = $members_qry[0]->amount;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_amount = $members_qry[0]->amount;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select ifnull(sum(m.Amount),0) as amount from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` where m.StatusId="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_amount = $members_qry[0]->amount;
        }
        return $members_amount;
    }
	
	public static function statusSubsMatchCount($status_id, $user_role, $user_id, $monthyear=false){
		if($monthyear==false){
			$monthyear=date('Y-m-01');
		}
		if($user_role=='union'){
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
        }
		return $members_count;
    }

    public static function statusSubsMatchNotDojCount($status_id, $user_role, $user_id, $monthyear=false){
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }

    public static function statusSubsMatchNotDojActCount($status_id, $user_role, $user_id, $monthyear=false){
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND mc.banktype<>1 AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND mc.banktype<>1 AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }

    public static function statusSubsMatchNotApprovalCount($status_id, $user_role, $user_id,$status, $monthyear=false){
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($status==1){
            $statuscond='mm.approval_status = 1';
        }else{
            $statuscond='( mm.approval_status != 1 OR mm.approval_status IS NULL)';
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
            
        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }

    public static function statusSubsMatchNotApprovalActCount($status_id, $user_role, $user_id,$status, $monthyear=false){
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($status==1){
            $statuscond='mm.approval_status = 1';
        }else{
            $statuscond='( mm.approval_status != 1 OR mm.approval_status IS NULL)';
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND mc.banktype<>1 AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
            
        }else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND mc.banktype<>1 AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }

	public static function statusSubsMatchApprovalCount($status_id, $user_role, $user_id,$status, $monthyear=false){
		if($monthyear==false){
			$monthyear=date('Y-m-01');
		}
		if($status==1){
			$statuscond='mm.approval_status = 1';
		}else{
			$statuscond='( mm.approval_status != 1 OR mm.approval_status IS NULL)';
		}
		if($user_role=='union'){
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
			
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->count;
        }
		return $members_count;
    }
    public static function statusSubsCompanyMatchCount($status_id, $user_role, $user_id,$company_id, $monthyear=false)
    {
        if($monthyear==false){
			$monthyear=date('Y-m-01');
		}
		if($user_role=='union'){
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'"  AND m.MonthlySubscriptionCompanyId = "'.$company_id.'" '));
			$members_count = $members_qry[0]->count;
        }
        else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->count;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->count;
        }
        return $members_count;
    }

     public static function statusSubsCompanyMatchNotDojCount($status_id, $user_role, $user_id,$company_id, $monthyear=false)
    {
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND m.MonthlySubscriptionCompanyId = "'.$company_id.'" '));
            $members_count = $members_qry[0]->count;
        }
        else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }
	 public static function statusSubsCompanyMatchApprovalCount($status_id, $user_role, $user_id,$company_id,$status, $monthyear=false)
    {
        if($monthyear==false){
			$monthyear=date('Y-m-01');
		}
		if($status==1){
			$statuscond='mm.approval_status = 1';
		}else{
			$statuscond='( mm.approval_status != 1 OR mm.approval_status IS NULL)';
		}
		if($user_role=='union'){
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'" AND '.$statuscond.' AND m.MonthlySubscriptionCompanyId = "'.$company_id.'" '));
			$members_count = $members_qry[0]->count;
        }
        else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'"  AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->count;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'"  AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'"  AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->count;
        }
        return $members_count;
    }
     public static function statusSubsCompanyMatchApprovalNotCount($status_id, $user_role, $user_id,$company_id,$status, $monthyear=false)
    {
        if($monthyear==false){
            $monthyear=date('Y-m-01');
        }
        if($status==1){
            $statuscond='mm.approval_status = 1';
        }else{
            $statuscond='( mm.approval_status != 1 OR mm.approval_status IS NULL)';
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'" AND '.$statuscond.' AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND m.MonthlySubscriptionCompanyId = "'.$company_id.'" '));
            $members_count = $members_qry[0]->count;
        }
        else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'"  AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'"  AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('SELECT count(*) as count FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` LEFT JOIN `membership` AS `member` ON `member`.`id` = `m`.`MemberCode` WHERE mm.match_id="'.$status_id.'"  AND '.$statuscond.' AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND `sm`.`Date` <> DATE_FORMAT(member.doj, "%Y-%m-01") AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }
        return $members_count;
    }
	public static function statusSubsMatchAmount($status_id, $user_role, $user_id, $monthyear=false){
		if($monthyear==false){
			$monthyear=date('Y-m-01');
		}
		if($user_role=='union'){
			$members_qry = DB::select(DB::raw('SELECT ifnull(sum(m.Amount),0) as amount FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->amount;
		}else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT ifnull(sum(m.Amount),0) as amount FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->amount;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('SELECT ifnull(sum(m.Amount),0) as amount FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->amount;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT ifnull(sum(m.Amount),0) as amount FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'"'));
			$members_count = $members_qry[0]->amount;
        }
		return $members_count;
    }
	public static function statusSubsCompanyMatchAmount($status_id, $user_role, $user_id,$company_id, $monthyear=false)
    {
        if($monthyear==false){
			$monthyear=date('Y-m-01');
		}
		if($user_role=='union'){
			$members_qry = DB::select(DB::raw('SELECT ifnull(sum(m.Amount),0) as amount FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND `sm`.`Date`="'.$monthyear.'"  AND m.MonthlySubscriptionCompanyId = "'.$company_id.'" '));
			$members_count = $members_qry[0]->amount;
        }
        else if($user_role=='union-branch'){
			$union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT ifnull(sum(m.Amount),0) as amount FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->amount;
		}else if($user_role=='company'){
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_qry = DB::select(DB::raw('SELECT ifnull(sum(m.Amount),0) as amount FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->amount;
        }else if($user_role=='company-branch'){
			$branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			$members_qry = DB::select(DB::raw('SELECT ifnull(sum(m.Amount),0) as amount FROM `mon_sub_member_match` as mm left join `mon_sub_member` as m on m.id=mm.mon_sub_member_id left join mon_sub_company as mc on mc.id=m.MonthlySubscriptionCompanyId left join `mon_sub` as `sm` on `sm`.`id` = `mc`.`MonthlySubscriptionId` WHERE mm.match_id="'.$status_id.'" AND mc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			$members_count = $members_qry[0]->amount;
        }
        return $members_count;
    }
   
    public static function getOverallDue($memberid, $doj, $subscriptionamt){
        //DB::connection()->enableQueryLog();
        $to = Carbon::createFromFormat('Y-m-d H:s:i', date('Y-m-d H:i:s'));
        $from = Carbon::createFromFormat('Y-m-d H:s:i', $doj.date('H:i:s'));
        $diff_in_months = $to->diffInMonths($from);
        $overall_pay = $diff_in_months*$subscriptionamt;
       
        $overall_amt = MonthlySubscriptionMember::select(DB::raw('ifnull(SUM(Amount),0) as amount'))->where('MemberCode','=',$memberid)->get();
        //$queries = DB::getQueryLog();
        $paid_amt = $overall_amt[0]->amount;
        return round($overall_pay-$paid_amt);
    }

    public static function getSubscriptionAmt($memberid){
        return MonthlySubscriptionMember::select('Amount')->where('MemberCode','=',$memberid)->orderBY('id','asc')->first();
    }
	
	public static function getCountryName($countryid){
		return $country_name = Country::find($countryid)->country_name;
    }
	public static function getstateName($stateid){
		return $state_name = State::find($stateid)->state_name;
    }
	public static function getcityName($cityid){
		return $city_name = City::find($cityid)->city_name;
    }
	public static function getCompanyName($companyid){
		$company_name = Company::where('id',$companyid)->pluck('company_name');
        if(count($company_name)>0){
            return $company_name[0];
        }
        return false;
    }
	
	public static function ChecklastTranfer($histry_id,$memberid){
		
		$commonselect = DB::table('member_transfer_history as h')->select('h.id')
						->where('h.MemberCode','=',$memberid)
						->OrderBy('transfer_date','desc')
						->limit(1)->first();
		if(!empty($commonselect)){
			if($commonselect->id == $histry_id){
				return 1;
			}
		}
		return 0;
	}
	
	public static function get_irc_confirmation_status($memberid){
        $company_data = DB::table('irc_confirmation')->where('resignedmemberno','=',$memberid)->pluck('status');
        if(count($company_data)>0){
            return $company_data[0];
        }
        return '';
    }
	public static function getIrcDataByMember($memberid){
		$irc = Irc::where('resignedmemberno', '=' ,$memberid)->first();
		return $irc;
    }

    public static function getmembercode_byid($id){
		$autoid = Membership::where('id',$id)->pluck('member_number');
        if(count($autoid)>0){
            return $autoid[0];
        }
        return false;
    }
    public static function getdesignationname($id)
    {
        $designation_name = DB::table('membership as m')
                            ->leftjoin('designation as d','m.designation_id','=','d.id' )
                            ->where('m.id','=',$id)
                            ->pluck('d.designation_name');
       
        if(count($designation_name)>0){
            return $designation_name[0];
        }
        return false;
    }
    public static function getircreason_byid($id){
       // echo $id; die;
		 $reasonname = Reason::where('id',$id)->pluck('reason_name');
        if(count($reasonname)>0){
            return $reasonname[0];
        }
        return false;
    }
	public static function getResignDataByMember($memberid){
		$resign = Resignation::where('member_code', '=' ,$memberid)->first();
		return $resign;
    }

	public static function getlastMonthEndByMember($memberid){
		$lastrecord =  DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$memberid)
						->OrderBy('ms.StatusMonth','desc')->OrderBy('ms.arrear_status','desc')->first();
		return $lastrecord;
    }

	public static function getResignData(){
		$reason = Reason::where('status','=',1)->get();
		return $reason;
    }
	public static function getLogo()
	{
		$get_logo = UnionBranch::where('is_head','=','1')->pluck('logo');
		if(!empty($get_logo[0]->logo)){
            return $get_logo[0];
        }
		else{
			$get_logo = 'logo.png';
			return $get_logo;
		}
	}
	public static function ConvertdatetoDBFormat($date){
		if($date!=""){
			$date = str_replace('/', '-', $date );
			$new_date = date("Y-m-d", strtotime($date));
		}else{
			$new_date = '0000-00-00';
		}
		return $new_date;
    }
	
	public static function getBankCode($branchid){
		$company_id = CompanyBranch::where('id',$branchid)->pluck('company_id')->first();
		$shortcode = Company::where('id','=',$company_id)->pluck('short_code')->first();
        
        return $shortcode;
    }
	
	public static function getStatusList()
    {
        $status = status::where('status','=','1')->get();
        return $status;
    }
	
	public static function get_member_match_name($status_id){
       $status_data = DB::table('mon_sub_match_table')->where('id','=',$status_id)->pluck('match_name')->first();
       return $status_data;
    }
	
	public static function getmemberName($memberid){
      return $status_data = DB::table('membership')->where('id', $memberid)->pluck('name')->first();
       
    }
	public static function getStatusColor($statusid){
        return $status_data = DB::table('status')->where('id', $statusid)->pluck('font_color')->first();
    }
	public static function getUserName($userid){
        return $status_data = DB::table('users')->where('id', $userid)->pluck('name')->first();
    }
	public static function getCompanyIDbyMemberID($memberid){
		return $bank_name = DB::table('membership as m')->where('m.id', $memberid)
						->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
						->leftjoin('company as c','c.id','=','cb.company_id')
						->pluck('c.company_name')->first();
	 }
	public static function getCompanyIDbySubMemberID($memberid){
		return $bank_name = DB::table('mon_sub_member as m')->where('m.id', $memberid)
						->leftjoin('mon_sub_company as sc','sc.id','=','m.MonthlySubscriptionCompanyId')
						->leftjoin('company as c','c.id','=','sc.CompanyCode')
						->pluck('c.company_name')->first();
	}
	public static function getCompanyAutoIDbyMemberID($memberid){
		return $bank_name = DB::table('membership as m')->where('m.id', $memberid)
						->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
						->leftjoin('company as c','c.id','=','cb.company_id')
						->pluck('cb.company_id')->first();
	 }
	public static function getCompanyAutoIDbySubMemberID($memberid){
		return $bank_name = DB::table('mon_sub_member as m')->where('m.id', $memberid)
						->leftjoin('mon_sub_company as sc','sc.id','=','m.MonthlySubscriptionCompanyId')
						->leftjoin('company as c','c.id','=','sc.CompanyCode')
						->pluck('c.id')->first();
	}
	
	public static function get_member_match_id($autoid){
       $status_data = DB::table('mon_sub_member_match')->where('id','=',$autoid)->pluck('match_id')->first();
       return $status_data;
    }
	
	public static function getMonthlyPaidCount($company_id, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		
		$count = DB::table('mon_sub_member as sm')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id')
								->where('mc.CompanyCode','=',$company_id)
								->where(DB::raw('month(ms.Date)'),'=',$month)
								->where(DB::raw('year(ms.Date)'),'=',$year)
								->count();
		return $count;
    }
	
	public static function statusSubsMembersCompanyTotalCount($user_role, $user_id,$company_id,$monthyear=false)
    {
        if($monthyear==false){
			$monthyear=date('Y-m-01');
        }
        if($user_role=='union'){
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
			
			$members_count = $members_qry[0]->count;
        }
        else if($user_role=='union-branch'){
            $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.union_branch_id="'.$union_branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company'){
            $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.company_id="'.$company_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }else if($user_role=='company-branch'){
            $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
            $members_qry = DB::select(DB::raw('select count(m.id) as count from `mon_sub_member` as `m` left join `mon_sub_company` as `sc` on `sc`.`id` = `m`.`MonthlySubscriptionCompanyId` left join `mon_sub` as `sm` on `sm`.`id` = `sc`.`MonthlySubscriptionId` where sc.CompanyCode in (select company_id from `company_branch` as `cb` where cb.id="'.$branch_id.'") AND `sm`.`Date`="'.$monthyear.'" AND m.MonthlySubscriptionCompanyId = "'.$company_id.'"'));
            $members_count = $members_qry[0]->count;
        }
		return $members_count;
    }
	public static function get_gender_race_count($raceid,$branchid,$status_active,$month_year,$gender)
    {
	 
		// $month_year  = '';
		//$month_year = '1964-05-01';
		$monthno = '';
        $yearno = '';
        if($month_year!=""){
			$fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }else{		
			$monthno = date('m');
			$yearno = date('Y');
		}
		
		$male_count = CacheMonthEnd::get_gender_race_count($raceid,$branchid,$status_active,$month_year,$gender);
		/* $male_count = DB::table('membermonthendstatus as ms')
						->leftjoin('membership as m','m.branch_id','=','ms.BRANCH_CODE')
						//->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
						//->leftjoin('race as r','m.race_id','=','r.id')
						->where('m.race_id','=',$raceid)
						->where('ms.BRANCH_CODE','=',$branchid)
						->where('m.gender','=',$gender)
						->where(DB::raw('month(ms.StatusMonth)'),'=',$monthno)  
						->where(DB::raw('year(ms.StatusMonth)'),'=',$yearno)  
						->where('ms.STATUS_CODE','=',$status_active)
						->count(); */
		
       /*  $male_count = DB::table('membership as m')->select('m.gender','m.doj')
                    ->leftjoin('race as r','m.race_id','=','r.id')
					->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
					->leftjoin('status as s','s.id','=','m.status_id')
                    ->where('m.gender','=','Male');
					       

     
        $male_count =  $male_count->where('r.id','=',$raceid)
					->where('cb.branch_shortcode','=',$branchid)
					->where(DB::raw('month(m.doj)'),'=',$monthno)  
					->where(DB::raw('year(m.doj)'),'=',$yearno)  
					->where('s.status_name','=',$status_active)
                    //->dump()   
                    ->count(); */
        return $male_count;
		

    }

    public static function get_all_gender_race_count($branchid,$month_year){
        $monthno = '';
        $yearno = '';
        if($month_year!=""){
            $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }else{      
            $monthno = date('m');
            $yearno = date('Y');
        }
        
        $male_count = CacheMonthEnd::get_all_gender_race_count($branchid,$month_year);
        return $male_count;
    }

    public static function group_all_gender_race_count($total_data,$branchid,$from_month_year,$to_month_year){
       //dd($total_data);
        $total_count = 0;
        $form_branch = array();
        foreach ($total_data as $key => $value) {
            if($value->branchid==$branchid){
                $form_branch[] = $value;
            }
           
        }
        return $form_branch;
        //return $male_count;
    }

    public static function group_all_union_gender_race_count($total_data,$branchid,$month_year){
       //dd($total_data);
        $total_count = 0;
        $form_branch = array();
        foreach ($total_data as $key => $value) {
            if($value->branchid==$branchid){
                $form_branch[] = $value;
            }
           
        }
        return $form_branch;
        //return $male_count;
    }
	
	public static function get_union_gender_race_count($raceid,$branchid,$status_active,$month_year,$gender){
		$monthno = '';
        $yearno = '';
        if($month_year!=""){
			$fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }else{		
			$monthno = date('m');
			$yearno = date('Y');
		}
		
		
		$male_count = CacheMonthEnd::get_union_gender_race_count($raceid,$branchid,$status_active,$month_year,$gender);
		return $male_count;
	}
	public static function get_female_gender_race_count($raceid,$branchshortcode,$status_active,$month_year)
    {
 
		 //$month_year = $request->input('month_year');
		//$month_year = '1964-05-01';
		$month_year  = '';
		$monthno = '';
        $yearno = '';
        if($month_year!=""){
			$fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }else{		
			$monthno = date('m');
			$yearno = date('Y');
		}
		
        $female_count = DB::table('membership as m')->select('m.gender','m.doj')
                    ->leftjoin('race as r','m.race_id','=','r.id')
					->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
					->leftjoin('status as s','s.id','=','m.status_id')
                    ->where('m.gender','=','Female');
					       
        $female_count =  $female_count->where('r.id','=',$raceid)
					->where('cb.branch_shortcode','=',$branchshortcode)
					->where(DB::raw('month(m.doj)'),'=',$monthno)  
					->where(DB::raw('year(m.doj)'),'=',$yearno) 
					->where('s.status_name','=',$status_active)
                    //->dump()   
                    ->count();
        return $female_count;	
    }
	public static function get_male_gender_race_count_defaulter($raceid,$branchshortcode,$status_defaulter,$month_year)
	{
		 //$month_year = $request->input('month_year');
		//$month_year = '1964-05-01';
		$month_year  = '';
		$monthno = '';
        $yearno = '';
        if($month_year!=""){
			$fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }else{		
			$monthno = date('m');
			$yearno = date('Y');
		}
		
        $maledefaulter_count = DB::table('membership as m')->select('m.gender','m.doj')
                    ->leftjoin('race as r','m.race_id','=','r.id')
					->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
					->leftjoin('status as s','s.id','=','m.status_id')
                    ->where('m.gender','=','Male');			       

        $maledefaulter_count =  $maledefaulter_count->where('r.id','=',$raceid)
					->where('cb.branch_shortcode','=',$branchshortcode)
					->where(DB::raw('month(m.doj)'),'=',$monthno)  
					->where(DB::raw('year(m.doj)'),'=',$yearno)  
					->where('s.status_name','=',$status_defaulter)
                    //->dump()   
                    ->count();
        return $maledefaulter_count;	
	}
	public static function get_female_gender_race_count_defaulter($raceid,$branchshortcode,$status_defaulter,$month_year)
	{
		 //$month_year = $request->input('month_year');
		//$month_year = '1964-05-01';
		$month_year  = '';
		$monthno = '';
        $yearno = '';
        if($month_year!=""){
			$fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }else{		
			$monthno = date('m');
			$yearno = date('Y');
		}
		
        $femaledefaulter_count = DB::table('membership as m')->select('m.gender','m.doj')
                    ->leftjoin('race as r','m.race_id','=','r.id')
					->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
					->leftjoin('status as s','s.id','=','m.status_id')
                    ->where('m.gender','=','Female');			       


        $femaledefaulter_count =  $femaledefaulter_count->where('r.id','=',$raceid)
					->where('cb.branch_shortcode','=',$branchshortcode)
					->where(DB::raw('month(m.doj)'),'=',$monthno)  
					->where(DB::raw('year(m.doj)'),'=',$yearno)  
					->where('s.status_name','=',$status_defaulter)
                    //->dump()   
                    ->count();
        return $femaledefaulter_count;	
	}
	public static function subCompanyMembersCount($company_enc_id, $user_role, $user_id,$date)
	{
		// return $date;
		if($date!=''){
		  $fmmm_date = explode("/",$date);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
		else{
			$date=date('Y-m-01');
		}
		$members_count=0;
        //dd($country_id);
		$company_auto_id = Crypt::decrypt($company_enc_id);
       /*  if($user_role=='union')
        {
			
			
			$members_count	=DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
							'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','status.status_name as statusId','status.font_color','mon_sub_member.created_by','m.branch_id','m.member_number as member_number')
							->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
							->join('company','company.id','=','mon_sub_company.CompanyCode')
							->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
							->leftjoin('status','mon_sub_member.StatusId','=','status.id')
							->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('race as r','r.id','=','m.race_id')
							->leftjoin('designation as d','d.id','=','m.designation_id')
						   ->where('mon_sub_company.id','=',$company_auto_id)
							->where(DB::raw('month(mon_sub.Date)'),'=',$monthno)  
							->where(DB::raw('year(mon_sub.Date)'),'=',$yearno)  
						   ->count();
		}
		else if($user_role=='union-branch')
		{
			 $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			 $members_count	=DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
							'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','status.status_name as statusId','status.font_color','mon_sub_member.created_by','m.branch_id','m.member_number as member_number')
							->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
							->join('company','company.id','=','mon_sub_company.CompanyCode')
							->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
							->leftjoin('status','mon_sub_member.StatusId','=','status.id')
							->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('race as r','r.id','=','m.race_id')
							->leftjoin('designation as d','d.id','=','m.designation_id')
						   ->where('mon_sub_company.id','=',$company_auto_id)
							->where(DB::raw('month(mon_sub.Date)'),'=',$monthno)  
							->where(DB::raw('year(mon_sub.Date)'),'=',$yearno)  
							->where('cb.union_branch_id','=',$union_branch_id)
						   ->count();
		}
		elseif($user_role=='company')
		{
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_count	=DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
							'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','status.status_name as statusId','status.font_color','mon_sub_member.created_by','m.branch_id','m.member_number as member_number')
							->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
							->join('company','company.id','=','mon_sub_company.CompanyCode')
							->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
							->leftjoin('status','mon_sub_member.StatusId','=','status.id')
							->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('race as r','r.id','=','m.race_id')
							->leftjoin('designation as d','d.id','=','m.designation_id')
						   ->where('mon_sub_company.id','=',$company_auto_id)
							->where(DB::raw('month(mon_sub.Date)'),'=',$monthno)  
							->where(DB::raw('year(mon_sub.Date)'),'=',$yearno) 
							->where('cb.company_id','=',$company_id)
						   ->count();
		}
		else if($user_role=='company-branch')
		{
			$company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			$members_count	=DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
							'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','status.status_name as statusId','status.font_color','mon_sub_member.created_by','m.branch_id','m.member_number as member_number')
							->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
							->join('company','company.id','=','mon_sub_company.CompanyCode')
							->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
							->leftjoin('status','mon_sub_member.StatusId','=','status.id')
							->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('race as r','r.id','=','m.race_id')
							->leftjoin('designation as d','d.id','=','m.designation_id')
						   ->where('mon_sub_company.id','=',$company_auto_id)
							->where(DB::raw('month(mon_sub.Date)'),'=',$monthno)  
							->where(DB::raw('year(mon_sub.Date)'),'=',$yearno)  
							->where('cb.company_id','=',$company_id)
						   ->count();
		} */
		$members_count	=DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
							'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','status.status_name as statusId','status.font_color','mon_sub_member.created_by','m.branch_id','m.member_number as member_number')
							->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
							->join('company','company.id','=','mon_sub_company.CompanyCode')
							->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
							->leftjoin('status','mon_sub_member.StatusId','=','status.id')
							->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
							->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
							->leftjoin('race as r','r.id','=','m.race_id')
							->leftjoin('designation as d','d.id','=','m.designation_id')
						   ->where('mon_sub_company.id','=',$company_auto_id)
							->where(DB::raw('month(mon_sub.Date)'),'=',$monthno)  
							->where(DB::raw('year(mon_sub.Date)'),'=',$yearno)  
						   ->count();
		return $members_count;
	}

    public static function subCompanyMembersNotDojCount($company_enc_id, $user_role, $user_id,$date)
    {
        // return $date;
        if($date!=''){
          $fmmm_date = explode("/",$date);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
        else{
            $date=date('Y-m-01');
        }
        $members_count=0;
        //dd($country_id);
        $company_auto_id = Crypt::decrypt($company_enc_id);
       
        $members_count  =DB::table('mon_sub')->select('mon_sub.id','mon_sub.Date','mon_sub_company.MonthlySubscriptionId',
                            'mon_sub_company.CompanyCode','company.company_name','company.id','mon_sub_member.Name','mon_sub_member.membercode','mon_sub_member.nric','mon_sub_member.amount','status.status_name as statusId','status.font_color','mon_sub_member.created_by','m.branch_id','m.member_number as member_number')
                            ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
                            ->join('company','company.id','=','mon_sub_company.CompanyCode')
                            ->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
                            ->leftjoin('status','mon_sub_member.StatusId','=','status.id')
                            ->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
                            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                            ->leftjoin('race as r','r.id','=','m.race_id')
                            ->leftjoin('designation as d','d.id','=','m.designation_id')
                           ->where('mon_sub_company.id','=',$company_auto_id)
                            ->where('mon_sub.Date','!=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
                            ->where(DB::raw('month(mon_sub.Date)'),'=',$monthno)  
                            ->where(DB::raw('year(mon_sub.Date)'),'=',$yearno)  
                           ->count();
        return $members_count;
    }

    public static function subCompanyMembersActCount($company_enc_id, $user_role, $user_id,$date)
    {
        // return $date;
        if($date!=''){
          $fmmm_date = explode("/",$date);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
        else{
            $date=date('Y-m-01');
        }
        $members_count=0;
        //dd($country_id);
        $company_auto_id = Crypt::decrypt($company_enc_id);
     
        $members_count  =DB::table('mon_sub')->select('mon_sub.id')
                            ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
                           // ->join('company','company.id','=','mon_sub_company.CompanyCode')
                            ->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
                            //->leftjoin('status','mon_sub_member.StatusId','=','status.id')
                            //->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
                            //->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                            //->leftjoin('race as r','r.id','=','m.race_id')
                            //->leftjoin('designation as d','d.id','=','m.designation_id')
                           ->where('mon_sub_company.id','=',$company_auto_id)
                           ->where('mon_sub_member.StatusId','<=',2)
                            ->where(DB::raw('month(mon_sub.Date)'),'=',$monthno)  
                            ->where(DB::raw('year(mon_sub.Date)'),'=',$yearno)  
                           ->count();
        return $members_count;
    }

     public static function subCompanyMembersNotDOJActCount($company_enc_id, $user_role, $user_id,$date)
    {
        // return $date;
        if($date!=''){
          $fmmm_date = explode("/",$date);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
        else{
            $date=date('Y-m-01');
        }
        $members_count=0;
        //dd($country_id);
        $company_auto_id = Crypt::decrypt($company_enc_id);
     
        $members_count  =DB::table('mon_sub')->select('mon_sub.id')
                            ->join('mon_sub_company', 'mon_sub.id' ,'=','mon_sub_company.MonthlySubscriptionId')
                           // ->join('company','company.id','=','mon_sub_company.CompanyCode')
                            ->join('mon_sub_member','mon_sub_company.id','=','mon_sub_member.MonthlySubscriptionCompanyId')
                            //->leftjoin('status','mon_sub_member.StatusId','=','status.id')
                            ->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
                            //->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                            //->leftjoin('race as r','r.id','=','m.race_id')
                            //->leftjoin('designation as d','d.id','=','m.designation_id')
                           ->where('mon_sub_company.id','=',$company_auto_id)
                           ->where('mon_sub.Date','!=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
                           ->where('mon_sub_member.StatusId','<=',2)
                            ->where(DB::raw('month(mon_sub.Date)'),'=',$monthno)  
                            ->where(DB::raw('year(mon_sub.Date)'),'=',$yearno)  
                           ->count();
        return $members_count;
    }

    public static function subCompanyMembersAmount($company_enc_id, $user_role, $user_id,$date)
    {
        // return $date;
        if($date!=''){
          $fmmm_date = explode("/",$date);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
        else{
            $date=date('Y-m-01');
        }
        $members_amt=0;
        //dd($country_id);
        $company_auto_id = Crypt::decrypt($company_enc_id);
      
        $members_amt  =DB::table('mon_sub as ms')->select(DB::raw('sum(mm.Amount) as amount'))
                            ->join('mon_sub_company as mc', 'ms.id' ,'=','mc.MonthlySubscriptionId')
                            //->join('company','company.id','=','mon_sub_company.CompanyCode')
                            ->join('mon_sub_member as mm','mc.id','=','mm.MonthlySubscriptionCompanyId')
                            //->leftjoin('status','mon_sub_member.StatusId','=','status.id')
                            //->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
                            //->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                            //->leftjoin('race as r','r.id','=','m.race_id')
                            //->leftjoin('designation as d','d.id','=','m.designation_id')
                           ->where('mc.id','=',$company_auto_id)
                            ->where(DB::raw('month(ms.Date)'),'=',$monthno)  
                            ->where(DB::raw('year(ms.Date)'),'=',$yearno)  
                           ->first();
        return $members_amt->amount;
    }


    public static function subCompanyMembersNotDojAmount($company_enc_id, $user_role, $user_id,$date)
    {
        // return $date;
        if($date!=''){
          $fmmm_date = explode("/",$date);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
        else{
            $date=date('Y-m-01');
        }
        $members_amt=0;
        //dd($country_id);
        $company_auto_id = Crypt::decrypt($company_enc_id);
      
        $members_amt  =DB::table('mon_sub as ms')->select(DB::raw('sum(mm.Amount) as amount'))
                            ->join('mon_sub_company as mc', 'ms.id' ,'=','mc.MonthlySubscriptionId')
                            //->join('company','company.id','=','mon_sub_company.CompanyCode')
                            ->join('mon_sub_member as mm','mc.id','=','mm.MonthlySubscriptionCompanyId')
                            //->leftjoin('status','mon_sub_member.StatusId','=','status.id')
                            ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                            //->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                            //->leftjoin('race as r','r.id','=','m.race_id')
                            //->leftjoin('designation as d','d.id','=','m.designation_id')
                            ->where('ms.Date','!=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
                           ->where('mc.id','=',$company_auto_id)
                            ->where(DB::raw('month(ms.Date)'),'=',$monthno)  
                            ->where(DB::raw('year(ms.Date)'),'=',$yearno)  
                           ->first();
        return $members_amt->amount;
    }

    public static function subCompanyMembersActAmount($company_enc_id, $user_role, $user_id,$date)
    {
        // return $date;
        if($date!=''){
          $fmmm_date = explode("/",$date);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
        else{
            $date=date('Y-m-01');
        }
        $members_amt=0;
        //dd($country_id);
        $company_auto_id = Crypt::decrypt($company_enc_id);
      
        $members_amt  =DB::table('mon_sub as ms')->select(DB::raw('sum(mm.Amount) as amount'))
                            ->join('mon_sub_company as mc', 'ms.id' ,'=','mc.MonthlySubscriptionId')
                            //->join('company','company.id','=','mon_sub_company.CompanyCode')
                            ->join('mon_sub_member as mm','mc.id','=','mm.MonthlySubscriptionCompanyId')
                            //->leftjoin('status','mon_sub_member.StatusId','=','status.id')
                            //->leftjoin('membership as m','m.id','=','mon_sub_member.MemberCode')
                            //->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                            //->leftjoin('race as r','r.id','=','m.race_id')
                            //->leftjoin('designation as d','d.id','=','m.designation_id')
                            ->where('mc.id','=',$company_auto_id)
                             ->where('mm.StatusId','<=',2)
                            //->where('mc.banktype','<>',1)
                            ->where(DB::raw('month(ms.Date)'),'=',$monthno)  
                            ->where(DB::raw('year(ms.Date)'),'=',$yearno)  
                           ->first();
        return $members_amt->amount;
    }

    public static function subCompanyMembersNotDojActAmount($company_enc_id, $user_role, $user_id,$date)
    {
        // return $date;
        if($date!=''){
          $fmmm_date = explode("/",$date);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }
        else{
            $date=date('Y-m-01');
        }
        $members_amt=0;
        //dd($country_id);
        $company_auto_id = Crypt::decrypt($company_enc_id);
      
        $members_amt  =DB::table('mon_sub as ms')->select(DB::raw('sum(mm.Amount) as amount'))
                            ->join('mon_sub_company as mc', 'ms.id' ,'=','mc.MonthlySubscriptionId')
                            //->join('company','company.id','=','mon_sub_company.CompanyCode')
                            ->join('mon_sub_member as mm','mc.id','=','mm.MonthlySubscriptionCompanyId')
                            //->leftjoin('status','mon_sub_member.StatusId','=','status.id')
                            ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                            //->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
                            //->leftjoin('race as r','r.id','=','m.race_id')
                            //->leftjoin('designation as d','d.id','=','m.designation_id')
                            ->where('mc.id','=',$company_auto_id)
                             ->where('mm.StatusId','<=',2)
                            //->where('mc.banktype','<>',1)
                            ->where('ms.Date','!=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
                            ->where(DB::raw('month(ms.Date)'),'=',$monthno)  
                            ->where(DB::raw('year(ms.Date)'),'=',$yearno)  
                           ->first();
        return $members_amt->amount;
    }
	
	public static function get_overall_approval_status($submemberid){
		$total_member_count = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$submemberid)->count();
		$approved_match_count = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$submemberid)->where('approval_status','=',1)->count();
		return $total_member_count==$approved_match_count ? '1' : '0';
	}
	public static function getmemberBranchid($memberid){
      return $status_data = DB::table('membership')->where('id', $memberid)->pluck('branch_id')->first();
       
    }
	
	public static function getLastMonthlyPaidCount($company_id, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		$last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
		
		$members = DB::table('mon_sub_member as sm')->select('sm.MemberCode')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id')
								->where('mc.CompanyCode','=',$company_id)
								->where(DB::raw('month(ms.Date)'),'=',$month)
								->where(DB::raw('year(ms.Date)'),'=',$year)
								->get();
		$count = 0;						
		foreach($members as $member){
			$memebr_id = $member->MemberCode;
			$old_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$memebr_id)
							->where('ms.Date','=',$last_month)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			if($old_subscription_count==0){
				$count++;
			}
		}	
		return $count;
	}
	
	public static function getcurrentMonthlyPaidCount($company_id, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		$last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
		$last_month_no = date("m", strtotime($last_month));
		$last_year_no = date("Y", strtotime($last_month));
		
		$members = DB::table('mon_sub_member as sm')->select('sm.MemberCode')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id')
								->where('mc.CompanyCode','=',$company_id)
								->where(DB::raw('month(ms.Date)'),'=',$last_month_no)
								->where(DB::raw('year(ms.Date)'),'=',$last_year_no)
								->get();
		$count = 0;						
		foreach($members as $member){
			$memebr_id = $member->MemberCode;
			$current_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$memebr_id)
							->where(DB::raw('month(ms.Date)'),'=',$month)
							->where(DB::raw('year(ms.Date)'),'=',$year)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			if($current_subscription_count==0){
				$count++;
			}
		}	
		return $count;
	}
	 public static function getIRCVariable(){
        $irc_val = env("IRC",false);
        return $irc_val;
    }
	
	public static function getCompanyMembers($type_id,$date,$type){
        $members = CacheMonthEnd::getVariationMembers($type_id,$date,$type);
		// if($date==""){
		// 	$date = date('Y-m-01');
		// }
		// $month = date("m", strtotime($date));
		// $year = date("Y", strtotime($date));
		
		// $subscription_qry = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE')
		// 			->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
		// 			->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
		// 			->leftjoin('membership as m','m.id','=','mm.MemberCode')
		// 			->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
		// 			->leftjoin('company as c','cb.company_id','=','c.id')
		// 			->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
		// 			->where('mm.update_status', '=', 1)
		// 			->where('mm.MemberCode', '!=', Null)
		// 			->where(DB::raw('month(ms.Date)'),'=',$month)
		// 			->where(DB::raw('year(ms.Date)'),'=',$year);
		
		/* $subscription_qry = DB::table("membermonthendstatus as ms")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.LASTPAYMENTDATE as LASTPAYMENTDATE','ms.SUBSCRIPTION_AMOUNT as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE')
								->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
								//->where(DB::raw('ms.BANK_CODE'),'=',$id)
								->where(DB::raw('month(ms.StatusMonth)'),'=',$month)
								->where(DB::raw('year(ms.StatusMonth)'),'=',$year);
								//->limit(10)
								//->dump()
								//->get(); */
		// if($type==1){
		// 	$subscription_qry = $subscription_qry->where('cb.union_branch_id','=',$type_id);
		// }else if($type==2){
		// 	$subscription_qry = $subscription_qry->where('cb.company_id','=',$type_id);
		// }
		// else{
		// 	$subscription_qry = $subscription_qry->where('m.branch_id','=',$type_id);
		// }
		// $subscriptions = $subscription_qry->get();		
		return $members;
	}
	
	public static function getMonthEndPaidCount($type_id, $date, $type){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		
		$query = DB::table("mon_sub_member as mm")->select('cb.union_branch_id as union_branchid','u.union_branch as union_branch_name')
					->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
					->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
					->leftjoin('membership as m','m.id','=','mm.MemberCode')
					->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
					->leftjoin('company as c','cb.company_id','=','c.id')
					->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
					->where(DB::raw('month(ms.Date)'),'=',$month)
					->where(DB::raw('year(ms.Date)'),'=',$year);
		if($type==1){
			$query = $query->where('cb.union_branch_id','=',$type_id);
		}else if($type==2){
			$query = $query->where('cb.company_id','=',$type_id);
		}
		else{
			$query = $query->where('m.branch_id','=',$type_id);
		}
		$count = $query->count();	
		
		/* $query = DB::table("membermonthendstatus as mm")->select('mm.id')
                                ->leftjoin('company as c','mm.BANK_CODE','=','c.id')
								->where(DB::raw('month(mm.StatusMonth)'),'=',$month)
								->where(DB::raw('year(mm.StatusMonth)'),'=',$year);
		if($type==1){
			$query = $query->where('mm.NUBE_BRANCH_CODE','=',$type_id);
		}else if($type==2){
			$query = $query->where('mm.BANK_CODE','=',$type_id);
		}
		else{
			$query = $query->where('mm.BRANCH_CODE','=',$type_id);
		}
		$count = $query->count();			 */	
	
		return $count;
    }
	
	public static function getCompanyPaidSubs($bank_id,$member_id,$date){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		$subscriptions = DB::table("mon_sub_member as mm")->select('mm.Amount as SUBSCRIPTION_AMOUNT')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								//->leftjoin('membership as m','m.id','=','mm.MemberCode')
								//->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								//->leftjoin('company as c','cb.company_id','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
								->where(DB::raw('month(ms.Date)'),'=',$month)
								->where(DB::raw('year(ms.Date)'),'=',$year)
								->where('mm.update_status', '=', 1)
								->where('mm.MemberCode', '=', $member_id)
								->first();
		/* $subscriptions = DB::table("membermonthendstatus as ms")->select('ms.SUBSCRIPTION_AMOUNT as SUBSCRIPTION_AMOUNT')
								->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
								//->where(DB::raw('ms.BANK_CODE'),'=',$id)
								->where(DB::raw('ms.MEMBER_CODE'),'=',$member_id)
								->where(DB::raw('month(ms.StatusMonth)'),'=',$month)
								->where(DB::raw('year(ms.StatusMonth)'),'=',$year)
								->limit(1)
								//->dump()
								->first(); */
								//dd($subscriptions);
		if($subscriptions!=Null){
			return $subscriptions->SUBSCRIPTION_AMOUNT;
		}else{
			return '*';
		}
	}
	
	public static function getMonthDifference($fromdate,$todate){
		$date1 = strtotime($fromdate." 00:00:00");  
		$date2 = strtotime($todate." 00:00:00");  
		$diff_sign = $date2 - $date1;  
		$months = 0;
		if($diff_sign>0){
			$diff = abs($date2 - $date1);  
			$years = floor($diff / (365*60*60*24));  
			$months = floor(($diff - $years * 365*60*60*24) 
								   / (30*60*60*24));
		}
		
		/* $date = Carbon::parse($fromdate.' 01:00:00');
		$now = Carbon::parse($todate.' 01:00:00');

		$diff = $date->diffInMonths($now); */
		return $months;
	}
	
	public static function getLastPayDate($memberid, $date){
		if($date==""){
			return '';
		}else{
			$curr_date = date("Y-m-01", strtotime($date));
			$subscription_last = DB::table("mon_sub_member as mm")->select('ms.Date as pay_date')
						->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
						->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
						->leftjoin('membership as m','m.id','=','mm.MemberCode')
						//->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
						//->leftjoin('company as c','cb.company_id','=','c.id')
						//->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
						->where('mm.update_status', '=', 1)
						->where('mm.MemberCode', '=', $memberid)
						->where('ms.Date','<',$curr_date)
						->orderBY('ms.Date', 'desc')
						->limit(1)
						//->dump()
						->first();
			if(!empty($subscription_last)){
				return $subscription_last->pay_date;
			}else{
				return '';
			}
			
		}
	}
	
	public static function get_status_idbyname($statusname){
		$status = DB::table("status")->where('status_name', '=', $statusname)->pluck('id')->first();
		return $status;
	}
	
	public static function getMontendcompanySummary($companies,$date){
		$members = CacheMonthEnd::getMontendcompanyGroup($companies,$date);
		
		return $members;
	}

    public static function getTotalMembersSummary($companies,$date){
        $monthno = date('m',strtotime($date));
        $yearno = date('Y',strtotime($date));

        $members = DB::table('mon_sub_member as mm')
                    ->select(DB::raw("count(mm.id) as total_members"))
                    ->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
                    ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                    ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                    //->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    //->leftjoin('company as com','com.id','=','sc.CompanyCode')
                    ->where(DB::raw('DATE_FORMAT(ms.Date, "%m-%Y")'), '=', $monthno.'-'.$yearno)
                    ->where('ms.Date','!=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
                    ->where(function ($query) {
                        $query->where('mm.StatusId', '=', 1)
                              ->orWhere('mm.StatusId', '=', 2)
                              ->orWhere('mm.StatusId', '=', 3)
                              ->orWhere('mm.StatusId', '=', 4);
                    })
                    ->whereIn('sc.CompanyCode', $companies)
                    //->where('mm.approval_status', '=', 1)
                    ->whereNull('mm.additional_member')
                    ->where('mm.update_status', '=', 1)
                    //->dump()
                    ->first();

       // $members = CacheMonthEnd::getMontendcompanyGroup($companies,$date);
        
        return $members->total_members;
    }

    public static function getTotalNewMembersSummary($companies,$date){
        $monthno = date('m',strtotime($date));
        $yearno = date('Y',strtotime($date));

        $members = DB::table('mon_sub_member as mm')
                    ->select(DB::raw("count(mm.id) as total_members"))
                    ->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
                    ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                    ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                    //->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    //->leftjoin('company as com','com.id','=','sc.CompanyCode')
                    ->where(DB::raw('DATE_FORMAT(ms.Date, "%m-%Y")'), '=', $monthno.'-'.$yearno)
                    ->where('ms.Date','=',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
                    ->where(function ($query) {
                        $query->where('mm.StatusId', '=', 1)
                              ->orWhere('mm.StatusId', '=', 2)
                              ->orWhere('mm.StatusId', '=', 3)
                              ->orWhere('mm.StatusId', '=', 4);
                    })
                    ->whereIn('sc.CompanyCode', $companies)
                    //->where('mm.approval_status', '=', 1)
                    ->whereNull('mm.additional_member')
                    //->where('mm.additional_member', '=', 1)
                    ->where('mm.update_status', '=', 1)
                    ->first();

       // $members = CacheMonthEnd::getMontendcompanyGroup($companies,$date);
        
        return $members->total_members;
    }

    public static function getTotalAddMembersSummary($companies,$date){
        $monthno = date('m',strtotime($date));
        $yearno = date('Y',strtotime($date));

        $members = DB::table('mon_sub_member as mm')
                    ->select(DB::raw("count(mm.id) as total_members"))
                    ->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
                    ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                    //->leftjoin('membership as m','m.id','=','mm.MemberCode')
                    //->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    //->leftjoin('company as com','com.id','=','sc.CompanyCode')
                    ->where(DB::raw('DATE_FORMAT(ms.Date, "%m-%Y")'), '=', $monthno.'-'.$yearno)
                    ->where(function ($query) {
                        $query->where('mm.StatusId', '=', 1)
                              ->orWhere('mm.StatusId', '=', 2)
                              ->orWhere('mm.StatusId', '=', 3)
                              ->orWhere('mm.StatusId', '=', 4);
                    })
                    ->whereIn('sc.CompanyCode', $companies)
                    //->where('mm.approval_status', '=', 1)
                    ->whereNotNull('mm.additional_member')
                    //->where('mm.additional_member', '=', 1)
                    ->where('mm.update_status', '=', 1)
                    ->first();

       // $members = CacheMonthEnd::getMontendcompanyGroup($companies,$date);
        
        return $members->total_members;
    }

    public static function getStruckoffMembersSummary($companies,$date){
        $monthno = date('m',strtotime($date));
        $yearno = date('Y',strtotime($date));

        $members = DB::table('mon_sub_member as mm')
                    ->select(DB::raw("count(mm.id) as total_members"))
                    ->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
                    ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                    //->leftjoin('membership as m','m.id','=','mm.MemberCode')
                    //->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    //->leftjoin('company as com','com.id','=','sc.CompanyCode')
                    ->where(DB::raw('DATE_FORMAT(ms.Date, "%m-%Y")'), '=', $monthno.'-'.$yearno)
                    ->where('mm.StatusId', '=', 3)
                    ->whereIn('sc.CompanyCode', $companies)
                    //->where('mm.approval_status', '=', 1)
                    ->whereNull('mm.additional_member')
                    ->where('mm.update_status', '=', 1)
                    ->first();

       // $members = CacheMonthEnd::getMontendcompanyGroup($companies,$date);
        
        return $members->total_members;
    }

     public static function getResignedMembersSummary($companies,$date){
        $monthno = date('m',strtotime($date));
        $yearno = date('Y',strtotime($date));

        $members = DB::table('mon_sub_member as mm')
                    ->select(DB::raw("count(mm.id) as total_members"))
                    ->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
                    ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                    //->leftjoin('membership as m','m.id','=','mm.MemberCode')
                    //->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    //->leftjoin('company as com','com.id','=','sc.CompanyCode')
                    ->where(DB::raw('DATE_FORMAT(ms.Date, "%m-%Y")'), '=', $monthno.'-'.$yearno)
                    ->where('mm.StatusId', '=', 4)
                    ->whereIn('sc.CompanyCode', $companies)
                    //->where('mm.approval_status', '=', 1)
                    ->whereNull('mm.additional_member')
                    ->where('mm.update_status', '=', 1)
                    ->first();

       // $members = CacheMonthEnd::getMontendcompanyGroup($companies,$date);
        
        return $members->total_members;
    }
	
	public static function getUnionMonthlyPaidCount($unionid, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		
		$count = DB::table('mon_sub_member as sm')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                //->leftjoin('company as c','mc.CompanyCode','=','c.id')
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->where('cb.union_branch_id','=',$unionid)
								->where(DB::raw('month(ms.Date)'),'=',$month)
								->where(DB::raw('year(ms.Date)'),'=',$year)
								->count();
		return $count;
    }
	
	public static function getUnionLastMonthlyPaidCount($unionid, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		$last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
		
		$members = DB::table('mon_sub_member as sm')->select('sm.MemberCode')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->where('cb.union_branch_id','=',$unionid)
								->where(DB::raw('month(ms.Date)'),'=',$month)
								->where(DB::raw('year(ms.Date)'),'=',$year)
								->get();
		$count = 0;						
		foreach($members as $member){
			$memebr_id = $member->MemberCode;
			$old_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$memebr_id)
							->where('ms.Date','=',$last_month)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			if($old_subscription_count==0){
				$count++;
			}
		}	
		return $count;
	}
	
	public static function getUnioncurrentMonthlyPaidCount($unionid, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		$last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
		$last_month_no = date("m", strtotime($last_month));
		$last_year_no = date("Y", strtotime($last_month));
		
		$members = DB::table('mon_sub_member as sm')->select('sm.MemberCode')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->where('cb.union_branch_id','=',$unionid)
								->where(DB::raw('month(ms.Date)'),'=',$last_month_no)
								->where(DB::raw('year(ms.Date)'),'=',$last_year_no)
								->get();
		$count = 0;						
		foreach($members as $member){
			$memebr_id = $member->MemberCode;
			$current_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$memebr_id)
							->where(DB::raw('month(ms.Date)'),'=',$month)
							->where(DB::raw('year(ms.Date)'),'=',$year)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			if($current_subscription_count==0){
				$count++;
			}
		}	
		return $count;
	}
	
	public static function getBranchMonthlyPaidCount($branchid, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		
		$count = DB::table('mon_sub_member as sm')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                //->leftjoin('company as c','mc.CompanyCode','=','c.id')
								//->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->where('m.branch_id','=',$branchid)
								->where(DB::raw('month(ms.Date)'),'=',$month)
								->where(DB::raw('year(ms.Date)'),'=',$year)
								->count();
		return $count;
    }
	
	public static function getBranchLastMonthlyPaidCount($branchid, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		$last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
		
		$members = DB::table('mon_sub_member as sm')->select('sm.MemberCode')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                //->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->where('m.branch_id','=',$branchid)
								->where(DB::raw('month(ms.Date)'),'=',$month)
								->where(DB::raw('year(ms.Date)'),'=',$year)
								->get();
		$count = 0;						
		foreach($members as $member){
			$memebr_id = $member->MemberCode;
			$old_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$memebr_id)
							->where('ms.Date','=',$last_month)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			if($old_subscription_count==0){
				$count++;
			}
		}	
		return $count;
	}
	
	public static function getBranchcurrentMonthlyPaidCount($branchid, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		$last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
		$last_month_no = date("m", strtotime($last_month));
		$last_year_no = date("Y", strtotime($last_month));
		
		$members = DB::table('mon_sub_member as sm')->select('sm.MemberCode')->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                //->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->where('m.branch_id','=',$branchid)
								->where(DB::raw('month(ms.Date)'),'=',$last_month_no)
								->where(DB::raw('year(ms.Date)'),'=',$last_year_no)
								->get();
		$count = 0;						
		foreach($members as $member){
			$memebr_id = $member->MemberCode;
			$current_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$memebr_id)
							->where(DB::raw('month(ms.Date)'),'=',$month)
							->where(DB::raw('year(ms.Date)'),'=',$year)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			if($current_subscription_count==0){
				$count++;
			}
		}	
		return $count;
	}
	
	public static function getMontendcompanyVariation($companies,$date){
		$members = CacheMonthEnd::getMontendcompanyGroupVariation($companies,$date);
		
		return $members;
	}
	
	public static function getGroupLastMonthlyPaidCount($companies,$date){
		$members = CacheMonthEnd::getMontendcompanymembers($companies,$date);
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		$last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
		
		$count = 0;						
		foreach($members as $member){
			$memebr_id = $member->memberid;
			$old_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$memebr_id)
							->where('ms.Date','=',$last_month)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			if($old_subscription_count==0){
				$count++;
			}
		}	
		return $count;
		
		return $members;
	}
	
	public static function getGroupcurrentMonthlyPaidCount($companies, $date=false){
		if($date==""){
			$date = date('Y-m-01');
		}
		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));
		//return $month;
		$last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
		$last_month_no = date("m", strtotime($last_month));
		$last_year_no = date("Y", strtotime($last_month));
		
		$members = CacheMonthEnd::getMontendcompanymembers($companies,$last_month);
		
		$count = 0;						
		foreach($members as $member){
			$memebr_id = $member->memberid;
			$current_subscription_count = DB::table("mon_sub_member as mm")
							->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
							->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
							->where('mm.MemberCode','=',$memebr_id)
							->where(DB::raw('month(ms.Date)'),'=',$month)
							->where(DB::raw('year(ms.Date)'),'=',$year)
                            ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
                            ->count();
			if($current_subscription_count==0){
				$count++;
			}
		}	
		return $count;
	}

	public static function getsubscription_bysalary($salary){
		$netsalary = (float)$salary;
		$bf_amount = Fee::where('fee_shortcode','=','BF')->pluck('fee_amount')->first();
        $ins_amount = Fee::where('fee_shortcode','=','INS')->pluck('fee_amount')->first();
        $bf_amount = $bf_amount=='' ? 3 : $bf_amount;
		$ins_amount = $ins_amount=='' ? 7 : $ins_amount;
		$one_percent = ($netsalary*1)/100;
		$subs_amt = $one_percent-($bf_amount+$ins_amount);
		return $subs_amt;
	}

    public static function get_group_gender_race_count($over_all_count,$race_id,$status,$gender){
        $total_count = 0;
        foreach ($over_all_count as $key => $value) {
           if($value->race_id==$race_id && $value->STATUS_CODE==$status && $value->gender==$gender){
                $total_count += $value->count;
           }
        }
        return $total_count;
    }

    public static function get_all_union_gender_race_count($branchid,$month_year){
        $monthno = '';
        $yearno = '';
        if($month_year!=""){
            $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }else{      
            $monthno = date('m');
            $yearno = date('Y');
        }
        
        
        $male_count = CacheMonthEnd::get_all_union_gender_race_count($branchid,$month_year);
        return $male_count;
    }

    public static function get_group_union_gender_race_count($over_all_count,$race_id,$status,$gender){
        $total_count = 0;
        foreach ($over_all_count as $key => $value) {
           if($value->race_id==$race_id && $value->STATUS_CODE==$status && $value->gender==$gender){
                $total_count += $value->count;
           }
        }
        return $total_count;
    }

    public static function getTotalInsCount($memberid){
        $countrecord =  DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$memberid)
                        ->where('ms.TOTALINSURANCE_AMOUNT','!=',0)->count();
        return $countrecord;
	}
	
	public static function getBranchShortCode($branchid){
		$shortcode = CompanyBranch::where('id',$branchid)->pluck('branch_shortcode')->first();
        
        return $shortcode;
	}
	
	public static function getInsuranceData($memberid){
		$members_pay = DB::select(DB::raw("SELECT count(*) as count,TOTALINSURANCE_AMOUNT FROM `membermonthendstatus` where TOTALINSURANCE_AMOUNT!=0 and MEMBER_CODE=$memberid"));
		return $members_pay;
	}

    public static function get_duemonths_monthend($memberid, $date){
        $date = date('Y-m-01',$date);
        $members_due = DB::select(DB::raw("SELECT TOTALMONTHSDUE FROM `membermonthendstatus` WHERE `MEMBER_CODE` = $memberid and StatusMonth<='$date' ORDER BY `membermonthendstatus`.`StatusMonth` DESC limit 1"));
        $members_due = empty($members_due) ? 0 : $members_due[0]->TOTALMONTHSDUE;
      // dd($members_due);
        return $members_due;
    }

    public static function getlastMonthEndByMemberMay($memberid){
        $mayrecord =  DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$memberid)->orderBY('StatusMonth','asc')
                        ->where('ms.StatusMonth','=','2017-05-01')->first();
        return $mayrecord;
    }

    public static function getReasonNameBYCode($shortcode){
        return Reason::where('short_code','=',$shortcode)->pluck('reason_name')->first();
    }


    public static function getLastMonthlyPaidMembers($type_id, $date, $type){
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        $last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
        
        $membersqry = DB::table('mon_sub_member as sm')
                                //->select('sm.MemberCode as MemberCode')
                                ->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                //->where('cb.union_branch_id','=',$unionid)
                                ->where(DB::raw('month(ms.Date)'),'=',$month)
                                ->where(DB::raw('year(ms.Date)'),'=',$year);
        if($type==1){
            $membersqry = $membersqry->where('cb.union_branch_id','=',$type_id);
        }else if($type==2){
            $membersqry = $membersqry->where('mc.CompanyCode','=',$type_id);
        }
        else{
            $membersqry = $membersqry->where('m.branch_id','=',$type_id);
        }
       $members = $membersqry->pluck('sm.MemberCode');
       //dd($members);

        $subscription_qry = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE','mm.NRIC as ic')
                        ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                        ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                        ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                        ->where('mm.update_status', '=', 1)
                        ->where('mm.MemberCode', '!=', Null) 
                        ->whereNotIn('mm.MemberCode',$members)
                        ->where('ms.Date','=',$last_month);
        
            if($type==1){
                $subscription_qry = $subscription_qry->where('cb.union_branch_id','=',$type_id);
            }else if($type==2){
                $subscription_qry = $subscription_qry->where('cb.company_id','=',$type_id);
            }
            else{
                $subscription_qry = $subscription_qry->where('m.branch_id','=',$type_id);
            }
            $subscriptions = $subscription_qry->get();  
        // $count = 0;                     
        // foreach($members as $member){
        //     $memebr_id = $member->MemberCode;
        //     $old_subscription_count = DB::table("mon_sub_member as mm")
        //                     ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
        //                     ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
        //                     ->where('mm.MemberCode','=',$memebr_id)
        //                     ->where('ms.Date','=',$last_month)
        //                     ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
        //                     ->count();
        //     if($old_subscription_count==0){
        //         $count++;
        //     }
        // }   
        return $subscriptions;
    }

    public static function getcurrentMonthlyPaidMembers($type_id, $date, $type){
        //dd('sds');
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        $last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
        $current_month_temp = date('m-Y',strtotime('01-'.$month.'-'.$year));
        
        $membersqry = DB::table('mon_sub_member as sm')
                                //->select('sm.MemberCode as MemberCode')
                                ->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                //->where(DB::raw("DATE_FORMAT(m.doj, '%m-%Y')"),'!=',$last_month_temp)
                                //->where('cb.union_branch_id','=',$unionid)
                                ->where('ms.Date','=',$last_month);
        if($type==1){
            $membersqry = $membersqry->where('cb.union_branch_id','=',$type_id);
        }else if($type==2){
            $membersqry = $membersqry->where('mc.CompanyCode','=',$type_id);
        }
        else{
            $membersqry = $membersqry->where('m.branch_id','=',$type_id);
        }
       $members = $membersqry->pluck('sm.MemberCode');
      // dd($members);

        $subscription_qry = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE','mm.NRIC as ic')
                        ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                        ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                        ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                        ->where('mm.update_status', '=', 1)
                        ->where('mm.MemberCode', '!=', Null) 
                        ->whereNotIn('mm.MemberCode',$members)
                        ->where(DB::raw("DATE_FORMAT(m.doj, '%m-%Y')"),'!=',$current_month_temp)
                        ->where(DB::raw('month(ms.Date)'),'=',$month)
                        ->where(DB::raw('year(ms.Date)'),'=',$year);
        
            if($type==1){
                $subscription_qry = $subscription_qry->where('cb.union_branch_id','=',$type_id);
            }else if($type==2){
                $subscription_qry = $subscription_qry->where('cb.company_id','=',$type_id);
            }
            else{
                $subscription_qry = $subscription_qry->where('m.branch_id','=',$type_id);
            }
            $subscriptions = $subscription_qry->get();  
        // $count = 0;                     
        // foreach($members as $member){
        //     $memebr_id = $member->MemberCode;
        //     $old_subscription_count = DB::table("mon_sub_member as mm")
        //                     ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
        //                     ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
        //                     ->where('mm.MemberCode','=',$memebr_id)
        //                     ->where('ms.Date','=',$last_month)
        //                     ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
        //                     ->count();
        //     if($old_subscription_count==0){
        //         $count++;
        //     }
        // }   
        return $subscriptions;
    }

    public static function getNewJoinPaidMembers($type_id, $date, $type){
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        $last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
        $current_month_temp = date('m-Y',strtotime('01-'.$month.'-'.$year));
        
        $membersqry = DB::table('mon_sub_member as sm')
                                //->select('sm.MemberCode as MemberCode')
                                ->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                //->where(DB::raw("DATE_FORMAT(m.doj, '%m-%Y')"),'=',$last_month_temp)
                                //->where('cb.union_branch_id','=',$unionid)
                                ->where('ms.Date','=',$last_month);
        if($type==1){
            $membersqry = $membersqry->where('cb.union_branch_id','=',$type_id);
        }else if($type==2){
            $membersqry = $membersqry->where('mc.CompanyCode','=',$type_id);
        }
        else{
            $membersqry = $membersqry->where('m.branch_id','=',$type_id);
        }
       $members = $membersqry->pluck('sm.MemberCode');
       //dd($members);

        $subscription_qry = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE','mm.NRIC as ic')
                        ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                        ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                        ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                        ->where('mm.update_status', '=', 1)
                        ->where('mm.MemberCode', '!=', Null) 
                        ->whereNotIn('mm.MemberCode',$members)
                        ->where(DB::raw("DATE_FORMAT(m.doj, '%m-%Y')"),'=',$current_month_temp)
                        ->where(DB::raw('month(ms.Date)'),'=',$month)
                        ->where(DB::raw('year(ms.Date)'),'=',$year);
        
            if($type==1){
                $subscription_qry = $subscription_qry->where('cb.union_branch_id','=',$type_id);
            }else if($type==2){
                $subscription_qry = $subscription_qry->where('cb.company_id','=',$type_id);
            }
            else{
                $subscription_qry = $subscription_qry->where('m.branch_id','=',$type_id);
            }
            $subscriptions = $subscription_qry->get();  
        // $count = 0;                     
        // foreach($members as $member){
        //     $memebr_id = $member->MemberCode;
        //     $old_subscription_count = DB::table("mon_sub_member as mm")
        //                     ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
        //                     ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
        //                     ->where('mm.MemberCode','=',$memebr_id)
        //                     ->where('ms.Date','=',$last_month)
        //                     ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
        //                     ->count();
        //     if($old_subscription_count==0){
        //         $count++;
        //     }
        // }   
        return $subscriptions;
    }

    public static function getMonthEndPaidAmount($type_id, $date, $type){
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        
        $query = DB::table("mon_sub_member as mm")->select(DB::raw('sum(mm.Amount) as amount'))
                    ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                    ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                    ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                    ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                    ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                    ->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                    ->where(DB::raw('month(ms.Date)'),'=',$month)
                    ->where(DB::raw('year(ms.Date)'),'=',$year);
        if($type==1){
            $query = $query->where('cb.union_branch_id','=',$type_id);
        }else if($type==2){
            $query = $query->where('mc.CompanyCode','=',$type_id);
        }
        else{
            $query = $query->where('m.branch_id','=',$type_id);
        }
        $result = $query->first();  
        //dd($count);
        
        /* $query = DB::table("membermonthendstatus as mm")->select('mm.id')
                                ->leftjoin('company as c','mm.BANK_CODE','=','c.id')
                                ->where(DB::raw('month(mm.StatusMonth)'),'=',$month)
                                ->where(DB::raw('year(mm.StatusMonth)'),'=',$year);
        if($type==1){
            $query = $query->where('mm.NUBE_BRANCH_CODE','=',$type_id);
        }else if($type==2){
            $query = $query->where('mm.BANK_CODE','=',$type_id);
        }
        else{
            $query = $query->where('mm.BRANCH_CODE','=',$type_id);
        }
        $count = $query->count();            */ 
    
        return $result->amount;
    }

    public static function getMontendcompanyVariationAmount($companies,$datestring){

        $monthno = date('m',strtotime($datestring));
        $yearno = date('Y',strtotime($datestring));
        $result = DB::table('mon_sub_member as mm')
                ->select(DB::raw("sum(mm.Amount) as amount"))
                ->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
                ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
                //->leftjoin('membership as m','m.id','=','mm.MemberCode')
                //->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                //->leftjoin('company as c','cb.company_id','=','c.id')
                //->leftjoin('membership as m','m.id','=','mm.MemberCode')
                //->leftjoin('company_branch as c','c.id','=','m.branch_id')
                //->leftjoin('company as com','com.id','=','sc.CompanyCode')
                ->where(DB::raw('DATE_FORMAT(ms.Date, "%m-%Y")'), '=', $monthno.'-'.$yearno)
                ->where('mm.MemberCode', '!=', Null)
                ->whereIn('sc.CompanyCode', $companies)
                //->where('mm.approval_status', '=', 1)
                ->where('mm.update_status', '=', 1)
                ->first();
        
        //$members = CacheMonthEnd::getMontendcompanyGroupVariation($companies,$date);
        
        return $result->amount;
    }

    public static function getSubscriptionIncDecMembers($type_id, $date, $type, $vtype){
        //0 dec
        //1 inc
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        $last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
        $current_month_temp = date('m-Y',strtotime('01-'.$month.'-'.$year));
        
        $membersqry = DB::table('mon_sub_member as sm')
                                ->select('sm.MemberCode','sm.Amount')
                                ->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                //->where(DB::raw("DATE_FORMAT(m.doj, '%m-%Y')"),'=',$last_month_temp)
                                //->where('cb.union_branch_id','=',$unionid)
                                ->where('ms.Date','=',$last_month);
        if($type==1){
            $membersqry = $membersqry->where('cb.union_branch_id','=',$type_id);
        }else if($type==2){
            $membersqry = $membersqry->where('mc.CompanyCode','=',$type_id);
        }
        else{
            $membersqry = $membersqry->where('m.branch_id','=',$type_id);
        }
        $members = $membersqry->get();
       //dd($members);
        $subdata = [];

        if($vtype==1){
            $cond = '>';
        }else{
            $cond = '<';
        }

        foreach ($members as $member) {

           $subscription_data = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as Amount','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE','mm.NRIC as ic',DB::raw($member->Amount.' as last_amount'))
                        ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                        ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                        ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                        ->where('mm.update_status', '=', 1)
                        ->where('mm.MemberCode', '=', $member->MemberCode) 
                        ->where('mm.Amount', $cond, $member->Amount) 
                        ->where(DB::raw('month(ms.Date)'),'=',$month)
                        ->where(DB::raw('year(ms.Date)'),'=',$year)->first();
            if($subscription_data!=null){
             //   print_r($member);
                 //dd($subscription_data); 
                $subdata[] = $subscription_data;
            }     
            
        }
          
        // $count = 0;                     
        // foreach($members as $member){
        //     $memebr_id = $member->MemberCode;
        //     $old_subscription_count = DB::table("mon_sub_member as mm")
        //                     ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
        //                     ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
        //                     ->where('mm.MemberCode','=',$memebr_id)
        //                     ->where('ms.Date','=',$last_month)
        //                     ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
        //                     ->count();
        //     if($old_subscription_count==0){
        //         $count++;
        //     }
        // }   
        return $subdata;
    }

    public static function getDueMonthends($memberid,$months){
        $records =  DB::table('membermonthendstatus')
                        ->select('SUBSCRIPTIONDUE','BFDUE','INSURANCEDUE','TOTALMONTHSDUE','StatusMonth')
                        ->where('MEMBER_CODE', '=' ,$memberid)
                        ->where('TOTAL_MONTHS', '!=' ,1)
                        ->where('arrear_status', '!=' ,1)
                        ->OrderBy('StatusMonth','asc')
                        ->limit($months)
                        ->get();
        return $records;
    }

    public static function getDueMonthendsByDate($memberid,$months,$date){
        $records =  DB::table('membermonthendstatus')
                        ->select('SUBSCRIPTIONDUE','BFDUE','INSURANCEDUE','TOTALMONTHSDUE','StatusMonth','TOTALSUBCRP_AMOUNT','TOTALBF_AMOUNT','TOTALINSURANCE_AMOUNT')
                        ->where('MEMBER_CODE', '=' ,$memberid)
                        ->where('TOTAL_MONTHS', '=' ,1)
                        ->where('arrear_status', '=' ,1)
                        ->where('arrear_date', '=' ,$date)
                        ->OrderBy('StatusMonth','asc')
                        ->limit($months)
                        ->get();
        return $records;
    }

    public static function getMonthendCountByDoj($memberid,$date){
        return DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$memberid)->where('ms.StatusMonth','=',$date)->where('ms.TOTAL_MONTHS','=',1)->count();
    }

     public static function getMonthendsByJoinDate($memberid,$months,$date){
        $records =  DB::table('membermonthendstatus')
                        ->select('SUBSCRIPTIONDUE','BFDUE','INSURANCEDUE','TOTALMONTHSDUE','StatusMonth','TOTALSUBCRP_AMOUNT','TOTALBF_AMOUNT','TOTALINSURANCE_AMOUNT','Id as autoid','TOTAL_MONTHS','TOTALMONTHSPAID','ENTRYMODE')
                        ->where('MEMBER_CODE', '=' ,$memberid)
                        ->where('StatusMonth', '>' ,$date)
                        ->OrderBy('StatusMonth','asc')
                        //->limit($months)
                        ->get();
        return $records;
    }
	
     public static function getMonthendsOnJoinDate($memberid,$months,$date){
        $records =  DB::table('membermonthendstatus')
                        ->select('SUBSCRIPTIONDUE','BFDUE','INSURANCEDUE','TOTALMONTHSDUE','StatusMonth','TOTALSUBCRP_AMOUNT','TOTALBF_AMOUNT','TOTALINSURANCE_AMOUNT','TOTAL_MONTHS','TOTALMONTHSPAID','ENTRYMODE')
                        ->where('MEMBER_CODE', '=' ,$memberid)
                        ->where('StatusMonth', '=' ,$date)
                        ->OrderBy('StatusMonth','asc')
                        ->first();
        return $records;
    }

    public static function getBFAmountByDate($memberid,$doj,$todate){
        $resign_month = date('m', strtotime($todate));
        $resign_day = date('d', strtotime($todate));
        $resign_year = date('Y', strtotime($todate));
        $doj_month = date('m', strtotime($doj));
        $doj_day = date('d', strtotime($doj));
        $doj_year = date('Y', strtotime($doj));

        $date1 = Carbon::createMidnightDate($resign_year, $resign_month, $resign_day);
        $date2 = Carbon::createMidnightDate($doj_year, $doj_month, $doj_day);

        $rdate1 = Carbon::createMidnightDate($doj_year, $doj_month, $doj_day);
        $rdate2 = Carbon::createMidnightDate('2017', '05', '31');
        $service_year = $date2->diffInYears($date1);

        $dojtime = strtotime($doj);
        $bftime = strtotime('2017-05-31');
        $bfamount = 0; 
        if($dojtime<=$bftime){
             $memberstatus =  DB::table('membermonthendstatus as ms')->where('StatusMonth', '=',$todate)->where('MEMBER_CODE', '=',$memberid)->pluck('STATUS_CODE')->first();

             if($memberstatus==""){
                 $memberstatus =  DB::table('membership as m')->where('id', '=',$memberid)->pluck('status_id')->first();
             }

             if($memberstatus==1){
                $reasondata =  Reason::where('reason_name','LIKE',"%DECEASED%")->first();
                $five_year_amount = $reasondata->five_year_amount;
                $fiveplus_year_amount = $reasondata->fiveplus_year_amount;
                $one_year_amount = $reasondata->one_year_amount;
                $minimum_year = $reasondata->minimum_year;
                $minimum_refund = $reasondata->minimum_refund;
                $maximum_refund = $reasondata->maximum_refund;
                 if($service_year>=$minimum_year){
                    $fiveplusyears =$service_year - $minimum_year; 
                    $paid_amount = ($fiveplusyears*$fiveplus_year_amount)+$five_year_amount;
                    if($paid_amount<=$minimum_refund){
                        $bfamount = $minimum_refund; 
                    }else if($paid_amount>$minimum_refund && $paid_amount<$maximum_refund){
                        $bfamount = $paid_amount; 
                    }else{
                        $bfamount = $maximum_refund; 
                    }
                }
             }
            
        }
        return $bfamount;
        //return $doj.'//'.$todate;
    }

    public static function getMonthendDueCount($memberid){
        //$duecount = DB::table('member_payments_reports as ms')->select('ms.totdue_months')->where('ms.member_id', '=' ,$memberid)->pluck('ms.totdue_months')->first();
        $duecount = DB::table('membermonthendstatus as ms')->select('ms.TOTALMONTHSDUE')->where('ms.MEMBER_CODE', '=' ,$memberid)->OrderBy('ms.StatusMonth','desc')->limit(1)->pluck('ms.TOTALMONTHSDUE')->first();
        $duecount = $duecount=='' ? 0 : $duecount;
        return $duecount;
    }

     public static function getUnionBranchName($unionbranchid){
        return UnionBranch::where('id',$unionbranchid)->pluck('union_branch')->first();
     }

    public static function getcurrentMonthlyPaidMembersAll($type_id, $date, $type){
        //dd('sds');
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        $last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
        $current_month_temp = date('m-Y',strtotime('01-'.$month.'-'.$year));
        
        $membersqry = DB::table('mon_sub_member as sm')
                                //->select('sm.MemberCode as MemberCode')
                                ->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                ->where('sm.MemberCode', '!=', Null)
                                //->where(DB::raw("DATE_FORMAT(m.doj, '%m-%Y')"),'!=',$last_month_temp)
                                //->where('cb.union_branch_id','=',$unionid)
                                ->where('ms.Date','=',$last_month);
        if($type==1){
            $membersqry = $membersqry->where('cb.union_branch_id','=',$type_id);
        }else if($type==2){
            $membersqry = $membersqry->where('mc.CompanyCode','=',$type_id);
        }
        else{
            $membersqry = $membersqry->where('m.branch_id','=',$type_id);
        }
       $members = $membersqry->pluck('sm.MemberCode');
      // dd($members);

        $subscription_qry = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE','mm.NRIC as ic','mm.id as sub_member_id')
                        ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                        ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                        ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                        ->where('mm.update_status', '=', 1)
                        ->where('mm.MemberCode', '!=', Null) 
                        ->whereNotIn('mm.MemberCode',$members)
                        //->where(DB::raw("DATE_FORMAT(m.doj, '%m-%Y')"),'!=',$current_month_temp)
                        ->where(DB::raw('month(ms.Date)'),'=',$month)
                        ->where(DB::raw('year(ms.Date)'),'=',$year);
        
            if($type==1){
                $subscription_qry = $subscription_qry->where('cb.union_branch_id','=',$type_id);
            }else if($type==2){
                $subscription_qry = $subscription_qry->where('mc.CompanyCode','=',$type_id);
            }
            else{
                $subscription_qry = $subscription_qry->where('m.branch_id','=',$type_id);
            }
            $subscriptions = $subscription_qry->get();  
        // $count = 0;                     
        // foreach($members as $member){
        //     $memebr_id = $member->MemberCode;
        //     $old_subscription_count = DB::table("mon_sub_member as mm")
        //                     ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
        //                     ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
        //                     ->where('mm.MemberCode','=',$memebr_id)
        //                     ->where('ms.Date','=',$last_month)
        //                     ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
        //                     ->count();
        //     if($old_subscription_count==0){
        //         $count++;
        //     }
        // }   
        return $subscriptions;
    }

    public static function getLastMonthlyPaidMembersAll($type_id, $date, $type){
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        $last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
        
        $membersqry = DB::table('mon_sub_member as sm')
                                //->select('sm.MemberCode as MemberCode')
                                ->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                //->where('cb.union_branch_id','=',$unionid)
                                ->where('sm.MemberCode', '!=', Null) 
                                ->where(DB::raw('month(ms.Date)'),'=',$month)
                                ->where(DB::raw('year(ms.Date)'),'=',$year);
        if($type==1){
            $membersqry = $membersqry->where('cb.union_branch_id','=',$type_id);
        }else if($type==2){
            $membersqry = $membersqry->where('mc.CompanyCode','=',$type_id);
        }
        else{
            $membersqry = $membersqry->where('m.branch_id','=',$type_id);
        }
       $members = $membersqry->pluck('sm.MemberCode');
       // /dd($members);

        $subscription_qry = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE','mm.NRIC as ic','mm.id as sub_member_id')
                        ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                        ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                        ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                        ->where('mm.update_status', '=', 1)
                        ->where('mm.MemberCode', '!=', Null) 
                        ->whereNotIn('mm.MemberCode',$members)
                        ->where('ms.Date','=',$last_month);
        
            if($type==1){
                $subscription_qry = $subscription_qry->where('cb.union_branch_id','=',$type_id);
            }else if($type==2){
                $subscription_qry = $subscription_qry->where('mc.CompanyCode','=',$type_id);
            }
            else{
                $subscription_qry = $subscription_qry->where('m.branch_id','=',$type_id);
            }
            $subscriptions = $subscription_qry->get();  
        // $count = 0;                     
        // foreach($members as $member){
        //     $memebr_id = $member->MemberCode;
        //     $old_subscription_count = DB::table("mon_sub_member as mm")
        //                     ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
        //                     ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
        //                     ->where('mm.MemberCode','=',$memebr_id)
        //                     ->where('ms.Date','=',$last_month)
        //                     ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
        //                     ->count();
        //     if($old_subscription_count==0){
        //         $count++;
        //     }
        // }   
        return $subscriptions;
    }

     public static function getBankLastMonthlyPaidMembersAll($type_id, $date, $type){
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        $last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
        
        $membersqry = DB::table('mon_sub_member as sm')
                                //->select('sm.MemberCode as MemberCode')
                                ->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                //->where('cb.union_branch_id','=',$unionid)
                                ->where(DB::raw('month(ms.Date)'),'=',$month)
                                ->where('sm.MemberCode', '!=', Null) 
                                ->where(DB::raw('year(ms.Date)'),'=',$year);
        if($type==1){
            $membersqry = $membersqry->where('cb.union_branch_id','=',$type_id);
        }else if($type==2){
            $membersqry = $membersqry->where('mc.CompanyCode','=',$type_id);
        }
        else{
            $membersqry = $membersqry->where('m.branch_id','=',$type_id);
        }
       $members = $membersqry->pluck('sm.MemberCode');
       // /dd($members);

        $subscription_qry = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE','mm.NRIC as ic','mm.id as sub_member_id')
                        ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                        ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                        ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                        ->where('mm.update_status', '=', 1)
                        ->where('mm.StatusId', '<=', 2)
                        ->where('mm.MemberCode', '!=', Null) 
                        ->whereNotIn('mm.MemberCode',$members)
                        ->where('ms.Date','=',$last_month);
        
            if($type==1){
                $subscription_qry = $subscription_qry->where('cb.union_branch_id','=',$type_id);
            }else if($type==2){
                $subscription_qry = $subscription_qry->where('mc.CompanyCode','=',$type_id);
            }
            else{
                $subscription_qry = $subscription_qry->where('m.branch_id','=',$type_id);
            }
            $subscriptions = $subscription_qry->get();  
       //dd($subscriptions);
        return $subscriptions;
    }

    public static function getBranchCompany($branchid){
        $company_data = DB::table("company_branch as cb")->select('cb.branch_name as branch_name','c.company_name as company_name')
                        ->leftjoin('company as c','cb.company_id','=','c.id')->where('cb.id','=',$branchid)->first();
        
        return $company_data;
    }

    public static function getMonthendCountNextDoj($memberid,$date){
        return DB::table('membermonthendstatus as ms')->where('ms.MEMBER_CODE', '=' ,$memberid)->where('ms.StatusMonth','>=',$date)->where('ms.TOTAL_MONTHS','=',1)->count();
    }

    public static function getBankCodeByBranch($branchid){
        $bankcode = DB::table('company_branch as c')->select('com.short_code as companycode')->leftjoin('company as com','com.id','=','c.company_id')->where('c.id',$branchid)->pluck('companycode')->first();
        return $bankcode;
    }

     public static function getDifferenceMonths($fromdate, $todate){
        //DB::connection()->enableQueryLog();
        $to = Carbon::createFromFormat('Y-m-d H:s:i', $todate.' 3:30:34');
        $from = Carbon::createFromFormat('Y-m-d H:s:i', $fromdate.' 3:30:34');
        $diff_in_months = $to->diffInMonths($from);
        
        return $diff_in_months;
    }
	
	public static function getMonthendNewDueCount($memberid){
        $duedata = DB::table('member_payments_reports as ms')->select(DB::raw('ms.totdue_months as TOTALMONTHSDUE'),DB::raw('ms.last_paid_date as LASTPAYMENTDATE'))->where('ms.member_id', '=' ,$memberid)->first();
        //$duedata = DB::table('membermonthendstatus as ms')->select('ms.TOTALMONTHSDUE','ms.LASTPAYMENTDATE')->where('ms.MEMBER_CODE', '=' ,$memberid)->OrderBy('ms.StatusMonth','desc')->limit(1)->first();
        // dd($duecount);
        // $duecount = $duecount=='' ? 0 : $duecount;
        return $duedata;
    }

    public static function getconfirmtionmember($userid){
        $res = DB::table('irc_account as irc')->select('m.id as mid','m.name as membername','c.company_name as bankname','cb.address_one','cb.phone','cb.mobile','m.member_number as member_number')
                ->leftjoin('membership as m','irc.MemberCode','=','m.id')
                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                ->leftjoin('company as c','cb.company_id','=','c.id')
                ->where('irc.account_type','=','irc-confirmation')
                ->where('irc.user_id','=',$userid)
                ->first();
       // $membercode = DB::table('irc_account as irc')->where('user_id','=',$userid)->pluck('MemberCode')->first();
        return $res;
    }

    public static function getCommittieinfo($userid){
        $res = DB::table('irc_account as irc')->select('ub.id as unionid','ub.group_name as union_branch','u.name as name')
                ->leftjoin('union_groups as ub','ub.id','=','irc.union_branch_id')
                ->leftjoin('users as u','u.id','=','irc.user_id')
                //->leftjoin('company as c','cb.company_id','=','c.id')
                //->where('irc.account_type','=','irc-confirmation')
                ->where('irc.user_id','=',$userid)
                ->first();
       // $membercode = DB::table('irc_account as irc')->where('user_id','=',$userid)->pluck('MemberCode')->first();
        return $res;
    }

    public static function getSubscriptionVarianceMembers($date, $companyid){
        //0 dec
        //1 inc
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        //return $month;
        $last_month = date('Y-m-01',strtotime('01-'.$month.'-'.$year.' -1 Month'));
        $current_month_temp = date('m-Y',strtotime('01-'.$month.'-'.$year));
        
        $membersqry = DB::table('mon_sub_member as sm')
                                ->select('sm.MemberCode','sm.Amount')
                                ->leftjoin('mon_sub_company as mc','sm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','sm.MemberCode','=','m.id')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                //->where(DB::raw("DATE_FORMAT(m.doj, '%m-%Y')"),'=',$last_month_temp)
                                //->where('cb.union_branch_id','=',$unionid)
                                ->where('ms.Date','=',$last_month);
        // if($type==1){
        //     $membersqry = $membersqry->where('cb.union_branch_id','=',$type_id);
        // }else if($type==2){
             $membersqry = $membersqry->where('mc.CompanyCode','=',$companyid);
        // }
        // else{
        //     $membersqry = $membersqry->where('m.branch_id','=',$type_id);
        // }
        $members = $membersqry->get();
       //dd($members);
        $subdata = [];

       

        foreach ($members as $member) {

           $subscription_data = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as Amount','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE','mm.NRIC as ic',DB::raw($member->Amount.' as last_amount'),'mm.id as submemberid')
                        ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                        ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                        ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                        ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                        ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                        ->where('mm.update_status', '=', 1)
                        ->where('mm.MemberCode', '=', $member->MemberCode) 
                        ->where('mm.Amount', '!=', $member->Amount) 
                        ->where(DB::raw('month(ms.Date)'),'=',$month)
                        ->where(DB::raw('year(ms.Date)'),'=',$year)->first();
            if($subscription_data!=null){
             //   print_r($member);
                 //dd($subscription_data); 
                $subdata[] = $subscription_data;
            }     
            
        }
          
        // $count = 0;                     
        // foreach($members as $member){
        //     $memebr_id = $member->MemberCode;
        //     $old_subscription_count = DB::table("mon_sub_member as mm")
        //                     ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
        //                     ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
        //                     ->where('mm.MemberCode','=',$memebr_id)
        //                     ->where('ms.Date','=',$last_month)
        //                     ->orderBY('mm.MonthlySubscriptionCompanyId','desc')
        //                     ->count();
        //     if($old_subscription_count==0){
        //         $count++;
        //     }
        // }   
        return $subdata;
    }
    public static function get_unmatched_data($submemberid){
        $matchdata = DB::table('mon_sub_remarks')->where('mon_sub_member_id','=',$submemberid)
        ->where('type','=',0)
        ->where('approval_status','=',1)
        ->first();
        
        return $matchdata;
    }
    public static function get_unpaid_data($submemberid,$date){
        $matchdata = DB::table('mon_sub_remarks')->where('mon_sub_member_id','=',$submemberid)
        ->where('type','=',1)
        ->where('date','=',$date)
        ->where('approval_status','=',1)
        ->first();
        
        return $matchdata;
    }
    public static function get_unmatch_reason($reasonid){
        if($reasonid==1){
            $reasonname = 'Resigned';
        }elseif($reasonid==2){
            $reasonname = 'IC not match';
        }elseif($reasonid==3){
            $reasonname = 'Bank not match';
        }else{
            $reasonname = 'Others';
        }
        
        return $reasonname;
    }
    public static function get_unpaid_reason($reasonid){
        if($reasonid==1){
            $reasonname = 'Resigned';
        }elseif($reasonid==2){
            $reasonname = 'Retired';
        }elseif($reasonid==3){
            $reasonname = 'Promoted';
        }elseif($reasonid==4){
            $reasonname = 'Demised';
        }else{
            $reasonname = 'Others';
        }
        
        return $reasonname;
    }

    public static function get_lastunpaid_reason($reasonid){
        if($reasonid==1){
            $reasonname = 'No pay leave';
        }elseif($reasonid==2){
            $reasonname = 'Excessive medical leave';
        }else{
            $reasonname = 'Others';
        }
        return $reasonname;
    }

    public static function getMemberPaidSubs($member_id,$date){
        if($date==""){
            $date = date('Y-m-01');
        }
        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        $subscriptions = DB::table("mon_sub_member as mm")->select('mm.Amount as SUBSCRIPTION_AMOUNT')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->where(DB::raw('month(ms.Date)'),'=',$month)
                                ->where(DB::raw('year(ms.Date)'),'=',$year)
                                ->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '=', $member_id)
                                ->first();
       
        if($subscriptions!=Null){
            return $subscriptions->SUBSCRIPTION_AMOUNT;
        }else{
            return '*';
        }
    }

    public static function getMonthendPaidMonths($memberid){
        $data = DB::table('membermonthendstatus as ms')
                ->select('ms.StatusMonth')
                ->where('ms.MEMBER_CODE', '=' ,$memberid)->where('ms.TOTAL_MONTHS','=',1)->where('ms.TOTALINSURANCE_AMOUNT','!=',0)
                ->orderBy('ms.StatusMonth','asc')
                ->pluck('ms.StatusMonth');
       
       return $data;
        //->pluck('ms.StatusMonth');
    }

    public static function getIncrementValue($memberid,$tomonth,$frommonth){
         $data = DB::table('salary_updations as s')
                ->select('increment_type_id',DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d") as date'),'amount_type','additional_amt','basic_salary','summary')
                ->where('s.member_id', '=' ,$memberid)
                ///->where('s.date', '>=' ,$frommonth)
                //->where('s.date', '<=' ,$tomonth)
                ->where('s.date', '=' ,(function($query) use ($tomonth,$memberid){
                    $query->select(DB::raw("max(date)"))
                    ->from('salary_updations')
                    ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'), '<=' ,$tomonth)
                    ->where('member_id', '=' ,$memberid)
                    ->limit(1);
                }))->orderBy('s.date','desc')
                //->dump()
                ->get();
        return $data;
    }

    public static function getIncrementTypeName($typeid){
          return DB::table('increment_types as i')->where('id','=',$typeid)->pluck('type_name')->first();
    }

    public static function getUnionListAll(){
        return $results = DB::table('union_branch')->where('status','=','1')->get();
    }
    public static function getHeadCompanyListAll(){
        return $results = Company::where('status',1)->where('head_of_company','=','0')->get();
    }

    public static function get_mismatchstatus_data($submemberid){
        $matchdata = DB::table('mon_sub_member_match')->where('mon_sub_member_id','=',$submemberid)
                        ->where('match_id','!=',1)
                        ->first();
        
        return $matchdata;
    }

     public static function getStateCity($stateid){
        $res = DB::table('state as s')->select(Db::raw("concat(s.state_name,' - ',c.country_name) as statename"))
                ->leftjoin('country as c','c.id','=','s.country_id')
                ->where('s.id','=',$stateid)
                ->pluck('statename')
                ->first();
       // $membercode = DB::table('irc_account as irc')->where('user_id','=',$userid)->pluck('MemberCode')->first();
        return $res;
    }

    public static function getcityusedcount($cityid){
        $City_membership =  DB::table('membership as m')->where('m.city_id','=',$cityid)->count();
        $City_member_gua =  DB::table('member_guardian as mg')->where('mg.city_id','=',$cityid)->count();
        $City_member_nomi =  DB::table('member_nominees as mn')->where('mn.city_id','=',$cityid)->count();
        $City_company_bran =  DB::table('company_branch as cb')->where('cb.city_id','=',$cityid)->count();
        $City_union_bran =  DB::table('union_branch as ub')->where('ub.city_id','=',$cityid)->count();
        if($City_membership > 0 || $City_member_gua > 0 || $City_member_nomi > 0 || $City_company_bran  > 0 || $City_union_bran > 0)
        {
            return 1;
        }else{
            return 0;
        }
    }

    public static function getBasicSalary($memberid,$date){
        $data = DB::table('salary_updations as s')
                ->select('basic_salary')
                ->where('s.member_id', '=' ,$memberid)
                ->where(DB::raw('DATE_FORMAT(s.date, "%Y-%m-%d")'), '=' ,$date)
                //->orderBy('s.date','desc')
                ->pluck('basic_salary')
                ->first();
        return $data;
    }

    public static function GetMonthsCount($frommonth,$tomonth)
    {
        $start = new DateTime($frommonth);
        $end   = new DateTime($tomonth);
        $diff  = $start->diff($end);

        $date_diff = abs(strtotime($tomonth) - strtotime($frommonth));

        $years = floor($date_diff / (365*60*60*24));
        $months = floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($date_diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

        $months += $diff->format('%y') * 12;

        return $months+1;
    }

    public static function get_designation_name($designationid){
        $des = DB::table('designation')->where('id','=',$designationid)
        ->pluck('designation_name')
        ->first();
        
        return $des;
    }

    public static function getMemberDetails($memberid){
        $member = DB::table('membership as m')
            ->select('m.member_number','m.name','m.new_ic','m.old_ic','m.employee_id','com.short_code as companycode','cb.branch_name','m.gender','m.doj','s.status_name')
            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
            ->leftjoin('company as com','com.id','=','cb.company_id')
            ->leftjoin('status as s','s.id','=','m.status_id')
            ->where('m.id','=',$memberid)
            ->first();
           // dd($memberid);
        return $member;
    }

    public static function getAdvanceDetails($memberid){
        $history = DB::table('membermonthendstatus as mm')
            ->select('mm.StatusMonth','mm.TOTALSUBCRP_AMOUNT','mm.TOTALBF_AMOUNT','mm.TOTALINSURANCE_AMOUNT')
            ->where('mm.MEMBER_CODE', $memberid)
            ->where('mm.TOTAL_MONTHS','>',1)
            ->where('mm.TOTALMONTHSDUE','<',0)
            ->get();
           // dd($memberid);
        return $history;
    }

    public static function getUnionRejectedCount($userid){
        $union_branch_id = UnionBranch::where('user_id',$userid)->pluck('id')->first();
        $count = 0;
        if($union_branch_id!=''){
            $count = DB::table('membership as m')
            ->select('m.member_number','m.name','m.new_ic','m.old_ic','m.employee_id','com.short_code as companycode','cb.branch_name','m.gender','m.doj','s.status_name')
            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
            ->leftjoin('union_branch as u','u.id','=','cb.union_branch_id')
            ->where('u.id','=',$union_branch_id)
            ->where('m.approval_status','=','Rejected')
            ->count();
        }
        return $count;
    }

    public static function getNewDesignationList(){
        $des_data = DB::table('designation_new')->where('status', 1)->get();
        
        return $des_data;
     }

    public static function getMemberAttachaments($memberid){
        $m_data = DB::table('membership_attachments')->where('member_id', $memberid)->get();
        //dd($m_data);
        return $m_data;
     }

    public static function getBranchAddress($branchid){
        $m_data = DB::table('company_branch as cb')->select('cb.branch_name','cb.postal_code','cb.address_one','cb.address_two','cb.address_three','cit.city_name','st.state_name')
                ->leftjoin('state as st','st.id','=','cb.state_id')
                ->leftjoin('city as cit','cit.id','=','cb.city_id')
                ->where('cb.id', $branchid)->first();
        //dd($m_data);
        return $m_data;
     }

    public static function getMonthendHistory($memberid,$year){
        return DB::table('membermonthendstatus as ms')->select('ms.id as id','ms.id as memberid','ms.StatusMonth',
                                         'ms.TOTALSUBCRP_AMOUNT as SUBSCRIPTION_AMOUNT','ms.TOTALBF_AMOUNT as BF_AMOUNT','ms.TOTALINSURANCE_AMOUNT as INSURANCE_AMOUNT','ms.TOTAL_MONTHS','ms.LASTPAYMENTDATE','ms.TOTALMONTHSPAID',DB::raw('IFNULL(ms.TOTALMONTHSDUE,0) as TOTALMONTHSDUE'),'ms.ACCSUBSCRIPTION','ms.ACCBF','ms.ACCINSURANCE','s.font_color','ms.arrear_status','ms.SUBSCRIPTIONDUE','ms.ENTRYMODE','ms.advance_amt','ms.advance_balamt','ms.advance_totalmonths')
                                         //->leftjoin('membership as m', 'm.id' ,'=','ms.MEMBER_CODE')
                                         ->leftjoin('status as s','s.id','=','ms.STATUS_CODE')
                                         ->where('ms.MEMBER_CODE','=',$memberid)
                                         ->where(DB::raw('YEAR(ms.StatusMonth)'),'=',$year)
                                         ->OrderBy('ms.StatusMonth','asc')
                                         ->OrderBy('ms.arrear_status','asc')
                                        // ->dump()
                                         ->get();
    }
    public static function getCountryState($countryid){
        $res = DB::table('country as c')
                ->where('c.id','=',$countryid)
                ->pluck('country_name')
                ->first();
       // $membercode = DB::table('irc_account as irc')->where('user_id','=',$userid)->pluck('MemberCode')->first();
        return $res;
    }

     public static function getstateusedcount($stateid){
        $City_membership =  DB::table('membership as m')->where('m.state_id','=',$stateid)->count();
        $City_member_gua =  DB::table('member_guardian as mg')->where('mg.state_id','=',$stateid)->count();
        $City_member_nomi =  DB::table('member_nominees as mn')->where('mn.state_id','=',$stateid)->count();
        $City_company_bran =  DB::table('company_branch as cb')->where('cb.state_id','=',$stateid)->count();
        $City_union_bran =  DB::table('union_branch as ub')->where('ub.state_id','=',$stateid)->count();
        if($City_membership > 0 || $City_member_gua > 0 || $City_member_nomi > 0 || $City_company_bran  > 0 || $City_union_bran > 0)
        {
            return 1;
        }else{
            return 0;
        }
    }

    public static function getIrcConfirmationStatus($memberid){
        $irc = DB::table('irc_confirmation as irc')
                  ->select('irc_status','status')
                   ->where('irc.resignedmemberno','=',$memberid)
                   //->pluck('irc_status','status')
                   ->first();
                  // dd($irc);
        return $irc;
    }

    public static function getIrcMailList(){
         $irc = DB::table('irc_confirmation as irc')
                   ->select('irc.resignedmembername','m.member_number','irc.resignedmembericno as icno','irc.resignedmemberbankname as bankname','irc.resignedmemberbranchname as branchname','s.status_name')
                   ->leftjoin('membership as m','m.id','=','irc.resignedmemberno')
                   ->leftjoin('status as s','s.id','=','m.status_id')
                   ->where('irc.irc_status','=',1)
                   ->where('irc.status','=',1)
                   ->where('irc.mail_status','=',0)
                   //->pluck('irc_status','status')
                   ->get();
                  // dd($irc);
        return $irc;
    }

    public static function getAdvanceAmount($memberid,$subsmonth){
        $amount = DB::table('membermonthendstatus as mm')
            ->select('mm.SUBSCRIPTION_AMOUNT','mm.BF_AMOUNT','mm.INSURANCE_AMOUNT')
            ->where('mm.MEMBER_CODE', $memberid)
            ->where('mm.StatusMonth','<' ,$subsmonth)
           // ->where()
            ->where(function ($query) {
                $query->where('mm.TOTAL_MONTHS','=',1)
                      ->orWhere('mm.TOTAL_MONTHS', '=', 0);
            })
            //->where('mm.TOTALMONTHSDUE','<',0)
            ->orderBy('mm.StatusMonth', 'desc')
            //->pluck('mm.SUBSCRIPTION_AMOUNT','mm.BF_AMOUNT','mm.INSURANCE_AMOUNT')
            ->first();
           // dd($amount);
           // dd($memberid);
        return $amount;
    }

    public static function getAdvanceNextAmount($memberid,$subsmonth){
        $amount = DB::table('membermonthendstatus as mm')
            ->select('mm.SUBSCRIPTION_AMOUNT','mm.BF_AMOUNT','mm.INSURANCE_AMOUNT')
            ->where('mm.MEMBER_CODE', $memberid)
            ->where('mm.StatusMonth','>' ,$subsmonth)
           // ->where()
            ->where(function ($query) {
                $query->where('mm.TOTAL_MONTHS','=',1)
                      ->orWhere('mm.TOTAL_MONTHS', '=', 0);
            })
            //->where('mm.TOTALMONTHSDUE','<',0)
            ->orderBy('mm.StatusMonth', 'asc')
            //->pluck('mm.SUBSCRIPTION_AMOUNT','mm.BF_AMOUNT','mm.INSURANCE_AMOUNT')
            ->first();
           // dd($amount);
           // dd($memberid);
        return $amount;
    }

    public static function getCurrentDues($memberid){
        $dues = DB::table('membermonthendstatus as mm')
            ->select('mm.TOTALMONTHSDUE')
            ->where('mm.MEMBER_CODE', $memberid)
            ->orderBy('mm.StatusMonth', 'desc')
            ->pluck('mm.TOTALMONTHSDUE')
            ->first();

        return $dues;
    }

    public static function getAdvanceAmt($memberid,$statusmonth){
        $dues = DB::table('advance_payments as ap')
            ->select('ap.advance_amount')
            ->where('ap.member_id', $memberid)
            ->where('ap.from_date', $statusmonth)
            ->pluck('ap.advance_amount')
            ->first();

        return $dues;
    }

    public static function getAdvanceID($memberid,$statusmonth){
        $dues = DB::table('advance_payments as ap')
            ->select('ap.id')
            ->where('ap.member_id', $memberid)
            ->where('ap.from_date', $statusmonth)
            ->pluck('ap.id')
            ->first();

        return $dues;
    }

    public static function getCurrentAdvanceAmt($advanceid,$memberid,$statusmonth){
        $dues = DB::table('advance_payments_history as ap')
             ->select(DB::raw('sum(ap.advance_amount) as amount'))
           // ->select('ap.advance_amount')
            ->where('ap.member_id', $memberid)
            ->where('ap.advance_id', $advanceid)
            ->where('ap.pay_date','<=' , $statusmonth)
            ->pluck('amount')
            ->first();

        return $dues;
    }

    public static function getCurrentAdvanceCount($advanceid,$memberid,$statusmonth){
        $dues = DB::table('advance_payments_history as ap')
             ->select(DB::raw('count(ap.id) as total'))
           // ->select('ap.advance_amount')
            ->where('ap.member_id', $memberid)
            ->where('ap.advance_id', $advanceid)
            ->where('ap.pay_date','<=' , $statusmonth)
            ->pluck('total')
            ->first();

        return $dues;
    }

    public static function getTempMemberCount($memberid){
         $temp_count = DB::table('temp_membership as m')->select('m.id')
                         ->where('m.member_id', $memberid)
                         ->whereNull('m.updated_by')
                         ->count();

        return $temp_count;
    }

    public static function getPgmMembers($month_year,$company_id,$branch_id,$unionbranch_id,$status_id){
        //dd($month_year);
        $monthno = '';
        $yearno = '';
        $fulldate = date('Y-m-01');
        if($month_year!=""){
          
          $fulldate = $month_year;
        }
         $members = DB::table('mon_sub_member as mm')->select('m.name', 'm.member_number','m.gender','com.company_name','m.doj','m.employee_id',DB::raw('IF(`m`.`new_ic`="",`m`.`old_ic`,`m`.`new_ic`) as ic')
        ,DB::raw('IF(`m`.`levy`="Not Applicable","N/A",`m`.`levy`) as levy'),DB::raw('IF(`m`.`tdf`="Not Applicable","N/A",`m`.`tdf`) as tdf'),'m.tdf_amount',DB::raw('CONCAT( `com`.`short_code`, "/",  `cb`.`branch_shortcode` ) AS companycode'),'cb.branch_name as branch_name',DB::raw('IF(`d`.`designation_name`="CLERICAL","C","N") AS designation_name'),'mp.last_paid_date','m.salary','mm.Amount','s.status_name  as status_name')
               ->leftjoin('mon_sub_company as mc','mc.id','=','mm.MonthlySubscriptionCompanyId')
               ->leftjoin('mon_sub as ms','ms.id','=','mc.MonthlySubscriptionId')
               ->leftjoin('membership as m','mm.MemberCode','=','m.id')
               ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
               ->leftjoin('company as com','com.id','=','cb.company_id')
               ->leftjoin('status as s','s.id','=','mm.StatusId')
               ->leftjoin('designation as d','m.designation_id','=','d.id')
               ->leftjoin('member_payments as mp','m.id','=','mp.member_id');

               if($status_id!=''){
                    $members = $members->where('mm.StatusId','=',$status_id);
               }else{
                    $members = $members->where('mm.StatusId','<=',2);
               }
              
                $members = $members->where(DB::raw('ms.`Date`'),'=',$fulldate);
                if($company_id!=""){
                    $members = $members->where('mc.CompanyCode','=',$company_id);
                }
                if($unionbranch_id!=''){
                     $members = $members->where('cb.union_branch_id','=',$unionbranch_id);
                     $unionbranch_name = DB::table('union_branch')->where('id','=',$unionbranch_id)->pluck('union_branch')->first();
                }
                if($branch_id!=""){
                    $members = $members->where('m.branch_id','=',$branch_id);
                }
               //  if($member_auto_id!=""){
               //      $members = $members->where('m.id','=',$member_auto_id);
               //  }
               //  if($from_member_no!="" && $to_member_no!=""){
               //      $members = $members->where('m.member_number','>=',$from_member_no);
               //      $members = $members->where('m.member_number','<=',$to_member_no);
               // }
               //$members = $members->groupBy('com.id');
               $members = $members->orderBy('com.company_name','asc');
               $members = $members->orderBy('m.name','asc');
            $members = $members->get();
        return $members;
    }
}
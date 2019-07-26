<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use DB;
use Carbon\Carbon;
use App\Model\FormType;
use App\Model\AppForm;
use App\Model\Country;
use App\Model\UnionBranch;
use App\Model\CompanyBranch;
use App\Model\Company;
use App\User;
use Illuminate\Support\Facades\Auth;

class CommonHelper
{
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
       $status_data = DB::table('status')->where('status.id','=',$status_id)->pluck('status_name');
       if(!empty($status_data)){
           return $status_data[0];
       }
       return '';
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
        $status_data = DB::table('relation')->where('relation.id','=',$relationship_id)->pluck('relation_name');
        if(!empty($status_data)){
            return $status_data[0];
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
        $last_no = DB::table('membership')->orderBy('id', 'desc')->limit(1)->pluck('member_number');
       
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

    public static function getapprovedStatus($user_id){
        $status_data = DB::table('membership')->where('user_id', $user_id)->pluck('is_request_approved');
         if(count($status_data)>0){
             return $status_data[0];
         }
         return '';
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
   
}
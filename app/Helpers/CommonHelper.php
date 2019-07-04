<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use DB;
use Carbon\Carbon;

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
        $company_data = DB::table('branch')->where('branch.id','=',$branch_id)->pluck('company_id');
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
}
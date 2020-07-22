<?php

namespace App\Repository;

use App\Model\Membership;
use App\Model\UnionBranch;
use App\Model\CompanyBranch;
use Carbon\Carbon;
use Cache;
use DB;
use Auth;

class CacheMonthEnd
{
	CONST CACHE_KEY="membersend";

	public function __construct() {
		$this->membermonthendstatus_table = "membermonthendstatus";
	}

	public function all($orderBy){
		/* $key = "all.{$orderBy}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$orderBy)
		{
		    return Membership::orderBy($orderBy)->limit(10000)->get();
		}); */
		

	}

	public function getCompaniesByDate($datestring){
		$key = "getCompaniesByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			$get_roles = Auth::user()->roles;
	        $user_role = $get_roles[0]->slug;
			$user_id = Auth::user()->id; 
			$company_view = DB::table("mon_sub_member as mm")->select('mc.CompanyCode as company_id','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								->leftjoin('membership as m','m.id','=','mm.MemberCode')
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->leftjoin('company as c','mc.CompanyCode','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $datestring)
								->where('mm.update_status', '=', 1)
								->where('mm.MemberCode', '!=', Null);
								 if($user_role=='union-branch'){
			                        $union_branch_id = UnionBranch::where('user_id',$user_id)->pluck('id')->first();
			                        $company_view = $company_view->where(DB::raw('c.`union_branch_id`'),'=',$union_branch_id);
			                    }else if($user_role=='company'){
			                        $company_id = CompanyBranch::where('user_id',$user_id)->pluck('company_id')->first();
			                        $company_view = $company_view->where(DB::raw('mc.`CompanyCode`'),'=',$company_id);
			                    }else if($user_role=='company-branch'){
			                        $branch_id = CompanyBranch::where('user_id',$user_id)->pluck('id')->first();
			                        $company_view = $company_view->where(DB::raw('m.`branch_id`'),'=',$branch_id);
			                    }
								$company_view = $company_view->groupBY('mc.CompanyCode')
								//->limit(2000)
								->get();
		    	// $company_view = DB::table("membermonthendstatus as mm")->select('mm.BANK_CODE as company_id','c.company_name as company_name')
                //                 ->leftjoin('company as c','mm.BANK_CODE','=','c.id')
                //                 ->where('mm.StatusMonth', '=', $datestring)
				// 				->groupBY('mm.BANK_CODE')
				// 				->get();
				return $company_view;
		});
		

	}
	
	public function getUnionBranchByDate($datestring){
		$key = "getUnionBranchByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			$company_view = DB::table("mon_sub_member as mm")->select('cb.union_branch_id as union_branchid','u.union_branch as union_branch_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								->leftjoin('membership as m','m.id','=','mm.MemberCode')
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->leftjoin('company as c','cb.company_id','=','c.id')
                                ->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $datestring)
								->where('mm.update_status', '=', 1)
								->where('mm.MemberCode', '!=', Null)
								->groupBY('cb.union_branch_id')
								//->limit(2000)
								->get();
			// $company_view = DB::table("membermonthendstatus as mm")->select('mm.NUBE_BRANCH_CODE as union_branchid','u.union_branch as union_branch_name')
			// 				->leftjoin('union_branch as u','mm.NUBE_BRANCH_CODE','=','u.id')
			// 				->where('mm.StatusMonth', '=', $datestring)
			// 				->groupBY('mm.NUBE_BRANCH_CODE')
			// 				->get();
			return $company_view;
		});
		

	}
	
	public function getBranchByDate($datestring){
		$key = "getBranchByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			$company_view = DB::table("mon_sub_member as mm")->select('m.branch_id as branch_id','cb.branch_name as branch_name','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								->leftjoin('membership as m','m.id','=','mm.MemberCode')
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->leftjoin('company as c','cb.company_id','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $datestring)
								->where('mm.update_status', '=', 1)
								->where('mm.MemberCode', '!=', Null)
								->groupBY('m.branch_id')
								//->limit(2000)
								->get();

		    	// $company_view = DB::table("membermonthendstatus as mm")->select('mm.BRANCH_CODE as branch_id','cb.branch_name as branch_name','c.company_name as company_name')
                //                 ->leftjoin('company_branch as cb','mm.BRANCH_CODE','=','cb.id')
				// 				->leftjoin('company as c','mm.BANK_CODE','=','c.id')
                //                 ->where('mm.StatusMonth', '=', $datestring)
				// 				->groupBY('mm.BRANCH_CODE')
				// 				->get();
				return $company_view;
		});
		

	}
	
	public function getMonthEndByDate($datestring){
		$key = "getMonthEndByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			$monthno = date('m',strtotime($datestring));
			$yearno = date('Y',strtotime($datestring));
			$last_month_no = date('m',strtotime($datestring.' -1 Month'));
			$last_month_year = date('Y',strtotime($datestring.' -1 Month'));

			$members_view = DB::table('mon_sub_member as mm')
					->select('c.id as cid','m.name','m.id as id','m.branch_id as branch_id', 'm.member_number','com.company_name','mm.NRIC as new_ic','c.branch_name as branch_name','com.short_code as companycode')
					->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
					->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
					->leftjoin('membership as m','m.id','=','mm.MemberCode')
					->leftjoin('company_branch as c','c.id','=','m.branch_id')
					->leftjoin('company as com','com.id','=','sc.CompanyCode')
					->where('ms.Date', '=', $datestring)
					->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '!=', $monthno.'-'.$yearno)
					->where(function ($query) {
						$query->where('mm.StatusId', '=', 1)
							  ->orWhere('mm.StatusId', '=', 2);
					})
					//->where('mm.approval_status', '=', 1)
					->where('mm.update_status', '=', 1)
					->get();

			// $members_view = DB::table($this->membermonthendstatus_table.' as ms')
			// 		->select('c.id as cid','m.name','m.id as id','ms.BRANCH_CODE as branch_id', 'm.member_number','com.company_name','m.old_ic','m.new_ic','c.branch_name as branch_name','com.short_code as companycode','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`INSURANCE_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
			// 		->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
			// 		->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
			// 		->leftjoin('company as com','com.id','=','ms.BANK_CODE')
			// 		->where('ms.StatusMonth', '=', $datestring)
			// 		->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '!=', $monthno.'-'.$yearno)
			// 		->where(function ($query) {
			// 			$query->where('ms.STATUS_CODE', '=', 1)
			// 				  ->orWhere('ms.STATUS_CODE', '=', 2);
			// 		})
			// 		->get();
		    	
			return $members_view;
		});
		

	}

	public function getMonthEndByDateFilter($datestring,$company_id,$branchid,$memberid,$unionbranch_id){
		$key = "getMonthEndByDateFilter.{$datestring}.c.{$company_id}.b.{$branchid}.m.{$memberid}.u.{$unionbranch_id}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring,$company_id,$branchid,$memberid,$unionbranch_id)
		{
			$monthno = date('m',strtotime($datestring));
			$yearno = date('Y',strtotime($datestring));
			
			$last_month_no = date('m',strtotime($datestring.' -1 Month'));
			$last_month_year = date('Y',strtotime($datestring.' -1 Month'));

			$members_view = DB::table('mon_sub_member as mm')
					->select('c.id as cid','m.name','m.id as id','m.branch_id as branch_id', 'm.member_number','com.company_name','mm.NRIC as new_ic','c.branch_name as branch_name','com.short_code as companycode')
					->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
					->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
					->leftjoin('membership as m','m.id','=','mm.MemberCode')
					->leftjoin('company_branch as c','c.id','=','m.branch_id')
					->leftjoin('company as com','com.id','=','sc.CompanyCode')
					->where('ms.Date', '=', $datestring)
					->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '!=', $monthno.'-'.$yearno)
					->where(function ($query) {
						$query->where('mm.StatusId', '=', 1)
							  ->orWhere('mm.StatusId', '=', 2);
					})
					//->where('mm.approval_status', '=', 1)
					->where('mm.update_status', '=', 1);
					
			
			// $members_view = DB::table($this->membermonthendstatus_table.' as ms')
			// 		->select('c.id as cid','m.name','m.id as id','ms.BRANCH_CODE as branch_id', 'm.member_number','com.company_name','m.old_ic','m.new_ic','c.branch_name as branch_name','com.short_code as companycode','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`INSURANCE_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
			// 		->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
			// 		->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
			// 		->leftjoin('company as com','com.id','=','ms.BANK_CODE')
			// 		->where('ms.StatusMonth', '=', $datestring)
			// 		->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '!=', $monthno.'-'.$yearno)
			// 		->where(function ($query) {
			// 			$query->where('ms.STATUS_CODE', '=', 1)
			// 				  ->orWhere('ms.STATUS_CODE', '=', 2);
			// 		});

			if($branchid!=""){
				$members_view = $members_view->where('m.branch_id','=',$branchid);
			}else{
				if($company_id!=""){
					$members_view = $members_view->where('sc.CompanyCode','=',$company_id);
				}
				if($unionbranch_id!=""){
					$members_view = $members_view->where('c.union_branch_id','=',$unionbranch_id);
				}
			}
			if($memberid!=""){
				$members_view = $members_view->where('m.id','=',$memberid);
			}
				
			$members_view = $members_view->get();
		    	
			return $members_view;
		});

	}

	public function getPremiumMonthEndByDate($datestring){
		$key = "getPremiumMonthEndByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			$monthno = date('m',strtotime($datestring));
			$yearno = date('Y',strtotime($datestring));

			$last_month_no = date('m',strtotime($datestring.' -1 Month'));
			$last_month_year = date('Y',strtotime($datestring.' -1 Month'));

			$members_view = DB::table('mon_sub_member as mm')
					->select('c.id as cid','m.name','m.id as id','m.branch_id as branch_id', 'm.member_number','com.company_name','mm.NRIC as new_ic','c.branch_name as branch_name','com.short_code as companycode')
					->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
					->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
					->leftjoin('membership as m','m.id','=','mm.MemberCode')
					->leftjoin('company_branch as c','c.id','=','m.branch_id')
					->leftjoin('company as com','com.id','=','sc.CompanyCode')
					->where('ms.Date', '=', $datestring)
					->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '=', $monthno.'-'.$yearno)
					->where(function ($query) {
						$query->where('mm.StatusId', '=', 1)
							  ->orWhere('mm.StatusId', '=', 2);
					})
					//->where('mm.approval_status', '=', 1)
					->where('mm.update_status', '=', 1)
					->get();
			// $members_view = DB::table($this->membermonthendstatus_table.' as ms')
			// 	         ->select('c.id as cid','m.name','m.id as id','m.branch_id as branch_id', 'm.member_number','com.company_name','m.old_ic','m.new_ic','c.branch_name as branch_name','com.short_code as companycode','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`INSURANCE_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
			// 	         ->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
			// 	         ->leftjoin('company_branch as c','c.id','=','m.branch_id')
			// 			 ->leftjoin('company as com','com.id','=','c.company_id')
			// 			 ->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '=', $monthno.'-'.$yearno)
			// 			 ->where(DB::raw('DATE_FORMAT(ms.StatusMonth, "%m-%Y")'), '=', $monthno.'-'.$yearno)
			// 			->get();
		    	
			return $members_view;
		});
		
	}

	public function getSummaryMonthEndByDate($datestring){
		$key = "getSummaryMonthEndByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			$members_view = DB::table($this->membermonthendstatus_table.' as ms')
						->select('com.company_name','com.short_code as companycode',DB::raw("count(ms.id) as total_members"),DB::raw("ifnull(SUM(ms.`INSURANCE_AMOUNT`)+SUM(ms.`BF_AMOUNT`),0) AS totalsubs"))
						->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
						->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
						->leftjoin('company as com','com.id','=','ms.BANK_CODE')
						->where('ms.StatusMonth', '=', $datestring)
						->where(function ($query) {
							$query->where('ms.STATUS_CODE', '=', 1)
								  ->orWhere('ms.STATUS_CODE', '=', 2);
						})
						->groupBY('ms.BANK_CODE')
						//->dump()
						->get();
		    	
			return $members_view;
		});
		
	}

	public function getSummaryMonthEndByDateFilter($datestring,$companyid){
		$key = "getSummaryMonthEndByDateFilter.{$datestring}.c.{$companyid}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring,$companyid)
		{
			$members_view = DB::table($this->membermonthendstatus_table.' as ms')
						->select('com.company_name','com.short_code as companycode',DB::raw("count(ms.id) as total_members"),DB::raw("ifnull(SUM(ms.`INSURANCE_AMOUNT`)+SUM(ms.`BF_AMOUNT`),0) AS totalsubs"))
						->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
						->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
						->leftjoin('company as com','com.id','=','ms.BANK_CODE')
						->where('ms.StatusMonth', '=', $datestring)
						->where('c.company_id','=',$companyid)
						->where(function ($query) {
							$query->where('ms.STATUS_CODE', '=', 1)
								  ->orWhere('ms.STATUS_CODE', '=', 2);
						})
						->groupBY('ms.BANK_CODE')
						//->dump()
						->get();
		    	
			return $members_view;
		});
		
	}
	
	//statistics report
	public function getMonthEndCompaniesByDate($fromfulldate,$tofulldate){
		$key = "getMonthEndCompaniesByDate.f.{$fromfulldate}.t.{$tofulldate}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($fromfulldate,$tofulldate)
		{
			//  $members = DB::table('membermonthendstatus as ms')
			// 			->select('c.branch_shortcode','c.branch_name','c.id as branchid')
			// 			//->leftjoin('membership as m','m.branch_id','=','ms.BRANCH_CODE')
			// 			->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
			// 			->where(function ($query) {
			// 				$query->where('ms.STATUS_CODE', '=', 1)
			// 					  ->orWhere('ms.STATUS_CODE', '=', 2);
			// 			})
			// 			->where('ms.StatusMonth', '=', $datestring)
			// 					->groupBY('ms.BRANCH_CODE')
			// 					->get();

			$members = DB::select(DB::raw('SELECT c.branch_shortcode,comp.short_code as companycode, c.branch_name, c.id as branchid, count(*) as count,`m`.`race_id`,`m`.`gender`,ms.STATUS_CODE FROM `membermonthendstatus` AS `ms` LEFT JOIN `company_branch` AS `c` ON `c`.`id` = `ms`.`BRANCH_CODE` LEFT JOIN `company` AS `comp` ON `comp`.`id` = `ms`.`BANK_CODE` LEFT JOIN `membership` AS `m` ON `m`.`id` = `ms`.`MEMBER_CODE` WHERE ms.StatusMonth >= "'.$fromfulldate.'" AND ms.StatusMonth <= "'.$tofulldate.'" AND (`ms`.`STATUS_CODE` = 1 or `ms`.`STATUS_CODE`=2) group by ms.BRANCH_CODE,`ms`.`STATUS_CODE`,m.race_id,m.gender'));
		    	
			return $members;
		});
		

	}

	public function getMonthEndStatisticsFilter($fromfulldate,$tofulldate,$union,$company,$branch){
		$key = "getMonthEndStatisticsFilter.f.{$fromfulldate}.t.{$tofulldate}.u.{$union}.c.{$company}.b.{$branch}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($fromfulldate,$tofulldate,$union,$company,$branch)
		{
			 $members = DB::table('membermonthendstatus as ms')
			 			->select(DB::raw("c.branch_shortcode,comp.short_code as companycode, c.branch_name, c.id as branchid, count(*) as count,`m`.`race_id`,`m`.`gender`,ms.STATUS_CODE"))
						->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
						->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
						->leftjoin('company as comp','comp.id','=','ms.BANK_CODE')
						->where(function ($query) {
							$query->where('ms.STATUS_CODE', '=', 1)
								  ->orWhere('ms.STATUS_CODE', '=', 2);
						})
						->where('ms.StatusMonth', '>=', $fromfulldate)
						->where('ms.StatusMonth', '<=', $tofulldate);
			if($branch!=""){
				$members = $members->where('ms.BRANCH_CODE','=',$branch);
			}
			if($company!=""){
				$members = $members->where('ms.BANK_CODE','=',$company);
			}
			if($union!=""){
				$members = $members->where('ms.NUBE_BRANCH_CODE','=',$union);
			}
			$members = $members->groupBY('ms.BRANCH_CODE')
								->groupBY('ms.STATUS_CODE')
								->groupBY('m.race_id')
								->groupBY('m.gender')
								//->groupBY('m.id')
								//->dump()
								->get();
		    	
			return $members;
		});
		

	}

	// public function getMonthEndStatisticsFilter($datestring,$union,$company,$branch){
	// 	$key = "getMonthEndStatisticsFilter.{$datestring}.u.{$union}.c.{$company}.b.{$branch}";
	// 	$cacheKey = $this->getCacheKey($key);
		
	// 	return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring,$union,$company,$branch)
	// 	{
	// 		 $members = DB::table('membermonthendstatus as ms')
	// 		 			->select(DB::raw("c.branch_shortcode,comp.short_code as companycode, c.branch_name, c.id as branchid, count(*) as count,`m`.`race_id`,`m`.`gender`,ms.STATUS_CODE"))
	// 					->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
	// 					->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
	// 					->leftjoin('company as comp','comp.id','=','ms.BANK_CODE')
	// 					->where(function ($query) {
	// 						$query->where('ms.STATUS_CODE', '=', 1)
	// 							  ->orWhere('ms.STATUS_CODE', '=', 2);
	// 					})
	// 					->where('ms.StatusMonth', '=', $datestring);
	// 		if($branch!=""){
	// 			$members = $members->where('ms.BRANCH_CODE','=',$branch);
	// 		}
	// 		if($company!=""){
	// 			$members = $members->where('ms.BANK_CODE','=',$company);
	// 		}
	// 		if($union!=""){
	// 			$members = $members->where('ms.NUBE_BRANCH_CODE','=',$union);
	// 		}
	// 		$members = $members->groupBY('ms.BRANCH_CODE')
	// 							->groupBY('ms.STATUS_CODE')
	// 							->groupBY('m.race_id')
	// 							->groupBY('m.gender')
	// 							//->dump()
	// 							->get();
		    	
	// 		return $members;
	// 	});
		

	// }

	public function getMontendcompanyGroup($companies,$datestring){
		$company_str_List ='';
		foreach($companies as $cids){
			$company_str_List .=$cids.",";
		}
		$key = "getMontendcompanyGroup.{$datestring}.c.{$company_str_List}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($companies,$datestring)
		{
			$monthno = date('m',strtotime($datestring));
			$yearno = date('Y',strtotime($datestring));
			$members_view = DB::table('mon_sub_member as mm')
					->select(DB::raw("count(mm.id) as total_members"))
					->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
					->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
					//->leftjoin('membership as m','m.id','=','mm.MemberCode')
					//->leftjoin('company_branch as c','c.id','=','m.branch_id')
					//->leftjoin('company as com','com.id','=','sc.CompanyCode')
					->where(DB::raw('DATE_FORMAT(ms.Date, "%m-%Y")'), '=', $monthno.'-'.$yearno)
					->where(function ($query) {
						$query->where('mm.StatusId', '=', 1)
							  ->orWhere('mm.StatusId', '=', 2);
					})
					->whereIn('sc.CompanyCode', $companies)
					//->where('mm.approval_status', '=', 1)
					->where('mm.update_status', '=', 1)
					->first();

			// $members = DB::table('membermonthendstatus as ms')
            //     ->select(DB::raw("count(ms.id) as total_members"),DB::raw("ifnull(SUM(ms.`INSURANCE_AMOUNT`)+SUM(ms.`BF_AMOUNT`),0) AS totalsubs"))
            //     ->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
            //     ->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
            //     ->leftjoin('company as com','com.id','=','ms.BANK_CODE');
      
			// 	$members = $members->where(DB::raw('DATE_FORMAT(ms.StatusMonth, "%m-%Y")'), '=', $monthno.'-'.$yearno);
			// 	$members = $members->whereIn('ms.BANK_CODE', $companies);
						
			// 	$members = $members->first();
		    	
			return $members_view;
		});
	}

	public function get_gender_race_count($raceid,$branchid,$status_active,$month_year,$gender){
		$key = "get_gender_race_count.{$raceid}.b.{$branchid}.s.{$status_active}.m.{$month_year}.g.{$gender}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($raceid,$branchid,$status_active,$month_year,$gender)
		{
			if($month_year!=""){
				$fmmm_date = explode("/",$month_year);
				$monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
				$yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
			}else{		
				$monthno = date('m');
				$yearno = date('Y');
			}

			$male_count = DB::table('membermonthendstatus as ms')
						->select('ms.id')
						->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
						//->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
						//->leftjoin('race as r','m.race_id','=','r.id')
						->where('m.race_id','=',$raceid)
						->where('ms.BRANCH_CODE','=',$branchid)
						->where('m.gender','=',$gender)
						->where(DB::raw('month(ms.StatusMonth)'),'=',$monthno)  
						->where(DB::raw('year(ms.StatusMonth)'),'=',$yearno)  
						->where('ms.STATUS_CODE','=',$status_active)
						//->dump()
						->count(); 
			return $male_count;
		});
	}

	public function get_all_gender_race_count($branchid,$month_year){
		$key = "get_all_gender_race_count.{$branchid}.m.{$month_year}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($branchid,$month_year)
		{
			if($month_year!=""){
				$fmmm_date = explode("/",$month_year);
				$monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
				$yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
			}else{		
				$monthno = date('m');
				$yearno = date('Y');
			}

			$male_count = DB::select(DB::raw('SELECT count(*) as count,`m`.`race_id`,`m`.`gender`,ms.STATUS_CODE FROM `membermonthendstatus` AS `ms` LEFT JOIN `membership` AS `m` ON `m`.`id` = `ms`.`MEMBER_CODE` WHERE `ms`.`BRANCH_CODE` = "'.$branchid.'" AND MONTH(ms.StatusMonth) = "'.$monthno.'" AND YEAR(ms.StatusMonth) = "'.$yearno.'" AND (`ms`.`STATUS_CODE` = 1 or `ms`.`STATUS_CODE`=2) group by `ms`.`STATUS_CODE`,m.race_id,m.gender'));

			// $male_count = DB::table('membermonthendstatus as ms')
			// 			->select('ms.id')
			// 			->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
			// 			//->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
			// 			//->leftjoin('race as r','m.race_id','=','r.id')
			// 			->where('m.race_id','=',$raceid)
			// 			->where('ms.BRANCH_CODE','=',$branchid)
			// 			->where('m.gender','=',$gender)
			// 			->where(DB::raw('month(ms.StatusMonth)'),'=',$monthno)  
			// 			->where(DB::raw('year(ms.StatusMonth)'),'=',$yearno)  
			// 			->where('ms.STATUS_CODE','=',$status_active)
			// 			->dump()
			// 			->count(); 
			return $male_count;
		});
	}
	
	public function get_all_union_gender_race_count($branchid,$month_year){
		$key = "get_all_union_gender_race_count.{$branchid}.m.{$month_year}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($branchid,$month_year)
		{
			if($month_year!=""){
				$fmmm_date = explode("/",$month_year);
				$monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
				$yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
			}else{		
				$monthno = date('m');
				$yearno = date('Y');
			}

			$male_count = DB::select(DB::raw('SELECT count(*) as count,`m`.`race_id`,`m`.`gender`,ms.STATUS_CODE FROM `membermonthendstatus` AS `ms` LEFT JOIN `membership` AS `m` ON `m`.`id` = `ms`.`MEMBER_CODE` WHERE `ms`.`NUBE_BRANCH_CODE` = "'.$branchid.'" AND MONTH(ms.StatusMonth) = "'.$monthno.'" AND YEAR(ms.StatusMonth) = "'.$yearno.'" AND (`ms`.`STATUS_CODE` = 1 or `ms`.`STATUS_CODE`=2) group by `ms`.`STATUS_CODE`,m.race_id,m.gender'));

			// $male_count = DB::table('membermonthendstatus as ms')
			// 			->select('ms.id')
			// 			->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
			// 			//->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
			// 			//->leftjoin('race as r','m.race_id','=','r.id')
			// 			->where('m.race_id','=',$raceid)
			// 			->where('ms.NUBE_BRANCH_CODE','=',$branchid)
			// 			->where('m.gender','=',$gender)
			// 			->where(DB::raw('month(ms.StatusMonth)'),'=',$monthno)  
			// 			->where(DB::raw('year(ms.StatusMonth)'),'=',$yearno)  
			// 			->where('ms.STATUS_CODE','=',$status_active)
			// 			->count(); 
			return $male_count;
		});
	}

	// public function get_all_union_gender_race_count($branchid,$month_year){
	// 	$key = "get_all_union_gender_race_count.{$branchid}.m.{$month_year}";
	// 	$cacheKey = $this->getCacheKey($key);
	// 	return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($branchid,$month_year)
	// 	{
	// 		if($month_year!=""){
	// 			$fmmm_date = explode("/",$month_year);
	// 			$monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
	// 			$yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
	// 		}else{		
	// 			$monthno = date('m');
	// 			$yearno = date('Y');
	// 		}

	// 		$male_count = DB::select(DB::raw('SELECT count(*) as count,`m`.`race_id`,`m`.`gender`,ms.STATUS_CODE FROM `membermonthendstatus` AS `ms` LEFT JOIN `membership` AS `m` ON `m`.`id` = `ms`.`MEMBER_CODE` WHERE `ms`.`NUBE_BRANCH_CODE` = "'.$branchid.'" AND MONTH(ms.StatusMonth) = "'.$monthno.'" AND YEAR(ms.StatusMonth) = "'.$yearno.'" AND (`ms`.`STATUS_CODE` = 1 or `ms`.`STATUS_CODE`=2) group by `ms`.`STATUS_CODE`,m.race_id,m.gender'));

			
	// 		return $male_count;
	// 	});
	// }
	
	public function getMonthEndUnionByDate($datestring){
		$key = "getMonthEndUnionByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			 $members = DB::table('membermonthendstatus as ms')
						->select('u.union_branch as branch_name','ms.NUBE_BRANCH_CODE as branchid',DB::raw("count(*) as count"),'m.race_id','m.gender','ms.STATUS_CODE')
						->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
						//->leftjoin('company_branch as cb','c.id','=','ms.BRANCH_CODE')
						->leftjoin('union_branch as u','u.id','=','ms.NUBE_BRANCH_CODE')
						->where(function ($query) {
							$query->where('ms.STATUS_CODE', '=', 1)
								  ->orWhere('ms.STATUS_CODE', '=', 2);
						})
						->where('ms.StatusMonth', '=', $datestring)
								->groupBY('ms.NUBE_BRANCH_CODE')
								->groupBY('ms.STATUS_CODE')
								->groupBY('m.race_id')
								->groupBY('m.gender')
								->get();
		    	
			return $members;
		});
	}
	
	public function getMonthEndUnionStatisticsFilter($datestring,$union){
		$key = "getMonthEndUnionStatisticsFilter.{$datestring}.u.{$union}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring,$union)
		{
			 $members = DB::table('membermonthendstatus as ms')
						->select('u.union_branch as branch_name','ms.NUBE_BRANCH_CODE as branchid',DB::raw("count(*) as count"),'m.race_id','m.gender','ms.STATUS_CODE')
						->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
						->leftjoin('union_branch as u','u.id','=','ms.NUBE_BRANCH_CODE')
						->where(function ($query) {
							$query->where('ms.STATUS_CODE', '=', 1)
								  ->orWhere('ms.STATUS_CODE', '=', 2);
						})
						->where('ms.StatusMonth', '=', $datestring);
			
			if($union!=""){
				$members = $members->where('ms.NUBE_BRANCH_CODE','=',$union);
			}
			$members = $members->groupBY('ms.NUBE_BRANCH_CODE')
								->groupBY('ms.STATUS_CODE')
								->groupBY('m.race_id')
								->groupBY('m.gender')
								->get();
		    	
			return $members;
		});
		

	}
	
	public function getMontendcompanyGroupVariation($companies,$datestring){
		$company_str_List ='';
		foreach($companies as $cids){
			$company_str_List .=$cids.",";
		}
		$key = "getMontendcompanyGroupVariation.{$datestring}.c.{$company_str_List}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($companies,$datestring)
		{
			$monthno = date('m',strtotime($datestring));
			$yearno = date('Y',strtotime($datestring));
			$members_view = DB::table('mon_sub_member as mm')
					->select(DB::raw("count(mm.id) as total_members"))
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

			// $members = DB::table('membermonthendstatus as ms')
            //     ->select(DB::raw("count(ms.id) as total_members"),DB::raw("ifnull(SUM(ms.`INSURANCE_AMOUNT`)+SUM(ms.`BF_AMOUNT`),0) AS totalsubs"))
            //     ->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
            //     ->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
            //     ->leftjoin('company as com','com.id','=','ms.BANK_CODE');
      
			// 	$members = $members->where(DB::raw('DATE_FORMAT(ms.StatusMonth, "%m-%Y")'), '=', $monthno.'-'.$yearno);
			// 	$members = $members->whereIn('ms.BANK_CODE', $companies);
						
			// 	$members = $members->first();
		    	
			return $members_view;
		});
		
	}
	
	public function getMontendcompanymembers($companies,$datestring){
		$company_str_List ='';
		foreach($companies as $cids){
			$company_str_List .=$cids.",";
		}
		$key = "getMontendcompanymembers.{$datestring}.c.{$company_str_List}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($companies,$datestring)
		{
			$monthno = date('m',strtotime($datestring));
			$yearno = date('Y',strtotime($datestring));
			$members_view = DB::table('mon_sub_member as mm')
					->select('mm.MemberCode as memberid')
					->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
					->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
					//->leftjoin('membership as m','m.id','=','mm.MemberCode')
					//->leftjoin('company_branch as c','c.id','=','m.branch_id')
					//->leftjoin('company as com','com.id','=','sc.CompanyCode')
					->where(DB::raw('DATE_FORMAT(ms.Date, "%m-%Y")'), '=', $monthno.'-'.$yearno)
					/* ->where(function ($query) {
						$query->where('mm.StatusId', '=', 1)
							  ->orWhere('mm.StatusId', '=', 2);
					}) */
					->whereIn('sc.CompanyCode', $companies)
					//->where('mm.approval_status', '=', 1)
					->where('mm.update_status', '=', 1)
					->get();

			// $members = DB::table('membermonthendstatus as ms')
            //     ->select(DB::raw("count(ms.id) as total_members"),DB::raw("ifnull(SUM(ms.`INSURANCE_AMOUNT`)+SUM(ms.`BF_AMOUNT`),0) AS totalsubs"))
            //     ->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
            //     ->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
            //     ->leftjoin('company as com','com.id','=','ms.BANK_CODE');
      
			// 	$members = $members->where(DB::raw('DATE_FORMAT(ms.StatusMonth, "%m-%Y")'), '=', $monthno.'-'.$yearno);
			// 	$members = $members->whereIn('ms.BANK_CODE', $companies);
						
			// 	$members = $members->first();
		    	
			return $members_view;
		});
	}

	public function getMonthEndDue($datestring){
		$key = "getMonthEndDue.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			 $members = DB::table('membermonthendstatus as ms')->select(DB::raw('max(DATE_FORMAT(ms.LASTPAYMENTDATE,"%d/%m/%Y")) as LASTPAYMENTDATE'),'ms.MEMBER_CODE','ms.TOTALMONTHSDUE','m.name','m.member_number','m.employee_id',DB::raw('if(m.new_ic is not null,m.new_ic,m.old_ic) as ic'),DB::raw("DATE_FORMAT(m.doj,'%d/%m/%Y') as doj"),'c.company_name','cb.branch_name as branch_name','u.union_branch as unionbranch')
			     ->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
			     ->leftjoin('company as c','c.id','=','ms.BANK_CODE')
			     ->leftjoin('company_branch as cb','cb.id','=','ms.BRANCH_CODE')
			     ->leftjoin('union_branch as u','u.id','=','ms.NUBE_BRANCH_CODE')
				 ->where('ms.StatusMonth', '=', $datestring)
				 ->groupBy('ms.MEMBER_CODE')
			     ->orderBy('ms.id','desc')
				 ->get();
		    	
			return $members;
		});
	}

	public function getMonthEndDueFilter($datestring,$company_id,$unionbranch_id){
		$key = "getMonthEndDueFilter.{$datestring}.c.{$company_id}.u.{$unionbranch_id}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring,$company_id,$unionbranch_id)
		{
			$members = DB::table('membermonthendstatus as ms')->select(DB::raw('max(DATE_FORMAT(ms.LASTPAYMENTDATE,"%d/%m/%Y")) as LASTPAYMENTDATE'),'ms.MEMBER_CODE','ms.TOTALMONTHSDUE','m.name','m.member_number','m.employee_id',DB::raw('if(m.new_ic is not null,m.new_ic,m.old_ic) as ic'),DB::raw("DATE_FORMAT(m.doj,'%d/%m/%Y') as doj"),'c.company_name','cb.branch_name as branch_name','u.union_branch as unionbranch')
			     ->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
			     ->leftjoin('company as c','c.id','=','ms.BANK_CODE')
			     ->leftjoin('company_branch as cb','cb.id','=','ms.BRANCH_CODE')
				 ->leftjoin('union_branch as u','u.id','=','ms.NUBE_BRANCH_CODE')
				 ->where('ms.StatusMonth', '=', $datestring);
			if($company_id!=""){
				$members = $members->where('ms.BANK_CODE','=',$company_id);
			}else{
				if($unionbranch_id!=""){
					$members = $members->where('ms.NUBE_BRANCH_CODE','=',$unionbranch_id);
				}
			}
			$members = $members->groupBy('ms.MEMBER_CODE')
			     ->orderBy('ms.id','desc')
				 ->get();
		    	
			return $members;
		});
	}

	public function getVariationMembers($type_id,$date,$type){
		$key = "getVariationMembers.{$date}.typeid.{$type_id}.type.{$type}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($type_id,$date,$type)
		{	
			if($date==""){
				$date = date('Y-m-01');
			}
			$month = date("m", strtotime($date));
			$year = date("Y", strtotime($date));
			
			$subscription_qry = DB::table("mon_sub_member as mm")->select('m.member_number as member_number','m.name as name','m.doj as doj','ms.Date as pay_date','mm.Amount as SUBSCRIPTION_AMOUNT','m.salary as salary','m.id as member_id','m.status_id as STATUS_CODE')
						->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
						->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
						->leftjoin('membership as m','m.id','=','mm.MemberCode')
						->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
						->leftjoin('company as c','cb.company_id','=','c.id')
						->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
						->where('mm.update_status', '=', 1)
						->where('mm.MemberCode', '!=', Null)
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
			return $subscriptions;
		});
	}


	public function getCacheKey($key){
		$key = strtoupper($key);
		return self::CACHE_KEY.".$key"; 
	}
}
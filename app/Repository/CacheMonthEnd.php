<?php

namespace App\Repository;

use App\Model\Membership;
use Carbon\Carbon;
use Cache;
use DB;

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
			$company_view = DB::table("mon_sub_member as mm")->select('cb.company_id as company_id','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
								->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
								->leftjoin('membership as m','m.id','=','mm.MemberCode')
								->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
								->leftjoin('company as c','cb.company_id','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '=', $datestring)
								->where('mm.update_status', '=', 1)
								->where('mm.MemberCode', '!=', Null)
								->groupBY('cb.company_id')
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
			$members_view = DB::table($this->membermonthendstatus_table.' as ms')
					->select('c.id as cid','m.name','m.id as id','ms.BRANCH_CODE as branch_id', 'm.member_number','com.company_name','m.old_ic','m.new_ic','c.branch_name as branch_name','com.short_code as companycode','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`INSURANCE_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
					->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
					->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
					->leftjoin('company as com','com.id','=','ms.BANK_CODE')
					->where('ms.StatusMonth', '=', $datestring)
					->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '!=', $monthno.'-'.$yearno)
					->where(function ($query) {
						$query->where('ms.STATUS_CODE', '=', 1)
							  ->orWhere('ms.STATUS_CODE', '=', 2);
					})
					//->dump()
					//->where(DB::raw('month(m.doj)'), '!=', $monthno)
					//->where(DB::raw('year(m.doj)'), '!=', $yearno)
					->get();
		    	
			return $members_view;
		});
		

	}

	public function getMonthEndByDateFilter($datestring,$company_id,$branchid,$memberid){
		$key = "getMonthEndByDateFilter.{$datestring}.c.{$company_id}.b.{$branchid}.m.{$memberid}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring,$company_id,$branchid,$memberid)
		{
			$monthno = date('m',strtotime($datestring));
			$yearno = date('Y',strtotime($datestring));
			
			$members_view = DB::table($this->membermonthendstatus_table.' as ms')
					->select('c.id as cid','m.name','m.id as id','ms.BRANCH_CODE as branch_id', 'm.member_number','com.company_name','m.old_ic','m.new_ic','c.branch_name as branch_name','com.short_code as companycode','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`INSURANCE_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
					->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
					->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
					->leftjoin('company as com','com.id','=','ms.BANK_CODE')
					->where('ms.StatusMonth', '=', $datestring)
					->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '!=', $monthno.'-'.$yearno)
					->where(function ($query) {
						$query->where('ms.STATUS_CODE', '=', 1)
							  ->orWhere('ms.STATUS_CODE', '=', 2);
					});

			if($branchid!=""){
				$members_view = $members_view->where('ms.BRANCH_CODE','=',$branchid);
			}else{
				if($company_id!=""){
					$members_view = $members_view->where('ms.BANK_CODE','=',$company_id);
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
			$members_view = DB::table($this->membermonthendstatus_table.' as ms')
				         ->select('c.id as cid','m.name','m.id as id','m.branch_id as branch_id', 'm.member_number','com.company_name','m.old_ic','m.new_ic','c.branch_name as branch_name','com.short_code as companycode','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`INSURANCE_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
				         ->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
				         ->leftjoin('company_branch as c','c.id','=','m.branch_id')
						 ->leftjoin('company as com','com.id','=','c.company_id')
						 ->where(DB::raw('DATE_FORMAT(m.doj, "%m-%Y")'), '=', $monthno.'-'.$yearno)
						 ->where(DB::raw('DATE_FORMAT(ms.StatusMonth, "%m-%Y")'), '=', $monthno.'-'.$yearno)
						//->where('m.doj', '=', $datestring)
						->get();
		    	
			return $members_view;
		});
		
	}

	public function getSummaryMonthEndByDate($datestring){
		$key = "getSummaryMonthEndByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			$members_view = DB::table($this->membermonthendstatus_table.' as ms')
						->select('com.company_name','com.short_code as companycode',DB::raw("ifnull(SUM(ms.SUBSCRIPTION_AMOUNT),0) as totalsum"),DB::raw("count(ms.id) as total_members"),DB::raw("ifnull(SUM(ms.`INSURANCE_AMOUNT`)+SUM(ms.`BF_AMOUNT`),0) AS totalsubs"))
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
						->select('com.company_name','com.short_code as companycode',DB::raw("ifnull(SUM(ms.SUBSCRIPTION_AMOUNT),0) as totalsum"),DB::raw("count(ms.id) as total_members"),DB::raw("ifnull(SUM(ms.`INSURANCE_AMOUNT`)+SUM(ms.`BF_AMOUNT`),0) AS totalsubs"))
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
	
	public function getMonthEndCompaniesByDate($datestring){
		$key = "getMonthEndCompaniesByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			 $members = DB::table('membermonthendstatus as ms')
						->select('c.branch_shortcode','c.branch_name','c.id as branchid')
						//->leftjoin('membership as m','m.branch_id','=','ms.BRANCH_CODE')
						->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
						->where('ms.StatusMonth', '=', $datestring)
								->groupBY('ms.BRANCH_CODE')
								->get();
		    	
			return $members;
		});
		

	}



	public function getCacheKey($key){
		$key = strtoupper($key);
		return self::CACHE_KEY.".$key"; 
	}
}
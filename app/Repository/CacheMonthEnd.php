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
		$this->membermonthendstatus_table = "membermonthendstatus1";
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
		    	$company_view = DB::table("membermonthendstatus1 as mm")->select('mm.BANK_CODE as company_id','c.company_name as company_name')
                                ->leftjoin('company as c','mm.BANK_CODE','=','c.id')
                                ->where('mm.StatusMonth', '=', $datestring)
								->groupBY('mm.BANK_CODE')
								->get();
				return $company_view;
		});
		

	}
	
	public function getUnionBranchByDate($datestring){
		$key = "getUnionBranchByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
		    	$company_view = DB::table("membermonthendstatus1 as mm")->select('mm.NUBE_BRANCH_CODE as union_branchid','u.union_branch as union_branch_name')
                                ->leftjoin('union_branch as u','mm.NUBE_BRANCH_CODE','=','u.id')
                                ->where('mm.StatusMonth', '=', $datestring)
								->groupBY('mm.NUBE_BRANCH_CODE')
								->get();
				return $company_view;
		});
		

	}
	
	public function getBranchByDate($datestring){
		$key = "getBranchByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
		    	$company_view = DB::table("membermonthendstatus1 as mm")->select('mm.BRANCH_CODE as branch_id','cb.branch_name as branch_name','c.company_name as company_name')
                                ->leftjoin('company_branch as cb','mm.BRANCH_CODE','=','cb.id')
								->leftjoin('company as c','mm.BANK_CODE','=','c.id')
                                ->where('mm.StatusMonth', '=', $datestring)
								->groupBY('mm.BRANCH_CODE')
								->get();
				return $company_view;
		});
		

	}
	
	public function getMonthEndByDate($datestring){
		$key = "getMonthEndByDate.{$datestring}";
		$cacheKey = $this->getCacheKey($key);
		
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($datestring)
		{
			$members_view = DB::table($this->membermonthendstatus_table.' as ms')
					->select('c.id as cid','m.name','m.id as id','m.branch_id as branch_id', 'm.member_number','com.company_name','m.old_ic','m.new_ic','c.branch_name as branch_name','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`SUBSCRIPTION_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
					->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
                    ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                    ->leftjoin('company as com','com.id','=','c.company_id')
					->where('ms.StatusMonth', '=', $datestring)
					->get();
		    	
			return $members_view;
		});
		

	}



	public function getCacheKey($key){
		$key = strtoupper($key);
		return self::CACHE_KEY.".$key"; 
	}
}
<?php

namespace App\Repository;

use Carbon\Carbon;
use Cache;
use DB;
use App\Model\Membership;
use App\Model\MonthlySubscriptionCompany;
use App\Model\CompanyBranch;
use App\Model\Company;

class CacheMembers
{
	CONST CACHE_KEY="members";
	
	public function __construct() {
		$this->membermonthendstatus_table = "membermonthendstatus";
	}

	public function all($orderBy){
	    $key = "all.{$orderBy}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$orderBy)
		{
		    return DB::table('membership')->select('new_ic','old_ic','employee_id')->get();
		}); 
		
	}
	
	public function getcompanyidOfsubscribeCompanyid($companyid){
		$key = "getcompanyidOfsubscribeCompanyid.{$companyid}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$companyid)
		{
		    return MonthlySubscriptionCompany::where('id',$companyid)->pluck('CompanyCode')->first();
		}); 
    }
	
	public function getcompanyidbyBranchid($branchid){
		$key = "getcompanyidbyBranchid.{$branchid}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$branchid)
		{
		    return CompanyBranch::where('id',$branchid)->pluck('company_id')->first();
		}); 
    }
	
	public function getHeadcompanyidbyid($companyid){
		$key = "getHeadcompanyidbyid.{$companyid}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$companyid)
		{
			return Company::find($companyid);
		}); 
    }
	
	public function getDatebycompanyid($companyid){
		$key = "getDatebycompanyid.{$companyid}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$companyid)
		{
			return DB::table("mon_sub_company as mc")->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')->where('mc.id','=',$companyid)->pluck('Date')->first();
		}); 
    }
	
	public function getDojbyMemberCode($memberid){
		$key = "getDojbyMemberCode.{$memberid}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$memberid)
		{
			return DB::table("membership as m")->where('m.id','=',$memberid)->pluck('doj')->first();
		}); 
    }
	
	public function getTotalAmtByCode($memberid){
		$key = "getTotalAmtByCode.{$memberid}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$memberid)
		{
			return DB::table('mon_sub_member')->select(DB::raw('IFNULL(sum("Amount"),0) as amount'))
						->where('MemberCode', '=', $memberid)
						->first();
		}); 
    }
	
	public function getTotalSubsByCode($memberid){
		$key = "getTotalSubsByCode.{$memberid}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$memberid)
		{
			return DB::table('mon_sub_member')
						->where('MemberCode', '=', $memberid)
						->count();
		}); 
    }
	
	public function getMonthEndMemberStatus($cur_date, $member_code){
	$key = "getMonthEndMemberStatus.{$member_code}.d.{$cur_date}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cur_date,$member_code)
		{
			return DB::table($this->membermonthendstatus_table)->where('StatusMonth', '=', $cur_date)->where('MEMBER_CODE', '=', $member_code)->count();
		}); 
    }

	public function get($id){
		/* $key = "get.{$id}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($id)
		{
		    return Membership::find($id);
		}); */
		

	}


	public function getCacheKey($key){
		$key = strtoupper($key);
		return self::CACHE_KEY.".$key";
	}
}
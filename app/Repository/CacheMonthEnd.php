<?php

namespace App\Repository;

use App\Model\Membership;
use Carbon\Carbon;
use Cache;
use DB;

class CacheMonthEnd
{
	CONST CACHE_KEY="membersend";

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


	public function getCacheKey($key){
		$key = strtoupper($key);
		return self::CACHE_KEY.".$key"; 
	}
}
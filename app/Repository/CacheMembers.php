<?php

namespace App\Repository;

use App\Model\Membership;
use Carbon\Carbon;
use Cache;
use DB;

class CacheMembers
{
	CONST CACHE_KEY="members";

	public function all($orderBy){
		$key = "all.{$orderBy}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($cacheKey,$orderBy)
		{
		    return Membership::orderBy($orderBy)->limit(10000)->get();
		});
		

	}

	public function get($id){
		$key = "get.{$id}";
		$cacheKey = $this->getCacheKey($key);
		return Cache::remember($cacheKey,Carbon::now()->addMinutes(5), function() use($id)
		{
		    return Membership::find($id);
		});
		

	}


	public function getCacheKey($key){
		$key = strtoupper($key);
		return self::CACHE_KEY.".$key";
	}
}
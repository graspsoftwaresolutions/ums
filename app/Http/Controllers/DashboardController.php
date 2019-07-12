<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\UnionBranch;
use App\Model\Membership;
use App\Model\Company;
use App\Model\Branch;
use App\User;

class DashboardController extends Controller
{   
   public function unionBranchCount()
   {
        $union_branch_count = unionBranch::where('is_head',0)->count();
        //$phone = User::find(1)->unionbranch;
        //$union = UnionBranch::with('User')->get();
      
       return $phone;
   }
}
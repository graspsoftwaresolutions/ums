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
    $union = UnionBranch::with('User')->get();
      
       dd($union);
   }
}
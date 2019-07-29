<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\CommonHelper;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Fee;
use App\User;
use App\Model\Relation;
use App\Model\Race;
use App\Model\Reason;
use App\Model\Persontitle;
use App\Model\UnionBranch;
use App\Model\AppForm;
use App\Model\CompanyBranch;
use App\Model\Designation;
use App\Model\Status;
use App\Model\FormType;
use App\Model\Company;
use App\Mail\UnionBranchMailable;
use App\Mail\CompanyBranchMailable;
use App\Model\MonthlySubscription;
use App\Model\MonthlySubscriptionMember;
use DB;
use View;
use Mail;
use App\Role;
use URL;
use Response;


class SubscriptionController extends Controller
{
    public function __construct() {
        ini_set('memory_limit', '-1');
        $this->middleware('auth');
        $this->middleware('module:master');       
        $this->Company = new Company;
        $this->MonthlySubscription = new MonthlySubscription;
        $this->MonthlySubscriptionMember = new MonthlySubscriptionMember;
        $this->Status = new Status;
    }
    //excel file download and upload it
    public function index() {
        $data = "";
        $status_all = Status::all();
        foreach($status_all as $stat){
            //var_dump($stat->status_name); 
           // var_dump($stat->Subscription_members->count());
           //foreach($stat->Subscription_members as $memberstat){
            
          //var_dump($memberstat->count());
           
          // }
        }
       // dd($data);
       return view('subscription.sub_fileupload.sub_listing')->with('data', $data);
    }

}

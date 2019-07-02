<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Http\Request;
use App\Model\Membership;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Company;
use DB;
use View;
use Mail;
use App\Role;
use App\User;
use App\Model\MemberNominees;
use App\Helpers\CommonHelper;
use App\Mail\SendMemberMailable;
use URL;

class MembershipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
		$this->middleware('role:union|branch');
        $this->Membership = new Membership;
       
    }
    public function index()
    {
         
         $data['member_view'] = DB::table('membership')
                                 ->where('membership.status','=','1')->get();
       
        return view('membership.membership')->with('data',$data); 
       
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['member_view'] = DB::table('membership')
                                ->join('country','membership.country_id','=','country.id')
                                ->join('state','membership.state_id','=','state.id')
                                ->join('city','membership.city_id','=','city.id')
                                ->join('branch','membership.branch_id','=','branch.id')
                                ->join('persontitle','membership.member_title_id','=','persontitle.id')
                                ->join('race','membership.race_id','=','race.id')
                                ->join('designation','membership.designation_id','=','designation.id')
                                ->join('user_type','user_type.uid','=','membership.user_type')
                                ->where([
                                    ['membership.status','=','1'],
                                    ['membership.id','=',$id]
                                ])->get();
        return view('membership.view_membership')->with('data',$data); 
    }
    public function addMember()
    {
         $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
    
         $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
         $data['company_view'] = DB::table('company')->where('status','=','1')->get();
         $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
         $data['race_view'] = DB::table('race')->where('status','=','1')->get();
         $data['status_view'] = DB::table('status')->where('status','=','1')->get();
         
       
        return view('membership.add_membership')->with('data',$data);  
        
    }
    public function getoldMemberList()
    {
        
        $res = DB::table('membership')
            ->join('status','membership.status_id','=','status.id')        
        ->where([
            ['status.status_name','=','inactive']
        ])->get();
        $res['suggestions'] = [ array('value' => 'United States', 'data' => 'us') ];
        echo json_encode($res); die;
        return response()->json($res);
        
    }
    public function getStateList(Request $request)
    {
        $id = $request->country_id;
        $res = DB::table('state')
                ->select('id','state_name')
                ->where([
                    ['country_id','=',$id],
                    ['status','=','1']
                ])->get();
        
                return response()->json($res);
    }
    public function getCitiesList(Request $request){
      
        $id = $request->State_id;
        $res = DB::table('city')
        ->select('id','city_name')
        ->where([
            ['state_id','=',$id],
            ['status','=','1']
        ])->get();
       
        return response()->json($res);
    }
    
    public function getBranchList(Request $request){
       
         $id = $request->company_id;
         $res = DB::table('branch')
         ->select('id','branch_name')
         ->where([
             ['company_id','=',$id],
             ['status','=','1']
         ])->get();
       
         return response()->json($res);
     }
    public function Save(Request $request)
    {
        $request->validate([
            'member_title'=>'required',
            'member_number'=>'required',
            'name'=>'required',
            'gender'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'doe'=>'required',
            'designation'=>'required',
            'race'=>'required',
            'country_id'=>'required',
            'state_id'=>'required',
            'city_id'=>'required',
            'postal_code'=>'required',
            'address_one'=>'required',
            'dob'=>'required',
            'salary'=>'required',
            'new_ic'=>'required',
            'branch_id'=>'required',
        ],
        [
            'member_title.required'=>'Please Enter Your Title',
            'member_number.required'=>'Please Enter Member NUmber',
            'name.required'=>'Please Enter Your Name',
            'gender.required'=>'Please choose Gender',
            'phone.required'=>'Please Enter Mobile Number',
            'email.required'=>'Please Enter Email Address',
            'doe.required'=>'Please choose DOE',
            'designation.required'=>'Please choose  your Designation',
            'race.required'=>'Please Choose your Race ',
            'country_id.required'=>'Please choose  your Country',
            'state_id.required'=>'Please choose  your State',
            'city_id.required'=>'Please choose  your city',
            'postal_code.required'=>'Please Enter postal code',
            'address_one.required'=>'Please Enter your Address',
            'dob.required'=>'Please choose DOB',
            'salary.required'=>'Enter your Salary',
            'new_ic.required'=>'Please Enter New Ic Number',
            'branch_id.required'=>'Please Choose Branch Name',

        ]);

        $member_name = $request->input('name');
        $member_email = $request->input('email');

        if($member_name!="" &&  $member_email!=""){
            $member_role = Role::where('slug', 'member')->first();
            $randompass = CommonHelper::random_password(5,true);

            $member_user = new User();
            $member_user->name = $member_name;
            $member_user->email = $member_email;
            $member_user->password = bcrypt($randompass);
            $member_user->save();
            $member_user->roles()->attach($member_role);
           // return $member_user;die;

            $member['member_title_id'] = $request->input('member_title');
            $member['member_number'] = $request->input('member_number');
            $member['name'] = $request->input('name');
            $member['gender'] = $request->input('gender');
            $member['phone'] = $request->input('phone');
            $member['email'] = $request->input('email');
            $member['designation_id'] = $request->input('designation');
            $member['old_member_number'] = $request->input('old_mumber_number');
            $member['salary'] = $request->input('salary');
            $member['postal_code'] = $request->input('postal_code');
            $member['race_id'] = $request->input('race');
            $member['country_id'] = $request->input('country_id');
            $member['state_id'] = $request->input('state_id');
            $member['city_id'] = $request->input('city_id');
            $member['address_one'] = $request->input('address_one');
            $member['address_two'] = $request->input('address_two');
            $member['address_three'] = $request->input('address_three');
            $member['status_id'] = $request->input('status_id');
            $member['user_id'] = $member_user->id;
            $member['status'] = 1;

            $fm_date = explode("/",$request->input('dob'));         							
            $dob1 = $fm_date[2]."-".$fm_date[1]."-".$fm_date[0];
            $dob = date('Y-m-d', strtotime($dob1));
            $member['dob'] = $dob;

            $fmm_date = explode("/",$request->input('doe'));           							
            $doe1 = $fmm_date[2]."-".$fmm_date[1]."-".$fmm_date[0];
            $doe = date('Y-m-d', strtotime($doe1));
            $member['doe'] = $doe;

            $fmmm_date = explode("/",$request->input('doj'));           							
            $doj1 = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
            $doe = date('Y-m-d', strtotime($doj1));
            $member['doj'] = $doe;
            $member['old_ic'] = $request->input('old_ic');
            $member['new_ic'] = $request->input('new_ic');
            $member['branch_id'] = $request->input('branch_id');

            $email_exists = DB::table('membership')->where([
                            ['email','=',$member['email']],
                            ['status','=','1']
                            ])->count();
                            
            if($email_exists > 0)
            {
                return redirect()->back()->with('message','Email already Exists');
            }
            else{
              $id = $this->Membership->StoreMembership($member);
              if(!empty($member_user)){
                    $mail_data = array(
                        'name' => $member_name,
                        'email' => $member_email,
                        'password' => $randompass,
                        'site_url' => URL::to("/"),
                    );
                    $status = Mail::to($member_email)->send(new SendMemberMailable($mail_data));
               }
               if( count(Mail::failures()) > 0 ) {
                   return redirect('membership')->with('message','Member Account created successfully, Failed to send mail');
               }else{
                   return redirect('membership')->with('message','Member Account created successfully, password sent to mail');
               }
                //Save
               
                return redirect('membership')->with('message','Registration Successfull');
            }
        }else{
            return redirect('membership')->with('error','Name and email is invalid');
        }

        
    }
    public function edit($id)
    {
       
        $dec_id = Crypt::decrypt($id);
       // print_r($dec_id['id']) ;
        $id =  $dec_id['id'];
        //print_r($id) ;
         DB::connection()->enableQueryLog();
         $data['member_view'] = DB::table('membership')->select('membership.id as mid','membership.member_title_id','membership.member_number','membership.name','membership.gender','membership.designation_id','membership.email','membership.phone',
                                        'membership.country_id','membership.state_id','membership.city_id','membership.address_one','membership.address_two','membership.address_three','membership.race_id','membership.old_ic','membership.new_ic',
                                        'membership.dob','membership.doj','membership.doe','membership.postal_code','membership.salary','membership.status_id','branch_id','membership.password','membership.user_type','membership.status','country.id','country.country_name','country.status','state.id','state.state_name','state.status',
                                        'city.id','city.city_name','city.status','branch.id','branch.branch_name','branch.status','designation.id','designation.designation_name','designation.status','race.id','race.race_name','race.status','persontitle.id','persontitle.person_title','persontitle.status','membership.old_member_number')
                                ->leftjoin('country','membership.country_id','=','country.id')
                                ->leftjoin('state','membership.state_id','=','state.id')
                                ->leftjoin('city','membership.city_id','=','city.id')
                                ->leftjoin('branch','membership.branch_id','=','branch.id')
                                ->leftjoin('persontitle','membership.member_title_id','=','persontitle.id')
                                ->leftjoin('race','membership.race_id','=','race.id')
                                ->leftjoin('designation','membership.designation_id','=','designation.id')
                                ->where([
                                   ['membership.id','=',$id]
                                ])->get();

                                $queries = DB::getQueryLog();
                              // dd($queries);

        $country_id = $data['member_view'][0]->country_id;
      
        $state_id = $data['member_view'][0]->state_id;
        $city_id = $data['member_view'][0]->city_id;
        //$company_id = $data['member_view'][0]->company_id;
        $data['status_view'] = DB::table('status')->where('status','=','1')->get();
        $data['company_view'] = DB::table('company')->select('id','company_name')->where('status','=','1')->get();
        $data['state_view'] = DB::table('state')->select('id','state_name')->where('status','=','1')->where('country_id','=',$country_id)->get();
        $data['city_view'] = DB::table('city')->select('id','city_name')->where('status','=','1')->where('state_id','=',$state_id)->get();
        $data['country_view'] = DB::table('country')->select('id','country_name')->where('status','=','1')->get();
        $data['branch_view'] = DB::table('branch')->where('status','=','1')->get();
        $data['title_view'] = DB::table('persontitle')->where('status','=','1')->get();
        $data['designation_view'] = DB::table('designation')->where('status','=','1')->get();
        $data['race_view'] = DB::table('race')->where('status','=','1')->get();
        $data['relationship_view'] = DB::table('relation')->where('status','=','1')->get();
             
        return view('membership.edit_membership')->with('data',$data); 
   
    }
    public function update(Request $request)
    {
        $id = $request->input('auto_id');
        
         $fm_date = explode("/",$request->input('dob'));         							
         $dob1 = $fm_date[2]."-".$fm_date[1]."-".$fm_date[0];
         $dob = date('Y-m-d', strtotime($dob1));

         $fmm_date = explode("/",$request->input('doe'));           							
         $doe1 = $fmm_date[2]."-".$fmm_date[1]."-".$fmm_date[0];
         $doe = date('Y-m-d', strtotime($doe1));
         $member['doe'] = $doe;

         $fmmm_date = explode("/",$request->input('doj'));           							
         $doj1 = $fmmm_date[2]."-".$fmmm_date[1]."-".$fmmm_date[0];
         $doe = date('Y-m-d', strtotime($doj1));
       
        $member['member_title_id'] = $request->input('member_title');
        $member['member_number'] = $request->input('member_number');
        $member['name'] = $request->input('name');
        $member['gender'] = $request->input('gender');
        $member['phone'] = $request->input('phone');
        //$member['email'] = $request->input('email');
        $member['designation_id'] = $request->input('designation');
       // $member['race_id'] = 1;
        $member['country_id'] = $request->input('country_id');
        $member['state_id'] = $request->input('state_id');
        $member['city_id'] = $request->input('city_id');
        $member['address_one'] = $request->input('address_one');
        $member['address_two'] = $request->input('address_two');
        $member['address_three'] = $request->input('address_three');
        $member['dob'] = $dob;
        $member['old_ic'] = $request->input('old_ic');
        $member['new_ic'] = $request->input('new_ic');
        $member['branch_id'] = $request->input('branch_id');
        //$member['race_id'] = 1;
        //return $member;

        $id = DB::table('membership')->where('id','=',$id)->update($member);
        return redirect('membership')->with('message','Member Details Updated Successfull');
    }
    public function delete($id)
	{
		$data = DB::table('membership')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('membership')->with('message','Member Deleted Succesfully');
    }
    public function addNominee(Request $request){
       $nominee = new MemberNominees();

       $nominee->member_id = $request->auto_id;
       $nominee->relation_id = $request->nominee_relationship;
       $nominee->nominee_name = $request->nominee_name;
       $nominee->country_id = $request->nominee_country_id;
       $nominee->state_id = $request->nominee_state_id;
       $nominee->postal_code = $request->nominee_postal_code;
       $nominee->city_id = $request->nominee_city_id;
       $nominee->address_one = $request->nominee_address_one;
       $nominee->address_two = $request->nominee_address_two;
       $nominee->address_three = $request->nominee_address_three;
       $nominee->years = $request->nominee_years;
       $nominee->gender = $request->nominee_sex;
       $nominee->nric_n = $request->nric_n;
       $nominee->nric_o = $request->nric_o;
       $nominee->mobile = $request->nominee_mobile;
       $nominee->phone = $request->nominee_phone;

       $nominee->save();
       return $nominee;

    }
}

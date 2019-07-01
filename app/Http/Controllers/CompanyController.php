<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Company;
use DB;
use View;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->Company = new Company;
    }
    public function index()
    {
        $data['company'] = DB::table('company')->where('status','=','1')->get();
        return view('company.company')->with('data',$data);
    }
    public function addCompany()
    {
        return view('company.add_company');
    }
    public function save(Request $request)
    {
        $request->validate([ 
            'company_name' => 'required',
            'owner_name'=>'required',
            'phone' =>'required',
            'email' => 'required',
            'address_one' =>'required',
            'address_two' => 'required',
        ],
        [
            'company_name.required' => 'Please Enter your Company Name',
            'owner_name.required' => 'please Enter your owner Name',
            'phone.required' => 'Please Enter phone number',
            'email.required' => 'Please Enter your Email',
            'address_one.required' => 'Please Enter your Address',
            'address_two.required' => 'Please Enter your Address',
        ]);
        $company['company_name'] = $request->input('company_name');
        $company['owner_name'] = $request->input('owner_name');
        $company['phone'] = $request->input('phone');
        $company['email'] = $request->input('email');
        $company['address_one'] = $request->input('address_one');
        $company['address_two'] = $request->input('address_two');
        
        $data_exists = DB::table('company')->where([
            ['company_name', '=', $company['company_name']],
            ['status','=','1']
            ])->count();
        
        if($data_exists > 0 && $data_exists !='' && $data_exists != 'NULL')
        {
            return redirect('add-company')->with('message','Company Name Already Exists');
        }
        else{
            $id = $this->Company->StoreCompany($company);
        return redirect('company')->with('message','Company Name Added Succesfully');
        }
    } 
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['company_view'] = DB::table('company')->where('id','=',$id)->get(); 
        return view('company.view_company')->with('data',$data);
    }
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $data['company_edit'] = DB::table('company')->where('id','=',$id)->get(); 
        return view('company.edit_company')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
		$company['company_name'] = $request->input('company_name');
        $company['owner_name'] = $request->input('owner_name');
        $company['phone'] = $request->input('phone');
        $company['email'] = $request->input('email');
        $company['address_one'] = $request->input('address_one');
        $company['address_two'] = $request->input('address_two');
		$id = DB::table('company')->where('id','=',$id)->update($company);
		return redirect('company')->with('message','Company Details Updated Succesfully');
    }
    public function delete($id)
	{
		$data = DB::table('company')->where('id','=',$id)->update(['status'=>'0']);
		return redirect('company')->with('message','Company Deleted Succesfully');
	} 
}

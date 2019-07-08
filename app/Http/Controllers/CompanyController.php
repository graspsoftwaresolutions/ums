<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Model\Company;
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
        $data['company'] = DB::table('company')->where('status','=','1')->get();
        return view('company.add_company')->with('data',$data);
    }
    public function save(Request $request)
    {
        $request->validate([ 
            'company_name' => 'required',
            'short_code'=>'required',
            'head_of_company' =>'required'
        ],
        [
            'company_name.required' => 'Please Enter your Company Name',
            'short_code.required' => 'please Enter short code',
            'head_of_company.required' => 'Please Enter head of company',
        ]);
        $company['company_name'] = $request->input('company_name');
        $company['short_code'] = $request->input('short_code');
        $company['head_of_company'] = $request->input('head_of_company');
        
        $data_exists = DB::table('company')->where([
            ['company_name', '=', $company['company_name']],
            ['status','=','1']
            ])->count();
        
        if($data_exists > 0 && $data_exists !='' && $data_exists != 'NULL')
        {
            $defaultLanguage = app()->getLocale();
            return redirect($defaultLanguage.'/add-company')->with('Warning','Company Name Already Exists');
        }
        else{
            $id = $this->Company->StoreCompany($company);
            $defaultLanguage = app()->getLocale();
        return redirect($defaultLanguage.'/company')->with('message','Company Name Added Succesfully');
        }
    }
    public function view($id)
    {
        $id = Crypt::decrypt($id);
        $data['company_view'] = DB::table('company')->where('id','=',$id)->get(); 
        return view('company.view_company')->with('data',$data);
    }
    public function edit($lang,$id)
    {
        $id = Crypt::decrypt($id);
        $data['company'] = DB::table('company')->where('status','=','1')->get();
        $data['company_edit'] = DB::table('company')->where('id','=',$id)->get(); 
        $head_company_id = $data['company_edit'][0]->head_of_company;
        return view('company.edit_company')->with('data',$data);
    }
    public function update(Request $request)
    {
        $id = $request->input('id');
		$company['company_name'] = $request->input('company_name');
        $company['short_code'] = $request->input('short_code');
        $company['head_of_company'] = $request->input('head_of_company');
        $id = DB::table('company')->where('id','=',$id)->update($company);
        $defaultLanguage = app()->getLocale();
		return redirect($defaultLanguage.'/company')->with('message','Company Details Updated Succesfully');
    }
    public function delete($lang,$id)
	{
        $id = Crypt::decrypt($id);
        $data = DB::table('company')->where('id','=',$id)->update(['status'=>'0']);
        $defaultLanguage = app()->getLocale();
		return redirect($defaultLanguage.'/company')->with('message','Company Deleted Succesfully');
	}
}

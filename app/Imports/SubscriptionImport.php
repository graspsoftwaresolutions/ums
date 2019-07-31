<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\ToArray;
use App\User;
use App\Model\MonthlySubscription;
use App\Model\MonthlySubscriptionMember;
use App\Model\MonthlySubscriptionCompany;
use App\Model\MonSubCompanyAttach;
use Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Helpers\CommonHelper;


class SubscriptionImport implements ToCollection, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $request_data;
    protected $month_auto_id;
    protected $company_auto_id;

    public function __construct($requestinfo)
    {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', -1);
        $this->request_data = $requestinfo;
        $entry_date = $this->request_data['entry_date'];
        $sub_company = $this->request_data['sub_company'];

        $datearr = explode("/",$entry_date);  
        $monthname = $datearr[0];
        $year = $datearr[1];
        $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));

        $subscription_qry = MonthlySubscription::where('Date','=',$form_date);
        $subscription_count = $subscription_qry->count();
        if($subscription_count>0){
            $subscription_month = $subscription_qry->get();
            $this->month_auto_id = $subscription_month[0]->id;
        }else{
            $subscription_month = new MonthlySubscription();
            $subscription_month->Date = $form_date;
            $subscription_month->created_by = Auth::user()->id;
            $subscription_month->created_on = date('Y-m-d');
            $subscription_month->save();
            $this->month_auto_id =  $subscription_month->id;
        }
        
        $subscription_company_qry = MonthlySubscriptionCompany::where('MonthlySubscriptionId','=',$this->month_auto_id)->where('CompanyCode',$sub_company);
        $subscription_company_count = $subscription_company_qry->count();
        if($subscription_company_count>0){
            $subscription_company =$subscription_company_qry->get();
            $this->company_auto_id = $subscription_company[0]->id;
        }else{
            $subscription_company = new MonthlySubscriptionCompany();
            $subscription_company->MonthlySubscriptionId = $this->month_auto_id;
            $subscription_company->CompanyCode = $sub_company;
            $subscription_company->created_by = Auth::user()->id;
            $subscription_company->created_on = date('Y-m-d');
            $subscription_company->save();
    
            $this->company_auto_id =  $subscription_company->id;
        }

        $get_roles = Auth::user()->roles;
        $user_role = $get_roles[0]->id;

        $attachment = new MonSubCompanyAttach();
        $attachment->MonthlySubscriptionCompanyId = $this->company_auto_id;
        $attachment->MonthlySubscriptonFileTypeId = $user_role;
        $attachment->FilePath = 'subscription';
        $attachment->created_by = Auth::user()->id;
        $attachment->created_on = date('Y-m-d');
        $attachment->save();
       
    }

    public function collection(Collection $rows)
    {
        
        $totalcount=count($rows);      
        $count=0;
        foreach($rows as $key=>$row1){
            
            if($rows[$key] != $rows[0]){
                $icno = $row1[1];
                $nric = $row1[2];
                $membername = $row1[3];
                $amount = $row1[4];

                if($icno!='' && $nric!="" && $membername!="" && $amount!=""){

                    $subscription_member_qry = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$this->company_auto_id)
                                                ->where('NRIC',$nric);
                    $subscription_member_count = $subscription_member_qry->count();
                    if($subscription_member_count>0){
                        $subscription_member_res = MonthlySubscriptionMember::where('MonthlySubscriptionCompanyId','=',$this->company_auto_id)
                        ->where('NRIC',$nric)->get();
                        $company_member_id = $subscription_member_res[0]->id;
                        $subscription_member = MonthlySubscriptionMember::find($company_member_id);
                    }else{
                        $subscription_member = new MonthlySubscriptionMember();
                        $subscription_member->MonthlySubscriptionCompanyId = $this->company_auto_id;
                    }
                    $subscription_member->NRIC = $nric;
                    $subscription_member->Name = $membername;
                    $subscription_member->Amount = $amount;
                    $subscription_member->StatusId = null;
                    $subscription_member->MemberCode = null;
                    $subscription_member->created_by = Auth::user()->id;
                    $subscription_member->created_on = date('Y-m-d');
                    $subscription_member->save();
                }
                //return $subscription_member;

            }
            $count++;
        }
    }
	// public function headingRow(): int
    // {
    //     return 2;
    // } 
}

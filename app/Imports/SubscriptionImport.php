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
        $this->request_data = $requestinfo;
        $entry_date = $this->request_data['entry_date'];
        $sub_company = $this->request_data['sub_company'];

        $datearr = explode("/",$entry_date);  
        $monthname = $datearr[0];
        $year = $datearr[1];
        $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
        $subscription_month = new MonthlySubscription();
        $subscription_month->Date = $form_date;
        $subscription_month->created_by = Auth::user()->id;
        $subscription_month->created_on = date('Y-m-d');
        $subscription_month->save();
        $this->month_auto_id =  $subscription_month->id;

        $subscription_company = new MonthlySubscriptionCompany();
        $subscription_company->MonthlySubscriptionId = $this->month_auto_id;
        $subscription_company->CompanyCode = $sub_company;
        $subscription_company->created_by = $this->month_auto_id;
        $subscription_company->created_on = date('Y-m-d');
        $subscription_company->save();

        $this->company_auto_id =  $subscription_company->id;
    }

    public function collection(Collection $rows)
    {
        $totalcount=count($rows);      
        foreach($rows as $key=>$row1){
            
            if($rows[$key] != $rows[0]){
                $icno = $row1[1];
                $nric = $row1[2];
                $membername = $row1[3];
                $amount = $row1[4];
                $subscription_member = new MonthlySubscriptionMember();
                $subscription_member->MonthlySubscriptionCompanyId = $this->company_auto_id;
                $subscription_member->NRIC = $nric;
                $subscription_member->Name = $membername;
                $subscription_member->Amount = $amount;
                $subscription_member->StatusId = null;
                $subscription_member->MemberCode = null;
                $subscription_member->created_by = Auth::user()->id;
                $subscription_member->created_on = date('Y-m-d');
                $subscription_member->save();
                //return $subscription_member;

            }
        }
    }
	// public function headingRow(): int
    // {
    //     return 2;
    // } 
}

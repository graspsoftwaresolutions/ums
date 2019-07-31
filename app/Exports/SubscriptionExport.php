<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Model\MonthlySubscription;
use App\Model\MonthlySubscriptionMember;
use App\Model\MonthlySubscriptionCompany;
use DB;

class SubscriptionExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $export_type;
    protected $request_data;
    protected $month_auto_id;
    protected $company_auto_id;

    public function __construct($export_type, $requestinfo)
    {
        $this->export_type = $export_type;
        $this->request_data = $requestinfo;
        $entry_date = $this->request_data['entry_date'];
        $sub_company = $this->request_data['sub_company'];

        $datearr = explode("/",$entry_date);  
        $monthname = $datearr[0];
        $year = $datearr[1];
        $form_date = date('Y-m-d',strtotime('01-'.$monthname.'-'.$year));
    }

    public function collection()
    {
        if($this->export_type==0){
            return collect([]);
        }else{
            $company_id = $this->request_data['company_id'];
            $subscription_data = collect(DB::select( DB::raw("SELECT @a:=@a+1 ID, NRIC as ICNO, NRIC as NRIC, Name as MemberName, Amount as Amount,'' as Department FROM mon_sub_member ,(SELECT @a:= 0) AS a WHERE mon_sub_member.MonthlySubscriptionCompanyId = '$company_id'") ));

            //$subscription_data = MonthlySubscriptionMember::select('id','NRIC as ICNO','NRIC as NRIC','Name','Amount')->where('MonthlySubscriptionCompanyId',$company_id)->get();
            return $subscription_data;
        }
		
    }

	public function headings(): array
    {
        return [
            'ID',
            'ICNO',
            'NRIC',
            'MemberName',
            'Amount',
            'Department'
        ];
    }
	public function registerEvents(): array
    {
		
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:F1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->
				applyFromArray(array(
					'font' => array(
						'bold'  => true,
					)
				));
            },
        ];
    }
}

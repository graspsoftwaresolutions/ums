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

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;
use Facades\App\Repository\CacheMonthEnd;
use App\Model\Fee;

class StatusMemberExport implements FromView
{
    protected $request_data;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($requestinfo)
    {
        $bf_amount = Fee::where('fee_shortcode','=','BF')->pluck('fee_amount')->first();
        $ins_amount = Fee::where('fee_shortcode','=','INS')->pluck('fee_amount')->first();
        $ent_amount = Fee::where('fee_shortcode','=','EF')->pluck('fee_amount')->first();
        $hq_amount = Fee::where('fee_shortcode','=','HQ')->pluck('fee_amount')->first();
        $this->bf_amount = $bf_amount=='' ? 3 : $bf_amount;
        $this->ins_amount = $ins_amount=='' ? 7 : $ins_amount;		
        $this->hq_amount = $hq_amount=='' ? 2 : $hq_amount;		
        $this->ent_amount = $ent_amount=='' ? 5 : $ent_amount;	
        $this->request_data = $requestinfo;
        //$objPHPExcel = new PHPExcel();
    }
    public function view(): View
    {
        $request_data = $this->request_data;
        $offset = $request_data['offset'];
        $month_year = $request_data['month_year'];
        $company_id = $request_data['company_id'];
        $branch_id = $request_data['branch_id'];
        $member_auto_id = $request_data['member_auto_id'];
        $unionbranch_id = $request_data['unionbranch_id'];
        $from_member_no = $request_data['from_member_no'];
        $to_member_no = $request_data['to_member_no'];
        $status_id = $request_data['status_id'];
        $unionbranch_name = '';
        $monthno = '';
        $yearno = '';
        $month_year = date('M/Y',strtotime($month_year));
        $fulldate = date('Y-m-01');
        if($month_year!=""){
          $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $yearno = date('Y',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
          $fulldate = date('Y-m-01',strtotime('01-'.$fmmm_date[0].$fmmm_date[1]));
        }

        $members = DB::table('mon_sub_member as mm')->select('m.name', 'm.member_number','m.gender','com.company_name','m.doj',DB::raw('IF(`m`.`new_ic`=Null,`m`.`old_ic`,`m`.`new_ic`) as ic')
        ,DB::raw('IF(`m`.`levy`="Not Applicable","N/A",`m`.`levy`) as levy'),DB::raw('IF(`m`.`tdf`="Not Applicable","N/A",`m`.`tdf`) as tdf'),'m.tdf_amount',DB::raw('CONCAT( `com`.`short_code`, "/",  `cb`.`branch_shortcode` ) AS companycode'),'cb.branch_name as branch_name',DB::raw('IF(`d`.`designation_name`="CLERICAL","C","N") AS designation_name'),'mp.last_paid_date')
               ->leftjoin('mon_sub_company as mc','mc.id','=','mm.MonthlySubscriptionCompanyId')
               ->leftjoin('mon_sub as ms','ms.id','=','mc.MonthlySubscriptionId')
               ->leftjoin('membership as m','mm.MemberCode','=','m.id')
               ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
               ->leftjoin('company as com','com.id','=','cb.company_id')
               //->leftjoin('status as s','s.id','=','mm.StatusId')
               ->leftjoin('designation as d','m.designation_id','=','d.id')
               ->leftjoin('member_payments as mp','m.id','=','mp.member_id');
                //->leftjoin('designation as d','m.designation_id','=','d.id')
                //->leftjoin('state as st','st.id','=','m.state_id')
                //->leftjoin('city as cit','cit.id','=','m.city_id')
                //->leftjoin('race as r','r.id','=','m.race_id');
                if($status_id!="" && $status_id!=0){
                    $members = $members->where('mm.StatusId','=',$status_id);
                }
                if($monthno!="" && $yearno!=""){
                  $members = $members->where(DB::raw('month(ms.`Date`)'),'=',$monthno);
                  $members = $members->where(DB::raw('year(ms.`Date`)'),'=',$yearno);
                }
                if($branch_id!=""){
                    $members = $members->where('m.branch_id','=',$branch_id);
                }else{
                    if($unionbranch_id!=''){
                         $members = $members->where('cb.union_branch_id','=',$unionbranch_id);
                         $unionbranch_name = DB::table('union_branch')->where('id','=',$unionbranch_id)->pluck('union_branch')->first();
                    }
                    if($company_id!=""){
                        $members = $members->where('mc.CompanyCode','=',$company_id);
                    }
                }
                if($member_auto_id!=""){
                    $members = $members->where('m.id','=',$member_auto_id);
                }
                if($from_member_no!="" && $to_member_no!=""){
                    $members = $members->where('m.member_number','>=',$from_member_no);
                    $members = $members->where('m.member_number','<=',$to_member_no);
               }
               $members = $members->orderBy('m.member_number','asc');
            $members = $members->get();


        $data['member_view'] = $members;
        $data['month_year'] = $fulldate;

        $data['company_id'] = $company_id;
        $data['unionbranch_id'] = $unionbranch_id;
        $data['unionbranch_name'] = $unionbranch_name;
        $data['branch_id'] = $branch_id;
        $data['member_auto_id'] = $member_auto_id;
        $data['from_member_no']=$from_member_no;
        $data['to_member_no']=$to_member_no;
        $data['status_id']=$status_id;
        $data['data_limit']='';
 
         $dataarr = ['data' => $data ];
       return view('reports.pdf_members')->with('data',$data);  
        // return view('exports.invoices', [
        //     'invoices' => MonthlySubscription::all()
        // ]);
    }

    public function collection()
    {
        // return User::all();
        // dd(MonthlySubscription::all());
        // return collect(MonthlySubscription::all());
        return MonthlySubscriptionMember::select('id','MonthlySubscriptionCompanyId','company_branch_id','Name','MemberCode','NRIC','Amount')->where('update_status','=',1)->limit(100)->get();
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA');
        $drawing->setPath(public_path('assets/images/logo/logo.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('B1');

        
        return [$drawing];

        
    }

    public function headings(): array
    {
        return [
            '',
            '',
            'NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA',
        ];
    }

    public function startCell(): string
    {
        return 'B2';
    }

	public function registerEvents(): array
    {
		
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A3:F3'; // All headers
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

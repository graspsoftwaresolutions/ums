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
use App\Helpers\CommonHelper;

class ResignMembersExport implements FromView
{
    protected $request_data;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($requestinfo)
    {
        $this->request_data = $requestinfo;
        //$objPHPExcel = new PHPExcel();
    }
    public function view(): View
    {
        $request_data = $this->request_data;
        $offset = $request_data['offset'];

        $from_date = $request_data['from_date'];
        $to_date = $request_data['to_date'];
        $company_id = $request_data['company_id'];
        $branch_id = $request_data['branch_id'];
        $member_auto_id = $request_data['member_auto_id'];
        $date_type = $request_data['date_type'];
        $unionbranch_id = $request_data['unionbranch_id'];
        $from_member_no = $request_data['from_member_no'];
        $to_member_no = $request_data['to_member_no'];
        $resign_reason = $request_data['resign_reason'];
        $fromdate = CommonHelper::ConvertdatetoDBFormat($from_date);
        $todate = CommonHelper::ConvertdatetoDBFormat($to_date);
        $unionbranch_name='';
        
        $members = DB::table('resignation as rs')->select('c.id as cid','m.name','m.email','m.id as id','m.status_id as status_id','m.branch_id as branch_id', 'm.member_number','m.designation_id','d.id as designationid','d.designation_name','m.gender','com.company_name','m.doj','m.employee_id','m.old_ic','m.new_ic','m.mobile','st.state_name','cit.id as cityid','cit.city_name','st.id as stateid','m.state_id','m.city_id','m.race_id','m.levy','m.levy_amount','m.tdf','m.tdf_amount',DB::raw('CONCAT( `com`.`short_code`, "/",  `c`.`branch_shortcode` ) AS companycode'),'r.race_name','r.short_code as raceshortcode','s.font_color','c.branch_name as branch_name','rs.accbf as contribution','rs.insuranceamount as insuranceamount',DB::raw("ifnull(rs.`accbenefit`,0) AS benifit"),DB::raw("ifnull(rs.`amount`,0) AS total"),'rs.resignation_date','rs.paymode','rs.voucher_date','reason.short_code as reason_code','rs.claimer_name','u.short_code as unioncode')
                ->leftjoin('membership as m','m.id','=','rs.member_code')
                ->leftjoin('company_branch as c','c.id','=','m.branch_id')
                ->leftjoin('company as com','com.id','=','c.company_id')
                ->leftjoin('union_branch as u','u.id','=','c.union_branch_id')
                ->leftjoin('reason as reason','reason.id','=','rs.reason_code')
                ->leftjoin('status as s','s.id','=','m.status_id')
                ->leftjoin('designation as d','m.designation_id','=','d.id')
                ->leftjoin('state as st','st.id','=','m.state_id')
                ->leftjoin('city as cit','cit.id','=','m.city_id')
                ->leftjoin('race as r','r.id','=','m.race_id');
               if($fromdate!="" && $todate!="" && $date_type==1){
                  $members = $members->where(DB::raw('date(rs.`resignation_date`)'),'>=',$fromdate);
                  $members = $members->where(DB::raw('date(rs.`resignation_date`)'),'<=',$todate);
               }
               if($fromdate!="" && $todate!="" && $date_type==2){
                    $members = $members->where(DB::raw('date(rs.`voucher_date`)'),'>=',$fromdate);
                    $members = $members->where(DB::raw('date(rs.`voucher_date`)'),'<=',$todate);
               }
              if($branch_id!=""){
                  $members = $members->where('m.branch_id','=',$branch_id);
              }else{
                 if($unionbranch_id!=''){
                    $members = $members->where('c.union_branch_id','=',$unionbranch_id);
                    $unionbranch_name = DB::table('union_branch')->where('id','=',$unionbranch_id)->pluck('union_branch')->first();
                }
                  if($company_id!=""){
                      $members = $members->where('c.company_id','=',$company_id);
                  }
              }
              if($from_member_no!="" && $to_member_no!=""){
                    $members = $members->where('m.member_number','>=',$from_member_no);
                    $members = $members->where('m.member_number','<=',$to_member_no);
               }
                if($resign_reason!=""){
                    $members = $members->where('rs.reason_code','=',$resign_reason);
                }
           $members = $members->orderBy('m.member_number','asc');
              
          $members = $members->get();
        //echo json_encode($members);
        $data['member_view'] = $members;
        $data['from_date'] = $fromdate;
        $data['to_date'] = $todate;
        $data['company_id'] = $company_id;
        $data['branch_id'] = $branch_id;
        $data['member_auto_id'] = $member_auto_id;
        $data['unionbranch_id'] = $unionbranch_id;
        $data['unionbranch_name'] = $unionbranch_name;
        $data['date_type'] = $date_type;
        $data['from_member_no']=$from_member_no;
        $data['to_member_no']=$to_member_no;
        $data['resign_reason'] = $resign_reason;
        $data['data_limit'] = '';

        $dataarr = ['data' => $data ];
        return view('reports.excel_resignmembers')->with('data',$data);  
        
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
				$event->sheet->styleCells(
                    'A7:H7',
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ]
                    ]
                );
            },
        ];
    }

}

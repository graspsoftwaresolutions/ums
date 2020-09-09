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
use App\Model\Fee;

class HalfshareExport implements FromView
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
       
        $monthno = '';
        $yearno = '';
        $fulldate = date('Y-m-01');
        if($month_year!=""){
         // $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime($month_year));
          $yearno = date('Y',strtotime($month_year));
          $fulldate = date('Y-m-01',strtotime($month_year));
        }
       
        $data['month_year']=$fulldate;
        $data['date']=$fulldate;

        $half_s = DB::table('mon_sub_member as mm')->select(DB::raw('count(mm.id) as count'), DB::raw('sum(mm.Amount) as subamt'),'ms.Date as statusmonth','cb.union_branch_id','ub.union_branch')
            ->leftjoin('mon_sub_company as sc','sc.id','=','mm.MonthlySubscriptionCompanyId')
            ->leftjoin('mon_sub as ms','ms.id','=','sc.MonthlySubscriptionId')
            ->leftjoin('membership as m','m.id','=','mm.MemberCode')
            ->leftjoin('company_branch as cb','cb.id','=','m.branch_id')
            ->leftjoin('union_branch as ub','ub.id','=','cb.union_branch_id')  
            ->where(DB::raw('month(ms.Date)'),'=',$monthno)  
            ->where(DB::raw('year(ms.Date)'),'=',$yearno)
            ->where(DB::raw('ms.Date'),'<>',DB::raw('DATE_FORMAT(m.doj, "%Y-%m-01")'))
            ->where(function ($query) {
                $query->where('mm.StatusId', '=', 1)
                    ->orWhere('mm.StatusId', '=', 2);
            })
        ->groupBy('cb.union_branch_id')
        ->orderBy('ub.union_branch','asc')
        ->get();  

        $data['half_share'] = $half_s;
        $data['offset']=0;
        $data['data_limit']= '';
        $data['bf_amount']=$this->bf_amount;
        $data['ins_amount']=$this->ins_amount;
        $data['total_ins']=$this->bf_amount+$this->ins_amount;
        $data['nobreak']= 1;

        $dataarr = ['data' => $data ];
       return view('reports.pdf_half_share')->with('data',$data);  
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

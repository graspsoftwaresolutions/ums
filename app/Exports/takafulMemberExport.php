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

class takafulMemberExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct()
    {
        //$objPHPExcel = new PHPExcel();
    }
    public function view(): View
    {
        $data['data_limit']=100;
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
       
        $members = CacheMonthEnd::getMonthEndByDate('2019-06-01');
        // $members = DB::table($this->membermonthendstatus_table.' as ms')
		// 			->select('c.id as cid','m.name','m.id as id','ms.BRANCH_CODE as branch_id', 'm.member_number','com.company_name','m.old_ic','m.new_ic','c.branch_name as branch_name','com.short_code as companycode','ms.SUBSCRIPTION_AMOUNT','ms.BF_AMOUNT',DB::raw("ifnull(ms.`SUBSCRIPTION_AMOUNT`+ms.`BF_AMOUNT`,0) AS total"))
		// 			->leftjoin('membership as m','m.id','=','ms.MEMBER_CODE')
        //             ->leftjoin('company_branch as c','c.id','=','ms.BRANCH_CODE')
        //             ->leftjoin('company as com','com.id','=','ms.BANK_CODE');
                   
      
        // $members = $members->where(DB::raw('month(ms.`StatusMonth`)'),'=',date('m'));
        // $members = $members->where(DB::raw('year(ms.`StatusMonth`)'),'=',date('Y'));
                  
		// $members = $members->get();
		//dd($members);
        $data['member_view'] = $members;
        $data['month_year'] = '2019-06-01';
        $data['unionbranch_name'] = ''; 
        $data['unionbranch_id'] = '';
        $data['company_id']='';
        $data['branch_id']='';
        $data['member_auto_id']='';
        $data['total_ins']=10;
        $data['offset']=0;
       return view('reports.iframe_takaful_pdf')->with('data',$data);  
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

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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class takafulMemberExport implements FromView,WithEvents,WithColumnFormatting
{
    protected $request_data;
	protected $member_count;
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
       
        $unionbranch_name = '';
        $monthno = '';
        $yearno = '';
        $fulldate = date('Y-m-01');
        if($month_year!=""){
         // $fmmm_date = explode("/",$month_year);
          $monthno = date('m',strtotime($month_year));
          $yearno = date('Y',strtotime($month_year));
          $fulldate = date('Y-m-01',strtotime($month_year));
        }

        $members =[];

        if($unionbranch_id!=''){
            $unionbranch_name = DB::table('union_branch')->where('id','=',$unionbranch_id)->pluck('union_branch')->first();
        }
        
        if($branch_id!="" || $company_id!="" || $member_auto_id!="" || $unionbranch_id!=""){

            $members = CacheMonthEnd::getMonthEndByDateFilter($fulldate,$company_id,$branch_id,$member_auto_id,$unionbranch_id);
           
        }else{
            $members = CacheMonthEnd::getMonthEndByDate($fulldate);
        }
		$data['member_view'] = $members;
       
        $data['month_year']=$fulldate;
        $data['company_id']=$company_id;
        $data['branch_id']=$branch_id;
        $data['member_auto_id']=$member_auto_id;
        $data['unionbranch_name'] = $unionbranch_name; 
        $data['unionbranch_id'] = $unionbranch_id;
        //$data['data_limit']=$this->limit;
        $data['data_limit']='';
		$data['total_ins']=$this->bf_amount+$this->ins_amount;
        $data['offset']='';
        $data['company_view'] = DB::table('company')->where('status','=','1')->get();
		
		$this->member_count = count($members);
       

        $dataarr = ['data' => $data ];
       return view('reports.iframe_takaful_pdf')->with('data',$data);  
        // return view('exports.invoices', [
        //     'invoices' => MonthlySubscription::all()
        // ]);
    }

    public function collection()
    {
		return [];
        // return User::all();
        // dd(MonthlySubscription::all());
        // return collect(MonthlySubscription::all());
       // return MonthlySubscriptionMember::select('id','MonthlySubscriptionCompanyId','company_branch_id','Name','MemberCode','NRIC','Amount')->where('update_status','=',1)->limit(100)->get();
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
               /* $event->sheet->getDelegate()->getStyle($cellRange)->
				applyFromArray(array(
					'font' => array(
						'bold'  => true,
					)
				));*/
				$styleArray = [
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '988989'],
                                ]
                            ]
                        ];
                $event->sheet->getDelegate()->getStyle('A'.($this->member_count+4).':F'.($this->member_count+4))->applyFromArray($styleArray);
                $event->sheet->getDelegate()->getStyle('A'.($this->member_count+5).':F'.($this->member_count+5))->applyFromArray($styleArray);
            }, 
        ];
    }
	
	public function columnFormats(): array
    {
        return [
            'D' => '0',
            'F' => '0.00',
        ];
    }
}

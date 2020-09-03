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

class DiscrepancyExport implements FromView
{
    protected $request_data;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($requestinfo)
    {
        $this->request_data = $requestinfo;
    }
    public function view(): View
    {
        $request_data = $this->request_data;
        $datestring = $request_data['date'];
        $groupby = $request_data['groupby'];
        $display_subs = $request_data['display_subs'];
        $variation = $request_data['variation'];

        
        $data['sub_company'] = $request_data['sub_company'];
        $data['unionbranch_id'] = $request_data['unionbranch_id'];
        
        //$datestring = strtotime('2019-04-01');
        //return date('Y-m-01',strtotime($datestring));
        $data['month_year'] = date('M/Y',$datestring);
        $data['month_year_full'] = date('Y-m-01',$datestring);
        $data['groupby'] = $groupby;
        $data['DisplaySubscription'] = $display_subs;
        $data['print'] = $request_data['print'];
        $data['variation'] = $request_data['variation'];
        $data['inctype'] = $request_data['inctype'];
       // $last_month = date('Y-m-01',strtotime($cur_date.' -1 Month'));
        $data['last_month_year']= date("Y-m-01", strtotime($data['month_year_full']." -1 Month"));
        $data['month_year_first'] = date('Y-m-01',strtotime($data['month_year_full'].' -'.($variation-1).' months'));

         if($groupby==1){
            if($data['unionbranch_id']!=''){
                $union_view = DB::table('union_branch')->select('u.id as union_branchid','u.union_branch as union_branch_name')
                                    ->where('id','=',$data['unionbranch_id'])
                                    ->where('status','=','1')
                                    ->orderBy('union_branch','asc')->get();

                $data['union_branch_view'] = $union_view;
            }else{
                $union_view = DB::table('union_branch')->select('u.id as union_branchid','u.union_branch as union_branch_name')
                                    ->where('status','=','1')->orderBy('union_branch','asc')->get();
                $data['union_branch_view'] = $union_view;
            }
            //$data['union_branch_view'] = CacheMonthEnd::getUnionBranchByDate($data['month_year_full']);
            $data['company_view']=[];
            $data['branch_view']=[];
        }
        elseif($groupby==2){
            if($data['sub_company']!=''){
                $company_view = DB::table("company as c")->select('c.id as company_id','c.company_name as company_name')
                                ->where('c.id', '=', $data['sub_company']);
                $data['company_view'] = $company_view->get();
            }else{
                $company_view = DB::table("mon_sub_member as mm")->select('mc.CompanyCode as company_id','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                ->leftjoin('company as c','mc.CompanyCode','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '>=', $data['month_year_first'])
                                ->where('ms.Date', '<=', $data['month_year_full'])
                                ->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null);
                $data['company_view'] = $company_view->groupBY('mc.CompanyCode')
                                ->get();
            }
            //$data['company_view'] = CacheMonthEnd::getCompaniesByDate($data['month_year_full']);
            $data['union_branch_view']=[];
            $data['branch_view']=[];
            
        }
        else{
            if($data['sub_company']!=''){
                $company_view = DB::table("mon_sub_member as mm")->select('m.branch_id as branch_id','cb.branch_name as branch_name','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                ->leftjoin('company as c','cb.company_id','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '>=', $data['month_year_first'])
                                ->where('ms.Date', '<=', $data['month_year_full'])
                                ->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null)
                                ->where('mc.CompanyCode', '=', $data['sub_company']);
                $data['branch_view'] = $company_view->groupBY('m.branch_id')
                                ->get();
            }else{
                 $company_view = DB::table("mon_sub_member as mm")->select('m.branch_id as branch_id','cb.branch_name as branch_name','c.company_name as company_name')
                                ->leftjoin('mon_sub_company as mc','mm.MonthlySubscriptionCompanyId','=','mc.id')
                                ->leftjoin('mon_sub as ms','mc.MonthlySubscriptionId','=','ms.id')
                                ->leftjoin('membership as m','m.id','=','mm.MemberCode')
                                ->leftjoin('company_branch as cb','m.branch_id','=','cb.id')
                                ->leftjoin('company as c','cb.company_id','=','c.id')
                                //->leftjoin('union_branch as u','cb.union_branch_id','=','u.id')
                                ->where('ms.Date', '>=', $data['month_year_first'])
                                ->where('ms.Date', '<=', $data['month_year_full'])
                                ->where('mm.update_status', '=', 1)
                                ->where('mm.MemberCode', '!=', Null);
                $data['branch_view'] = $company_view->groupBY('m.branch_id')
                                ->get();
            }
            //$data['branch_view'] = CacheMonthEnd::getBranchByDate($data['month_year_full']);
            //dd($data['branch_view']);
            $data['union_branch_view']=[];
            $data['company_view']=[];
        }

        
        $dataarr = ['data' => $data ];
        return view('subscription.discrepancy_excel')->with('data',$data);  
        // return view('exports.invoices', [
        //     'invoices' => MonthlySubscription::all()
        // ]);
    }

    public function collection()
    {
        // return User::all();
        // dd(MonthlySubscription::all());
        // return collect(MonthlySubscription::all());
       
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

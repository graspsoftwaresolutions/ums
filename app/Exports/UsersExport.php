<?php

namespace App\Exports;

use App\User;
use App\Model\Membership;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PHPExcel_Worksheet_Drawing;

class UsersExport implements ShouldAutoSize, WithEvents
{
    protected $request_data;

    public function __construct($requestinfo)
    {   //dd($requestinfo);
         $this->branch_id = $requestinfo['branch_id'];
         $this->union_id = $requestinfo['union_id'];
    }

    public function collection()
    {
        return User::where('id','<=',5)->get();
    }

    public function headings(): array
    {
        return [];
        // return [[
        //     'ID',
        //     'Name',
        //     'Email',
        //     'Created at',
        //     ''
        // ]];
        // return [
        //     'Sample Title',
        //     'Name',
        //     'Email',
        //     'Created at',
        //     ''
        // ];
    }
    public function headingRow(): int
    {
        return 3;
    }
	
	/**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('/assets/images/logo/logo.png'));
                $drawing->setWidth(50);
                $drawing->setHeight(50);

                //$colWidth = $drawing->getColumnDimension('C')->getWidth();
                
                $drawing->setOffsetX('250');
                //$drawing->setOffsetY(0);

                $drawing->setCoordinates('A1');

                $drawing->setWorksheet($event->sheet->getDelegate());

                $event->sheet->setCellValue('D1', 'NATIONAL UNION OF BANK EMPLOYEES,PENINSULAR MALAYSIA');
                $event->sheet->setCellValue('D2', 'NEW MEMBERS REPORT');

                $event->sheet->setCellValue('A3', 'To Branch Hons. Secretary');
                $event->sheet->getDelegate()->mergeCells('A3:C3');

                $event->sheet->setCellValue('D3', '01 Aug 2020 - 31 Aug 2020');
                $event->sheet->getDelegate()->mergeCells('D3:M3');

                $event->sheet->getDelegate()->mergeCells('D1:M1');
                $event->sheet->getDelegate()->mergeCells('D2:M2');
                $event->sheet->getDelegate()->mergeCells('A1:C2');
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
;

                $styleArray3 = array(
                    'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        )
                );

                $event->sheet->getDelegate()->getStyle('A1:M3')->applyFromArray($styleArray3);

                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);
                $rowno = 4;
                $datarow = 0;
                if($this->branch_id==1){
                    $event->sheet->setCellValue('A'.$rowno, 'Bank: AFFN');
                    $rowno = $rowno+1;
                    $datarow++;
                }

                if($this->union_id==1){
                    $event->sheet->setCellValue('A'.$rowno, 'Union: IPOH');
                    $rowno = $rowno+1;
                    $datarow++;
                }

                $headerdata = [
                    'SNO',
                    'M/NO',
                    'MEMBER NAME',
                    'NRIC',
                    'GENDER',
                    'BANK',
                    'DOJ',
                ];

                $event->sheet->getDelegate()->fromArray($headerdata, null, 'A'.$rowno, true);
                $rowno = $rowno+1;

                $userdata = Membership::where('id','>=',5000)->where('id','<=',5030)->get();
                
                $slno = 1;
                foreach ($userdata as $key => $value) {
                    
                    $data = [ $slno,$value->member_number,$value->name,$value->new_ic,$value->old_ic];
                    $row = 'A'.($rowno);
                    $event->sheet->getDelegate()->fromArray($data, null, $row, true);
                    $rowno++;
                    $slno++;
                }

                
            },
        ];
        //  return [
        //     AfterSheet::class    => function(AfterSheet $event) {
        //         // All headers - set font size to 14
        //         $cellRange = 'A1:W1'; 
        //         $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

        //         // Apply array of styles to B2:G8 cell range
        //         $styleArray = [
        //             'borders' => [
        //                 'outline' => [
        //                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
        //                     'color' => ['argb' => 'FFFF0000'],
        //                 ]
        //             ]
        //         ];
        //         $event->sheet->getDelegate()->getStyle('B2:G8')->applyFromArray($styleArray);

        //         // Set first row to height 20
        //         $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);

        //         // Set A1:D4 range to wrap text in cells
        //         $event->sheet->getDelegate()->getStyle('A1:D4')
        //             ->getAlignment()->setWrapText(true);
        //     },
        // ];
    //     return [
    //         AfterSheet::class    => function(AfterSheet $event) {
    //             $cellRange = 'A1:E1'; // All headers
    //             $event->sheet->getDelegate()->mergeCells($cellRange);
    //             $event->sheet->getDelegate()->mergeCells('A2:D2');
    //             $event->sheet->setCellValue('A3', 'some value');

    //             $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
				// //$spreadsheet->getActiveSheet()->getStyle('A1:D4')
				// //->getAlignment()->setWrapText(true);
    //         },
    //     ];
    }

}

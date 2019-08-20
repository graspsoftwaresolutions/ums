<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class CustomerController extends Controller
{
    public function printPDF()
    {
       // This  $data array will be passed to our PDF blade
       $data = [
          'title' => 'Test PDF',
          'heading' => 'Test heading',
          'content' => 'Test contnt'        
            ];
        
       //return view('pdf_view', $data);  
        $pdf = PDF::loadView('pdf_view', $data);  
        return $pdf->download('medium.pdf');
    }
}

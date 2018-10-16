<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use Barryvdh\DomPDF\Facade as PDF;
use Jenssegers\Date\Date as Date;
class PrinterController extends Controller
{

    public function codes(Request $request){
    $Codes = Code::whereActivated($request->input('certificate_id'))->firstOrFail();
    return response()->json($Codes);
     // return redirect('/index');
    }

    public function index(Request $request){
     $code = Code::whereActivated($request->input('certificate_id'))->firstOrFail();
        $theme = ['тема'];
        $adapter = new Local(__DIR__.'/public/assets', 0);
        $date = new Date('2018-10-10');
        Date::setLocale(config('app.locale'));
        $parsed = $date->formatLocalized('%e %B %Y');
      //$pdf = PDF::loadView('certificate', compact('$codes'));
     // $code = compact('code');
      // Log::debug($code);
      $pdf = PDF::loadView('certificate', ['code' => $code, 'date' => $parsed]);

      // return PDF::loadView('certificate', ['code' => $code])->save('/public/assetsinvoice.pdf')->stream('download.pdf');
   // return view('certificate', ['code' => $code, 'date' => $parsed]);
     return $pdf->save(public_path().'/assets/invoice.pdf')->download('invoice.pdf');
        // return response()->json($code);

    }

    // TODO 
    // set filename to template and invoice template in database
    // maybe do it with array or objetct
    // get code with company name and company tax 
    // print invoice and know how to save it on hdd with background
}
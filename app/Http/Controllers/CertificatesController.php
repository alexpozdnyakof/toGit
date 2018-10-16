<?php

namespace App\Http\Controllers;

// internal models
use App\Models\Email;
use App\Models\Certs;
use App\Models\Code;
use App\Models\CertsStore;
//use League\Flysystem\Filesystem;

// internal extended
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

// internal resources
use App\Http\Resources\Certificate as CertificateResource;
use App\Http\Resources\ActivatedFull as ActivatedFullResource;
use App\Http\Resources\Code as CodeResource;

// external use with composerr
use Barryvdh\DomPDF\Facade as PDF;
use Jenssegers\Date\Date as Date;
// helper needed and composer.json updates


class CertificatesController extends Controller {
    public function __construct()
    {

    }

    public function index(){
        return  CertificateResource::collection(Certs::all());
    }

    public function certificates(Request $request){
        try {
            if ($request->input('tax') == 'null'){
                return ActivatedFullResource::collection(CertsStore::with(['certs','code'])->whereSeller($request->input('seller'))->get());
            }
            return ActivatedFullResource::collection(CertsStore::with(['certs','code'])->where('seller', '!=', $request->input('seller'))->whereTax($request->input('tax'))->get());
        }
        catch (Exception $e) {
            report($e);
            return $e;
        }
    }

    public function certificate(Request $request){
        try {
            $withNestedModel =  CertsStore::with(['certs','code'])->findOrFail($request->input('certificate'));
            if ($request->input('email') == 'null'){
                return response()->json($withNestedModel);
            }

            if($withNestedModel->status = 0){
                    Date::setLocale(config('app.locale'));
                    $deadLine = new Date($withNestedModel->created);
                    $deadLine = $deadLine->addDays(5);
                    $deadLineLocalized = $deadLine->formatLocalized('%e %B');
                    $mailMeta = array(
                        'message' => 'Для вас оформлен сертификат Amulex, оплатите счет в течении 5 рабочих дней до '.$deadLineLocalized.'. При отстуствии оплаты, сертификат будет аннулирован. Сертификат и счет для оплаты вы можете найти в приложениях к этому письму',
                        'subject' => 'ПАО «Росбанк»: сертификат Amulex'
                    );
                }
                // Log::debug($withNestedModel);
                $mailMeta = array(
                    'message' => 'Ваш сертификат Amulex'.$withNestedModel->certs->name.' во вложении',
                    'subject' => 'ПАО «Росбанк»: сертификат Amulex'
                );
                return $this->printPDF($withNestedModel, $mailMeta);
                }
                catch (Exception $e) {
                    report($e);
                    return $e;
                }
    }
    public function getFiles(Request $request){
        try {
            $withNestedModel =  CertsStore::with(['certs','code'])->findOrFail($request->input('certificate'));
            return $this->printPDF($withNestedModel);
        }
        catch (Exception $e) {
            report($e);
            return $e;
        }
    }

    public function createCertificate(Request $request){
        try {
            $certificateCode = Code::find(1)->where('type', '=', $request->input('product'))->where('activated', '=', NULL)->first();
            $certificate = CertsStore::create(
            ['company' => $request->input('company'),
            'created' => Carbon::now(),
            'type' =>$request->input('product'),
            'ceo' => $request->input('ceo'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'tax' => $request->input('tax'),
            'seller' => $request->input('seller')]);
            $certificateCode->activated = $certificate->id;
            $certificateCode->save();
            $withNestedModel =  CertsStore::with(['certs','code'])->findOrFail($certificate->id);
            Date::setLocale(config('app.locale'));
            $deadLine = new Date($withNestedModel->created);
            $deadLine = $deadLine->addDays(5);
            $deadLineLocalized = $deadLine->formatLocalized('%e %B');
            $mailMeta = array(
                'message' => 'Для вас оформлен сертификат Amulex, оплатите счет в течении 5 рабочих дней до '.$deadLineLocalized.'. При отстуствии оплаты, сертификат будет аннулирован. Сертификат и счет для оплаты вы можете найти в приложениях к этому письму',
                'subject' => 'ПАО «Росбанк»: ваш сертификат Amulex'
            );
            return $this->printPDF($withNestedModel, $mailMeta);
        } catch (Exception $e) {
            report($e);
            return $e;
        }
    }

    private function printPDF($certificate, $mailMeta = array()){
        Date::setLocale(config('app.locale'));
        $date = new Date($certificate->created);
        $parsed = $date->formatLocalized('%e %B %Y');
        $invoiceDate = $date->formatLocalized('%e %B');
        $pdf = PDF::loadView('certificate', ['code' => $certificate, 'date' => $parsed])->save(public_path().'/assets/'.$certificate->code->serial_code.'.pdf')->stream($certificate->code->serial_code.'.pdf');
        $pdfUrl = $certificate->code->serial_code;
        $urls = array($pdfUrl);
        if($certificate->status == 0){
            $invoice = PDF::loadView('invoice', ['code' => $certificate, 'date' => $invoiceDate])->save(public_path().'/assets/'.$certificate->code->serial_code.'_invoice.pdf')->stream($certificate->code->serial_code.'_invoice.pdf');
            $invoiceUrl = $certificate->code->serial_code.'_invoice';
            array_push($urls, $invoiceUrl);
        }
        if( isset($mailMeta['message']) ){
            $this->sendMail($certificate->email, $mailMeta['subject'], $mailMeta['message'], $urls);
        }
        //$pdf->save(public_path().'/assets/'.$certificate->code->serial_code.'.pdf');
        // s$template = base64_encode(file_get_contents(public_path().'/assets/invoice.pdf'));
        // base64_encode(file_get_contents($request->file('image')->pat‌​h()));
        /*$headers = array(
            'Content-Type: application/pdf',
          );
          */
         // return view('invoice', ['code' => $certificate, 'date' => $invoiceDate]);
          return  response()->json($urls);
      // return response()->download($template, 'filename.pdf', $headers);
    }



    public  function testPdf(){
        $withNestedModel =  CertsStore::with(['certs','code'])->findOrFail(83);
        return $this->printPDF($withNestedModel);
    }


    public function sendMail($to, $subject, $body, $attachments) {
        try {
            $mail = Email::create([
                 'to' => $to,
                 'subject' => $subject,
                 'body' => $body,
                 'attachments' =>json_encode($attachments),
                 'created' => Carbon::now()
            ]);
            $mail->save();
        } catch (Exception $e) {
            Log::debug($e);
            report($e);
        }
    }
    public function certificateCode(Request $request){
            try{
                $code = Code::with(['type','store'])->whereActivated($request->input('activated'))->first();
                return new CodeResource($code);
                // return response()->json($code);
            } catch (Exception $e) {
                report($e);
                return $e;
            }
    }




/*
    public function generateReport(){

        $certs = CertsStore::with(['certs','code'])->where('created', Carbon::today())->get(['company_person', 'phone', 'company', 'tax', 'email', 'created']);
      $certsArray = [];
      $certsArray[] = ['company_person', 'phone','company','tax','email', 'created'];
      foreach ($certs as $cert) {
        $certsArray[] = $cert->toArray();
    }
    Excel::create('payments', function($excel) use ($certsArray) {

        $excel->setTitle('Продажи');
        $excel->setCreator('Laravel')->setCompany('Rosbank');
        $excel->setDescription('отчет по продажам');

        $excel->sheet('sheet1', function($sheet) use ($certsArray) {
            $sheet->fromArray($certsArray, null, 'A1', false, false);
        });
    })->download('xlsx');
    */
    //  $csvExporter->build($certs, ['company_person', 'phone', 'company', 'tax', 'email', 'created']);
    //  return $csvExporter->download('active_users.csv');

         //return Excel::download($certs, 'users.xlsx');
         //return response()->json($certs);
  //  }
    
    /*
    $csvExporter->build($users, ['cert_number', 'name'])->download();

    a. Номер сертификата;
    b. Фамилия Имя Отчество – руководителя организации;
    c. Номер мобильного телефона – руководителя организации;
    
    1 С 1 января 2019 года увеличивается до 500 шт.
    2 С 1 января 2019 года увеличивается до 500 шт.
    
    d. Наименование организации;
    e. ИНН организации;
    f. Емейл
    g. Дата оформления.
    [
        {
            "id": 23,
            "cert_number": "4",
            "company": "росбанк",
            "tax": "7730060164",
            "created": "2018-10-04",
            "updated": null,
            "cert_type": 1,
            "royalty": null,
            "company_person": "Поздняков а с ",
            "phone": "(913)-041-7726",
            "email": "gag.04@mail.ru",
            "curator_dmb": "поздняков а с",
            "teroffice": "0000",
            "office_name": "головнной",
            "seller": "поздняков",
            "rb": "rb064144",
            "status": 0,
            "certs": {
                "id": 1,
                "name": "Business Lite",
                "description": "Самый простой пакет юридических услуг",
                "price": 7000,
                "royalty_min": 3000,
                "royalty_max": 3200
            },
            "code": {
                "id": 4,
                "serial_code": "185-0001002",
                "activated_code": "488282",
                "cert_type": "1",
                "cert_store": "23"
            }
        }
    ]
*/
    // 1 сертификатстор имеет один сертификат и один активэйтед код

/*
    public function index() {

        $users_w_p = User::withCount(['clients' => function ($query) {
            $query->where('is_active', 1);
          }])->has('clients', '>', 0)->get()->mapToGroups(function ($item, $key) {
            return [$item['glavniy'] => array('username' => $item['username'], 'id' => $item['id'], 'count' => $item['clients_count'])];
        });

        foreach($users_w_p as $key => $value){
            $s = "Перезакрепите проспекты уволенных сотрудников";
            $template =  $this -> generateEmail($key, $s, $value);
            Log::debug($template);

           $this -> sendEmail($template);

        }


        return response()->json($users_w_p);
    }
*/


}




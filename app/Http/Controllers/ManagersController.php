<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prospect;
use App\Models\Client;
use App\Models\Email;
use Carbon\Carbon;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

//left join
//user_id
//



class ManagersController extends Controller {

    public function __construct()
    {

    }
    private function sendEmail($template) {
        try {
            $mail = new Email;
            $mail->to = $template['to'];
            $mail->subject = $template['subject'];
            $mail->body = $template['body'];
            $mail->created = Carbon::now();
            $mail->save();
            return $mail->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    private function generateEmail($to, $subject, $values){
        $body = "<p>За вами числятся уволенные менеджеры, перезакрепите проспекты за другими сотрудниками</p><ul>";
        foreach($values as $value){
            $type = gettype($value);
           // Log::debug($value['id']);
            $body .= "<li><a href=https://proportal/prospects/{$value['id']}>{$value['username']}</a> <span>проспектов:{$value['count']}</span></li>";
        }

        return array('to' => $to, 'subject' => $subject, 'body'  => $body);
    }

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

        //Debugbar::info($users);
      //  Debugbar::addMessage('Another message', 'mylabel');
        return response()->json($users_w_p);
    }



}




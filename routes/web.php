<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/




$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
  $router->get('prospects',  ['uses' => 'ProspectController@showAllProspects']);
  $router->get('prospects/{id}', ['uses' => 'ProspectController@showOneProspect']);
  $router->post('prospects', ['uses' => 'ProspectController@create']);
  $router->delete('prospects/{id}', ['uses' => 'ProspectController@delete']);
  $router->put('prospects/{id}', ['uses' => 'ProspectController@update']);
  $router->get('manager/{id}/prospects', ['uses' => 'ManagersController@searchProspects']);
  $router->get('printInvoice', ['uses' => 'CertificatesController@certificateTemplates']);

  $router->get('email', function ()  {
    return view('mail', ['theme' => 'Перезакрепление']);
});

$router->get('certificate', ['uses' => 'CertificatesController@certificate']);
$router->get('certificates', ['uses' => 'CertificatesController@index']);
$router->post('createdList', ['uses' => 'CertificatesController@certificates']);
$router->post('certificateFiles', ['uses' => 'CertificatesController@getFiles']);
$router->post('certificateSend', ['uses' => 'CertificatesController@certificate']);
$router->post('certificates', ['uses' => 'CertificatesController@createCertificate']);


$router->get('getPdf', function ()  {
    return view('certificate', ['theme' => 'Сертификаты']);
});


//$router->get('test', ['uses' => 'PrinterController@index']);
$router->get('test', ['uses' => 'CertificatesController@testPdf']);


});



// todo where get controllers
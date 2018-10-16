<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="assets/certificaes/normalize.css" rel="stylesheet" type="text/css">

    <title></title>
    <style>
      @charset "utf-8";
      html, body{
        width: 100%;
        height: 100%;
        min-height:100%;
        position: relative;
        margin:0;
        font-family: DejaVu Sans;
        color:#101012 !important;
      }
        .contentLayer{
          position: absolute;
        width: 100%;
        height:100;
        top:0;
        left:0;
        z-index: 3;
        }

        img{
          display: block;
          width: 1240px;
          height: 1754px;
          position: absolute;
          top:0;
          left:0;
          bottom:0;
          z-index: 2;
        }
        p{
          display: inline-block;
          font-size: 20px;
          position: absolute;
        }
        .activation{
          left: 970px;
          top: 20px;
        }
        .serial{
          top: 255px;
          color: #101012;
          left: 380px;
          letter-spacing: -.5px;
          font-size: 25px;
          font-weight: bold !important;
        }
        .date{
          top: 255px;
          left: 620px;
          font-size: 25px;
          font-weight: bold !important;
        }
        .company{
          top: 400px;
          left: 250px;
          font-weight: bold;
        }

        @media print {
          html, body {
              height: 100%;
              min-height: 100%;
              position: relative;
              margin:0;
              color:#101012 !important;
          }
       }
        @page {
          size: A4;
          margin: 0;
          margin-left: 0px;
          margin-right: 0px;
          margin-top: 0px;
          margin-bottom: 0px;
        }
      </style>

  </head>
  <body >
      <div class="contentLayer">
        <p class="serial">{{$code->code->serial_code}}</p>
        <p class="company">{{$code->company}} ИНН: {{$code->tax}}</p>
        <p class="date">{{$date}}</p>
      </div>
     <!-- <img src="/assets/invoices/{{$code->type}}.jpg">-->
     <img src="{{public_path()}}/assets/invoices/{{$code->type}}.jpg">
  </body>
</html>




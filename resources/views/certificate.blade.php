<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="assets/certificaes/normalize.css" rel="stylesheet" type="text/css">

    <title></title>
    <style>
      @charset "utf-8";
      body {
font-family: DejaVu Sans;
}
      html, body{
        width: 100%;
        height: 100%;
        min-height:100%;
        position: relative;
        margin:0;
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
          top: 20px;
          left: 95px;
        }
        .date{
          top: 1580px;
          left: 940px;
        }

        @media print {
          html, body {
              height: 100%;
              min-height: 100%;
              position: relative;
              margin:0;
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
        <p class="activation">{{$code->code->activated_code}}</p>
        <p class="date">{{$date}}</p>
      </div>
      <!-- <img src="/assets/certificates/3.jpg">-->
     <img src="{{public_path()}}/assets/certificates/{{$code->type}}.jpg">
  </body>
</html>




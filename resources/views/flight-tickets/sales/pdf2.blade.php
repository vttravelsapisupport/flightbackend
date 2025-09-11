
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Print Ticket - VishalTravels</title>
    <style>
        .row {
            display: -webkit-box; /* wkhtmltopdf uses this one */
            display: flex;
            /* -webkit-box-pack: center; wkhtmltopdf uses this one */
            /* justify-content: center; */
        }
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */

        /*All the styling goes here*/

        /* img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        } */

        body {
            /* background-color: #f6f6f6; */
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%; }


        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background-color: #ffffff;
            -webkit-print-color-adjust: exact;
            border-radius: 3px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            /* padding: 20px;  */
        }

        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }


        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3,
        h4 {
            color: #000000;
            font-family: sans-serif;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 35px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }

        p,
        ul,
        ol {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 5px;
        }
        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }

        a {
            color: #3498db;
            text-decoration: underline;
        }

        /* -------------------------------------
            BUTTONS
        ------------------------------------- */
        .btn {
            box-sizing: border-box;
            width: 100%; }
        .btn > tbody > tr > td {
            padding-bottom: 15px; }
        .btn table {
            width: auto;
        }
        .btn table td {
            background-color: #ffffff;
            -webkit-print-color-adjust: exact;
            border-radius: 5px;
            text-align: center;
        }
        .btn a {
            background-color: #ffffff;
            -webkit-print-color-adjust: exact;
            border: solid 1px #3498db;
            border-radius: 5px;
            box-sizing: border-box;
            color: #3498db;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .btn-primary table td {
            background-color: #3498db;
            -webkit-print-color-adjust: exact;
        }

        .btn-primary a {
            background-color: #3498db;
            -webkit-print-color-adjust: exact;
            border-color: #3498db;
            color: #ffffff;
        }

        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .mt0 {
            margin-top: 0;
        }

        .mb0 {
            margin-bottom: 0;
        }

        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        .powered-by a {
            text-decoration: none;
        }

        hr {
            border: 0;
            /* border-bottom: 1px solid #f6f6f6; */
            margin: 20px 0;
        }

        /* -------------------------------------
            PRESERVE THESE STYLES IN THE HEAD
        ------------------------------------- */
        @media all {
            .ExternalClass {
                width: 100%;
            }
            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }
            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }
            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
            }
            .btn-primary table td:hover {
                background-color: #34495e !important;
                -webkit-print-color-adjust: exact;
            }
            .btn-primary a:hover {
                background-color: #34495e !important;
                -webkit-print-color-adjust: exact;
                border-color: #34495e !important;
            }
        }

        /* Utilities */
        @media all{
            .text-primary{
                color:#ee8622!important;
            }
            .text-white{
                color:#ffffff!important;
            }
            .bg-primary{
                background-color:#ee8622!important;
                -webkit-print-color-adjust: exact;
            }
            .bg-gray{
                background-color:#e2e2e2!important;
                -webkit-print-color-adjust: exact;
            }
            .bg-dark{
                background-color: #666666!important;
                -webkit-print-color-adjust: exact;
            }
            .text-right{
                text-align: right;
            }
            .text-center{
                text-align: center;
            }
            .pull-left{
                float: left;
            }
            .border{
                border: 1px solid #eeeeee;
            }
            .border-bottom{
                border-bottom: 1px solid #eeeeee;
            }
            .border-top{
                border-top: 1px solid #eeeeee;
            }
            .border-primary{
                border-color: #ee8622 !important;
            }
            .border-dark{
                border-color: #666666 !important;
            }
            .text-uppercase{
                text-transform: uppercase;
            }
            .mb-0{
                margin-bottom: 0 !important;
            }
            .mb-1{
                margin-bottom: 10px !important;
            }
            .mb-1{
                margin-bottom: 20px !important;
            }
            .mb-3{
                margin-bottom: 30px !important;
            }
            .p-05{
                padding: 5px;
            }
            .p-1{
                padding: 10px;
            }
            .p-2{
                padding: 20px;
            }
            .pt-1{
                padding-top: 10px;
            }
            .pt-2{
                padding-top: 20px;
            }
            .pb-1{
                padding-bottom: 10px;
            }
            .pb-05{
                padding-bottom: 5px;
            }
            .py-1{
                padding-top: 10px !important;
                padding-bottom: 10px !important;
            }
            .px-1{
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            .py-05{
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }
            .px-2{
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
            td[valign*="middle"] {
                vertical-align: middle !important;
            }
            table[width*="48%"] {
                width: 48% !important;
            }
            ul{
                padding: 0;
                font-size: 12px;
            }
            .font-sm{
                font-size: 12px !important;
            }
            .font-md{
                font-size: 13px !important;
            }
        }
    </style>
</head>
<body class="">

<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" id="m_-8783757586868756026m_-2559622463710647374ticket_show"
    style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6; width: 100%;" width="100%" bgcolor="#f6f6f6">
    <tr>
      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
      <td class="container"
        style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 680px; width: 680px;"
        width="100%" valign="top">
        <div class="" style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 680px; padding: 10px;">

          <!-- START CENTERED WHITE CONTAINER -->
          <table role="presentation" class="main"
            style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;"
            width="100%">

            <!-- START MAIN CONTENT AREA -->
            <tr>
              <td class="wrapper"
                style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;"
                valign="top">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                  style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"
                  width="100%">
                  <tr>
                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                      <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;clear:both;empty-cells:show;font-weight:normal">
                        <tbody style="margin:0px;padding:0px;font-size:13px;color:rgb(60,60,60);border:0px">
                            <tr style="margin:0px;padding:0px;border:0px">
                                <td style="margin-top:2px;text-align:left;margin-bottom:2px;padding:5px;color:rgb(0,0,0);font-family:Arial!important;">
                                    <div style="width:100%;margin:0 auto;padding-top:5px;padding-bottom:5px;text-align:center;">
                                        <span style="margin:0;padding:0px;font-size:10pt;color:#095075;font-weight:600;">SpiceJet</span><br>
                                        <span style="margin:0px;padding:0px;font-size:17pt;font-weight:bold;;color:#095075;">LV7R6Q</span><br>
                                        <span style="margin:0px;padding:0px">Airline PNR</span>
                                    </div>
                                </td>
                                <td width="50%" style="margin-top:2px;margin-bottom:2px;text-align:right;padding:5px 2px 5px 7px;color:rgb(0,0,0);font-family:Arial!important;">
                                    <div style="margin:0 auto;padding-top:5px;padding-bottom:5px">
                                       <img src="https://goflysmart.blob.core.windows.net/assets/spicejetlogo.png" width="200" height="79" alt="Logo" style="color:rgb(0,0,0);font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0px;padding:0px;border:0px" class="CToWUd">
                                    </div>
                                </td>
            
                                <td width="50%" style="margin-top:2px;margin-bottom:2px;text-align:right;padding:5px 2px 5px 7px;color:rgb(0,0,0);font-family:Arial!important;">
                                  <div style="margin:0 auto;padding-top:5px;padding-bottom:5px">
                                    <span>Booked On : <strong>10/11/2023 09:46:55</strong></span> <br>
                                    <span>Ticket is <strong>Non Refundable</strong> / <strong>Non Cancellable.</strong></span>
                                  </div>
                                </td>
                            </tr>
                        </tbody>
                      </table>

                      <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%;" width="100%">
                        <tbody>
                          <tr>
                            <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                              <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                <tbody>
                                  <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center;" valign="top" align="center"> 
                                      <h4 class="title" style="margin-bottom: 5pt;text-transform: uppercase;">Flight Deatils : </h4>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 5pt;">
                        <tbody>
                          <tr>
                            <td style="border: 1px solid #ddd; border-radius: 0.375rem; margin-top: 1rem;padding: 0.25rem;">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;clear:both;empty-cells:show;font-weight:normal">
                                    <tbody style="margin:0px;padding:0px;font-size:13px;color:rgb(60,60,60);border:0px">
                                        <tr style="margin:0px;padding:0px;border:0px">
                                            <td width="40%" style="margin-top:2px;text-align:left;margin-bottom:2px;padding:5px;color:rgb(0,0,0);font-family:Arial!important;">
                                              
                                                <div style="width:100%;margin:0 auto;padding-top:5px;padding-bottom:5px;">
                                                    <div style="display: flex;display: -webkit-box;">
                                                        <div class="image" style="width: 50px;height:40px;overflow: hidden;">
                                                            <img src="https://images.jdmagicbox.com/comp/delhi/c9/011pxx11.xx11.171229170107.e6c9/catalogue/indigo-air-cargo-services-mahipalpur-extension-delhi-cargo-services-i9owvibyd4.jpg" width="45" alt="flight-logo"
                                                                style="max-width: 100%; height: 100%; border-radius: 5pt;object-fit:contain">
                                                        </div>
                                                        <div>
                                                            <p style="margin: 0; font-size: 0.875rem;">Indigo</p>
                                                            <p style="margin: 0; text-transform: uppercase; font-size: 0.875rem;">6E 5177</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td width="50%" style="margin-top:2px;margin-bottom:2px;text-align:right;padding:5px 2px 5px 7px;color:rgb(0,0,0);font-family:Arial!important;">
                                                <div style="margin:0 auto;padding-top:5px;padding-bottom:5px">
                                                    <div style="text-align: center;">
                                                        <p style=" font-weight: 600; margin: 0;">PATNA (PAT)</p>
                                                        <p style="font-size: 0.875rem;  margin: 0;">09:15 | 12-Nov-2023</p>
                                                    </div>
                                                </div>
                                            </td>
                        
                                            <td width="50%" style="margin-top:2px;margin-bottom:2px;text-align:right;padding:5px 2px 5px 7px;color:rgb(0,0,0);font-family:Arial!important;">
                                                <div style="margin:0 auto;padding-top:5px;padding-bottom:5px">
                                                    <div style="text-align: center;">
                                                        <p style=" font-weight: 600; margin: 0;">PATNA (PAT)</p>
                                                        <p style="font-size: 0.875rem;  margin: 0;">09:15 | 12-Nov-2023</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                              
                            </td>
                          </tr>
                        </tbody>
                      </table>
                     
                      
                      <!-- booking details -->
                      <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%;" width="100%">
                        <tbody>
                          <tr>
                            <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                              <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                <tbody>
                                  <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center;" valign="top" align="center"> 
                                      <h4 class="title" style="margin-bottom: 5pt;">YOUR BOOKING DETAILS : </h4>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>

                      <table width="100%" cellpadding="0" cellspacing="0" border="0" style="color:rgb(0,0,0);margin:0px 0px 10px;padding:0px;border:1px solid rgb(207, 207, 207);border-collapse:collapse;clear:both;empty-cells:show;font-weight:bold;text-transform:uppercase;font-size:12px!important;font-family:Arial!important">
                        <tbody style="margin:0px;padding:0px;color:rgb(60,60,60);border:0px">
                          <tr>
                            <td>
                              <table cellpadding="0" cellspacing="0" width="100%">
                                <tbody>
                                  <tr style="margin:0px;padding:0px;border:0px;background-color:#f0f0f0f0;height:32pt;">
                                    <th style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:5px 5px 5px 18px;font-family:Arial!important;text-align:center">                       
                                        Sl No.
                                    </th>
                                    <th style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;font-family:Arial!important;text-align:left">
                                        Passenger
                                    </th>
                                    <th style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;font-family:Arial!important;text-align:center">
                                      Type
                                    </th>
                                    <th style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;font-family:Arial!important;text-align:center">
                                      Status
                                    </th>
                                  </tr>
                                  <tr style="margin:0px;padding:0px;border:0px;height:50pt">
                                      <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);font-family:Arial!important;text-align:center">
                                          <p class="s15" style="padding-left: 1pt;text-indent: 0pt;line-height: 14pt;text-align: center;">1</p>
                                      </td>
                                      <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);font-family:Arial!important;text-align:center">
                                          <p style="padding-top: 5pt;text-indent: 0pt;text-align: left;margin:0px; padding-bottom:3px;font-size:11pt;">John Doe</p>
                                          <p style="text-indent: 0pt;text-align: left!important;margin:0px;font-size:8pt;">Cabin : 7 Kg</p>
                                          <p style="text-indent: 0pt;text-align: left;margin:0px;font-size:8pt;">Check-In : 15 Kg</p>
                                      </td>
                                      <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);font-family:Arial!important;text-align:center">
                                          <p class="s17" style="padding-left: 19pt;padding-right: 17pt;text-indent: 9pt;line-height: 119%;text-align: left;">Adult</p>
                                      </td>
                                      
                                      <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);font-family:Arial!important;text-align:center">
                                          <p class="s26" style="padding-left: 13pt;padding-right: 12pt;text-indent: 0pt;text-align: center;color:#03B10F">Confirmed</p>
                                      </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <!-- terms and condition -->
                      <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%;" width="100%">
                        <tbody>
                          <tr>
                            <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                              <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                <tbody>
                                  <tr>
                                    <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 5px; text-align: center;" valign="top" align="center"> 
                                      <h4 class="title" style="margin-bottom: 5pt;">Terms &amp; Conditions : </h4>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;margin:0px;padding:0px;color:rgb(60,60,60);border:0px;border-collapse:collapse;clear:both;empty-cells:show;font-family:Arial!important">
                        <tbody style="margin:0px;padding:0px;border:0px">
                          <tr style="margin:0px;padding:0px;border:0px">
                              <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:5px 10px 0px 18px;font-size:12px;font-family:Arial!important">
                                  <ul style="margin:0px;padding:0px 0px 0px 0px">
                                      <li style="margin:0px;padding:0px">
                                          <strong style="margin:0px;padding:0px">This ticket is Non Refundable &amp; Non Changeable </strong>
                                      </li>
          
                                      <li style="margin:0px;padding:0px">
                                          <strong style="margin:0px;padding:0px">All Guests, including children and infants, must present valid identification at check-in.</strong>
                                      </li>
          
                                      <li style="margin:0px;padding:0px">
                                          <strong style="margin:0px;padding:0px">As per government directives, Web Check-in is mandatory for all passengers before the scheduled departure of their domestic flight. Charges apply*</strong>
                                      </li>
          
                                      <li style="margin:0px;padding:0px">
                                          <strong style="margin:0px;padding:0px">Check-in begins 3 hours prior to the flight for seat assignment and closes 45 minutes prior to the scheduled departure.</strong>
                                      </li>
          
                                      <li style="margin:0px;padding:0px">
                                          <strong style="margin:0px;padding:0px">Charged fare is totally agreed between "BUYER &amp; SELLER", any issues related to fares thereafter will not be entertained.</strong>
                                      </li>
          
                                      <li style="margin:0px;padding:0px">
                                          <strong style="margin:0px;padding:0px">We are not responsible for any Flight delay/Cancellation from airline's end. kindly contact the airline at least 24 hrs before to reconfirm your flight detail giving reference of Airline PNR Number. For any schedule change, flight cancelled &amp; terminal related issues.</strong>
                                      </li>
                                  </ul>
                              </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- END MAIN CONTENT AREA -->
          </table>

        </div>
      </td>
      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
    </tr>
</table>
</body>
</html>

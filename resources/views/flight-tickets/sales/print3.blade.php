
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <title>Print Ticket - VishalTravels</title>
    <style>
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */

        /*All the styling goes here*/

        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

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
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%; }
        table td {
            font-family: sans-serif;
            font-size: 14px;
            vertical-align: top;
        }

        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */

        .body {
            /* background-color: #f6f6f6; */
            width: 100%;
        }

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            /* max-width: 580px; */
            /* padding: 10px; */
            /* width: 580px;  */
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            /* max-width: 580px; */
            /* padding: 10px;  */
        }

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
        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
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

<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td class="container">
            <div class="content">

                <!-- START CENTERED WHITE CONTAINER -->
                <table role="presentation" class="main">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <!-- Header Info -->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="text-right font-md">
                                                    <p class="text-uppercase text-primary">{{ $data->company_name}} </p>
                                                    Booking ID : {{ $data->bill_no}} <br> Booked on : {{ $data->created_at }}
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Header Info -->

                                        <!-- Header Empty Bordered -->
                                        <table role="presentation" border="0" cellpadding="2" cellspacing="0" style="border-top: 1px solid #ee8622; border-bottom: 1px solid #ee8622;">
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                        <!-- Header Empty Bordered -->

                                        <!-- Flight Basic Details -->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="py-1">
                                            <tr>
                                                <td valign="middle" width="180">
                                                </td>
                                                <td valign="middle">
                                                    <!-- <p class="mb-0">{{ $data->airline }}</p> -->
                                                </td>
                                                <td valign="middle">
                                                    <!-- <p class="mb-0">Agency</p>
                                                    <p class="text-primary"><strong>{{ $data->phone}}</strong></p> -->
                                                </td>
                                                <td valign="middle" class="border border-dark p-05 text-center" width="150">
                                                    <p class="text-primary text-uppercase mb-0">Airline PNR</p>
                                                    <h3 class="mb-0"><strong>{{ $data->pnr }}</strong></h3>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Flight Basic Details -->

                                        <!-- Flight Onward Details -->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="bg-primary text-white py-05 px-2">
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td>Onward Flight Details</td>
                                                            <td class="text-right">*Please verify flight times with the airlines prior to departure</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border border-primary">
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td class="bg-gray text-primary py-05 px-2">Flight</td>
                                                            <td class="bg-gray py-05 px-2">Departing</td>
                                                            <td class="bg-gray py-05 px-2" colspan="2">Arriving</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-05 px-2">
                                                               
                                                                {{ $data->airline }}
                                                            </td>
                                                            <td class="py-05 px-2">
                                                                @if(isset($purchase_entry))
                                                                    {{ $data->destinationDetails->origin->name }} ( {{ $data->destinationDetails->origin->code }} ) <br>
                                                                    {{-- Terminal - T1<br> --}}
                                                                    {{ $data->purchase_entry->travel_date->format('d-m-Y') }} {{ $data->purchase_entry->departure_time }}
                                                                @else
                                                                    {{-- Terminal - T1<br> --}}
                                                                    {{ \Carbon\Carbon::parse($data->departureDate)->format('Y-m-d') }}  {{ \Carbon\Carbon::parse($data->departureDate)->format('H:i:s') }}
                                                                @endif
                                                            </td>
                                                            <td class="py-05 px-2">
                                                                @if(isset($purchase_entry))
                                                                    {{ $data->destinationDetails->destination->name }} ( {{ $data->destinationDetails->destination->code }} )<br>
                                                                    {{-- Terminal - T3<br> --}}
                                                                {{ $data->purchase_entry->arrival_date->format('d-m-Y') }} {{ $data->purchase_entry->arrival_time }}
                                                                @else
                                                                {{ $data->dest }} ( {{ $data->dest }} )<br>
                                                                {{-- Terminal - T3<br> --}}
                                                                {{ \Carbon\Carbon::parse($data->arrivalDate)->format('Y-m-d') }}  {{ \Carbon\Carbon::parse($data->arrivalDate)->format('H:i:s') }}
                                                                @endif
                                                               
                                                            </td>
                                                            <td class="py-05 px-2">
                                                                Non Stop<br>
                                                                <strong>Non-Refundable</strong><br>
                                                              
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Flight Onward Details -->

                                        <!-- Spacer -->
                                        <table role="presentation" border="0" cellpadding="1" cellspacing="0">
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                        <!-- Spacer -->

                                        <!-- Passenger Details -->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="bg-dark text-white py-05 px-2">
                                                    Passenger(s) Details
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border border-dark">
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td>
                                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <td class="bg-gray py-05 px-2" width="80">Sr No.</td>
                                                                        <td class="bg-gray py-05 px-2">Passenger(s) Name</td>
                                                                        <td class="bg-gray py-05 px-2">Type</td>
                                                                    </tr>
                                                                    @foreach($book_ticket_details->where('is_refund',0) as $key => $val)
                                                                    <tr>
                                                                        <td class="py-05 px-2">{{ 1 +$loop->index }}</td>
                                                                        <td class="py-05 px-2">{{ $val->title }} {{ $val->first_name }} {{ $val->last_name }} @if($val->type == 3) <small>DOB - {{ $val->dob->format('d-m-Y') }}</small> @endif</td>
                                                                        <td class="py-05 px-2"> @if($val->type == 1) Adult @elseif($val->type == 2) Child @elseif($val->type == 3) Infant @endif</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Passenger Details -->

                                        <!-- Spacer -->
                                        <table role="presentation" border="0" cellpadding="1" cellspacing="0">
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                        <!-- Spacer -->

                                        <!-- Charges -->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td class="border border-dark">
                                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <td>
                                                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                                                <tr>
                                                                                    <td class="py-05 px-1 border-bottom border-dark" colspan="4">Flight Inclusions </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="py-05 px-1 bg-gray">Baggage</td>
                                                                                    <td class="py-05 px-1 bg-gray">Adult</td>
                                                                                    <td class="py-05 px-1 bg-gray">Child</td>
                                                                                    <td class="py-05 px-1 bg-gray">Infant</td>
                                                                                </tr>
                                                                                @php
                                                                                $baggage_info = json_decode($purchase_entry->baggage_info);
                                                                                $originCountryCode = $purchase_entry->destination->origin->countryCode;
                                                                                $destinationCountryCode = $purchase_entry->destination->destination->countryCode;
                                                                                @endphp
                                                                                 @if($baggage_info)
                                                                                 @foreach($baggage_info as $i => $val)
                                                                                     @php
                                                                                     if($originCountryCode == 'IN' && $destinationCountryCode == 'IN') {
                                                                                         if($val->mode == 'international') {
                                                                                             continue;
                                                                                         }
                                                                                     }else{
                                                                                         if($val->mode == 'domestic') {
                                                                                             continue;
                                                                                         }
                                                                                     }
                                                                                     @endphp
                                                                                <tr>
                                                                                    <td class="py-05 px-1">{{$val->type}}</td>
                                                                                    <td class="py-05 px-1">{{$val->adult}}</td>
                                                                                    <td class="py-05 px-1">{{$val->child}}</td>
                                                                                    <td class="py-05 px-1">{{$val->infant}}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                                @else
                                                                                <tr>
                                                                                    <td class="pb-05 px-1">Cabin Baggage</td>
                                                                                    <td class="pb-05 px-1">7kg</td>
                                                                                    <td class="pb-05 px-1">7kg</td>
                                                                                    <td class="pb-05 px-1">0 Kg</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="pb-05 px-1">Check-in Baggage </td>
                                                                                    <td class="pb-05 px-1">15kg </td>
                                                                                    <td class="pb-05 px-1">15kg </td>
                                                                                    <td class="pb-05 px-1">0 Kg</td>
                                                                                </tr>
                                                                                @endif
                                                                                <tr>
                                                                                    <td class="pt-1 px-1 pb-1 border-top border-dark" colspan="4">
                                                                                        <span class="font-sm">* Flight inclusions are subject to change with Airlines</span>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- Spacer -->
                                                    <table role="presentation" border="0" cellpadding="1" cellspacing="0">
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                    <!-- Spacer -->
                                                    @if($data->display_price)
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td class="border border-dark p-1">
                                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                                    <tr>
                                                                        <td>
                                                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                                                <tr>
                                                                                    <td class="pb-1 border-bottom border-dark">Payment Details </td>
                                                                                    <td class="pb-1 border-bottom border-dark text-right">Amount (INR)</td>
                                                                                </tr>
                                                                                <td class="pt-1">Total Fare</td>
                                                                                <td class="pt-1 text-right">{{ number_format($data->display_price, 2, '.', '') }}</td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Charges -->

                                        <!-- Spacer -->
                                        <table role="presentation" border="0" cellpadding="1" cellspacing="0">
                                            <tr>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </table>
                                        <!-- Spacer -->

                                        <!-- Important Information -->
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td>
                                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td width="200" class="font-md">Important Information</td>
                                                            <td class="pt-1"><p class="border-top border-dark">&nbsp;</p></td>
                                                        </tr>
                                                    </table>
                                                    <ul>
                                                        <li>This ticket is Non Refundable & Non Changeable</li>
                                                        <li>All Guests, including children and infants, must present valid identification at check-in.</li>
                                                        <li>Check-in begins 3 hours prior to the flight for seat assignment and closes 45 minutes prior to the scheduled departure.</li>
                                                        <li>Carriage and other services provided by the carrier are subject to conditions of carriage, which are hereby incorporated by reference. These conditions may be obtained from the issuing carrier.</li>
                                                        <li>In case of cancellations less than 6 hours before departure please cancel with the airlines directly. We are not responsible for any losses if the request is received less than 6 hours before departure.</li>
                                                        <li>Please contact airlines for Terminal Queries.</li>
                                                        <li>If the basic fare is less than cancellation charges then only statutory taxes would be refunded.</li>
                                                        <li>We are not be responsible for any Flight delay/Cancellation from airline's end.</li>
                                                        <li>Kindly contact the airline at least 24 hrs before to reconfirm your flight detail giving reference of Airline PNR Number.</li>
                                                        <li>We are a travel agent and all reservations made through our website are as per the terms and conditions of the concerned airlines. All modifications,cancellations and refunds of the airline tickets shall be strictly in accordance with the policy of the concerned airlines and we disclaim all liability in connection thereof.</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- Important Information -->
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>
                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
    </tr>
</table>
</body>
<script>
    window.print()
</script>
</html>

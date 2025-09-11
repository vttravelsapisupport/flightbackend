<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap">
</head>
<style type="text/css">
    body {
        font-family: Arial, Helvetica, sans-serif !important;
        margin: 0;
    }
    table {
        border-spacing: 0;
    }
    img {
        border: 0;
    }
</style>
<body>
<center class="wraper" style="width: 100%;
         table-layout: fixed;">
    <table class="main" width="100%"
           style="background-color: white; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; float: left;">
        <!----top-border------>
        <tr>
            <td height="5" style="background-color: #095075;"></td>
        </tr>
        <!-------LOGO SECTION-------->
        <tr>
            <td style="padding: 14px 18px 4px;">
                <table width="100%">
                    <tr>
                        <td class="two-columns" style="padding-bottom: 1%;">
                            <table class="column" style="display: inline-block;
                              vertical-align: top;
                              text-align: center;">
                                <tr>
                                    <td>
                                        <a href="https://goflysmart.com">
                                            <img src="https://goflysmart.blob.core.windows.net/emailer/ppaf/logo.png"
                                                 alt="logo" width="150">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <table class="column" style="float: right;
                              font-size: 11px;
                              font-weight: bold;
                              line-height: 0%;
                              margin-top: 1%;">
                                <tr>
                                    <td>
                                        <a href="tel:8389898989" style="text-decoration: none;">
                                            <p style="color: #095075;">Call & WhatsApp -
                                                083 89 89 89 89
                                            </p>
                                            <hr style="border:1px solid #f28f32;">
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!------Departure section--------->
        <tr>
            <td class="two-columns2" style="text-align: center;">
                <h6 style="font-size: 33px; margin-top: 0%; margin-bottom: 0%;
                     color: #095075;">Fixed Departure Airfare</h6>
                <span style="font-size: 17px; color: #095075; font-weight: 600;">Book Online - <a href="https://goflysmart.com" style="text-decoration: none; color: #f28f32;">www.goflysmart.com</a></span>
            </td>
        </tr>
        <tr>
            <td>
                <hr style="border: 2px solid #f28f32;">
            </td>
        </tr>
        <!-----------THREE COLUME SECTION------------->
        @php $row_count = 0; @endphp
        @foreach($output_datas as $i => $d)


            @if ($row_count % 2 == 0)
                <tr class="column4" style="width: 100%; vertical-align: top;">
            <td style="padding-left: 25px;padding-right: 10px; display: inline-block;">
                @else
                <td style="padding-right: 10px;display: inline-block;">
                    @endif
                <table width="100%" style="margin-top: 4%;">
                    <tr>
                        <td class="three-columns">
                            <table class="column3"
                                   style="background-color: #095075; color: white; width: 270px; height: 35px;">
                                <tr>
                                    <td>
                                <tr>
                                    <td style="padding-left: 12px; font-weight: 600;width: 22px;">
                                        <img src="https://goflysmart.blob.core.windows.net/emailer/ppaf/FLIGHT%20FLYSMART.png"
                                             width="22px">
                                    </td>
                                    <td>
                                        <span style="padding-left: 5px;">{{ $i }}</span>
                                    </td>
                                </tr>


                            </table>
                            <table class="column3" style="width: 270px; text-align: center;border-spacing: 0;">
                                <thead style="font-size: 10px; height: 30px;">
                                <tr>
                                    <th>DATE</th>
                                    <th>PRICE</th>
                                    <th>LIVE PNR</th>
                                </tr>
                                </thead>
                                <tbody style="font-size: 12px; font-weight: 600;">
                                @php $row_count2 = 0; @endphp
                                @foreach($d as $x => $y)
                                    @php
                                        $date = Carbon\Carbon::parse($y->travel_date)->format('d M Y');
                                        $date_url = str_replace("-","%2F",Carbon\Carbon::parse($y->travel_date)->format('m-d-Y'));
                                        $row_count2++;
                                    @endphp
                                    <tr style="background-color: #e5f6ff; height: 30px;">
                                        <td>{{ $date }}</td>
                                        <td>₹{{ number_format($y->sell_price, 0, '.',
                                                ',') }}/-</td>
                                        <td><a href="https://agent.goflysmart.com/search?adults=1&child=0&infant=0&destination={{ $y->destination_code }}&origin={{ $y->origin_code }}&departure_date={{ $date_url }}" style="text-decoration: none;
                                       color: #095075;">Book Now</a></td>
                                    </tr>
                                @endforeach
                                @while ($row_count2 < request()->query('days'))
                                    @php $row_count2++; @endphp
                                    <tr style="background-color: #e5f6ff; height: 30px;">
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                @endwhile
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
                        @if ($row_count % 2 != 0)
                            </tr>
            @endif
            @php $row_count++; @endphp

        @endforeach

        <tr>
            <td>
                <hr style="margin-top: 4%; border: 1px solid #f28f32; border-top: none;">
            </td>
        </tr>
        <!-------------BOTTOM-------->
        <tr class="column7" style="margin-left: 4%; margin-bottom: 3%;display: inline-block;vertical-align: top;">
            <td>
                <table width="100%">
                    <tr>
                        <td class="three-columns">
                            <table class="column3" style="width: 270px;">
                                <tbody style="font-weight: 600;">
                                <tr>
                                    <td>
                                        <p style="margin-bottom: 0%; color: #095075;"> GoFlySmart Holidays Pvt
                                            Ltd
                                        </p>
                                        <span style="font-size: 9px; font-weight: 700; opacity: 75%;">
                                       <img src="https://goflysmart.blob.core.windows.net/emailer/ppaf/location-icon-png.png"
                                            alt="map-logo" width="7px" style="max-width: 100%;
                                          max-height: 100%;
                                          margin-top: 3px;">
                                       &nbsp;The
                                       Planet Mall,Sevoke Road,Siliguri, WB</span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table width="100%">
                    <tr>
                        <td>
                            <table class="column8" style="width: 270px;">
                                <tbody style=" font-weight: 600;">
                                <tr style="color: #095075; font-size: 10px;">
                                    <td>
                                        <h4 style="margin-bottom: 0%; margin-left: 67%;">Follow us on:</h4>
                                    </td>
                                </tr>
                                <tr style="text-align: end; font-size: 11px; float: right;">
                                    <td class="icon">
                                        <a href="https://www.instagram.com/go_flysmart_holidays/">
                                            <img src="https://goflysmart.blob.core.windows.net/emailer/ppaf/insta.png"
                                                 alt="instagram-icon" width="16px" style="max-width: 100%;
                                          max-height: 100%;
                                          margin-top: 3px;"></a>
                                        <a href="https://www.facebook.com/goflysmartholidays/">
                                            <img src="https://goflysmart.blob.core.windows.net/emailer/ppaf/Facebook-Logo.png"
                                                 alt="fb" width="16px" style="max-width: 100%;
                                          max-height: 100%;
                                          margin-top: 3px;"></a>
                                        <a href="https://in.linkedin.com/company/goflysmart">
                                            <img src="https://goflysmart.blob.core.windows.net/emailer/ppaf/linkedin-logo.png"
                                                 alt="goggle-logo" width="16px" style="max-width: 100%;
                                          max-height: 100%;
                                          margin-top: 3px;"></a>
                                        <a href="https://twitter.com/GoFlySmart_">
                                            <img src="https://goflysmart.blob.core.windows.net/emailer/ppaf/twitter-logo.png"
                                                 alt="twitter-logo" width="16px" style="max-width: 100%;
                                          max-height: 100%;
                                          margin-top: 3px;"></a>
                                        <a href="https://www.youtube.com/@goflysmart">
                                            <img src="https://goflysmart.blob.core.windows.net/emailer/ppaf/youtube-icon.png"
                                                 alt="youtube-icon" width="16px" style="max-width: 100%;
                                          max-height: 100%;
                                          margin-top: 3px;"></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="font-size: 10px; font-weight: 600; text-align: center;">
            <td>
                <a href="https://blog.goflysmart.com/about-us" style="color: #f28f32;">About us |</a>
                <a href="https://goo.gl/maps/Jmdv727rCFBN8Zo29" style="color: #f28f32; margin-bottom: 5%;"> Our Address |</a>
                <a href="https://blog.goflysmart.com" style="color: #f28f32;">Blog</a>
            </td>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr
            style="font-size: 14px; font-weight: 600; background-color: #095075; height: 40px; text-align: center; color: white; margin-top: 1%;">
            <td>
                  <span style="color: white;"><a href="tel:8389898989" style="color: white; text-decoration: none;">Call: 07969244000</a> | <a href="mailto:support@vishaltravels.in" style="color: white; text-decoration: none;">E-mail:
                  support@vishaltravels.in</a></span>
            </td>
        </tr>
    </table>
</center>
</body>
</html>

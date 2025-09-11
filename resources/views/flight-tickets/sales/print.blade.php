<div id="m_-8783757586868756026m_-2559622463710647374ticket_show" style="background-color:#fff;border:1px solid #ddd;padding:5px">
    <div style="width:900px;margin:0 auto">

        <img src="{{ $data->purchase_entry->airline->description }}" width="200" height="79" alt="Logo" style="color:rgb(0,0,0);font-family:Arial,Helvetica,sans-serif;font-size:12px;margin:0px;padding:0px;border:0px" class="CToWUd">

        <p style="color:rgb(0,0,0);font-family:Arial,Helvetica,sans-serif;font-size:13px;margin:0px;padding:5px 0px 7px 18px;float:left;width:876px">
            <span style="margin:0px;padding:0px;float:left"><strong style="font-weight:bold"><br>Ticket is Non Refundable / Non Changeable / Non Cancellable.<br>&nbsp;</strong></span>
            <span style="float:right;text-align:right">Booked On {{ $data->created_at->format('d-m-Y ') }} at {{ $data->created_at->format('H:i:s') }} </span>
            <br style="margin:0px;padding:0px">
        </p>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;clear:both;empty-cells:show;font-weight:normal">
            <tbody style="margin:0px;padding:0px;font-size:13px;color:rgb(60,60,60);border:0px">
                <tr style="margin:0px;padding:0px;border:0px">

                    <td width="50%" style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:5px 2px 5px 18px;color:rgb(0,0,0);font-family:Arial!important;text-align:center">
                        <div style="width:98%;margin:0 auto;border:1px dashed #000;padding-top:5px;padding-bottom:5px">
                            <span style="margin:0px;padding:0px">Airline PNR</span><br>
                            <span style="margin:0px;padding:0px;font-size:18px;font-weight:bold">{{ $data->pnr }}</span>
                        </div>
                    </td>

                    <td width="50%" style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:5px 2px 5px 18px;color:rgb(0,0,0);font-family:Arial!important;text-align:center">
                        <div style="width:85%;margin:0 auto;padding-top:5px;padding-bottom:5px">
                            <span style="margin:0px;padding:0px;font-size:18px;font-weight:bold">

                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <br>

        <table width="898" cellpadding="0" cellspacing="0" border="0" style="color:rgb(0,0,0);margin:0px 0px 10px;padding:0px;border:1px solid rgb(82,82,82);border-collapse:collapse;clear:both;empty-cells:show;font-weight:bold;text-transform:uppercase;font-size:12px!important;font-family:Arial!important">
            <tbody style="margin:0px;padding:0px;color:rgb(60,60,60);border:0px">
                <tr style="margin:0px;padding:0px;border:0px">
                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:5px 5px 5px 18px;color:rgb(0,0,0);border:1px solid rgb(0,0,0);font-family:Arial!important;text-align:center">
                        Flight
                    </td>
                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);border:1px solid rgb(0,0,0);font-family:Arial!important;text-align:center">
                        Departing
                    </td>
                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);border:1px solid rgb(0,0,0);font-family:Arial!important;text-align:center">
                        Arriving
                    </td>
                </tr>
                <tr style="margin:0px;padding:0px;border:0px">
                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);border:1px solid rgb(0,0,0);font-family:Arial!important;text-align:center">
                        {{ $data->airlineDetails }} ( {{ $data->purchase_entry->flight_no }} )
                    </td>
                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);border:1px solid rgb(0,0,0);font-family:Arial!important;text-align:center">
                        {{ $data->destinationDetails->origin->name }} ( {{ $data->destinationDetails->origin->code }} )
                        <br> {{ $data->purchase_entry->travel_date->format('d-m-Y') }} {{ $data->purchase_entry->departure_time }}
                    </td>
                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 3px 3px 18px;color:rgb(0,0,0);border:1px solid rgb(0,0,0);font-family:Arial!important;text-align:center">
                        {{ $data->destinationDetails->destination->name }} ( {{ $data->destinationDetails->destination->code }} ) <br> {{ $data->purchase_entry->arrival_date->format('d-m-Y') }} {{ $data->purchase_entry->arrival_time }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table width="898" cellpadding="0" cellspacing="0" border="0" style="margin:0px 0px 10px;padding:6px 0px;color:rgb(60,60,60);border:1px solid rgb(0,0,0);border-collapse:collapse;clear:both;empty-cells:show;font-weight:bold;float:left;font-family:Arial!important">
            <tbody style="margin:0px;padding:0px;border:0px">
                <tr style="margin:0px;padding:0px;border:0px">
                    <td valign="top" width="100%" style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 8px 3px 5px;border:0px;font-family:Arial!important">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;clear:both;empty-cells:show;font-weight:normal">
                            <tbody style="margin:0px;padding:0px;border:0px">

                                <tr style="margin:0px;padding:0px;border:0px">
                                    <td>Sl No.</td>
                                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 18px 5px;color:rgb(0,0,0);border:0px;font-weight:bold;font-family:Arial!important;text-align:left">
                                        Passenger(s) Name
                                    </td>
                                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 5px 2px 18px;border:0px;font-weight:bold;width:190px;font-family:Arial!important">Type</td>
                                </tr>
                                @foreach($book_ticket_details->where('is_refund',0) as $key => $val)
                                <tr valign="top" style="margin:0px;padding:0px;border:0px">
                                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 5px 2px 18px;border:0px;font-weight:bold;width:200px;font-family:Arial!important">
                                        &nbsp; {{ 1 +$loop->index }}.

                                    </td>
                                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 18px 2px;border:0px;font-weight:bold;text-transform:uppercase;font-family:Arial!important;text-align:left">
                                        <span style="font-family:arial,sans,sans-serif;color:rgb(34,34,34)"> {{ $val->title }} {{ $val->first_name }} {{ $val->last_name }} @if($val->type == 3) <small>DOB - {{ $val->dob->format('d-m-Y') }}</small>  @endif</span>
                                    </td>
                                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:3px 5px 2px 18px;border:0px;font-weight:bold;width:100px;font-family:Arial!important">
                                        @if($val->type == 1) Adult @elseif($val->type == 2) Child @elseif($val->type == 3) Infant @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        @if($data->display_price)
        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;clear:both;empty-cells:show;font-weight:normal">
            <tr style="margin:0px;padding:0px;border:0px">
                <td width="100%" style="margin-top:2px;margin-bottom:2px;margin-right:10px;color:rgb(0,0,0);font-family:Arial!important;text-align:right">
                    <b>Total Fare</b> : {{ number_format($data->display_price, 2, '.', '') }}
                </td>
            </tr>
        </table>
        @endif

        <table width="100%" cellpadding="0" cellspacing="0" border="0" summary="" style="margin:0px;padding:0px;border:0px;border-collapse:collapse;clear:both;empty-cells:show;font-weight:normal">
            <tbody style="margin:0px;padding:0px;font-size:13px;color:rgb(60,60,60);border:0px">
                <tr style="margin:0px;padding:0px;border:0px">
                    <td width="100%" style="margin-top:2px;margin-bottom:2px;margin-left:10px;color:rgb(0,0,0);font-family:Arial!important;text-align:center">
                        <div style="width:100%;margin:0 auto;border:1px solid #000">
                            <div style="width:100%;border-bottom:1px solid #000;font-weight:bold;text-align:left"> &nbsp; Flight Inclusions </div>
                            <div style="clear:both"></div>
                            <div style="width:40%;float:left;text-align:left"> &nbsp; Baggage</div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">Adult </div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">Child </div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">Infant </div>
                            <div style="clear:both"></div>
                            @php
                            $baggage_info = json_decode($data->purchase_entry->baggage_info);
                            $originCountryCode = $data->purchase_entry->destination->origin->countryCode;
                            $destinationCountryCode = $data->purchase_entry->destination->destination->countryCode;
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
                            <div style="width:40%;float:left;text-align:left"> &nbsp; {{$val->type}}</div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">{{$val->adult}} </div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">{{$val->child}} </div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">{{$val->infant}} </div>
                            <div style="clear:both"></div>
                            @endforeach
                            @else
                            <div style="width:40%;float:left;text-align:left"> &nbsp; Cabin baggage</div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">7kg </div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">7kg </div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">0kg </div>
                            <div style="clear:both"></div>

                            <div style="width:40%;float:left;text-align:left"> &nbsp; Check-in baggage</div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">15kg </div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">15kg </div>
                            <div style="width:20%;float:left;font-weight:bold;text-align:center">0kg </div>
                            <div style="clear:both"></div>
                            @endif
                            <div style="width:100%;text-align:left"> &nbsp; *Flight inclusions are subject to change with Airlines.</div>
                            <div style="clear:both"></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="clear:both"></div> <br>

        <table width="898" cellpadding="0" cellspacing="0" border="0" style="font-size:13px;margin:0px;padding:0px;color:rgb(60,60,60);border:0px;border-collapse:collapse;clear:both;empty-cells:show;font-family:Arial!important">

            <tbody style="margin:0px;padding:0px;border:0px">

                <tr style="margin:0px;padding:0px;border:0px">

                    <td style="margin-top:2px;margin-bottom:2px;margin-left:10px;padding:5px 10px 0px 18px;font-size:12px;border:1px solid rgb(0,0,0);font-family:Arial!important">
                        <p style="margin:0px;padding:0px"><strong style="margin:0px;padding:0px;font-size:12px!important">Terms &amp; Conditions</strong></p>
                        <ul style="margin:0px;padding:0px 0px 0px 18px">
                            <li style="margin:0px;padding:0px">
                                <strong style="margin:0px;padding:0px">This ticket is Non Refundable & Non Changeable </strong>
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
                                <strong style="margin:0px;padding:0px">Charged fare is totally agreed between "BUYER & SELLER", any issues related to fares thereafter will not be entertained.</strong>
                            </li>

                            <li style="margin:0px;padding:0px">
                                <strong style="margin:0px;padding:0px">We are not responsible for any Flight delay/Cancellation from airline's end. kindly contact the airline at least 24 hrs before to reconfirm your flight detail giving reference of Airline PNR Number. For any schedule change, flight cancelled & terminal related issues.</strong>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="yj6qo"></div>
        <div class="adL">



        </div>
    </div>
    <div class="adL">
    </div>
</div>

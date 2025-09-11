@extends('layouts.app')
@section('title','Bookings')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <style>
        .select2-results__option {
            padding: 0px !important;
        }
        tr.text-dark th{
            color: black !important;
        }
        .sticky{
            position: 'fixed';
            top: 4px;
            width: 75%;
            z-index: 1000000;
        }
    </style>
@endsection
@section('contents')
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">Booking Request Logs</h4>
                        <p class="card-description"> Booking Requests</p>
                    </div>
                    <div class="col-md-6 text-right">

                    </div>
                </div>
                <br>

                <div class="row mt-2 mb-1" id="sticky-bar">


                </div>

                <div class="row mt-2">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <table id="sortable-table-2" class="table table-bordered table-sm sortable-table">
                                <thead class="thead-dark">
                                <tr>
                                    <th width="2%">#</th>
                                    <th class="sortStyle">Agent<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">User<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">IP<i class="mdi mdi-chevron-down"></i></th>

                                    <th class="sortStyle">departure_date<i class="mdi mdi-chevron-down"></i></th>
                                    <th  id="AvailableQtyOrder" width="2%"  >departure_time</th>
                                    <th class="sortStyle" class="sortStyle">arrival_date<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">arrival_time<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">origin<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">destination<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">ticket_id</th>
                                    <th class="sortStyle">vendor_code<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">adults<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">childs<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">infants<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">adult_price<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">child_price<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">infant_price<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">total<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">airline_code<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">paxes<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">flight_number<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">created_at<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">status<i class="mdi mdi-chevron-down"></i></th>
                                    <th class="sortStyle">user_agent <i class="mdi mdi-chevron-down"></i></th>

                                </tr>
                                </thead>
                                <tbody class="text-center">
                                        @foreach($booking_request_logs  as $key => $value)
                                            <tr>
                                                <th>{{ 1 + $key }}</th>
                                                <td>{{ $value->company_name .' '. $value->code}} </td>
                                                <td>{{ $value->first_name}} {{ $value->last_name}} </td>
                                                <td>{{ $value->ip}} </td>

                                                <td>{{ $value->departure_date->format('d-m-Y')}} </td>
                                                <td>{{ $value->departure_time}} </td>
                                                <td>{{ $value->arrival_date->format('d-m-Y')}} </td>
                                                <td>{{ $value->arrival_time}} </td>

                                                <td>{{ $value->origin}} </td>
                                                <td>{{ $value->destination}} </td>
                                                <td>{{ $value->ticket_id}} </td>
                                                <td>{{ $value->vendor_code}} </td>
                                                <td>{{ $value->adults}} </td>
                                                <td>{{ $value->childs}} </td>
                                                <td>{{ $value->infants }} </td>
                                                <td>{{ $value->adult_price }} </td>
                                                <td>{{ $value->child_price }} </td>
                                                <td>{{ $value->infant_price }} </td>
                                                <td>{{ $value->total }} </td>
                                                 <td>{{ $value->airline_code }} </td>
                                                <td>{{ $value->paxes }} </td>

                                                <td>{{ $value->flight_number }} </td>
                                                 <td>@if($value->created_at)
                                                    {{ $value->created_at->format('d-m-Y h:i:s')}}
                                                    @endif
                                                </td>
                                                <td>@if($value->status == 1)
                                                    in progress
                                                    @elseif($value->status == 2)
                                                    fail
                                                    @elseif($value->status == 3)
                                                    success
                                                    @endif

                                                </td>

                                                <td>{{ $value->user_agent}} </td>
                                            </tr>
                                        @endforeach
                                </tbody>
                        </table>

                        <div class="mt-3">
                          {{ $booking_request_logs->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ViewTicketDetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel-2">Ticket Purchase History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-primary" role="alert" id="modalAlertMsg" style="display: none">

                    </div>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-dark">
                        <tr>
                            <th>Airline</th>
                            <th>PNR</th>
                            <th>Destination</th>
                            <th>Travel Date</th>
                            <th>Flight No</th>
                            <th>DEPT</th>
                            <th>ARV</th>
                            <th>Qty</th>
                            <th>Available</th>
                            <th>Sold</th>
                        </tr>
                        </thead>
                        <tbody id="tableBookDetailBody">


                        </tbody>
                    </table>
                    <br>
                    <table class="table table-sm table-bordered">
                        <thead class="bg-gradient-warning">
                        <tr class=" text-dark">
                            <th >Airline</th>
                            <th>PNR</th>
                            <th>Destination</th>
                            <th>Travel Date</th>
                            <th>Flight No</th>
                            <th>DEPT</th>
                            <th>ARV</th>
                            <th>Qty</th>
                            <th>PNR Status</th>
                            <th>Flight Status</th>
                        </tr>
                        </thead>
                        <tbody id="tableBookDetailAirlineBody1">


                        </tbody>
                    </table>
                    <hr>


                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Passenger Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Website Passenger Details</button>
                        </li>

                      </ul>
                      <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Bill No</th>
                                        <th>Type</th>
                                        <th>Name </th>
                                        <th>Agency</th>
                                        <th>Pax Price</th>
                                        <th>Remarks</th>
                                        <th>Agent Remarks</th>
                                        <th>Internal Remark</th>
                                        <th>Comments</th>
                                        <th>Booking Date & Time</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tableBody">


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div id="notificationDiv"></div>
                            <table class="table table-sm table-bordered">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>

                                        <th>Passenger Name (P)</th>
                                        <th>Passenger Name (W)</th>
                                        <th>Gender (W)</th>
                                        <th>Type (W)</th>
                                        <th>Additional Service (W)</th>

                                    </tr>
                                    </thead>
                                    <tbody id="tableBody1">


                                    </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showTicketModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Booking Ticket Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('partials.notification')
                    <a href="" id="idFrameAnchor" target="_blank">Print</a>
                    <iframe src="" id="iframeID" frameborder="0" width="100%"
                            height="600px"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{  asset('assets/js/jq.tablesort.js') }}"></script>

    <script>
        $(document).ready(function() {
            if ($('#sortable-table-2').length) {
                $('#sortable-table-2').tablesort();
            }
            $(window).scroll(function(e){
                var $el = $('#sticky-bar');
                var isPositionFixed = ($el.css('position') == 'fixed');
                if ($(this).scrollTop() > 200 && !isPositionFixed){
                    $el.addClass('sticky');
                }
                if ($(this).scrollTop() < 200 && isPositionFixed){
                    $el.removeClass('sticky');
                }
            });

            $('.select2').select2({});
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#type-select').change(function(e) {
                $('#status-select').removeAttr('required');
            });

            $('#status-select').change(function(e) {
                $('#type-select').removeAttr('required');
                $('#price').removeAttr('required');
            });

            checkQueryParam();
            $('.ViewTicketDetailsBtn').click(function(e) {
                $('#tableBody').empty();
                $('#tableBody1').empty();
                $('#tableBookDetailBody').empty();
                $('#tableBookDetailAirlineBody1').empty();
                let id = $(this).val();

                $.ajax({
                    url: '/flight-tickets/ajax/passenger-details',
                    data: {
                        id: id
                    },
                    type: 'GET',
                    success: function(resp) {
                        console.log(resp);
                        if (resp.success) {

                            let passengers_name = [

                            ];

                            // show the success message;
                            $('#modalAlertMsg').show();
                            $('#modalAlertMsg').html(resp.message);
                            let i = 1;
                            $.each(resp.data, function(index, value) {
                                let type = '';
                                let styleDetail = '';

                                if(value.type == 1){
                                    type = 'Adult'
                                }else if(value.type == 2){
                                    type = 'Child'
                                }else{
                                    type = 'Infant'
                                    styleDetail = "color:red;font-weight:800"
                                }
                                if(value.is_refund != 1){
                                    let temp_name = value.title +' '+value.first_name +' '+ value.last_name;
                                    let temp_name_2 = value.first_name +' '+ value.last_name;
                                    passengers_name.push({
                                        name: temp_name,
                                        type:type,
                                        temp_name: temp_name_2
                                    })
                                }

                                $('#tableBody').append(`
                                <tr class=${(value.is_refund == 1) ? "bg-red " : (value.is_refund == 2) ? "bg-info" : ''} >
                                    <td>${i++}</td>
                                    <td>${value.bill_no}</td>
                                    <td style="${styleDetail}">
                                        ${type}</td>
                                    <td>${value.title} ${value.first_name} ${value.last_name}</td>
                                    <td>${value.agent} <br>  ${value.agent_phone_number}</td>
                                    <td>${value.pax_price}</td>
                                    <td>${value.intimation}</td>
                                    <td>${value.agent_remarks}</td>
                                    <td>${value.internal_remarks}</td>
                                    <td>${value.booking_date}</td>
                                </tr>
                            `);
                            });
                            $('#tableBookDetailBody').append(`
                                <tr>
                                    <td>${resp.book_detail.airline}</td>
                                    <td>${resp.book_detail.pnr}</td>
                                    <td>${resp.book_detail.destination}</td>
                                    <td>${resp.book_detail.travel_date}</td>
                                    <td>${resp.book_detail.flight_no}</td>
                                    <td>${resp.book_detail.departure_time}</td>
                                    <td>${resp.book_detail.arrival_time}</td>
                                    <td>${resp.book_detail.qty}</td>
                                    <td>${resp.book_detail.available}</td>
                                    <td>${resp.book_detail.sold}</td>


                                </tr>
                            `);

                            if(resp.book_ticket_details1)
                             {
                                let websitePassengerDetails = resp.book_ticket_details1.passenger_details;
                                let details = [];

                                 $.each(passengers_name, function(index, value) {
                                     details[index] = [];
                                     $.each(websitePassengerDetails, function(index2, value2)
                                     {
                                         if(value2.passenger_name.toLowerCase().replace(/[^a-zA-Z ]/g, "").includes(value.temp_name.toLowerCase().replace(/[^a-zA-Z ]/g, "") )) {
                                             details[index]['pax'] = value.name + "(" + value.type + ")";
                                             details[index]['spicejet_pax'] = value2.passenger_name;
                                             details[index]['gender'] = '';
                                             details[index]['type'] = '';
                                             details[index]['service'] = value2.additional_services_purchased != null  ? value2.additional_services_purchased : '';
                                             value2.matched = true;
                                             return false;
                                         }else {
                                             details[index]['pax'] = value.name + "(" + value.type + ")";
                                             details[index]['spicejet_pax'] = '';
                                             details[index]['gender'] = '';
                                             details[index]['type'] = '';
                                             details[index]['service'] = '';
                                         }
                                     });
                                 });

                            $('#tableBookDetailAirlineBody1').append(`
                                <tr>
                                    <td>${resp.book_ticket_details1.airline}</td>
                                    <td>${resp.book_ticket_details1.pnr}</td>
                                    <td>${resp.book_ticket_details1.source} -  ${resp.book_ticket_details1.destination}</td>
                                    <td>${resp.book_ticket_details1.travel_date}</td>
                                    <td>${resp.book_ticket_details1.flight_no}</td>
                                    <td>${resp.book_ticket_details1.departure_time}</td>
                                    <td>${resp.book_ticket_details1.arrival_time}</td>
                                    <td>${resp.book_ticket_details1.total_pax_count}</td>
                                    <td><span class="badge badge-primary text-uppercase">${resp.book_ticket_details1.pnr_status}</span></td>
                                    <td><span class="badge badge-info  text-uppercase">${resp.book_ticket_details1.flight_status}</span></td>
                                </tr>
                            `);


                            let j = 1;

                            $.each(details, function(index, value) {

                                let type = '';
                                let styleDetail = '';

                                $('#tableBody1').append(`
                                <tr>
                                    <td>${j++}</td>

                                    <td style="${styleDetail}" >${value['pax']}</td>
                                    <td>${value['spicejet_pax']}</td>
                                    <td>${value['gender']}</td>
                                    <td >
                                        ${value['type']}</td>
                                    <td>${(value['service'] != null )?value['service'] : ''} </td>

                                </tr>
                            `);

                            });

                             let notmatchepassengerdetails = [];
                             $.each(websitePassengerDetails,function(index,value) {
                                 console.log(value.matched)
                                 let data = '';
                                 if(value.matched == undefined){
                                     notmatchepassengerdetails.push(value);
                                     // data += `<li>${value.passenger_name}</li>`
                                 }
                             });
                             if(notmatchepassengerdetails.length > 0 )
                             {
                                 let data = '';
                                 $.each(notmatchepassengerdetails,function(index,value) {
                                     data += `<li>${value.passenger_name}</li>`
                                 });

                                 $('#notificationDiv').append(`
                                     <div class="alert alert-warning" role="alert" id="">
                                        <h4>Below name are not match with our records</h4>
                                        <ul>${data} </ul>
                                    </div>`);
                             }




                            }



                        } else {
                            $('#modalAlertMsg').show();
                            $('#modalAlertMsg').html(resp.message);
                        }

                    }
                })
            })


            $('.select2').select2();

            $(".datepicker").datepicker({ todayHighlight: true,autoclose: true,format:'dd-mm-yyyy'});
            $('.timepicker').datetimepicker({
                format: 'HH:mm',
            });
            $('#travel_date_from').change(function() {
                let from = $('#travel_date_from').val();
                $('#travel_date_to').val(from);
            })

            $('.sellPriceInput').dblclick(function(){
                var data = $(this).attr('data');
                $(this).prop('readonly',false)
            })


            $(document).on('click','.sellPriceBtn.edit', function() {
                var data = $(this).parent().find('.sellPriceInput').attr('data');
                $(this).parent().find('.sellPriceInput').prop('readonly',false);
                $(this).parent().find('.sellPriceInput').focus();
                $(this).removeClass('edit');
                $(this).addClass('save');
                $(this).text('Save');
            });


            $('.sellPriceInput').change(function(e){
                let data = $(this).attr('data');
                let value = $(this).val();
                if(value < 1000) {
                    alert('Can not update with 3 digit figure');
                    return false;
                }
                let resp = confirm("Are you sure want to update the sell price");
                if(resp){
                    $.ajax({
                        url: '/flight-tickets/purchase/sale-price-update',
                        type: 'POST',
                        data:{
                            id: data,
                            amount:value
                        },
                        success: () => {
                            $(this).prop('readonly',true);
                        }
                    })

                }else{
                    $(this).prop('readonly',true);
                }
            })


            function checkQueryParam() {
                let urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('show-ticket')) {
                    let showTicket = urlParams.get('show-ticket');
                    console.log(showTicket);
                    $("#iframeID").attr("src", "/flight-tickets/sales/print/" + showTicket);
                    $("#idFrameAnchor").attr("href", "/flight-ticket/sales/print/" + showTicket);
                    $('#showTicketModal').modal('show');
                }
            }
            $('.btnDelete').click((e) => {
                let resp = confirm("Are you sure you want to delete the Sale Ticket ?")
                if (!resp) {
                    e.preventDefault();
                }
            })
            let destination_order = $('#destination_order').val();
            $('#destinationOrder').click(function(e){
                if(destination_order == ''){
                    destination_order = 'asc'
                }else{
                    destination_order = (destination_order == 'desc') ? 'asc' : 'desc'
                }

                $('#destination_order').val(destination_order);
                $('#available_order').val("");

                $('#searchForm').submit();
            })
            let available_order = $('#available_order').val();

            $('#AvailableQtyOrder').click(function(e){

                if(available_order == ''){
                    available_order = 'asc'
                }else{
                    available_order = (available_order == 'desc') ? 'asc' : 'desc'
                }
                $('#destination_order').val("");
                $('#available_order').val(available_order);
                $('#searchForm').submit();
            })

            $("input[name='checked']").click(function(e) {
                let id = $.trim(e.target.value);
                $('#viewAnchor').attr('value', id);
                $('#bookAnchor').attr('href', '/flight-tickets/bookings/create?book_ticket_id=' + id);
                $('#blockAnchor').attr('href', '/flight-tickets/blocks/create?purchase_id=' + id);
                $('#modifyAnchor').attr('href', '/flight-tickets/purchase/' + id + '/edit');
                $('#statusAnchor').attr('href', '/flight-tickets/purchase/status/' + id);
                $('#ticketPurchaseShowAnchor').attr('href', '/flight-tickets/purchase/' + id);
                $('#NameListShowAnchor').attr('href', '/flight-tickets/pnr-name-list/' + id);
                $('#PnrHistoryShowAnchor').attr('href', '/accounts/pnr-history/show/' + id);
                $('#pnr_fetch').attr('href', "/flight-tickets/pnr-status/" + id);
            });
            $(document).on('dblclick', '.isOnlineButton', function(e) {

                let vm = $(this);
                let value = $(this).val();
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/online-status',
                    type: "POST",
                    data:{
                        status: 1
                    },
                    success:function(resp){
                        if(resp.success){
                            vm.addClass('badge-info');
                            vm.removeClass('badge-success');
                            vm.removeClass('isOnlineButton');
                            vm.addClass('isOfflineButton');
                            vm.html('Offline');
                            console.log(value);
                        }

                    }
                })

                //make it offline
            })
            $(document).on('dblclick', '.isOfflineButton', function(e) {

                let value = $(this).val();
                let vm = $(this);
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/online-status',
                    type: "POST",
                    data:{
                        status: 2
                    },
                    success:function(resp){
                        console.log(resp)
                        if(resp.success){
                            vm.addClass('badge-success');
                            vm.removeClass('badge-info');
                            vm.addClass('isOnlineButton');
                            vm.removeClass('isOfflineButton');
                            vm.html('Online');
                        }


                    }
                })

            })

            $(document).on('dblclick', '.isRefundableButton', function(e) {
                let value = $(this).val();
                let vm = $(this);
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/refundable-status',
                    type: "POST",
                    data:{
                        status: 0
                    },
                    success:function(resp){
                        if(resp.success){
                            vm.addClass('badge-grey');
                            vm.removeClass('badge-success');
                            vm.addClass('isNonRefundableButton');
                            vm.removeClass('isRefundableButton');
                            vm.html('Non Refundable');
                        }
                    }
                })

            })

            $(document).on('dblclick', '.isNonRefundableButton', function(e) {
                let value = $(this).val();
                let vm = $(this);
                $.ajax({
                    url: '/flight-tickets/purchase/'+value+'/refundable-status',
                    type: "POST",
                    data:{
                        status: 1
                    },
                    success:function(resp){
                        if(resp.success){
                            vm.addClass('badge-success');
                            vm.removeClass('badge-grey');
                            vm.addClass('isRefundableButton');
                            vm.removeClass('isNonRefundableButton');
                            vm.html('Refundable');
                        }
                    }
                })

            })

            function myFunction(purchase_entry_id,status){

                // $.ajax({
                //     url: '/purchase-entry/'+purchase_entry_id+'/online-status',
                //     type: "POST",
                //     data:{
                //         status: status
                //     },
                //     success:function(resp){
                //         console.log(resp)
                //     })
                // })
            }



        });
    </script>

    <script>
        $(document).on("click", ".tooltip-icon", function() {
            $(this).tooltip(
                {
                    items: ".tooltip-icon",
                    open: function( event, ui ) {
                        var id = this.id;
                        var flight_id = $(this).attr('data-id');

                        $.ajax({
                            url:'/flight-tickets/ajax/last-changed-price-details',
                            type:'POST',
                            data:{
                                flight_id : flight_id
                            },
                            success: function(response){
                                // Setting content option
                                $("#"+id).tooltip('option','content', response);
                            }
                        });
                    },
                    close: function( event, ui ) {
                        var me = this;
                        ui.tooltip.hover(
                            function () {
                                $(this).stop(true).fadeTo(400, 1);
                            },
                            function () {
                                $(this).fadeOut("400", function(){
                                    $(this).remove();
                                });
                            }
                        );
                        ui.tooltip.on("remove", function(){
                            $(me).tooltip("destroy");
                        });
                    },
                }
            );
            $(this).tooltip("open");
        });
        $(document).on("click", ".namelist-info", function() {
            $(this).tooltip(
                {
                    items: ".namelist-info",
                    open: function( event, ui ) {
                        var id = this.id;
                        var flight_id = $(this).attr('data-id');

                        $.ajax({
                            url:'/flight-tickets/ajax/last-changed-namelist-details',
                            type:'POST',
                            data:{
                                flight_id : flight_id
                            },
                            success: function(response){
                                // Setting content option
                                $("#"+id).tooltip('option','content', response);
                            }
                        });
                    },
                    close: function( event, ui ) {
                        var me = this;
                        ui.tooltip.hover(
                            function () {
                                $(this).stop(true).fadeTo(400, 1);
                            },
                            function () {
                                $(this).fadeOut("400", function(){
                                    $(this).remove();
                                });
                            }
                        );
                        ui.tooltip.on("remove", function(){
                            $(me).tooltip("destroy");
                        });
                    },
                }
            );
            $(this).tooltip("open");
        });
    </script>
@endsection



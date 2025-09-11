@extends('layouts.app')
@section('title','Api Vendors')
@section('contents')

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Agent</h4>
            <div class="row">
                <div class="col-md-6">
                    API vendors booking logs
                </div>

            </div>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">V1 API</button>
                    <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">V2 API</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm ">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Vendor Request </th>
                                    <th>Vendor Response </th>
                                   
                                    <th>Agency Code</th>
                                    <th>departure_date</th>
                                    <th>departure_time</th>
                                    <th>arrival_date</th>
                                    <th>arrival_time</th>
                                    <th>origin</th>
                                    <th>destination</th>
                                    <th>adults</th>
                                    <th>childs</th>
                                    <th>infants</th>
                                    <th>adult_price</th>
                                    <th>child_price</th>
                                    <th>infant_price</th>
                                    <th>total</th>
                                    <th>airline_code</th>
                                    <th>flight_number</th>
                                    <th>created_at</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data_v1 as $key => $val)
                                <tr>
                                    <td>{{ 1 + $key }} </td>
                                    <td class="">
                                        <a href="/api-logs/vendors/{{ $val['id']}}?data={{ $val['vendor_request']}} " target="__blank">
                                             View
                                        </a>
                                    </td>


                                    <td class="@if($val['vendor_response_type'] == 'SUCCESS') bg-green @else bg-red @endif">
                                        <a href="/api-logs/vendors/{{ $val['id']}}?data={{ $val['vendor_response']}} " target="__blank">
                                            {{ $val['vendor_response_type'] }}
                                        </a>
                                    </td>
                                  


                                    <td>{{ $val['agent_id'] }} </td>

                                    <td>{{ $val['departure_date'] }}</td>
                                    <td>{{ $val['departure_time'] }}</td>
                                    <td>{{ $val['arrival_date'] }}</td>
                                    <td>{{ $val['arrival_time'] }}</td>
                                    <td>{{ $val['origin'] }}</td>
                                    <td>{{ $val['destination'] }}</td>
                                    <td>{{ $val['childs'] }}</td>
                                    <td>{{ $val['infants'] }}</td>
                                    <td>{{ $val['adult_price'] }}</td>
                                    <td>{{ $val['child_price'] }}</td>
                                    <td>{{ $val['infant_price'] }}</td>
                                    <td>{{ $val['total'] }}</td>
                                    <td>{{ $val['paxes'] }}</td>
                                    <td>{{ $val['airline_code'] }}</td>
                                    <td>{{ $val['flight_number'] }}</td>
                                    <td>{{ $val['created_at'] }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="10">
                                        {{ $v1APILogs->links() }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm ">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Vendor Request </th>
                                    <th>Vendor Response </th>
                                    <th>GFS Request</th>
                                    <th>GFS Response </th>
                                    <th>Agency Code</th>
                                    <th>departure_date</th>
                                    <th>departure_time</th>
                                    <th>arrival_date</th>
                                    <th>arrival_time</th>
                                    <th>origin</th>
                                    <th>destination</th>
                                    <th>adults</th>
                                    <th>childs</th>
                                    <th>infants</th>
                                    <th>adult_price</th>
                                    <th>child_price</th>
                                    <th>infant_price</th>
                                    <th>total</th>
                                    <th>airline_code</th>
                                    <th>flight_number</th>
                                    <th>created_at</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $val)
                                <tr>
                                    <td>{{ 1 + $key }} </td>
                                    
                                    <td class="">
                                        <a href="/api-logs/vendors/{{ $val['id']}}?data={{ $val['vendor_request']}} " target="__blank">
                                           View
                                        </a>
                                    </td>
                                    <td class="@if($val['vendor_response_type'] == 'SUCCESS') bg-green @else bg-red @endif">
                                        <a href="/api-logs/vendors/{{ $val['id']}}?data={{ $val['vendor_response']}} " target="__blank">
                                            {{ $val['vendor_response_type'] }}
                                        </a>
                                    </td>
                                    <td class="">
                                        <a href="/api-logs/vendors/{{ $val['id']}}?data={{ $val['gfs_request']}} " target="__blank">
                                           View
                                        </a>
                                    </td>
                                    <td class="@if($val['gfs_response_type'] == 'SUCCESS') bg-green @else bg-red @endif">
                                        <a href="/api-logs/vendors/{{ $val['id']}}?data={{ $val['gfs_response']}} " target="__blank">
                                            {{ $val['gfs_response_type'] }}
                                        </a>
                                    </td>


                                    <td>{{ $val['agent_id'] }} </td>

                                    <td>{{ $val['departure_date'] }}</td>
                                    <td>{{ $val['departure_time'] }}</td>
                                    <td>{{ $val['arrival_date'] }}</td>
                                    <td>{{ $val['arrival_time'] }}</td>
                                    <td>{{ $val['origin'] }}</td>
                                    <td>{{ $val['destination'] }}</td>
                                    <td>{{ $val['childs'] }}</td>
                                    <td>{{ $val['infants'] }}</td>
                                    <td>{{ $val['adult_price'] }}</td>
                                    <td>{{ $val['child_price'] }}</td>
                                    <td>{{ $val['infant_price'] }}</td>
                                    <td>{{ $val['total'] }}</td>
                                    <td>{{ $val['paxes'] }}</td>
                                    <td>{{ $val['airline_code'] }}</td>
                                    <td>{{ $val['flight_number'] }}</td>
                                    <td>{{ $val['created_at'] }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="10">
                                        {{ $v2APILogs->links() }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endsection
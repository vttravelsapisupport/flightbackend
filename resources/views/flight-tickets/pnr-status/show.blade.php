@extends('layouts.app')
@section('title','PNR Status')
@section('contents')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="card-title text-uppercase">PNR Status</h4>
                        <p class="card-description">Passenger Name Record Status in the Appication.</p>
                    </div>
                    <div class="col-md-6 text-right">

                    </div>
                </div>
                <div class="table-responsive">
                    <form action="{{ route('pnr-status.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" name="purchase_id" value="{{ $details['purchase_id'] }}">
                                    <label for="">PNR</label>
                                    <input type="text" class="form-control" name="pnr" value="{{ $details['pnr']  }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Travel Date</label>
                                    <input type="text" class="form-control" name="travel_date" value="{{ $details['travel_date']  }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Flight No</label>
                                    <input type="text" class="form-control" name="flight_no" value="{{ $details['flight_no']  }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Fare Type</label>
                                    <input type="text" class="form-control" name="fare_type" value="{{ $details['fare_type']  }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">PNR Status</label>
                                    <input type="text" class="form-control" name="pnr_status" value="{{ $details['pnr_status']  }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">From Terminal</label>
                                    <input type="text" class="form-control" name="from_terminal" value="{{ $details['from_terminal']  }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">To Terminal</label>
                                    <input type="text" class="form-control"  name="to_terminal" value="{{ $details['to_terminal']  }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Departure Time</label>
                                    <input type="text" class="form-control" name="departure_time" value="{{ $details['departure_time']  }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Arrival Time</label>
                                    <input type="text" class="form-control" name="arrival_time" value="{{ $details['arrival_time']  }}" readonly >
                                </div>
                                <div class="form-group">
                                    <label for="">Current Flight Status</label>
                                    <input type="text" class="form-control" name="current_flight_status" value="{{ $details['current_flight_status']  }}" readonly>
                                </div>
                            </div>
                        </div>

                    <table class="table table-sm table-hover">
                        <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Type</th>
                            <th>Gender</th>
                            <th>Passport No</th>
                            <th>Additional Service Purchased </th>
                        </tr>
                        </thead>
                        <tbody class="text-left">
                        @foreach($passengers as $k => $i)
                            <tr>
                                <td>{{ 1+$k }}</td>
                                <td><input type="text" class="form-control" name="passenger_name[]" value="{{ $i['passenger_name']}}" readonly> </td>
                                <td><input type="text" class="form-control" name="passenger_type[]" value="{{ $i['passenger_type']}}" readonly> </td>
                                <td> <input type="text" class="form-control" name="passenger_gender[]" value="{{ $i['passenger_gender']}}" readonly>  </td>
                                <td><input type="text" class="form-control" name="passport_no[]" value="{{ $i['passport_no']}}" readonly>  </td>
                                <td><input type="text" class="form-control" name="additional_services_purchased[]" value="{{ $i['additional_services_purchased']}}" readonly>  </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                    <div class="mt-3">
                        <button class="btn btn-primary">Save</button>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

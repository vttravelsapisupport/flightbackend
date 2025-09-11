@extends('layouts.app')
@section('title','Airlines')
@section('contents')
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase"> Edit Airline</h4>
            <p class="card-description">
                Edit the Airline Details of <strong>{{ $details->name }}</strong>
            </p>
            <form class="forms-sample" method="POST" action="{{ route('airlines.update',$details->id) }}">
                @csrf
                @method('put')
                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" placeholder="Enter the Airline name" name="name" value="{{ $details->name }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Code</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="code" placeholder="Enter the Code" name="code" value="{{ $details->code }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Helpline No</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="helpline_no" placeholder="Enter the Helpline No" name="helpline_no" value="{{ $details->helpline_no }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-sm-3 col-form-label">Infant Charge</label>
                    <div class="col-sm-9">
                        <input type="hidden" name="infant_charge_modify" id="infant_charge_modify" value="">
                        <input type="text" class="form-control" id="infant_charge" placeholder="Enter the Infant Charges" name="infant_charge" value="{{ $details->infant_charge }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="description" class="col-sm-3 col-form-label">Airline Logo</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="description" placeholder="Enter the Infant Charges" name="description" value="{{ $details->description }}">
                       
                        <img src="{{ $details->description }}" alt="" class="img-fluid" class="mt-2" width="100px">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <select name="status" id="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="1" @if($details->status == 1) selected @endif>Active</option>
                            <option value="0" @if($details->status == 0) selected @endif>Inactive</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Update</button>
            </form>

            <form class="forms-sample mt-5" method="POST" action="{{ route('airlines.cancellation',$details->id) }}">
                @csrf
                <div class="form-group row">
                    <h4 class="card-title text-uppercase">Cancellation Slots</h4>
                </div>
                @foreach($slots as $key => $slot)
                <div class="form-group row">
                    <label for="status" class="col-sm-4 col-form-label">{{$slot->name}}</label>
                    <div class="col-sm-4">
                        <label for="status" class="col-sm-4 col-form-label">Domestic</label>
                        <input type="text" class="form-control" name="cancellation_slot_amount[]" value="{{isset($cancellation[$key]) ? $cancellation[$key]->amount : 0}}" required>
                    </div>
                    <div class="col-sm-4">
                        <label for="status" class="col-sm-4 col-form-label">International</label>
                        <input type="text" class="form-control" name="cancellation_slot_int_amount[]" value="{{isset($cancellation[$key]) ? $cancellation[$key]->int_amount : 0}}" required>
                    </div>
                </div>
                @endforeach
                <button type="submit" class="btn btn-primary mr-2">Update</button>
            </form>


            <form class="forms-sample mt-5" method="POST" action="{{ route('airlines.baggage-info', $details->id) }}">
                @csrf
                <div class="form-group row">
                    <h4 class="card-title text-uppercase">Baggage Information</h4>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="status" class="col-sm-4 col-form-label">Cabin  Baggage</label>
                    </div>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>Domestic</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Adult</label>
                                <input type="text" class="form-control" name="cabin_baggage_adult_domestic" value="{{isset($baggageInfo[0]) ? $baggageInfo[0]->adult : null}}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Child</label>
                                <input type="text" class="form-control" name="cabin_baggage_child_domestic" value="{{isset($baggageInfo[0]) ? $baggageInfo[0]->child : null}}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Infant</label>
                                <input type="text" class="form-control" name="cabin_baggage_infant_domestic" value="{{isset($baggageInfo[0]) ? $baggageInfo[0]->infant : null}}" required>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <h5>International</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Adult</label>
                                <input type="text" class="form-control" name="cabin_baggage_adult_international" value="{{isset($baggageInfo[1]) ? $baggageInfo[1]->adult : null}}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Child</label>
                                <input type="text" class="form-control" name="cabin_baggage_child_international" value="{{isset($baggageInfo[1]) ? $baggageInfo[1]->child : null}}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Infant</label>
                                <input type="text" class="form-control" name="cabin_baggage_infant_international" value="{{isset($baggageInfo[1]) ? $baggageInfo[1]->infant : null}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="status" class="col-sm-4 col-form-label">Check-In  Baggage</label>
                    </div>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>Domestic</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Adult</label>
                                <input type="text" class="form-control" name="checkin_baggage_adult_domestic" value="{{isset($baggageInfo[2]) ? $baggageInfo[2]->adult : null}}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Child</label>
                                <input type="text" class="form-control" name="checkin_baggage_child_domestic" value="{{isset($baggageInfo[2]) ? $baggageInfo[2]->child : null}}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Infant</label>
                                <input type="text" class="form-control" name="checkin_baggage_infant_domestic" value="{{isset($baggageInfo[2]) ? $baggageInfo[2]->infant : null}}" required>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-12">
                                <h5>International</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Adult</label>
                                <input type="text" class="form-control" name="checkin_baggage_adult_international" value="{{isset($baggageInfo[3]) ? $baggageInfo[3]->adult : null}}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Child</label>
                                <input type="text" class="form-control" name="checkin_baggage_child_international" value="{{isset($baggageInfo[3]) ? $baggageInfo[3]->child : null}}" required>
                            </div>
                            <div class="col-sm-4">
                                <label for="status" class="col-sm-4 col-form-label">Infant</label>
                                <input type="text" class="form-control" name="checkin_baggage_infant_international" value="{{isset($baggageInfo[3]) ? $baggageInfo[3]->infant : null}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-check">
                            <input class="" type="checkbox" id="check1" name="update_ticket_baggage_info" value="1">
                            <label class="">Update all the purchase entries with the following baggage info</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
<script src="{{ asset('assets/js/tablesorter.js') }}"></script>
<script>
    $('#infant_charge').change(function(){
        let resp  = confirm("Are you sure want to modify existing purchase entry ?");

        if(resp){
            $('#infant_charge_modify').val(1);
        }
    })
</script>
@endsection
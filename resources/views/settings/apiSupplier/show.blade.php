@extends('layouts.app')
@section('title','API Vendors')
@section('contents')
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-uppercase">Vendor Details</h4>
            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        placeholder="Enter the Vendor name"
                        name="name"
                        value="{{$val->name}}"
                        readonly
                    />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">
                        Vendor Bank Details
                    </h4>
                </div>
                <div class="col-md-6 text-right">
                    <button
                        class="btn btn-sm btn-primary"
                        data-toggle="modal"
                        data-target="#exampleModal"
                    >
                        Register Bank Details
                    </button>
                </div>
            </div>

            <table class="table table">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Account Holder Name</th>
                        <th>Bank Name</th>
                        <th>IFSC Code</th>
                        <th>Account Number</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Attachment</th>
                        <th>Created At</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($owner_bank_details as $i => $d)
                    <tr @if($d->status == 2) class="dg-danger" @endif >
                        <td> {{  1 + $i }}</td>
                        <td> {{ $d->account_holder_name }} </td>
                        <td> {{ $d->bank_name }} </td>
                        <td> {{ $d->ifsc_code }} </td>
                        <td> {{ $d->bank_account_no }} </td>
                        <td> {{ $d->branch }} </td>
                        <td> {{ $d->isVerified }} </td>
                        <td> <a href="{{ $d->attachment }}" target="_blank">Download</a>  </td>
                        <td> {{ $d->created_at }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div
                class="modal fade"
                id="exampleModal"
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                Register New Bank Details
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="newAccountRegistration">
                        <div class="modal-body">
                                <input type="hidden" name="supplier_id" value="{{ $val->id }}">
                                <div class="form-group">
                                    <label for="account_holder_name"
                                        >account_holder_name
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="account_holder_name"
                                        name="account_holder_name"
                                        aria-describedby="emailHelp"
                                        placeholder="Enter acoount holder name"
                                    />
                                </div>



                                <div class="form-group">
                                    <label for="account_holder_name"
                                        > Bank Name
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="bank_name"
                                        name="bank_name"
                                        placeholder="SBI / ICICI / HDFC"
                                    />
                                </div>

                                <div class="form-group">
                                    <label for="account_holder_name"
                                        >branch
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="branch"
                                        name="branch"
                                        placeholder="Enter branch name"
                                    />
                                </div>

                                <div class="form-group">
                                    <label for="account_holder_name"
                                        >bank account no
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="bank_account_no"
                                        name="bank_account_no"
                                        placeholder="Enter account no"
                                    />
                                </div>
                                <div class="form-group ">
                                    <label for="ifsc_code">ifsc code </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="ifsc_code"
                                        name="ifsc_code"
                                        placeholder="Enter ifsc_code"
                                    />
                                </div>

                                <div class="form-group">
                                    <input type="hidden" id="attachment_url" name="attachment_url">
                                    <label for="attachment">Attachment</label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        id="attachment"
                                        name="attachment"
                                    />

                                </div>

                                <div class="form-group">

                                    <input
                                        type="checkbox"
                                        id="account_verified"
                                        name="account_verified"
                                        value="1"
                                    />
                                    <label
                                    for="account_verified"
                                    >Account Verified</label
                                >

                                </div>



                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Save changes
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @section('js')
<script src="{{ asset('assets/js/jq.tablesort.js') }}"></script>
<script src="{{ asset('assets/js/tablesorter.js') }}"></script>
<script>
    $('#newAccountRegistration').submit(function(e) {
        let data = $(this).serialize();
        console.log(data);
        $.ajax({
            url :'/supplier-bank-details',
            type: 'POST',
            data: data,
            success: function(resp) {
                console.log(resp);
                if(resp.success){
                    alert(resp.message);
                    location.reload();
                }else{
                    alert(resp.message);
                }
            }
        })
        e.preventDefault();
    })
    //
    $('#attachment').change(function() {
        var fd = new FormData();
        console.log($('#attachment')[0].files);
        fd.append( 'file', $('#attachment')[0].files[0] );
        $.ajax({
            url: '/upload-images',
            data: fd,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(data){
                console.log(data);
                $('#attachment_url').val(data);
            }
            });
    })
</script>
@endsection

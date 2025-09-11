@extends('layouts.app')
@section('title','Manage Refunds')
@section('css')
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }
</style>
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Manage Refunds (Air Ticket Refunds)</h4>
                    <p class="card-description">Refund history for the given bill number (Soft deleted items are excluded).</p>
                </div>
            </div>
            <form class="forms-sample row" method="GET" action="">
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm form-control form-control-sm-sm" name="bill_no" placeholder="Bill No" value="{{ request()->query('bill_no') }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm">Search</button>
                </div>
            </form>
            <hr>
            <div>
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Agent Code</th>
                                <th>Pax Cost</th>
                                <th>Total Refund</th>
                                <th>Owner ID</th>
                                <th>Remarks</th>
                                <th>Creted AT</th>
                                <th>Updated AT</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            @if(!empty($data))
                            @foreach($data as $key => $val)
                            <tr>
                                <td>{{ 1 + $key }}</td>
                                <td>{{ $val->AgentCode }}</td>
                                <td>{{ $val->PaxCost }}</td>
                                <td>{{ $val->TotalRefund }}</td>
                                <td>{{ $val->OwnerID }}</td>
                                <td>{{ $val->Remarks }}</td>
                                <td>{{ $val->CreatedAT }}</td>
                                <td>{{ $val->UpdatedAT }}</td>
                                <td>
                                    <button data-ticketID="{{ $val->TicketID }}" data-AgentID="{{ $val->AgentID }}" data-AgentCode="{{ $val->AgentCode }}" data-PaxCost="{{ $val->PaxCost }}" data-TotalRefund="{{ $val->TotalRefund }}" data-OwnerID="{{ $val->OwnerID }}" data-AccountTransactionID="{{ $val->AccountTransactionID }}" data-CreatedAT="{{ strtotime($val->CreatedAT) }}" data-UpdatedAT="{{ strtotime($val->UpdatedAT) }}" class="btn btn-sm btn-info add-remarks btn-delete-refund">View</button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td style="text-align: left; padding: 10px 15px 10px;" colspan="10">Nothing to show</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('.btnDelete').click((e) => {
            let resp = confirm("Are you sure you want to delete the PurchaseEntry Entry Ticket ?")
            if (!resp) {
                e.preventDefault();
            }
        })
        $('#submitButton').click((e) => {
            e.preventDefault();
            let promptResp = confirm("Are you sure want to update");
            if (promptResp)
                $('#formsubmit').submit();
        })
        $('.btn-delete-refund').click(function(e) {
            e.preventDefault();
            let id         = $(this).data('ticketid');  
            let created_at = $(this).data('createdat');        
            
            window.location.href = "/maintanance/manage-refund-details/" + id + "/" + created_at;

            /*let resp       = confirm("Are you sure you want to delete the Refund Entry?");

            if (resp) {
                window.location.href = "/maintanance/manage-refund-details/" + id + "/" + created_at;
            }*/
        });
    });
</script>
@endsection
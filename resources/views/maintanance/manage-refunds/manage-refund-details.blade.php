@extends('layouts.app')
@section('title','Refund Details')
@section('css')
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 17px !important;
    }

    .alert-container {
        position: fixed;
        bottom: 5px;
        left: 2%;
        width: 50%;
        margin: 0 25% 0 25%;
    }

    .alert {
        text-align: center;
        padding: 17px 0 20px 0;
        height: 54px;
        font-size: 20px;
        font-size: 0.875rem;
        color: white;
        background-color: #66a566;
    }
</style>
@endsection
@section('contents')
<div class="col-12 grid-margin">
    <div class="card">
        <div class="card-body">
            <div>
                {{-- Agent Details --}}
                <div class="agent-details">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <h4 class="card-title text-uppercase">Booking Agent Details</h4>
                        <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Agent Code</th>
                                    <th>Company Name</th>
                                    <th>Contact Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State ID</th>
                                    <th>Phone</th>
                                    <th>Whats App</th>
                                    <th>Gst Number</th>
                                    <th>Opening Balance</th>
                                    <th>Credit Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                                @if(!empty($data['AgentDetails']))
                                @foreach($data['AgentDetails'] as $key => $val)
                                <tr>
                                    <td>{{ $val->ID }}</td>
                                    <td>{{ $val->AgentCode }}</td>
                                    <td>{{ $val->CompanyName }}</td>
                                    <td>{{ $val->StateID }}</td>
                                    <td>{{ $val->Address }}</td>
                                    <td>{{ $val->City }}</td>
                                    <td>{{ $val->City }}</td>
                                    <td>{{ $val->Phone }}</td>
                                    <td>{{ $val->WhatsApp }}</td>
                                    <td>{{ $val->GstNumber }}</td>
                                    <td>{{ $val->OpeningBalance }}</td>
                                    <td>{{ $val->CreditBalance }}</td>
                                    <td>{{ $val->Status }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td style="text-align: left; padding: 10px 15px 10px;" colspan="17">Nothing to show</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <br />
                {{-- Purchase Entries --}}
                <div class="purchase-entries">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <h4 class="card-title text-uppercase">Purchase Entries</h4>
                        <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>PNR</th>
                                    <th>Flight No</th>
                                    <th>Travel Date</th>
                                    <th>Quantity</th>
                                    <th>Available</th>
                                    <th>Sold</th>
                                    <th>Blocks</th>
                                    <th>Cost Price</th>
                                    <th>Sell Price</th>
                                    <th>Markup Price</th>
                                    <th>Owner ID</th>
                                    <th>Created AT</th>
                                    <th>Arrival Date</th>
                                    <th>Tax</th>
                                    <th>Infant</th>
                                    <th>Child</th>
                                    <th>Infant Count</th>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                                @if(!empty($data['PurchaseEntryDetails']))
                                @foreach($data['PurchaseEntryDetails'] as $key => $val)
                                <tr>
                                    <td>{{ $val->ID }}</td>
                                    <td>{{ $val->PNR }}</td>
                                    <td>{{ $val->FlightNo }}</td>
                                    <td>{{ $val->TravelDate }}</td>
                                    <td>{{ $val->Quantity }}</td>
                                    <td>{{ $val->Available }}</td>
                                    <td>{{ $val->Sold }}</td>
                                    <td>{{ $val->Blocks }}</td>
                                    <td>{{ $val->CostPrice }}</td>
                                    <td>{{ $val->SellPrice }}</td>
                                    <td>{{ $val->MarkupPrice }}</td>
                                    <td>{{ $val->OwnerID }}</td>
                                    <td>{{ $val->CreatedAT }}</td>
                                    <td>{{ $val->ArrivalDate }}</td>
                                    <td>{{ $val->Tax }}</td>
                                    <td>{{ $val->Infant }}</td>
                                    <td>{{ $val->Child }}</td>
                                    <td>{{ $val->InfantCount }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td style="text-align: left; padding: 10px 15px 10px;" colspan="17">Nothing to show</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <br />
                {{-- Account Transactions --}}
                <div class="account-transactions">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <h4 class="card-title text-uppercase">Account Transactions</h4>
                        <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Select</th>
                                    <th>ID</th>
                                    <th>Agent ID</th>
                                    <th>Reference Number</th>
                                    <th>Type ID</th>
                                    <th>Transaction Type</th>
                                    <th>Ticket ID</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Remarks</th>
                                    <th>Owner ID</th>
                                    <th>Status</th>
                                    <th>Payment Mode</th>
                                    <th>Exp Date</th>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                                @if(!empty($data['AccountTransactions']))
                                @foreach($data['AccountTransactions'] as $key => $val)
                                <tr id="AT_{{ $val->ID }}" data-amount="{{ $val->Amount }}">
                                    <td>
                                        <input type="checkbox" name="selected_account_transactions[]" value="{{ $val->ID }}" checked>
                                    </td>
                                    <td>{{ $val->ID }}</td>
                                    <td>{{ $val->AgentID }}</td>
                                    <td>{{ $val->ReferenceNumber }}</td>
                                    <td>{{ $val->Type }}</td>
                                    <td>{{ $val->TransactionType }}</td>
                                    <td>{{ $val->TicketID }}</td>
                                    <td>{{ $val->Amount }}</td>
                                    <td>{{ $val->Balance }}</td>
                                    <td>{{ $val->Remarks }}</td>
                                    <td>{{ $val->OwnerID }}</td>
                                    <td>{{ $val->Status }}</td>
                                    <td>{{ $val->PaymentMode }}</td>
                                    <td>{{ $val->ExpDate }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td style="text-align: left; padding: 10px 15px 10px;" colspan="18">Nothing to show</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <br />
                {{-- Supplier Transactions --}}
                <div class="supplier-transactions">
                    <div class="table-sorter-wrapper col-lg-12 table-responsive">
                        <h4 class="card-title text-uppercase">Supplier Transactions</h4>
                        <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Select</th>
                                    <th>ID</th>
                                    <th>Supplier ID</th>
                                    <th>Reference Number</th>
                                    <th>Type ID</th>
                                    <th>Transaction Type</th>
                                    <th>Ticket ID</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Remarks</th>
                                    <th>Owner ID</th>
                                    <th>Status</th>
                                    <th>Payment Mode</th>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                                @if(!empty($data['SupplierTransactions']))
                                @foreach($data['SupplierTransactions'] as $key => $val)
                                <tr id="AT_{{ $val->ID }}" data-amount="{{ $val->Amount }}">
                                    <td>
                                        <input type="checkbox" name="selected_supplier_transactions[]" value="{{ $val->ID }}" checked>
                                    </td>
                                    <td>{{ $val->ID }}</td>
                                    <td>{{ $val->SupplierID }}</td>
                                    <td>{{ $val->ReferenceNumber }}</td>
                                    <td>{{ $val->Type }}</td>
                                    <td>{{ $val->TransactionType }}</td>
                                    <td>{{ $val->TicketID }}</td>
                                    <td>{{ $val->Amount }}</td>
                                    <td>{{ $val->Balance }}</td>
                                    <td>{{ $val->Remarks }}</td>
                                    <td>{{ $val->OwnerID }}</td>
                                    <td>{{ $val->Status }}</td>
                                    <td>{{ $val->PaymentMode }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td style="text-align: left; padding: 10px 15px 10px;" colspan="17">Nothing to show</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <br />
                <div class="controls">
                    <button data-mode="all" class="btn_delete_refunds btn btn-danger" style="margin:10px;">Delete Entire</button>
                    <button data-mode="selected" class="btn_delete_refunds btn btn-danger" style="margin:10px;">Delete Selected</button>
                </div>
            </div>
        </div>
    </div>
    <div class="alert-container" style="display:none;">
        <div class="alert">
            Selected Items Has Been Deleted
        </div>
    </div>
    <div class="modal fade show" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
    <input type="hidden" id="atr_id" value="{{ $data['AirTicketRefundID'] }}">

    @endsection
    @section('js')
    <script>
        $(document).ready(function() {
            $('.alert-container').hide();

            $('.btnDelete').click((e) => {
                let resp = confirm("Are you sure you want to delete the PurchaseEntry Entry Ticket ?")
                if (!resp) {
                    e.preventDefault();
                }
            })
            $('.btn_delete_refunds').click((e) => {
                e.preventDefault();
                let promptResp = confirm("Are you sure want to delete the sected items. This operation can't be undone");
                if (promptResp) {
                    let atr_id = $('#atr_id').val();
                    let at_ids = get_selected_account_transaction_ids();
                    let st_ids = get_selected_supplier_transaction_ids();
                    let mode   = $(this).attr('data-mode');
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '/maintanance/manage-delete-refunds',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            'atr_id': atr_id,
                            'at_ids': at_ids,
                            'st_ids': st_ids,
                            'mode'  : mode
                        },
                        success: function(success) {
                            if (success) {
                                msg();                                
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            })

            function get_selected_account_transaction_ids() {
                /* Collect list of selected account transaction ids */
                let selected_account_transaction_ids = [];
                $('input[name="selected_account_transactions[]"]:checked').each(function() {
                    selected_account_transaction_ids.push($(this).val());
                });
                let selectedItemsList = selected_account_transaction_ids.join(",");
                console.log(selectedItemsList);
                return selectedItemsList;
            }

            function get_selected_supplier_transaction_ids() {
                /* Collect list of selected supplier transaction ids */
                let selected_supplier_transaction_ids = [];
                $('input[name="selected_supplier_transactions[]"]:checked').each(function() {
                    selected_supplier_transaction_ids.push($(this).val());
                });
                let selectedItemsList = selected_supplier_transaction_ids.join(",");
                console.log(selectedItemsList);
                return selectedItemsList;
            }

            function msg() {
                var alert = $(".alert-container");
                alert.slideDown();
                window.setTimeout(function() {
                    alert.slideUp();
                }, 2500);
                location.reload();
            }
        });
    </script>
    @endsection
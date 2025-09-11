@extends('layouts.app')
@section('title','PNR Excel Preview')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                    <ul class="text-capitalize">
                        <li>This PNR <span class="text-danger">ZYWFPF</span> is duplicate.</li>
                        <li>This Airline Code <span class="text-danger">SG</span> is not available.</li>
                        <li>Date Formate is Wrong.</li>
                        <li>Destination is not available.</li>
                    </ul>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-sm btn-info">
                        <i class="mdi mdi-import"></i> Cancel
                    </button>

                    <button class="btn btn-sm btn-success" id="" type="button">
                        <i class="mdi mdi-file-excel"></i>Upload
                    </button>
                </div>
            </div>
           
            <hr>

            <div>
                <div class="table-sorter-wrapper col-lg-12 table-responsive">
                    <table id="sortable-table-2" class="table table-bordered table-sm text-left">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Destination</th>
                                <th>Airline</th>
                                <th>PNR</th>
                                <th>Flight No</th>
                                <th>Travel Date</th>
                                <th>Departure Time</th>
                                <th>Arrival Time</th>
                                <th>Qty</th>
                                <th>Cost Price</th>
                                <th>Markup</th>
                                <th>Infant Price</th>
                                <th>Vendor</th>
                                <th>Flight Route</th>
                                <th>Name List Day</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            <tr>
                                <td>1.</td>
                                <td>
                                   <input type="text" value="IXBDEL" class="p-0 border-0"> 
                                </td>
                                <td>
                                    <select name="" id="" class="w-full">
                                        <option value="">SG</option>
                                        <option value="">IG</option>
                                        <option value="">SG</option>
                                        <option value="">SG</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" value="123" class="p-0 border-0 text-success">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="8154" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="9/6/2020" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="16:10" class="p-0 text-danger  border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="18:25" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="5" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="3000" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="3500" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="1500" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="2" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="Direct" class="p-0 border-0">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="1" class="p-0 border-0">
                                </td>
                               
                                <td>
                                    <button class="btn-danger btn-sm px-1 py-0" type="button">Delete</button>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/table2csv@1.1.3/dist/table2csv.min.js"></script>
@endsection

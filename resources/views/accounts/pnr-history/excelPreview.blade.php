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
                    <ul>
                        <li>This PNR <span class="text-danger">ZYWFPF</span> is duplicate.</li>
                        <li>This Airline Code <span class="text-danger">SG</span> is not available.</li>
                        <li>Date Formate is Wrong.</li>
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
                                <th>Payment Date</th>
                                <th>PNR</th>
                                <th>Passenger Name</th>
                                <th>Amount</th>
                                <th>Parent PNR</th>
                                <th>Airline Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-left">
                            <tr>
                                <td>1.</td>
                                <td>
                                   <input type="text" value="20-12-2023" class="p-0 border-0"> 
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="ZYWFPF" class="p-0 text-danger  border-0">
                                </td>
                                <td>
                                    <input type="text" value="TBA/TBA" class="p-0 border-0 text-success">
                                </td>
                                <td>
                                    <input type="text" name="" id="" value="-4500" class="p-0 border-0">
                                </td>
                                <td></td>
                                <td>
                                    <select name="" id="" class="w-full" style="width: 50%;">
                                        <option value="">SG</option>
                                        <option value="">IG</option>
                                        <option value="">SG</option>
                                        <option value="">SG</option>
                                    </select>
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

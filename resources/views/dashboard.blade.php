@extends('layouts.app')
@section('title','Dashboard')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangerpicker.css')}}" />
<style>
    .loader-container {
        width: 100%;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .loader-container img {
        width: 100px;
        top: 50%;
        position: absolute;
        left: 50%;
    }
</style>
@endsection
@section('contents')
<div class="container">
    <div class="row ">
        <div class="col-md-12 mt-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Search Flight Tickets</h4>
                    <div class="row">
                        <div class="col-md-12">
                            <form class="row" method="GET" action="/flight-tickets/flight-search" autocomplete="off">
                                <div class="col-md-2 mb-2">
                                    <label for="">From</label>
                                    <select name="origin" id="origin_id" required class="form-control  form-control-sm form-control-sm select2" style="width:100%">
                                        <option value="">From</option>
                                        <option value="">Select Origin</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label for="">To</label>
                                    <select name="destination" id="destination_id" class="form-control form-control-sm  select2" required style="width:100%">
                                        <option value="">To</option>
                                        <option value="">Select Origin</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label for="">Departure Date</label>
                                    <input type="text" class="form-control datepicker form-control-sm" name="departure_date" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Pax Details </label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1">Adults</span>
                                        <select name="adult" id="adult" required class="form-control form-control-sm select2">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                        </select>
                                        <span class="input-group-text" id="basic-addon1">Child</span>
                                        <select name="child" id="child" required class="form-control form-control-sm select2 ">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                        </select>
                                        <span class="input-group-text" id="basic-addon1">Infants</span>
                                        <select name="infant" id="infant" required class="form-control form-control-sm select2">
                                            <option value="0">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                        <div class="input-group-prepend">
                                            <button class="btn btn-behance btn-block btn-sm" name="search_btn" value="search">
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="offset-9 col-3 mb-4">
            <form id="filterForm">
                <input type="hidden" name="start_date" id="start_date">
                <input type="hidden" name="end_date" id="end_date">
                <input type="text" class="form-control" id="dates">
            </form>
        </div>
        <div class="col-md-12">
            @if (session('greetings'))
            <div class="alert alert-success" role="alert">
                <h1><strong> {{ session('greetings') }}</strong></h1>
            </div>
            @endif

            @if( in_array("administrator", Auth::user()->getRoleNames()->toArray()) )
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Over booking</h4>
                            <p class="text-muted" id="Overbooking"></p>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                          <div class="card">
                              <div class="card-body">
                                  <h4 class="card-title">Agent Registration</h4>
                                  <div class="d-flex justify-content-between">
                                      <p class="text-muted" id="AgentRegistrationSection"></p>
                                  </div>
                                  <div class="progress progress-md">
                                      <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                              </div>
                          </div>
                        </div> -->
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Sales</h4>
                            <div class="d-flex justify-content-between">
                                <p class="text-muted" id="SalesAmountSection"></p>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                              <div class="card-body">
                                <h4 class="card-title">Deposit Requests</h4>
                                <div class="d-flex justify-content-between">
                                  <p class="text-muted">Approved</p>
                                  <p class="text-muted" id="DepositRequestApproved"></p>

                                  <p class="text-muted"> Pending</p>
                                  <p class="text-muted" id="DepositRequestPending"></p>

                                  <p class="text-muted"> Rejected</p>
                                  <p class="text-muted" id="DepositRequestRejected"></p>
                                </div>
                                <div class="progress progress-md">
                                  <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                              <div class="card-body">
                                <h4 class="card-title">Credit Requests</h4>
                                <div class="d-flex justify-content-between">
                                  <p class="text-muted">Pending</p>
                                  <p class="text-muted" id="CreditRequestPending"></p>
                                  <p class="text-muted" >Approved</p>
                                  <p class="text-muted" id="CreditRequestApproved"></p>
                                  <p class="text-muted">Rejected</p>
                                  <p class="text-muted" id="CreditRequestRejected"></p>
                                </div>
                                <div class="progress progress-md">
                                  <div class="progress-bar bg-danger w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </div>
                            </div>
                        </div> -->
                <!-- <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                          <div class="card">
                            <div class="card-body">
                              <h4 class="card-title">Receipts</h4>
                              <div class="d-flex justify-content-between">
                                <p class="text-muted">Total </p>
                                <p class="text-muted" id="ReceiptsTotal"></p>
                                <p class="text-muted">Amount </p>
                                <p class="text-muted" id="ReceiptsAmount"></p>
                              </div>
                              <div class="progress progress-md">
                                <div class="progress-bar bg-info w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                            </div>
                          </div>
                        </div> -->

                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Ticket Sold</h4>
                            <div class="d-flex justify-content-between">
                                <p class="text-muted">Adult</p>
                                <p class="text-muted" id="SalesAdultSection"></p>
                                <p class="text-muted">Infant</p>
                                <p class="text-muted" id="SalesInfantSection"></p>
                            </div>
                            <div class="progress progress-md">
                                <div class="progress-bar bg-danger w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Cancellation Request</h4>
                                    <div class="d-flex justify-content-between">
                                        <p class="text-muted" >Approved</p>
                                        <p class="text-muted" id="CancellationRequestApproved"></p>
                                        <p class="text-muted">Pending</p>
                                        <p class="text-muted" id="CancellationRequestPending"></p>
                                    </div>
                                    <div class="progress progress-md">
                                        <div class="progress-bar bg-danger w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
            </div>

            <!-- graph -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card" style="height: 250px;">
                    <canvas id="salesdetails" class="border"></canvas>
                </div>
                <!-- <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card" style="height: 250px;">
                            <canvas id="salesapivolume" class="border"></canvas>
                        </div> -->
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card" style="height: 250px;">
                    <canvas id="stockdetails" class="border"></canvas>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card" style="height: 250px;">
                    <canvas id="stockapivolume" class="border"></canvas>
                </div>
            </div>
            <!-- <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card" style="height: 280px;">
                          <canvas id="stockapivolume" style="width:20%;max-width:600px;" class="border"></canvas>
                        </div>
                    </div> -->



            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center text-uppercase">Sales Details</h4>
                            <table class="table table-sm table-stripped">
                                <tr class="table-info">
                                    <th>API Count</th>
                                    <td id="api_agent_sales_dataCount"></td>
                                </tr>
                                <tr class="table-danger">
                                    <th>API Volume</th>
                                    <td id="api_agent_sales_dataVolume"></td>
                                </tr>
                                <tr class="table-info">
                                    <th>Portal Count</th>
                                    <td id="portal_agent_sales_dataCount"></td>
                                </tr>
                                <tr class="table-danger">
                                    <th>Portal Volume</th>
                                    <td id="portal_agent_sales_dataVolume"></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center text-uppercase">Stock Details</h4>
                            <table class="table table-sm table-stripped">
                                <tr class="table-info">
                                    <th>Third Party Count</th>
                                    <td id="stock_third_party_count"></td>
                                </tr>
                                <tr class="table-danger">
                                    <th>Third Party Volume</th>
                                    <td id="stock_third_party_volume"></td>
                                </tr>
                                <tr class="table-info">
                                    <th>Own Count</th>
                                    <td id="stock_own_count"></td>
                                </tr>
                                <tr class="table-danger">
                                    <th>Own Volume</th>
                                    <td id="stock_own_volume"></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="row">
                      <div class="col-12 grid-margin stretch-card" style="height: 500px;">
                        <canvas id="agentsdetails" style="width:100%;max-width:600px;" class="border"></canvas>
                      </div>

                      <div class="col-12 col-sm-6 col-md-6 col-xl-6 grid-margin stretch-card" style="height: 500px;">
                        <canvas id="agents_categories" style="width:100%;max-width:600px;" class="border"></canvas>
                      </div>
                    </div> -->
            @else
            <!-- <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Deposit Requests</h4>
                        <div class="d-flex justify-content-between">
                        <p class="text-muted">Approved</p>
                        <p class="text-muted" id="DepositRequestApproved"></p>

                        <p class="text-muted"> Pending</p>
                        <p class="text-muted" id="DepositRequestPending"></p>

                        <p class="text-muted"> Rejected</p>
                        <p class="text-muted" id="DepositRequestRejected"> </p>
                        </div>
                        <div class="progress progress-md">
                        <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Credit Requests</h4>
                        <div class="d-flex justify-content-between">
                        <p class="text-muted">Pending</p>
                        <p class="text-muted" id="CreditRequestPending"></p>
                        <p class="text-muted">Approved</p>
                        <p class="text-muted" id="CreditRequestApproved"></p>
                        <p class="text-muted">Rejected</p>
                        <p class="text-muted" id="CreditRequestRejected"></p>
                        </div>
                        <div class="progress progress-md">
                        <div class="progress-bar bg-danger w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Cancellation Request</h4>
                        <div class="d-flex justify-content-between">
                        <p class="text-muted">Approved</p>
                        <p class="text-muted" id="CancellationRequestApproved"></p>
                        <p class="text-muted">Pending</p>
                        <p class="text-muted" id="CancellationRequestPending"></p>
                        </div>
                        <div class="progress progress-md">
                        <div class="progress-bar bg-danger w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Over booking</h4>
                        <p class="text-muted" id="Overbooking"></p>
                        <div class="progress progress-md">
                        <div class="progress-bar bg-success w-25" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    </div>
                </div>
                </div> -->
            @endif
            @can('dashboard_agent_stats')
            <!-- <div class="row col-md-12 grid-margin">
                    <div class="alert alert-danger grid-margin" role="alert">
                    <a target="_blank" href="/api-logs/vendors" class="card-body">
                        <h4 class="card-title" style="color: #fff">V2 API Integration Failed Count</h4>
                        <p class="text-muted" id="v2apibookingfailed_apivendors" style="color: #fff !important"></p>
                    </a>
                    </div>
                    <div class="alert alert-danger grid-margin" role="alert">
                    <a target="_blank" href="/api-logs/vendors" class="card-body">
                        <h4 class="card-title" style="color: #fff">V1 API Integration Failed Count</h4>
                        <p class="text-muted" id="v1apibookingfailed_apivendors" style="color: #fff !important"></p>
                    </a>
                    </div>
                </div> -->

            <!-- AGENT COLLUMS -->
            <!-- <div class="row">
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card" style="background: orange">
                          <a target="_blank" href="/settings/show-agents?type=8" class="card-body">
                            <h4 class="card-title" style="color: #fff">Dormant Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="DormantAgents" style="color: #fff !important"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card" style="background: #00bbe0">
                          <a target="_blank" href="/settings/show-agents?type=9" class="card-body">
                            <h4 class="card-title" style="color: #fff">Duplicate Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="DuplicateAgents" style="color: #fff !important"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card" style="background: #3bb001">
                          <a target="_blank" href="/settings/show-agents?type=10" class="card-body">
                            <h4 class="card-title" style="color: #fff">B2C Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="B2CAgents" style="color: #fff !important"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card">
                          <a target="_blank" href="/settings/show-agents?type=1" class="card-body">
                            <h4 class="card-title">Total Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="TotalAgents"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card">
                          <a target="_blank" href="/settings/show-agents?type=2" class="card-body">
                            <h4 class="card-title">Total Active Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="TotalActiveAgents"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card">
                          <a target="_blank" href="/settings/show-agents?type=3" class="card-body">
                            <h4 class="card-title">Total Non-Active Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="TotalNonActiveAgents"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card">
                          <a target="_blank" href="/settings/show-agents?type=4" class="card-body">
                            <h4 class="card-title">Total Transacting Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="TotalTransactingAgents"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card">
                          <a target="_blank" href="/settings/show-agents?type=5" class="card-body">
                            <h4 class="card-title">Total Non-Transacting Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="TotalNonTransactingAgents"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card">
                          <a target="_blank" id="transacting-agents" href="" class="card-body">
                            <h4 class="card-title">Transacting Monthly Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="TransactingMonthlyAgents"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-12 col-sm-6 col-md-6 col-xl-4 grid-margin stretch-card">
                        <div class="card">
                          <a target="_blank" id="non-transacting-agents" href="" class="card-body">
                            <h4 class="card-title">Non Transacting Monthly Agents</h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-muted" id="NonTransactingMonthlyAgents"></p>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div> -->
            @endcan

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Todo list</h4>
                            <div class="add-items d-flex">
                                <input type="text" class="form-control todo-list-input" placeholder="What do you need to do today?">
                                <button class="add btn btn-primary font-weight-bold todo-list-add-btn">Add</button>
                            </div>
                            <div class="list-wrapper">
                                <ul class="d-flex flex-column-reverse todo-list">
                                    @foreach($lists as $list)
                                    <li @if($list->status == 0) class="completed" @endif>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input data-id="{{$list->id}}" class="checkbox" type="checkbox" @if($list->status == 0) checked @endif>
                                                {{$list->task}}
                                            </label>
                                        </div>
                                        <i data-id="{{$list->id}}" class="remove mdi mdi-close-circle-outline"></i>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loader-container">
        <img src="/images/loading.gif" />
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js" defer></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script>
    $('.flight_details').click(e => {
        let the = $(e).val();
        console.log(e);
        $('#exampleModalCenter').show();
    })
</script>
<script>
    var saleschartgraph;
    var stockchartgraph;
    // sales-api-count
    function salesChart(data) {
        var formattedData = data.map(value => value.toLocaleString()); // Format values with commas
        console.log(data)

        var xValues = [`API COUNT - ${formattedData[0]}`, `API VOLUME - ${formattedData[1]}`];
        var yValues = data;
        var barColors = [
            "#3bb001",
            "#00BBE0",
        ];
        // Destroy existing chart if it exists
        if (saleschartgraph) {
            saleschartgraph.destroy();
        }
        var chartElement = document.getElementById("salesdetails");

        saleschartgraph = new Chart(chartElement, {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                }]
            },
            options: {
                "responsive": true,
                "maintainAspectRatio": false,
                title: {
                    display: true,
                    text: "SALE DETAILS",
                    fontSize: 14,
                    fontColor: "black"
                }
            }
        });
    }

    // var salesapivolume;
    var stockapivolume;
    // Sales-api-volume
    // function salesapivolumeChart(data) {
    //   var formattedData = data.map(value => value.toLocaleString()); // Format values with commas
    //   console.log(data)

    //   var xValues = [`API - ${formattedData[0]}`, `PORTAL - ${formattedData[1]}`];
    //   var yValues = [data[0], data[1]];
    //   var barColors = [
    //     "#3bb001",
    //     "#00BBE0",
    //   ];
    //   // Destroy existing chart if it exists
    //   if (salesapivolume) {
    //     salesapivolume.destroy();
    //   }
    //   var chartElement = document.getElementById("salesapivolume");

    //   salesapivolume = new Chart(chartElement, {
    //     type: "pie",
    //     data: {
    //       labels: xValues,
    //       datasets: [{
    //         backgroundColor: barColors,
    //         data: yValues,
    //       }]
    //     },
    //     options: {
    //       "responsive": true,
    //       "maintainAspectRatio": false,
    //       title: {
    //         display: true,
    //         text: "SALES VOLUME",
    //         fontSize: 14,
    //         fontColor: "black"
    //       }
    //     }
    //   });
    // }
    // STOCK COUNT
    function stockChart(data) {
        var formattedData = data.map(value => value.toLocaleString()); // Format values with commas
        console.log(data)
        var xValues = [`Third Party Count - ${formattedData[0]}`, `First Party Count - ${formattedData[1]}`];
        var yValues = data;
        var barColors = [
            "#3bb001",
            "#00BBE0",
        ];
        // Destroy existing chart if it exists
        if (stockchartgraph) {
            stockchartgraph.destroy();
        }
        var chartElement = document.getElementById("stockdetails");

        stockchartgraph = new Chart(chartElement, {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                }]
            },
            options: {
                "responsive": true,
                "maintainAspectRatio": false,
                title: {
                    display: true,
                    text: "STOCK COUNT",
                    fontSize: 14,
                    fontColor: "black"
                }
            }
        });
    }

    // Stock-volume
    function stockapivolumeChart(data) {
        console.log(data);
        var formattedData = data.map(value => value.toLocaleString()); // Format values with commas
        var xValues = [`Third Party Volume - ${formattedData[0]}`, `First Party Volume - ${formattedData[1]}`];
        var yValues = [data[0], data[1]];
        var barColors = [
            "#3bb001",
            "#00BBE0",
        ];

        // Destroy existing chart if it exists
        if (stockapivolume) {
            stockapivolume.destroy();
        }
        var chartElement = document.getElementById("stockapivolume");
        stockapivolume = new Chart(chartElement, {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                }]
            },
            options: {
                "responsive": true,
                "maintainAspectRatio": false,
                title: {
                    display: true,
                    text: "STOCK VOLUME",
                    fontSize: 14,
                    fontColor: "black"
                }
            }
        });

    }
</script>
<!-- lineChart -->
<script>
    var agentsDetailsChart;
    var agentsCategoriesChart;

    function agentDetails(data) {
        console.log(data)

        var xValues = [
            `Total Agent - ${data[0]}`,
            `Total Active Agents - ${data[1]}`,
            `Total Non-Active Agents - ${data[2]}`,
            `Total Transacting Agent - ${data[3]}`,
            `Total Non-Transacting Agent - ${data[4]}`,
            `Transacting Monthly Agents - ${data[5]}`,
            `Non-Transacting M Agents - ${data[6]}`,
        ];

        var yValues = data;
        var barColors = [
            "#3bb001",
            "#00BBE0",
            "red",
            "orange",
            "blue",
            "green",
            "pink",
            "sky blue"
        ];

        // Destroy existing chart if it exists
        if (agentsDetailsChart) {
            agentsDetailsChart.destroy();
        }
        var chartElement = document.getElementById("agentsdetails");

        agentsDetailsChart = new Chart(chartElement, {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                }]
            },
            options: {
                legend: {
                    display: false,
                },
                "responsive": true,
                "maintainAspectRatio": false,
                title: {
                    display: true,
                    text: "Agents Details",
                    fontSize: 14,
                    fontColor: "black"
                }
            }
        });
    }

    // agent-categories
    function agent_categories(data) {
        console.log(data)
        var xValues = [
            `Dormant Agents - ${data[0]}`,
            `Dublicate Agents - ${data[1]}`,
            `B2C Agents - ${data[2]}`
        ];
        var yValues = data;
        var barColors = [
            "#3bb001",
            "#f10075",
            "orange",
        ];

        // Destroy existing chart if it exists
        if (agentsCategoriesChart) {
            agentsCategoriesChart.destroy();
        }
        var chartElement = document.getElementById("agents_categories");

        agentsCategoriesChart = new Chart(chartElement, {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues,
                }]
            },
            options: {
                legend: {
                    display: false,
                },
                "responsive": true,
                "maintainAspectRatio": false,
                title: {
                    display: true,
                    text: "Agent Categories",
                    fontSize: 14,
                    fontColor: "black",

                }
            }
        });

    }
</script>

<script>
    $(document).ready(function() {

        $(".loader-container").show();

        var start = moment();
        var end = moment();

        var transact_url = '/settings/show-agents?type=6&start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD');
        $("#transacting-agents").attr("href", transact_url)
        var non_transact_url = '/settings/show-agents?type=7&start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD');
        $("#non-transacting-agents").attr("href", non_transact_url)

        $.ajax({
            url: '{{ route('ajax.dashboard.data') }}',
            type: 'GET',
            data: {
                start_date: start.format('YYYY-MM-DD'),
                end_date: end.format('YYYY-MM-DD')
            },
            success: function(resp) {
                if (resp.success) {

                    $('#AgentRegistrationSection').html(resp.data.agents);
                    $('#SalesAmountSection').html(resp.data.sales);

                    $('#SalesAdultSection').html(resp.data.adult_count);
                    $('#SalesInfantSection').html(resp.data.infant_count);
                    $('#Overbooking').html(resp.data.overbooking);

                    $('#DepositRequestApproved').html(resp.data.depositApproved);
                    $('#DepositRequestPending').html(resp.data.depositPending);
                    $('#DepositRequestRejected').html(resp.data.depositRejected);

                    $('#CreditRequestPending').html(resp.data.creditRequestPending);
                    $('#CreditRequestApproved').html(resp.data.creditRequestApproved);
                    $('#CreditRequestRejected').html(resp.data.creditRequestRejected);

                    $('#ReceiptsTotal').html(resp.data.receiptCount);
                    $('#ReceiptsAmount').html(resp.data.receiptAmount);

                    $('#CancellationRequestApproved').html(resp.data.cancellationRequestApproved);
                    $('#CancellationRequestPending').html(resp.data.cancellationRequestPending);

                    $('#salesDetailFromPortal').html(resp.data.pax_details_portal);
                    $('#salesDetailFromAPI').html(resp.data.pax_details_api);

                    $('#portal_agent_sales_dataVolume').html(resp.data.portal_agent_sales_data.volume);
                    $('#portal_agent_sales_dataCount').html(resp.data.portal_agent_sales_data.count);
                    $('#api_agent_sales_dataVolume').html(resp.data.api_agent_sales_data.volume);
                    $('#api_agent_sales_dataCount').html(resp.data.api_agent_sales_data.count);

                    $('#stock_own_volume').html(resp.data.own_party_vendors_sales_volume);
                    $('#stock_own_count').html(resp.data.own_party_vendors_sales_count);
                    $('#stock_third_party_volume').html(resp.data.third_party_vendors_sales_volume);
                    $('#stock_third_party_count').html(resp.data.third_party_vendors_sales_count);

                    salesChart([resp.data.api_agent_sales_data.count, resp.data.api_agent_sales_data.volume]);
                    // salesapivolumeChart([resp.data.api_agent_sales_data.volume, resp.data.portal_agent_sales_data.volume]);

                    stockChart([resp.data.third_party_vendors_sales_count, resp.data.own_party_vendors_sales_count]);
                    stockapivolumeChart([resp.data.third_party_vendors_sales_volume, resp.data.own_party_vendors_sales_volume]);
                }
            }
        });

        $.ajax({
            url: '{{ route('ajax.dashboard.agents.data')}}',
            type: 'GET',
            data: {
                start_date: start.format('YYYY-MM-DD'),
                end_date: end.format('YYYY-MM-DD')
            },
            success: function(resp) {
                if (resp.success) {
                    $('#TotalAgents').html(resp.data.total_agent_count);
                    $('#TotalActiveAgents').html(resp.data.total_active_agent_count);
                    $('#TotalNonActiveAgents').html(resp.data.total_non_active_agent_count);
                    $('#TotalTransactingAgents').html(resp.data.total_transacting_agent_count);
                    $('#TotalNonTransactingAgents').html(resp.data.total_non_transacting_agent_count);
                    $('#TransactingMonthlyAgents').html(resp.data.transacting_monthly);
                    $('#NonTransactingMonthlyAgents').html(resp.data.non_transacting_monthly);
                    $('#DormantAgents').html(resp.data.dormant_count);
                    $('#DuplicateAgents').html(resp.data.duplicate_count);
                    $('#B2CAgents').html(resp.data.b2c_count);
                    $('#v2apibookingfailed_apivendors').html(resp.data.v2apibookingfailed_apivendors);
                    $('#v1apibookingfailed_apivendors').html(resp.data.v1apibookingfailed_apivendors);
                    $(".loader-container").hide();
                    agentDetails([resp.data.total_agent_count,
                        resp.data.total_active_agent_count,
                        resp.data.total_non_active_agent_count,
                        resp.data.total_transacting_agent_count,
                        resp.data.total_non_transacting_agent_count,
                        resp.data.transacting_monthly,
                        resp.data.non_transacting_monthly,

                    ]);
                    agent_categories([resp.data.dormant_count, resp.data.duplicate_count, resp.data.b2c_count]);
                    //  agent_categories([20,18,25]);
                }
            }
        });
    });
    $("#origin_id, #destination_id").select2({
        placeholder: "Select a Airport",
        width: '100%',
        allowClear: true,
        ajax: {
            url: '/ajax/search/airports',
            delay: 250,
            data: function(params) {
                var query = {
                    q: params.term,
                }
                return query;
            },
            processResults: function(data) {
                return {
                    results: data
                };
            },

            dataType: 'json',
            cache: true
        },
        minimumInputLength: 3,
    });
    $("form").submit(function(e) {
        let originValue = $("#origin_id").val();
        let destinationValue = $("#destination_id").val();
        if (originValue === destinationValue) {
            alert("Origin and Destinations cannot be the same");

            $("#origin_id").val(null).trigger('change');
            $("#destination_id").val(null).trigger('change');

            e.preventDefault();
        }
    })


    // Initialize datepicker
    $(".datepicker").datepicker({
        todayHighlight: true,
        autoclose: true,
        format: 'dd-mm-yyyy'
    });
    $(document).ready(function() {
        $(".datepicker").datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        $(".datepicker").datepicker('setDate', new Date());
    });

    $('#dates').daterangepicker({
            start_date: moment(),
            end_date: moment(),
            showDropdowns: true,
            maxDate: moment(),
            locale: {
                "format": "DD/MM/YYYY",
            }
        },
        function(start, end, label) {
            $(".loader-container").show();
            var transact_url = '/settings/show-agents?type=6&start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD');
            $("#transacting-agents").attr("href", transact_url)
            var non_transact_url = '/settings/show-agents?type=7&start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD');
            $("#non-transacting-agents").attr("href", non_transact_url)

            $.ajax({
                url: '{{ route('ajax.dashboard.data')}}',
                type: 'GET',
                data: {
                    start_date: start.format('YYYY-MM-DD'),
                    end_date: end.format('YYYY-MM-DD')
                },
                success: function(resp) {
                    console.log(resp.data)

                    if (resp.success) {
                        $('#AgentRegistrationSection').html(resp.data.agents);
                        $('#SalesAmountSection').html(resp.data.sales);
                        $('#SalesAdultSection').html(resp.data.adult_count);
                        $('#SalesInfantSection').html(resp.data.infant_count);

                        $('#DepositRequestApproved').html(resp.data.depositApproved);
                        $('#DepositRequestPending').html(resp.data.depositPending);
                        $('#DepositRequestRejected').html(resp.data.depositRejected);


                        $('#CreditRequestPending').html(resp.data.creditRequestPending);
                        $('#CreditRequestApproved').html(resp.data.creditRequestApproved);
                        $('#CreditRequestRejected').html(resp.data.creditRequestRejected);

                        $('#ReceiptsTotal').html(resp.data.receiptCount);
                        $('#ReceiptsAmount').html(resp.data.receiptAmount);

                        $('#CancellationRequestApproved').html(resp.data.cancellationRequestApproved);
                        $('#CancellationRequestPending').html(resp.data.cancellationRequestPending);

                        $('#portal_agent_sales_dataVolume').html(resp.data.portal_agent_sales_data.volume);
                        $('#portal_agent_sales_dataCount').html(resp.data.portal_agent_sales_data.count);
                        $('#api_agent_sales_dataVolume').html(resp.data.api_agent_sales_data.volume);
                        $('#api_agent_sales_dataCount').html(resp.data.api_agent_sales_data.count);

                        $('#stock_own_volume').html(resp.data.own_party_vendors_sales_volume);
                        $('#stock_own_count').html(resp.data.own_party_vendors_sales_count);
                        $('#stock_third_party_volume').html(resp.data.third_party_vendors_sales_volume);
                        $('#stock_third_party_count').html(resp.data.third_party_vendors_sales_count);

                        salesChart([resp.data.api_agent_sales_data.count, resp.data.api_agent_sales_data.volume]);
                        // salesapivolumeChart([resp.data.api_agent_sales_data.volume, resp.data.portal_agent_sales_data.volume]);

                        stockChart([resp.data.third_party_vendors_sales_count, resp.data.own_party_vendors_sales_count]);
                        stockapivolumeChart([resp.data.third_party_vendors_sales_volume, resp.data.own_party_vendors_sales_volume]);

                    }
                }
            });

            $.ajax({
                url: '{{ route('ajax.dashboard.agents.data')}}',
                type: 'GET',
                data: {
                    start_date: start.format('YYYY-MM-DD'),
                    end_date: end.format('YYYY-MM-DD')
                },
                success: function(resp) {
                    if (resp.success) {
                        $('#TotalAgents').html(resp.data.total_agent_count);
                        $('#TotalActiveAgents').html(resp.data.total_active_agent_count);
                        $('#TotalNonActiveAgents').html(resp.data.total_non_active_agent_count);
                        $('#TotalTransactingAgents').html(resp.data.total_transacting_agent_count);
                        $('#TotalNonTransactingAgents').html(resp.data.total_non_transacting_agent_count);
                        $('#TransactingMonthlyAgents').html(resp.data.transacting_monthly);
                        $('#NonTransactingMonthlyAgents').html(resp.data.non_transacting_monthly);
                        $('#v2apibookingfailed_apivendors').html(resp.data.v2apibookingfailed_apivendors);
                        $('#v1apibookingfailed_apivendors').html(resp.data.v1apibookingfailed_apivendors);
                        $(".loader-container").hide();
                        agentDetails([resp.data.total_agent_count,
                            resp.data.total_active_agent_count,
                            resp.data.total_non_active_agent_count,
                            resp.data.total_transacting_agent_count,
                            resp.data.total_non_transacting_agent_count,
                            resp.data.transacting_monthly,
                            resp.data.non_transacting_monthly,

                        ]);
                        agent_categories([resp.data.dormant_count, resp.data.duplicate_count, resp.data.b2c_count]);
                        // agent_categories([30,20,15]);
                    }
                }
            });
        });
</script>

@endsection
    <div class="row mt-3">
        <div class="table-sorter-wrapper col-lg-12 table-responsive">
            <table id="sortable-table-2" class="table table-bordered  table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>Booked On</th>
                        <th>Reference No.</th>
                        <th>Agent</th>
                        <th>Vendor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$book_ticket_details[0]->book_ticket->created_at}}</td>
                        <td>{{$book_ticket_details[0]->book_ticket->bill_no}}</td>
                        <td>{{$book_ticket_details[0]->book_ticket->agent->company_name}}</td>
                        <td>{{$book_ticket_details[0]->book_ticket->purchase_entry->owner->name}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="table-sorter-wrapper col-lg-12 table-responsive">
            <table id="sortable-table-2" class="table table-bordered  table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>PNR</th>
                        <th>Flight</th>
                        <th>Arrival</th>
                        <th>departure</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$book_ticket_details[0]->book_ticket->pnr}}</td>
                        <td>{{$book_ticket_details[0]->book_ticket->purchase_entry->airline->name}}</td>
                        <td>{{$book_ticket_details[0]->book_ticket->destinationDetails->origin->name}}</td>
                        <td>{{$book_ticket_details[0]->book_ticket->destinationDetails->destination->name}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="table-sorter-wrapper col-lg-12 table-responsive">
            <p class="mt-2">Passenger Details</p>
            <table id="sortable-table-2" class="table table-bordered  table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Type</th>
                        <th>Travelling With</th>
                        <th>DOB</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($book_ticket_details as $key => $value) 
                    <tr>
                        <td>{{$key + 1}} </td>
                        <td>{{$value->title}}</td>
                        <td>{{$value->first_name}}</td>
                        <td>{{$value->last_name}}</td>
                        <td>{{$value->type == 0 ? 'Adult' : 'Child'}}
                        <td>{{$value->travelling_with}}</td>
                        <td>{{$value->dob}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
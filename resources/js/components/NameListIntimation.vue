<template>
    <div>
        <form action="" @submit.prevent="SendSMSFormSubmit">
            <div class="mb-3">
                <label for="">Subject</label>
                <input type="text" class="form-control" name="subject" v-model="form.subject" />
            </div>
            <div class="mb-3">
                <label for="">Select Body</label>
                <select  class="form-control" @change="bodySelected" v-model="form.bodyID">
                    <option :value="d.id" v-for="(d,i) in bodySelect" :key="i">{{d.text }}</option>

                </select>
            </div>
            <div class="mb-3">
                <label for="">Content</label>
                <textarea class="form-control" rows="5" name="content"  v-model="form.content" ></textarea>
            </div>
            <div class="mb-3">
                <button class="btn btn-sm btn-primary" :disabled="processing" v-html="(processing) ? 'PROCESSING': 'SEND'"></button>
            </div>

            <p class="font-weight-bold">Total Bill = {{sales.length}}</p>

            <div class="col-m-12" v-if="sales.length > 0">
                <table class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sector</th>
                            <th>Travel Date</th>
                            <th>DEPT</th>
                            <th>ARV</th>
                            <th>PNR</th>
                            <th>No of PAX</th>
                            <th>Flight No</th>
                            <th>Vendor Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ sales[0].destination }}</td>
                            <td>{{ sales[0].travel_date }}</td>
                            <td>{{ ticket[0].departure_time }}</td>
                            <td>{{ ticket[0].arrival_time }}</td>
                            <td>{{ ticket[0].pnr }}</td>
                            <td>{{ total_pax }}</td>
                            <td>{{ ticket[0].flight_no }}</td>
                            <td>{{ ticket[0].name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-m-12" v-if="sales.length > 0">
                <table class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Bill No</th>
                            <th>Agent Name</th>
                            <th>Agent Email</th>
                            <th>PAX</th>
                            <th>Agent Contact No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sale,index) in sales" :key="index" :class="{ active: form.selected_bill.includes(sale.id) }">
                            <td>
                                <input type="checkbox" v-model='form.selected_bill' :value='sale.id'>
                            </td>
                            <td>
                                {{sale.bill_no}}
                                <span class="badge badge-success" v-if="sale.status == 1">Live</span>
                                <span class="badge badge-warning" v-if="sale.status == 2">Seat Live</span>
                                <span class="badge badge-info" v-if="sale.status == 3">Part XXLD</span>
                                <span class="badge badge-danger" v-if="sale.status == 4">XXLD</span>
                            </td>
                            <td>{{ sale.agent.company_name }}</td>
                            <td>{{ sale.agent.email }}</td>
                            <td>{{ parseInt(sale.adults) + parseInt(sale.child)  }}</td>
                            <td>{{ sale.agent.phone }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        data(){
            return {
                sales: [],

                ticket: [],
                processing: false,
                total_pax: 0,
                form:{
                    subject: 'Important Information about your Booking ID : BILL_NO // PNR_NO ',
                    content:'',
                    purchase_entry_id: '',
                    selected_bill: [],
                    bodyID:'',
                },
                bodySelect: [
                    {

                        text: 'body 1',
                        id: `As the flight is under IROP, and if the updated flight itinerary does not suit your requirement.
Kindly update us within 2 hours of the intimation. Please note that any requests post 2 hours will not be entertained and the given option will be considered as accepted.
Any re-accommodation requests will be accepted between the Group Desk Working Hours 10:30hrs 17:00hrs.`
                    },
                    {

                       text: 'body 2',
                       id: `As the flight is under IROP, and if the updated flight itinerary does not suit your requirement.
Kindly update us within 24 hours of the intimation. Please note that any requests post 2 hours will not be entertained and the given option will be considered as accepted.
Any re-accommodation requests will be accepted between the Group Desk Working Hours 10:30hrs 17:00hrs.
`
                   },
                   {

                       text: 'body 3',
                       id: `As the flight is postponed by less than 2hrs, pax is not eligible for refund or any changes.
Please update the pax accordingly.`
                   },
                   {

                       text: 'body 4',
                       id: `As the flight is preponed by less than 1hr, pax is not eligible for refund or any changes.
Please update the pax accordingly.`
                   },
                   {

                       text: 'body 5',
                       id: `In this case only full refund is applicable and the same will be processed in your agency ID.`
                   },
                   {

                       text: 'body 6',
                       id: `As the flight is cancelled, kindly inform your guest(s). Please raise any re-accommodation requests before 17:00hrs post which refunds will be processed in your agency ID.(Group Desk Working Hours 10:30hrs 17:00hrs.)`
                   }
                ]

            }
        },
        mounted(){
            this.getAllSaleTickets();
            this.getPurchaseEntryDetails();
        },
        methods:{
            bodySelected(){
                this.form.content = this.form.bodyID;
            },

            SendSMSFormSubmit(){
                this.processing = true;
                axios.post('/flight-tickets/ajax/purchase-entry/send-initimation',this.form)
                .then(resp => {
                    if(resp.data.success){
                        this.processing = false;
                        alert(resp.data.message);
                        location.reload();
                    }else{
                        this.processing = false;
                    }
                })

            },


            getPurchaseEntryId(){
              let url = window.location.href;
              const purchasenetry_id = url.split("/").pop();
              this.form.purchase_entry_id = purchasenetry_id;
              return purchasenetry_id;
            },


            getPurchaseEntryDetails(){
                let purchase_entry_id = this.getPurchaseEntryId();
                axios.get('/flight-tickets/ajax/purchase/'+purchase_entry_id)
                .then(resp => {
                    this.ticket = resp.data.data;
                })
            },


            getAllSaleTickets(){

                let purchase_entry_id = this.getPurchaseEntryId();

                axios.get('/flight-tickets/ajax/sales/' + purchase_entry_id)
                .then(resp => {
                    this.sales = resp.data.data;
                    let total = 0;
                    for(let i = 0; i <= resp.data.data.length; i++) {
                        if(resp.data.data[i] !== undefined) {
                            console.log(resp.data.data[i]);
                            let pax = parseInt(resp.data.data[i].adults) + parseInt(resp.data.data[i].child);

                            total = total + pax;
                        }
                    }
                    this.total_pax = total;
                })

            }
        }
    }
</script>
<style>
    .active {
        background-color: #0080003b;
    }

    .table-sm th, .table-sm td{
        padding: 0.3rem  !important;
        border-top: 3px solid #e3e7ed !important;
    }
</style>

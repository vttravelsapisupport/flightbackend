<template>
    <div>
        <div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <h4>SpiceJet PNR Reconciliation</h4>
                </div>
                <div class="text-right col-md-6">
                    <input type="file" ref="Fileupload" @change="fileUpload()">
                    <button class="btn btn-sm btn-primary" @click="verifyButton">Verify</button>
                </div>
            </div>


            <div class="table-sorter-wrapper col-lg-12 table-responsive">
                <table class="table table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>PNR</th>
                        <th>Passenger Name</th>
                        <th>Pax type</th>
                        <th>Flight No</th>
                        <th>Travel Date</th>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Dep Time</th>
                        <th>Arv Time</th>
                        <th>Flight Status</th>
                        <th>PNR Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(result,i) in results">
                        <td>{{ 1 + i }}</td>
                        <td>{{ result['PNR']}}</td>
                        <td>{{ result['Passenger Name']}}</td>
                        <td>{{ result['Pax Type']}}</td>
                        <td> {{ result['Flight No']}}</td>
                        <td>{{ result['Travel Date'] }}</td>
                        <td>{{ result['From'] }}</td>
                        <td>{{ result['To']}}</td>
                        <td>{{ result['Dep Time'] }}</td>
                        <td>{{ result['Arr Time'] }}</td>
                        <td>{{ result['Current Flight Status']}}</td>

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import papa from "papaparse";

export default {
    name: "pnrReconciliation",
    data(){
        return {
            results:[],
            pnrs:[]
        }
    },
    methods:{
        fileUpload(){
            let file = this.$refs.Fileupload.files[0];
            if(file === undefined){
                alert("Please select the file !");
                this.results = [];
                return
            }
            let that = this;
            papa.parse(file,{
                header:true,
                dynamicTyping: true,
                skipEmptyLines: false,
                preview:100,
                complete(result){
                    console.log(result);
                    that.results = result.data
                }
            })
        },
        verifyButton(){

            axios.post('/pnr/reconciliation/submit',{
                data: this.results
            })
                .then(resp => {
                    console.log(resp.data);
                    if(resp.data.success){
                        alert("SUCCESS");
                        location.reload();
                    }else{
                        alert("ERROR");
                    }

                })
        }
    }
}
</script>

<style scoped>

</style>

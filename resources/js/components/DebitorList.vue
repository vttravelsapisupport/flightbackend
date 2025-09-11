<template>
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h4 class="card-title text-uppercase">Debitor List</h4>
            <p class="card-description">Debitor’s List in the Appication.</p>
          </div>
          <div class="col-md-6 text-right"></div>
        </div>
        <br>
        <form action="" @submit.prevent="getAgents">
          <div class="row mb-3" v-if="searchAgentResult">
            <div class="col-md-3">
              <select
                name="agent_id"
                id="agent_id"
                class="form-control select2"
                v-model="searchForm.agent"
              >
                <option value="">Select Agent</option>
                <option
                  :value="result.id"
                  v-for="(result, i) in searchAgentResult"
                  :key="i"
                >
                  {{ result.company_name }} {{ result.code }} {{ result.phone }}
                </option>
              </select>
            </div>

            <div class="col-md-3">
              <select
                name="airport_id"
                id="airport_id"
                v-model="searchForm.airport"
                class="form-control"
              >
                <option value="">Select Airport</option>
                <option
                  :value="result.id"
                  v-for="(result, i) in searchAirportResult"
                  :key="i"
                >
                  {{ result.cityName }} {{ result.cityCode }}
                </option>
              </select>
            </div>
            <div class="col-md-2">
                <select
                name="type"
                id="type"
                class="form-control "
               v-model="searchForm.type"
              >
                <option value="">select mode</option>
                <option value="greater">Greater</option>
                <option value="lower">Less</option>
              </select>
            </div>
             <div class="col-md-2">

                <input type="text" class="form-control" v-model="searchForm.type_amount">
            </div>
 <div class="col-md-1">
              <button class="btn btn-primary">Search</button>
            </div>

            <div class="col-md-2">
              <input
                type="checkbox"
                v-model="searchForm.exclude_zero"
                value="1"
              />
              Exclude Zero
            </div>


          </div>
        </form>

        <div class="row">
          <div class="table-sorter-wrapper col-lg-12 table-responsive">
            <table
              id="sortable-table-2"
              class="table table-bordered table-sm text-left"
            >
              <thead class="thead-dark">
                <tr>
                  <th>#</th>
                  <th>Agent Code</th>
                  <th>Agency name</th>

                  <th>Representative</th>
                  <th>Actual balance </th>
                  <th>Credit balance</th>
                  <th>Remarks</th>
                  <th>Airport</th>
                  <th>City</th>
                  <th>Last Booking date</th>
                  <th width="10%">Action</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="tableLoading" class="text-center">
                  <b-spinner label="Spinning"></b-spinner>
                </template>
                <template v-else>
                  <tr v-for="(agent, i) in agents" :key="i">
                    <td>{{ i + 1 }}</td>
                  <td> {{ agent.code}}</td>
                    <td>
                      <a
                        target="_blank"
                        :href="'/settings/agents-distributors/' + agent.id"
                        :title="
                          agent.company_name
                          + ' ' +
                          agent.phone
                        "
                      >
                        {{ agent.company_name }}
                        {{ agent.phone }}</a
                      >
                    </td>

                    <td>
                      <span v-if="agent.account_manager">
                        {{ agent.account_manager.first_name }}
                        {{ agent.account_manager.last_name }}
                      </span>
                    </td>
                    <td>{{ agent.opening_balance }}</td>
                    <td>{{ agent.credit_balance }}</td>

                    <td>
                      <span v-if="agent.get_agent_debitor_remark">
                        {{ agent.get_agent_debitor_remark.remarks }}
                        <i
                          class="mdi mdi-information"
                          @click="showRemarkModal(agent.id)"
                        ></i>
                      </span>
                      <span v-else></span>
                    </td>

                    <td>
                      <span v-if="agent.nearest_airport_details">
                        <strong>{{
                          agent.nearest_airport_details.cityCode
                        }}</strong>
                      </span>
                    </td>
                    <td>{{ agent.city }}</td>
                    <td>
                      <span v-if="agent.get_latest_booking">
                        <a
                          target="_blank"
                          :href="'/flight-tickets/sales/' + agent.get_latest_booking.id"
                        >
                          {{
                            agent.get_latest_booking.created_ts | dateParse
                          }}</a
                        >
                      </span>
                    </td>
                    <td>
                      <b-dropdown
                        id="dropdown-1"
                        class="btn-sm"
                        text="Action"
                        size="sm"
                        variant="primary"
                      >
                        <b-dropdown-item
                          target="_blank"
                          :href="
                            '/accounts/agent-ledger?agent_id=' +
                            agent.id +
                            '&start_date=' + format() + '&end_date=' + format() + '&fys_id=4'
                          "
                          >Ledger</b-dropdown-item
                        >
                        <b-dropdown-item
                          href="#"
                          @click="createRemarkModal(agent)"
                          >Remarks</b-dropdown-item
                        >
                      </b-dropdown>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>

            <pagination
              :data="paginationAgent"
              @pagination-change-page="getAgents"
            ></pagination>
          </div>
        </div>
        <b-modal ref="remark-modal" title="Remarks">
            <div class="agent-container">
              <table class="table table-sm">
                <thead class="thead-dark">
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Remarks</th>
                    <th>Representative</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(remark, i) in agentRemarks" :key="i">
                    <td>{{ 1 + i }}</td>
                    <td>{{ remark.created_at | dateParse }}</td>
                    <td>{{ remark.remarks }}</td>
                    <td>
                      {{ remark.user.first_name }} {{ remark.user.last_name }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
        </b-modal>
        <b-modal
          ref="create-remark-modal"
          title="Agent Remark"
          @ok="submitRemark"
        >
          <form action="">
            <h6>
              {{ activeAgentRemark.company_name }} -
              {{ activeAgentRemark.code }}
            </h6>
            <div class="form-group">
              <textarea
                name=""
                id=""
                cols="30"
                rows="5"
                v-model="remarks"
                placeholder="Enter Current Remarks"
                class="form-control"
              ></textarea>
            </div>
          </form>
        </b-modal>
      </div>
    </div>
  </div>
</template>
<style>
.agent-container{
    overflow: auto;
}
</style>
<script>
export default {
  data() {
    return {
      agents: [],
      paginationAgent: "",
      activeAgentRemark: "",
      tableLoading: true,
      remarks: "",
      agentRemarks: [],
      searchAgentResult: false,
      searchAirportResult: [],
      searchForm: {
        airport: "",
        agent: "",
        exclude_zero: "",
        type:"",
        type_amount:"",
        page:1
      },
    };
  },
  components: {
    "vue-select": require("vue-select2"),
  },
  mounted() {
    this.getAgents();
    this.getAllAgents();
    this.getAllAirports();
  },
  filters: {
    dateParse: function (value) {
      var d = new Date();

      return (
        [d.getMonth() + 1, d.getDate(), d.getFullYear()].join("/") +
        " " +
        [d.getHours(), d.getMinutes(), d.getSeconds()].join(":")
      );
    },
  },
  methods: {
    format() {
        let inputDate = new Date();
        let date, month, year;

        date = inputDate.getDate();
        month = inputDate.getMonth() + 1;
        year = inputDate.getFullYear();

        date = date
            .toString()
            .padStart(2, '0');

        month = month
            .toString()
            .padStart(2, '0');

        return `${date}-${month}-${year}`;
    },
    getAllAirports() {
      axios
        .get("/flight-tickets/ajax/airport-search", {
          params: {
            agent: "",
          },
        })
        .then((resp) => {
          this.searchAirportResult = resp.data.data;
          //this.searchAirportResult = resp.data.data;
        });
    },
    getAllAgents() {
      axios
        .get("/flight-tickets/ajax/agent-search", {
          params: {
            agent: "",
          },
        })
        .then((resp) => {
          console.log(resp);
          this.searchAgentResult = resp.data.data;
          //this.searchAirportResult = resp.data.data;
        });
    },
    submitRemark() {
      console.log(this.remarks);
      console.log(this.activeAgentRemark);
      axios
        .post("/flight-tickets/ajax/debtor-remark", {
          agent_id: this.activeAgentRemark.id,
          remarks: this.remarks,
        })
        .then((resp) => {
          if (resp.data.success) {
            this.getAgents();
            alert("SUCCESSFULLY UPDATED");
          }
        });
    },

    createRemarkModal(agent) {
      this.$refs["create-remark-modal"].show();
      this.activeAgentRemark = agent;
    },
    showRemarkModal(agent_id) {
      this.$refs["remark-modal"].show();
      axios
        .get("/flight-tickets/ajax/debtor-remarks", {
          params: {
            agent_id: agent_id,
          },
        })
        .then((resp) => {
          console.log(resp);
          this.agentRemarks = resp.data.data.data;
        });
    },
    getAgents(page=1) {
      console.log(this.searchForm);
      this.searchForm.page = page;
      axios
        .get("/flight-tickets/ajax/agents-distributors", {
          params: this.searchForm,
        })
        .then((resp) => {
          console.log(resp.data.success);
          if (resp.data.success) {
            this.tableLoading = false;
            this.paginationAgent = resp.data.data;
            this.agents = resp.data.data.data;
          }
        });
    },
    searchDebitorForm() {
      console.log(this.searchForm);
    },
  },
};
</script>

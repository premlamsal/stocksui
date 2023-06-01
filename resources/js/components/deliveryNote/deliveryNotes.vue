<template>
  <div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Delivery Notes</h1>
    <p class="mb-4" v-if="hasPermission('add_delivery_notes')">
      <button class="btn btn-primary" @click="showAddModal()">
        New Delivery Note
      </button>
    </p>

    <!-- add deliverynote model start -->
    <b-modal id="bv-modal-add-deliverynote" hide-footer>
      <template v-slot:modal-title>
        {{ modalForName }}
      </template>
      <div class="d-block"></div>
      <b-button class="btn-primary mt-3" block @click="callFunc">{{
        modalForName
      }}</b-button>
    </b-modal>
    <!-- add deliverynote modal end-->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6
          class="m-0 font-weight-bold text-primary"
          style="display: inline-block"
        >
          Delivery Note
        </h6>

        <div class="text-center" v-if="isLoading == 'Loading all Data'">
          <b-spinner variant="success" label="Spinning"></b-spinner>
        </div>
        <div class="export-block">
          <template>
            <vue-blob-json-csv
              @success="handleSuccessExportCSV"
              @error="handleErrorExportCSV"
              file-type="csv"
              file-name="deliverynotes"
              :fields="deliverynotes_export_fileds"
              :data="deliverynotes"
            >
              <!-- <button class="btn btn-warning-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button> -->
              <img
                src="img/icon-red-csv.png"
                class="icon-red-csv-export"
                alt="Export data to CSV"
              />
            </vue-blob-json-csv>
          </template>
        </div>

        <!-- {{isLoading}} -->
        <div class="searchTable">
          <!-- Topbar Search -->
          <!-- <div class="input-group"> -->
          <div class="input-group no-border">
            <input
              type="text"
              value
              class="form-control"
              placeholder="Search..."
              v-model="searchTableKey"
              @keyup.enter="searchTableBtn"
            />
            <div class="input-group-append">
              <div class="input-group-text">
                <i class="nc-icon nc-zoom-split" @click="searchTableBtn"></i>
              </div>
            </div>
          </div>
          <!-- </div> -->
        </div>
      </div>
      <div class="card-body" v-if="deliverynotes.length > 0">
        <div class="table">
          <table
            class="table table-striped table-bordered"
            width="100%"
            cellspacing="0"
          >
            <thead>
              <tr>
                <th>Delivery Note No.</th>
                <th>Grand Total</th>
                <th>Client</th>
                <th>Date</th>
                <th>Due Date</th>
                <!-- <th>Status</th> -->
                <th>Last Modified at</th>
                <th>Modify</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="deliverynote in deliverynotes"
                v-bind:key="deliverynote.id"
              >
                <td>{{ deliverynote.custom_deliverynote_id }}</td>
                <td>Rs. {{ deliverynote.grand_total }}</td>
                <td>{{ deliverynote.customer_name }}</td>
                <td>
                  {{ deliverynote.deliverynote_date | moment("from", "now") }}
                </td>
                <td>
                  <span
                    v-if="
                      deliverynote.deliverynote_date === deliverynote.due_date
                    "
                    class="bg-danger text-white p-2"
                    >{{ deliverynote.due_date | moment("from", "now") }}</span
                  >
                  <span v-else class="bg-success text-white p-2">{{
                    deliverynote.due_date | moment("from", "now")
                  }}</span>
                </td>

                <!--
               <td v-if="(deliverynote.status==='Paid')">
                       <toggle-button v-bind:status="true" @statusChanges ="updateStatus($event,deliverynote.id)"/> 
                </td>
                
                <td v-else-if="(deliverynote.status==='To Pay')">
                       <toggle-button v-bind:status="false" @statusChanges ="updateStatus($event,deliverynote.id)"/> 
                </td>
                -->

                <td>{{ deliverynote.updated_at | moment("from", "now") }}</td>
                <td>
                  <button
                    class="btn btn-outline-primary custom_btn_table"
                    v-if="hasPermission('show_delivery_note')"
                  >
                    <span class="fa fa-align-justify custom_icon_table"></span>
                  </button>
                  <button
                    class="btn btn-outline-success custom_btn_table"
                    v-if="hasPermission('edit_delivery_note')"
                  >
                    <span class="fa fa-edit custom_icon_table"></span>
                  </button>

                  <button
                    class="btn btn-outline-danger custom_btn_table"
                    v-if="hasPermission('delete_delivery_note')"
                  >
                    <span class="fa fa-trash custom_icon_table"></span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-md-8">
            <ul class="pagination">
              <li
                class="page-item"
                v-bind:class="{ disabled: !pagination.first_link }"
              >
                <button
                  @click="fetchDeliveryNotes(pagination.first_link)"
                  class="page-link"
                >
                  First
                </button>
              </li>
              <li
                class="page-item"
                v-bind:class="{ disabled: !pagination.prev_link }"
              >
                <button
                  @click="fetchDeliveryNotes(pagination.prev_link)"
                  class="page-link"
                >
                  Previous
                </button>
              </li>
              <li
                v-for="n in pagination.last_page"
                v-bind:key="n"
                class="page-item"
                v-bind:class="{ active: pagination.current_page == n }"
              >
                <button
                  @click="fetchDeliveryNotes(pagination.path_page + n)"
                  class="page-link"
                >
                  {{ n }}
                </button>
              </li>
              <li
                class="page-item"
                v-bind:class="{ disabled: !pagination.next_link }"
              >
                <button
                  @click="fetchDeliveryNotes(pagination.next_link)"
                  class="page-link"
                >
                  Next
                </button>
              </li>
              <li
                class="page-item"
                v-bind:class="{ disabled: !pagination.last_link }"
              >
                <button
                  @click="fetchDeliveryNotes(pagination.last_link)"
                  class="page-link"
                >
                  Last
                </button>
              </li>
            </ul>
          </div>
          <div class="col-md-4">
            Page: {{ pagination.current_page }}-{{ pagination.last_page }} Total
            Records: {{ pagination.total_pages }}
          </div>
        </div>
      </div>
      <div class="errorDivEmptyData" v-else>No Data Found</div>
    </div>
  </div>
</template>

<script>
//custom toggle button
import ToggleButton from "../widgets/ToggleButton";

export default {
  components: {
    ToggleButton,
  },
  data() {
    return {
      deliverynotes: [],
      deliverynotes_id: "",
      pagination: {},
      edit: false,
      searchTableKey: "",
      tempStatus: {},
      modalForName: "",
      modalForCode: 0,

      isLoading: "",
      deliverynotes_export_fileds: [
        "grand_total",
        "customer_name",
        "status",
        "due_date",
      ],
    };
  },

  created() {
    this.fetchDeliveryNotes();
  },

  methods: {
    //methods codes here
    handleSuccessExportCSV() {
      console.log("success Export");
    },
    handleErrorExportCSV() {
      console.log("errorExport");
    },

    searchTableBtn() {
      this.autoCompleteTable();
    },
    autoCompleteTable() {
      this.searchTableKey = this.searchTableKey.toLowerCase();
      if (this.searchTableKey != "") {
        this.isLoading = "Loading Data...";
        let currObj = this;
        axios
          .post("/api/deliverynotess/search", {
            searchQuery: this.searchTableKey,
          })
          .then(function (response) {
            currObj.isLoading = "";

            currObj.deliverynotess = response.data.data;

            console.log(currObj.deliverynotess);

            if (response.data.data == "") {
              currObj.isLoading = "No Data Found";
            }

            currObj.errors = ""; //clearing errors
          })
          .catch(function (error) {
            if (error.response.status == "422") {
              currObj.validationErrors = error.response.data.errors;
              currObj.errors = currObj.validationErrors;
              currObj.isLoading = "Load Failed...";
              // console.log(currObj.errors);
            }
          });
      } else {
        this.isLoading = "Loading all Data";
        this.fetchCategories();
      }
    }, //end of autoCOmpleteTable

    fetchDeliveryNotes(page_url) {
      this.$Progress.start();
      this.isLoading = "Loading all Data";
      page_url = page_url || "/api/deliverynotes";
      let vm = this;
      axios
        .get(page_url)
        .then(function (response) {
          vm.deliverynotes = response.data.data;
          vm.isLoading = "";
          if (vm.deliverynotes.length != null) {
            vm.makePagination(response.data.meta, response.data.links);
            vm.$Progress.finish();
            vm.isLoading = "";
          }
        })
        .catch(function (error) {
          vm.$Progress.fail();
        });
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        from_page: meta.from,
        to_page: meta.to,
        total_pages: meta.total,
        path_page: meta.path + "?page=",
        first_link: links.first,
        last_link: links.last,
        prev_link: links.prev,
        next_link: links.next,
      };
      this.pagination = pagination;
    },

    showAddModal() {
      this.modalForName = "Add DeliveryNote";
      // Vue.set(this.modalForName,"Add DeliveryNote");
      this.modalForCode = 0; //0 for add
      // this.deliverynote.name = "";
      // this.deliverynote.description = "";
      this.errors = ""; //clearing errors
      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-deliverynote");
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addDeliveryNote();
        // console.log("Add DeliveryNote");
      } else if (this.modalForCode == 1) {
        this.updateDeliveryNote();
        // console.log("Edit DeliveryNote");
      }
    },
    addDeliveryNote() {
      this.$Progress.start();
      let currObj = this;
      axios
        .post("/api/deliverynotes", this.deliverynotes)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);

          currObj.$bvModal.hide("bv-modal-add-deliverynotes");

          currObj.errors = ""; //clearing errors

          currObj.deliverynotes.name = "";
          currObj.deliverynotes.description = "";
          currObj.$Progress.finish();

          currObj.fetchDeliveryNotes();
        })
        .catch(function (error) {
          currObj.$Progress.fail();

          if (error.response.status == 422) {
            currObj.validationErrors = error.response.data.errors;
            currObj.errors = currObj.validationErrors;
            // console.log(currObj.errors);
          }
        });
    },

    editDeliveryNote(id) {
      this.$Progress.start();
      // //this.$Progress.start();
      this.modalForName = "Edit DeliveryNote";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-deliverynote");
      this.errors = ""; //clearing errors
      axios
        .get("/api/deliverynotes/" + id)
        .then((response) => {
          // console.log(response.data.deliverynote)
          Vue.set(this.deliverynote, "name", response.data.deliverynote.name);
          Vue.set(
            this.deliverynote,
            "description",
            response.data.deliverynote.description
          );
          Vue.set(this.deliverynote, "id", id); //to send id to the update controller
          this.$Progress.finish();
        })
        .catch((error) => {
          // console.log(error)
          this.$Progress.fail();
        });
    },

    updateDeliveryNote() {
      this.$Progress.start();
      let currObj = this;
      let formData = new FormData();
      formData.append("_method", "PUT"); //add this otherwise data won't pass to backend
      formData.append("name", this.deliverynote.name);
      formData.append("description", this.deliverynote.description);
      formData.append("id", this.deliverynote.id);

      axios
        .post("/api/deliverynote", formData)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          // alert(currObj.status);

          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-deliverynote");

          currObj.deliverynote.name = "";
          currObj.deliverynote.description = "";
          currObj.errors = ""; //clearing errors
          currObj.$Progress.finish();

          currObj.fetchDeliveryNote();
        })
        .catch(function (error) {
          currObj.$Progress.fail();

          if (error.response.status == 422) {
            currObj.validationErrors = error.response.data.errors;
            currObj.errors = currObj.validationErrors;
            // console.log(currObj.errors);
          }
        });
    },

    deleteDeliveryNote(id) {
      this.$Progress.start();
      let currObj = this;
      this.$swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.value) {
          axios
            .delete("/api/deliverynote/" + id)
            .then(function (response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              // alert(currObj.status);

              let index_to_delete = currObj.deliverynotes.findIndex(
                (deliverynote) => deliverynote.id === id
              );
              currObj.deliverynotes.splice(index_to_delete, 1);
              currObj.$Progress.finish();
              // alert(currObj.status);
              currObj.$swal("Info", currObj.output, currObj.status);
            })
            .catch(function (error) {
              // currObj.output=error;
              // console.log(currObj.output);
              currObj.$Progress.fail();
            });
        }
      });
    }, //end of deleteDeliveryNote()

    deletefetchDeliveryNote(id) {
      this.$Progress.start();
      let currObj = this;
      this.$swal({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.value) {
          //delete code here
          axios
            .delete("/api/deliverynotes/" + id)

            .then(function (response) {
              // alert('Purchase Removed');
              currObj.output = response.data.msg;
              currObj.status = response.data.status;

              //will get index of that deliverynote to delete and delete only that particular deliverynote only, to reduce server load to refresh everytime when data changed on database from this particular frontend view ----for ex, this.fetchDeliveryNotes();
              let index_to_delete = currObj.deliverynotes.findIndex(
                (deliverynote) => deliverynote.id === id
              );
              //splice will delete that deliverynote from the array as specfied with index
              currObj.deliverynotes.splice(index_to_delete, 1);

              currObj.$Progress.finish();
              // alert(currObj.status);
              currObj.$swal("Info", currObj.output, currObj.status);
            })
            .catch(function (response) {
              currObj.$Progress.fail();
            });
        }
      });
    },

    hasPermission(action) {
      let permissions_from_store = this.$store.getters.permissions;

      if (
        permissions_from_store.includes(action) ||
        permissions_from_store.includes("all")
      ) {
        return true;
      } else {
        return false;
      }
    }, //has permision
  }, //end of methods
}; //end of default
</script>

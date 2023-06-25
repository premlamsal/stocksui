<template>
  <div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Pick Lists</h1>
    <p class="mb-4" v-if="hasPermission('add_pick_lists')">
      <button class="btn btn-primary" @click="showAddModal()">
        <span class="fa fa-plus-circle"></span>  New Pick List
      </button>
    </p>

    <!-- add picklist model start -->
    <b-modal id="bv-modal-add-picklist" hide-footer size="xl">
      <template v-slot:modal-title>
        {{ modalForName }}
      </template>
      <div class="d-block">
        <div class="card-body shadow">
          <div class="row">
            <div class="col-sm-4">
              <!-- <div class="form-group">
                <label>Pick List No. (auto generated)</label>
                {{ pick_list_number }}
              </div> -->

              <div class="form-group" style="position: relative">
                <label>Ship Name</label>
                <input
                  type="text"
                  v-model="info.ship_name"
                  class="form-control"
                />
                <span v-if="errors['info.ship_name']" :class="['errorText']">
                  {{ errors["info.ship_name"][0] }}
                  <br />
                </span>
              </div>

              <div class="form-group">
                <label>Ship Address</label>
                <textarea
                  v-model="info.ship_address"
                  class="form-control"
                ></textarea>
                <span
                  v-if="errors['info.ship_address']"
                  :class="['errorText']"
                  >{{ errors["info.ship_address"][0] }}</span
                >
              </div>
              <div></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <div class="form-group" style="position: relative">
                  <label>Sailing Date</label>
                  <date-picker
                    v-model="info.sailing_date"
                    :config="options"
                    :class="['form-control']"
                  ></date-picker>
                  <span
                    v-if="errors['info.sailing_date']"
                    :class="['errorText']"
                    >{{ errors["info.sailing_date"][0] }}</span
                  >
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <label>Date Requested</label>
                    <date-picker
                      v-model="info.date_requested"
                      :config="options"
                      :class="['form-control']"
                    ></date-picker>
                    <span
                      v-if="errors['info.date_requested']"
                      :class="['errorText']"
                      >{{ errors["info.date_requested"][0] }}</span
                    >
                  </div>
                  <div class="col-sm-6">
                    <label>Picked Date</label>
                    <date-picker
                      v-model="info.picked_date"
                      :config="options"
                      :class="['form-control']"
                    ></date-picker>
                    <span
                      v-if="errors['info.picked_date']"
                      :class="['errorText']"
                      >{{ errors["info.picked_date"][0] }}</span
                    >
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-1"></div>
            <div class="col-sm-3 mt-3" style="text-align:right">
              <div class="form-group mt-3"><h6>Picked By: .............................................</h6></div>
              <div class="form-group mt-3"><h6>Checked By: .........................................</h6></div>
              <!-- <div class="form-group mt-3"><h6>Missing: .............................................</h6></div> -->
            </div>
          </div>

          <div class="pick_list">
            <div class="pick_list-head">
              <div class="row">
                <div class="col-md-2">
                  <h6>Shelf</h6>
                </div>
                <div class="col-md-2">
                  <h6>Requested</h6>
                </div>
                <div class="col-md-2">
                  <h6>Picked</h6>
                </div>
                <div class="col-md-2">
                  <h6>Description</h6>
                </div>
                <div class="col-md-2">
                  <h6>Quantity Picked</h6>
                </div>
                <!-- <div class="col-md-2">
                  <h6>Pick List Number</h6>
                </div> -->
                <div class="col-md-2">
                  <h6>Action</h6>
                </div>
              </div>
            </div>
            <!-- end of pick_list head-->
            <div class="pick_list-body">
              <div
                class="pick_list-items"
                v-for="(item, index) in items"
                v-bind:key="item.id"
              >
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Shelf"
                        v-model="item.shelf"
                        :class="{
                          'is-invalid': errors['items.' + index + '.shelf'],
                        }"
                      />
                      <span
                        v-if="errors['items.' + index + '.shelf']"
                        :class="['errorText']"
                        >{{ errors["items." + index + ".shelf"][0] }}</span
                      >
                    </div>
                  </div>
                  <div class="col-md-2">
                    <input
                      type="number"
                      class="form-control"
                      placeholder="Requested"
                      v-model="item.requested"
                      :class="{
                        'is-invalid': errors['items.' + index + '.requested'],
                      }"
                    />
                  </div>
                  <div class="col-md-2">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Picked"
                      v-model="item.picked"
                      :class="{
                        'is-invalid': errors['items.' + index + '.picked'],
                      }"
                    />
                  </div>
                  <div class="col-md-2">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Description"
                      v-model="item.description"
                      :class="{
                        'is-invalid': errors['items.' + index + '.description'],
                      }"
                    />
                  </div>
                  <div class="col-md-2">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Quantity Picked"
                      v-model="item.quantity_picked"
                      :class="{
                        'is-invalid':
                          errors['items.' + index + '.quantity_picked'],
                      }"
                    />
                  </div>
                  <div class="col-md-2">
                    <button
                      href
                      class="btn btn-danger"
                      style="border: none"
                      @click="removeLine(index)"
                    >
                      <span
                        class="nc-icon nc-simple-remove"
                        style="font-size: 15px"
                      ></span>
                    </button>
                  </div>
                </div>
              </div>
              <!-- end of pick_list items-->
            </div>
            <!-- end of pick_list body-->
            <div class="pick_list-foot">
              <div class="row">
                <div class="col-md-2">
                  <button
                    class="table-add_line btn btn-primary"
                    @click="addNewLine"
                  >
                    <span class="fa fa-plus-circle"></span>
                  </button>
                </div>
                <div class="col-md-4">
                  <div style="text-align:right">
                  <h6>Total Quantity To Pick: ..................</h6>
                  <h6>Quantity Picked: ...................</h6>
                </div>
              </div>

                <div class="col-md-4"></div>
                <div class="col-md-4"></div>
              </div>
            </div>
            <!-- end of pick_list foot -->
          </div>
          <!-- end of pick_list -->
        </div>
      </div>
      <b-button class="btn-primary mt-3" block @click="callFunc">{{
        modalForName
      }}</b-button>
    </b-modal>
    <!-- add picklist modal end-->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6
          class="m-0 font-weight-bold text-primary"
          style="display: inline-block"
        >
          Pick List
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
              file-name="picklists"
              :fields="picklists_export_fileds"
              :data="picklists"
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
      <div class="card-body" v-if="picklists.length > 0">
        <div class="table">
          <table
            class="table table-striped table-brequested"
            width="100%"
            cellspacing="0"
          >
            <thead>
              <tr>
                <th>Pick List No.</th>
                <th>Ship Name</th>
                <th>Sailing Date</th>
                <th>Picked Date</th>
                <!-- <th>Status</th> -->
                <th>Date Request</th>
                <th>Edit</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="picklist in picklists" v-bind:key="picklist.id">
                <td>{{ picklist.custom_pick_list_id }}</td>
                <td> {{ picklist.ship_name }}</td>
                <td>{{ picklist.sailing_date}}</td>
                <td>
                  {{ picklist.picked_date}}
                </td>
                <td>
                  {{ picklist.date_requested }}
                </td>
               

                <!--
               <td v-if="(picklist.status==='Paid')">
                       <toggle-button v-bind:status="true" @statusChanges ="updateStatus($event,picklist.id)"/> 
                </td>
                
                <td v-else-if="(picklist.status==='To Pay')">
                       <toggle-button v-bind:status="false" @statusChanges ="updateStatus($event,picklist.id)"/> 
                </td>
                -->

                <td>
                  <button
                    class="btn btn-outline-primary custom_btn_table"
                    v-if="hasPermission('show_pick_list')"
                    @click="downloadPickListPDF(picklist.id)"
                  >
                    <span class="fa fa-align-justify custom_icon_table"></span>
                  </button>
                  <button
                    class="btn btn-success custom_btn_table"
                    v-if="hasPermission('edit_pick_list')"
                    @click="editPickList(picklist.id)"
                  >
                    <span class="fa fa-edit custom_icon_table"></span>
                  </button>

                  <button
                    class="btn btn-danger custom_btn_table"
                    v-if="hasPermission('delete_pick_list')"
                    @click="deletePickList(picklist.id)"
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
                  @click="fetchPickLists(pagination.first_link)"
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
                  @click="fetchPickLists(pagination.prev_link)"
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
                  @click="fetchPickLists(pagination.path_page + n)"
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
                  @click="fetchPickLists(pagination.next_link)"
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
                  @click="fetchPickLists(pagination.last_link)"
                  class="page-link"
                >
                  Last
                </button>
              </li>
            </ul>
          </div>
          <div class="col-md-4">
            Page: {{ pagination.current_page }}-{{ pagination.last_page }} Total
            Pick Lists: {{ pagination.total_pages }}
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
      id: "",
      items: [
        {
          shelf: "",
          requested: "1",
          picked: "",
          description: "",
          quantity_picked: "",
          changed: true,
        },
      ],

      cloneItems: [
        {
          shelf: "",
          requested: "1",
          picked: "",
          description: "",
          quantity_picked: "",
          changed: false,
        },
      ],
      pick_list_number: "",
      info: {},
      store: {},
      errors: [],
      tempCustomPickListID: "",
      showProductSuggestion: false,
      options: {
        format: "DD-MM-YYYY",
        useCurrent: true,
        showClear: true,
        showClose: true,
      },

      picklists: [],
      picklists_id: "",
      pagination: {},
      edit: false,
      searchTableKey: "",
      tempStatus: {},
      modalForName: "",
      modalForCode: 0,

      isLoading: "",
      picklists_export_fileds: [
        "id",
        "ship_name",
        "ship_address",
        "sailing_date",
        "picked_date",
        "date_requested",

      ],
    };
  },
  created() {
    this.fetchPickLists();
    this.fetchStore();
  },

  methods: {
    clearPickListInput() {
      this.pick_list_number = "";
      this.info = {};
      this.store = {};
      this.queryResult = [];
      this.errors = [];
      this.tempCustomPickListID = "";
      this.showProductSuggestion = false;
      this.pick_lists = [];
      this.picklists_id = "";
      this.id = "";

      this.items = [
        {
          shelf: "",
          requested: "1",
          picked: "",
          description: "",
          quantity_picked: "",
          changed: true,
        },
      ];
      this.cloneItems = [
        {
          shelf: "",
          requested: "1",
          picked: "",
          description: "",
          quantity_picked: "",
          changed: false,
        },
      ];
    },
    downloadPickListPDF(id) {
      console.log("download-btn-pressed");
      axios
        .get(`api/picklistpdf/${id}`, {
          responseType: "blob",
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "picklist.pdf"); //or any other extension
          document.body.appendChild(link);
          link.click();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchStore() {
      let currObj = this;
      this.isLoading = "Loading Data";

      axios
        .get("api/store")

        .then(function (response) {
          // Vue.set(currObj.store, "id", response.data.store.id);
          currObj.store.id = response.data.store.id;

          Vue.set(
            currObj.store,
            "pick_list_id_count",
            response.data.store.pick_list_id_count
          );

          currObj.pick_list_number =
            currObj.store.pick_list_id_count.split("-");

          currObj.pick_list_number[1] =
            parseInt(currObj.pick_list_number[1]) + 1;

          currObj.pick_list_number = currObj.pick_list_number.join("-");
          console.log(currObj.pick_list_number);

          currObj.isLoading = "";
        });
    }, //

    addNewLine() {
      this.items.push({
        shelf: "",
        requested: "1",
        picked: "",
        description: "",
        quantity_picked: "",
        changed: true,
      });

      this.cloneItems.push({
        shelf: "",
        requested: "1",
        picked: "",
        description: "",
        quantity_picked: "",
        changed: false,
      });
    }, // end of add new line
    removeLine(index) {
      // this.pick_lists.remove();
      if (index != 0) {
        this.items.splice(index, 1);
        this.cloneItems.splice(index, 1);
      } else {
        // alert('You can\'t delete this');
        this.$toast.error({
          title: "Opps!!",
          message: "You can't delete this.",
        });
      }
    }, //end of removeLine function

    autoComplete: _.debounce(function () {
      let currObj = this;
      if (this.info.ship_name === "") {
        this.queryResults = new Array();
        this.info.supplier_short_name = "";
        this.info.supplier_id = null;
        this.info.pick_list_reference_number = "";
      } else {
        axios
          .post("api/suppliers/search", {
            searchQuery: this.info.ship_name,
          })
          .then((response) => {
            this.queryResults = response.data.data;
          })
          .catch((error) => {
            if (error.response.status == 422) {
              currObj.validationErrors = error.response.data.errors;
              currObj.errors = currObj.validationErrors;
              // console.log(currObj.errors);
              currObj.$toast.error({
                title: "Opps!!",
                message: error.response.data.message,
              });
            }
          });
      }
    }, 300),

    //will find item exits in that items array or not
    //used to elimate duplicate produt/item in items/products
    hasItem(key) {
      if (this.items.find((item) => item.stock_id === key)) {
        return true;
      } else {
        return false;
      }
    },

    displayToastErrorMessage(title, message) {
      this.$toast.error({
        title: title,
        message: message,
      });
    },

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
          .post("/api/picklistss/search", {
            searchQuery: this.searchTableKey,
          })
          .then(function (response) {
            currObj.isLoading = "";

            currObj.picklistss = response.data.data;

            console.log(currObj.picklistss);

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

    fetchPickLists(page_url) {
      this.$Progress.start();
      this.isLoading = "Loading all Data";
      page_url = page_url || "/api/picklists";
      let vm = this;
      axios
        .get(page_url)
        .then(function (response) {
          vm.picklists = response.data.data;
          vm.isLoading = "";
          if (vm.picklists.length != null) {
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
      this.modalForName = "Add PickList";
      // Vue.set(this.modalForName,"Add PickList");
      this.modalForCode = 0; //0 for add
      // this.picklist.name = "";
      // this.picklist.description = "";
      this.errors = ""; //clearing errors
      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-picklist");
      this.clearPickListInput();
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addPickList();
        // console.log("Add PickList");
      } else if (this.modalForCode == 1) {
        this.updatePickList();
        // console.log("Edit PickList");
      }
    },

    addPickList() {
      //Add
      this.info.status = "To Pay";
      let currObj = this;
      axios
        .post("/api/picklist", { info: this.info, items: this.items })
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-picklist");
          currObj.fetchPickLists();
          currObj.errors = ""; //clearing errors
          currObj.clearPickListInput();
        })
        .catch(function (error) {
          if (error.response.status == 422) {
            currObj.validationErrors = error.response.data.errors;
            currObj.errors = currObj.validationErrors;
            // console.log(currObj.errors);
            currObj.$toast.error({
              title: "Opps!!",
              message: error.response.data.message,
            });
          }
        });
    },

    editPickList(id) {
      this.$Progress.start();
      this.clearPickListInput();
      let matches;
      let tempIDS = "";
      let currObj = this;
      this.modalForName = "Edit Pick List";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-picklist");
      currObj.errors = ""; //clearing errors
      axios
        .get("/api/picklist/" + id)
        .then(function (response) {
          Vue.set(currObj.info, "pick_list_no", response.data.pick_list.id),
          Vue.set(
              currObj.info,
              "ship_name",
              response.data.pick_list.ship_name
            ),
            Vue.set(
              currObj.info,
              "ship_address",
              response.data.pick_list.ship_address
            ),
            Vue.set(
              currObj.info,
              "sailing_date",
              response.data.pick_list.sailing_date
            ),
            Vue.set(
              currObj.info,
              "date_requested",
              response.data.pick_list.date_requested
            ),

            Vue.set(
              currObj.info,
              "picked_date",
              response.data.pick_list.picked_date
            ),
            Vue.set(
              currObj.info,
              "custom_pick_list_id",
              response.data.pick_list.custom_pick_list_id
            ),
         
            Vue.set(currObj.info, "status", response.data.pick_list.status);
          let items = response.data.pick_list.pick_list_detail;

          // veu.set will make data reactive and updated
          // Vue.set(currObj, "items",items),
          // Vue.set(currObj, "cloneItems",items),

          for (let i = 0; i < items.length; i++) {
            currObj.items[i] = items[i];
          }
          for (let i = 0; i < items.length; i++) {
            currObj.cloneItems[i] = items[i];
          }

          currObj.$Progress.finish();
        })
        .catch(function (error) {
          if (error.response.status == 404) {
            currObj.$router.push({ name: "404" });
            currObj.$Progress.finish();
          }
        });
    },

    updatePickList() {
      let currObj = this;
      axios
        .put("/api/picklist", {
          info: this.info,
          items: this.items,
          id: this.info.pick_list_no,
        })
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-picklist");
          currObj.clearPickListInput();

          currObj.fetchPickLists();

          // currObj.errors = '';//clearing errors
        })
        .catch(function (error) {
          if (error.response.status == 422) {
            currObj.validationErrors = error.response.data.errors;
            currObj.errors = currObj.validationErrors;
            // console.log(currObj.errors);
            currObj.$toast.error({
              title: "Opps!!",
              message: error.response.data.message,
            });
          }
        });
    },

    deletePickList(id) {
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
            .delete("/api/picklist/" + id)
            .then(function (response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              // alert(currObj.status);

              currObj.fetchPickLists();
              currObj.$Progress.finish();
              // alert(currObj.status);
              currObj.$swal("Info", currObj.output, currObj.status);
            })
            .catch(function (error) {
              currObj.$Progress.fail();
              // currObj.output=error;
              // console.log(currObj.output);
            });
        }
      });
    }, //end of deleteUnit()

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

  computed: {
    //checks errors in the fields

    subTotal: function () {
      //reduce function is used to sum the array elements
      this.info.subTotal = this.items.reduce(function (carry, item) {
        return (
          carry + parseFloat(item.requested) * parseFloat(item.description)
        );
      }, 0);
      return this.info.subTotal;
    },

    grandTotal: function () {
      return this.subTotal;
    },
  }, //end of computed
}; //end of default
</script>
<style scoped>
.pick_list {
  margin-top: 5em;
}

.pick_list-body {
  margin-top: 2em;
  padding: 8px;
}

.pick_list-head {
  padding: 1em;
  /*border-bottom: 1px solid #eee;*/
  background: coral;
  color: white;
  box-shadow: 1px 7px 17px -12px;
}

.pick_list-foot {
  margin-top: 1em;
  padding: 2em;
  border-top: 1px solid #eee;
}

.datetime-picker {
}

.datetime-picker input {
  display: block;
  width: 100%;
  height: calc(1.5em + 0.75rem + 2px);
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.supplier-search-suggestion {
  background: #fff;
  position: absolute;
  overflow-y: scroll;
  height: auto;
  max-height: 9em;
  color: #000;
  border: 1px solid #e2dfdf;
  border-top: 0px;
  width: 100%;
  box-shadow: 1px 7px 17px -12px;
  border-radius: 4px;
}

.supplier-search-suggestion-inner {
  padding: 1px;
  border-top: 1px solid #d6d6d6;
}

.supplier-search-suggestion-inner ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.supplier-search-suggestion-inner li {
  cursor: pointer;
  padding: 10px;
}

.supplier-search-suggestion-inner li:hover {
  background: #007bff;
  color: #fff;
}

.supplier-search-suggestion::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  background-color: #f5f5f5;
}

.supplier-search-suggestion::-webkit-scrollbar {
  width: 6px;
  background-color: #f5f5f5;
}

.supplier-search-suggestion::-webkit-scrollbar-thumb {
  background-color: #000000;
}

.product-search-suggestion-pick_list {
  position: absolute;
  /* background: #f4f3ef; */
  width: 100%;
  color: #212120;
  /* padding-right: 12px; */
  overflow-y: scroll;
  max-height: 9em;
  z-index: 1;
  box-shadow: 1px 7px 17px -12px;
  border-radius: 4px;
}

.product-search-suggestion-pick_list ul {
  list-style: none;
  margin: 0px;
  padding: 0px;
}

.product-search-suggestion-pick_list ul li {
  padding: 10px;
  cursor: pointer;
  background: #f4f3ef;
}

.product-search-suggestion-pick_list ul li:hover {
  background: #51cbce;
  color: white;
}

.product-search-suggestion-pick_list::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  background-color: #f5f5f5;
}

.product-search-suggestion-pick_list::-webkit-scrollbar {
  width: 6px;
  background-color: #f5f5f5;
}

.product-search-suggestion-pick_list::-webkit-scrollbar-thumb {
  background-color: #000000;
}
</style>
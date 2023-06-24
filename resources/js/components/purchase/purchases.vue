<template>
  <div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Purchases</h1>
    <p class="mb-4" v-if="hasPermission('add_purchases')">
      <button class="btn btn-primary" @click="showAddModal()">
        Add Purchase Order
      </button>
    </p>

    <!-- add purchase model start -->
    <b-modal id="bv-modal-add-purchase" hide-footer size="xl">
      <template v-slot:modal-title>
        {{ modalForName }}
      </template>
      <div class="d-block">
        <div class="card-body shadow">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label>Purchase No. (auto generated)</label>
                {{ purchase_number }}
              </div>
              <div class="form-group" v-if="info.supplier_id != null">
                <label
                  >Actual Purchase Number(Reference Purchase ID)</label
                >
                <span
                  >{{ info.supplier_short_name }} -
                  {{ info.purchase_reference_number }}</span
                >
                <input
                  type="text"
                  v-model="info.purchase_reference_number"
                  class="form-control"
                />
                <span
                  v-if="errors['info.purchase_reference_number']"
                  :class="['errorText']"
                >
                  {{ errors["info.purchase_reference_number"][0] }}
                  <br />
                </span>
              </div>
              <div class="form-group" style="position: relative">
                <label>Supplier</label>
                <input
                  type="text"
                  v-model="info.supplier_name"
                  v-on:keyup="autoComplete"
                  class="form-control"
                />
                <span
                  v-if="errors['info.supplier_name']"
                  :class="['errorText']"
                >
                  {{ errors["info.supplier_name"][0] }}
                  <br />
                </span>
                <!-- Search suggestion block -->
                <div class="supplier-search-suggestion">
                  <div
                    v-for="queryResult in queryResults"
                    v-bind:key="queryResult.id"
                    class="supplier-search-suggestion-inner"
                  >
                    <ul>
                      <li
                        @click="
                          clickSearchSuggestion(
                            queryResult.id,
                            queryResult.name
                          )
                        "
                      >
                        {{ queryResult.name }}
                      </li>
                    </ul>
                  </div>
                </div>

                <!-- Search suggestion block ends -->
              </div>
              <div></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group"></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label>Notes</label>
                <textarea v-model="info.note" class="form-control"></textarea>
                <span v-if="errors['info.note']" :class="['errorText']">{{
                  errors["info.note"][0]
                }}</span>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <label>Purchase Date</label>
                  <date-picker
                    v-model="info.purchase_date"
                    :config="options"
                    :class="['form-control']"
                  ></date-picker>
                  <span
                    v-if="errors['info.purchase_date']"
                    :class="['errorText']"
                    >{{ errors["info.purchase_date"][0] }}</span
                  >
                </div>
                <div class="col-sm-6">
                  <label>Due Date</label>
                  <date-picker
                    v-model="info.due_date"
                    :config="options"
                    :class="['form-control']"
                  ></date-picker>
                  <span v-if="errors['info.due_date']" :class="['errorText']">{{
                    errors["info.due_date"][0]
                  }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="purchase">
            <div class="purchase-head">
              <div class="row">
                <div class="col-md-1">
                  <h6>ID</h6>
                </div>
                <div class="col-md-3">
                  <h6>Product Name</h6>
                </div>
                <div class="col-md-1">
                  <h6>Quanity</h6>
                </div>
                <div class="col-md-1">
                  <h6>Unit</h6>
                </div>
                <div class="col-md-2">
                  <h6>Price</h6>
                </div>
                <div class="col-md-2">
                  <h6>Total</h6>
                </div>
                <div class="col-md-2">
                  <h6>Action</h6>
                </div>
              </div>
            </div>
            <!-- end of purchase head-->
            <div class="purchase-body">
              <div
                class="purchase-items"
                v-for="(item, index) in items"
                v-bind:key="item.id"
              >
                <div class="row">
                  <div
                    class="col-md-1"
                    v-if="item.product.custom_product_id != null"
                  >
                    {{ item.product.custom_product_id }}
                  </div>
                  <div class="col-md-1" v-else>#</div>
                  <div class="col-md-3">
                    <div class="auto-complete-product-container">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Product Name"
                          v-model="item.product_name"
                          v-on:keydown="autoCompleteProduct(index)"
                          :class="{
                            'is-invalid':
                              errors['items.' + index + '.product_name'],
                          }"
                        />
                        <span
                          v-if="errors['items.' + index + '.product_name']"
                          :class="['errorText']"
                          >{{
                            errors["items." + index + ".product_name"][0]
                          }}</span
                        >
                        <!--  suggestion block -->
                        <div
                          class="product-search-suggestion-purchase"
                          v-for="queryResultsProduct in queryResultsProducts[
                            index
                          ]"
                          v-bind:key="queryResultsProduct.id"
                        >
                          <ul>
                            <li
                              v-for="queryResultsProduct in queryResultsProducts[
                                index
                              ]"
                              v-bind:key="queryResultsProduct.id"
                              @click="
                                clickSearchProductSuggestion(
                                  queryResultsProduct.id,
                                  queryResultsProduct.product.id,
                                  queryResultsProduct.product.custom_product_id,
                                  queryResultsProduct.product.name,
                                  queryResultsProduct.product.unit.id,
                                  queryResultsProduct.price,
                                  index
                                )
                              "
                            >
                              {{ queryResultsProduct.product.name }} --
                              {{ queryResultsProduct.quantity }}
                              {{ queryResultsProduct.product.unit.short_name }}
                              -- Rs. {{ queryResultsProduct.price }}
                            </li>
                          </ul>
                        </div>
                        <!--  <span v-if="errors['items.' + index + '.product_name']">
                      {{ errors['items.' + index + '.product_name'] }}
                    </span> -->
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <input
                      type="number"
                      class="form-control"
                      placeholder="Quantity"
                      v-model="item.quantity"
                      :class="{
                        'is-invalid': errors['items.' + index + '.quantity'],
                      }"
                    />
                  </div>
                  <div class="col-md-1">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Unit"
                      v-model="item.unit"
                      :class="{
                        'is-invalid': errors['items.' + index + '.unit'],
                      }"
                    />
                  </div>
                  <div class="col-md-2">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Enter the price"
                      v-model="item.price"
                      v-if="item.product_id"
                      :class="{
                        'is-invalid': errors['items.' + index + '.price'],
                      }"
                    />
                  </div>
                  <div class="col-md-2">
                    <span class="table-text">{{
                      item.quantity * item.price
                    }}</span>
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
              <!-- end of purchase items-->
            </div>
            <!-- end of purchase body-->
            <div class="purchase-foot">
              <div class="row">
                <div class="col-md-2">
                  <button
                    class="table-add_line btn btn-primary"
                    @click="addNewLine"
                  >
                    <span class="fa fa-plus-circle"></span>
                  </button>
                </div>
                <div class="col-md-2">
                  <h6>Grand Total</h6>
                  {{ grandTotal }}
                </div>

                <div class="col-md-2">
                  <h6>SubTotal</h6>
                  {{ subTotal }}
                </div>
                <div class="col-md-2"></div>
              </div>
            </div>
            <!-- end of purchase foot -->
          </div>
          <!-- end of purchase -->
        </div>
      </div>
      <b-button class="btn-primary mt-3" block @click="callFunc">{{
        modalForName
      }}</b-button>
    </b-modal>
    <!-- add purchase modal end-->


   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6
          class="m-0 font-weight-bold text-primary"
          style="display: inline-block"
        >
          Purchase
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
              file-name="purchases"
              :fields="purchases_export_fileds"
              :data="purchases"
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
      <div class="card-body" v-if="purchases.length > 0">
        <div class="table">
          <table
            class="table table-striped table-bordered"
            width="100%"
            cellspacing="0"
          >
            <thead>
              <tr>
                <th>Purchase No.</th>
                <th>Grand Total</th>
                <th>Supplier</th>
                <th>Date</th>
                <th>Due Date</th>
                <!-- <th>Status</th> -->
                <th>Modify</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="purchase in purchases"
                v-bind:key="purchase.id"
              >
                <td>{{ purchase.custom_purchase_id }}</td>
                <td>Rs. {{ purchase.grand_total }}</td>
                <td>{{ purchase.supplier_name }}</td>
                <td>
                  {{ purchase.purchase_date | moment("from", "now") }}
                </td>
                <td>
                  <span
                    v-if="
                      purchase.purchase_date === purchase.due_date
                    "
                    class="bg-danger text-white p-2"
                    >{{ purchase.due_date | moment("from", "now") }}</span
                  >
                  <span v-else class="bg-success text-white p-2">{{
                    purchase.due_date | moment("from", "now")
                  }}</span>
                </td>

                <!--
               <td v-if="(purchase.status==='Paid')">
                       <toggle-button v-bind:status="true" @statusChanges ="updateStatus($event,purchase.id)"/> 
                </td>
                
                <td v-else-if="(purchase.status==='To Pay')">
                       <toggle-button v-bind:status="false" @statusChanges ="updateStatus($event,purchase.id)"/> 
                </td>
                -->

                <td>
                  <button
                    class="btn btn-primary custom_btn_table"
                    v-if="hasPermission('show_purchase')"
                    @click="downloadPurchasePDF(purchase.id)"
                  >
                    <span class="fa fa-align-justify custom_icon_table"></span>
                  </button>
                  <button
                    class="btn btn-success custom_btn_table"
                    v-if="hasPermission('edit_purchase')"
                    @click="editPurchase(purchase.id)"
                  >
                    <span class="fa fa-edit custom_icon_table"></span>
                  </button>

                  <button
                    class="btn btn-outline-danger custom_btn_table"
                    v-if="hasPermission('delete_purchase')"
                    @click="deletePurchase(purchase.id)"
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
                  @click="fetchPurchases(pagination.first_link)"
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
                  @click="fetchPurchases(pagination.prev_link)"
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
                  @click="fetchPurchases(pagination.path_page + n)"
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
                  @click="fetchPurchases(pagination.next_link)"
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
                  @click="fetchPurchases(pagination.last_link)"
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
      id: "",
      items: [
        {
          product_name: "",
          price: "0",
          quantity: "1",
          line_total: "",
          changed: true,
          product: {},
        },
      ],

      cloneItems: [
        {
          product_name: "",
          price: "0",
          quantity: "1",
          line_total: "",
          changed: false,
          product: {},
        },
      ],
      purchase_number: "",
      info: {},
      store: {},

      supplier: {},
      queryResults: [],
      queryResultsProducts: [],
      errors: [],
      tempCustomPurchaseID: "",
      showProductSuggestion: false,
      options: {
        format: "DD-MM-YYYY",
        useCurrent: true,
        showClear: true,
        showClose: true,
      },

      purchases: [],
      purchases_id: "",
      pagination: {},
      edit: false,
      searchTableKey: "",
      tempStatus: {},
      modalForName: "",
      modalForCode: 0,

      isLoading: "",
      purchases_export_fileds: [
        "grand_total",
        "customer_name",
        "status",
        "due_date",
      ],
    };
  },
  created() {
    this.fetchPurchases();
    this.fetchStore();
  },

  methods: {
    clearPurchaseInput() {
      this.purchase_number = "";
      this.info = {};
      this.store = {};
      this.supplier = {};
      this.queryResult = [];
      this.queryResultsProducts = [];
      this.errors = [];
      this.tempCustomPurchaseID = "";
      this.showProductSuggestion = false;
      // this.purchases = [];
      this.purchases_id = "";
      this.id = "";

      this.items = [
        {
          product_name: "",
          price: "0",
          quantity: "1",
          line_total: "",
          changed: true,
          product: {},
        },
      ];
      this.cloneItems = [
        {
          product_name: "",
          price: "0",
          quantity: "1",
          line_total: "",
          changed: false,
          product: {},
        },
      ];
    },
    downloadPurchasePDF(id) {
      console.log('download-btn-pressed')
      axios
        .get(`api/purchasepdf/${id}`, {
          responseType: "blob",
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "purchase.pdf"); //or any other extension
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
            "purchase_id_count",
            response.data.store.purchase_id_count
          );

          currObj.purchase_number =
            currObj.store.purchase_id_count.split("-");

          currObj.purchase_number[1] =
            parseInt(currObj.purchase_number[1]) + 1;

          currObj.purchase_number = currObj.purchase_number.join("-");
          console.log(currObj.purchase_number);

          currObj.isLoading = "";
        });
    }, //

    addNewLine() {
      this.items.push({
        product_name: "",
        price: "0",
        quantity: "1",
        product: {
          custom_product_id: "",
          unit: {},
        },
        line_total: "",
        changed: false,
      });

      this.cloneItems.push({
        product_name: "",
        price: "0",
        quantity: "1",
        product: {
          custom_product_id: "",
          unit: {},
        },
        line_total: "",
        changed: false,
      });
    }, // end of add new line
    removeLine(index) {
      // this.purchases.remove();
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

    calLineTotal(index) {
      // alert(this.purchases[index].price);
      this.items[index].line_total =
        this.items[index].price * this.items[index].quantity;
      this.cloneItems[index].line_total = this.items[index].line_total;
    },

    autoComplete: _.debounce(function () {
      let currObj = this;
      if (this.info.supplier_name === "") {
        this.queryResults = new Array();
        this.info.supplier_short_name = "";
        this.info.supplier_id = null;
        this.info.purchase_reference_number = "";
      } else {
        axios
          .post("api/suppliers/search", {
            searchQuery: this.info.supplier_name,
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

    autoCompleteProduct: _.debounce(function (index) {
      if (this.items[index].product_name === "") {
        this.queryResultsProducts = new Array();
        this.showProductSuggestion = false;
      } else {
        axios
          .post("/api/products/search", {
            searchQuery: this.items[index].product_name,
          })
          .then((response) => {
            this.queryResultsProducts[index] = response.data.data;
            if (this.queryResultsProducts[index].length > 0) {
              this.showProductSuggestion = true;
            } else {
              this.showProductSuggestion = false;
            }
          })
          .catch((error) => {
            // if (error.response.status) {
            //   this.errors = error.response.data.errors;
            //   console.log(this.errors);
            // }
          });
      }
      // alert(this.items[index].product_name);
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

    clickSearchProductSuggestion(
      stock_id,
      product_id,
      custom_product_id,
      product_name,
      unit_id,
      cp,
      index
    ) {
      if (!this.hasItem(stock_id)) {
        // console.log("Item not in List. So adding");
        Vue.set(this.items[index], "product_id", product_id);

        Vue.set(this.items[index], "custom_product_id", custom_product_id);

        Vue.set(this.items[index], "product_name", product_name);

        Vue.set(this.items[index], "unit_id", unit_id);

        Vue.set(this.items[index], "stock_id", stock_id);

        Vue.set(this.items[index], "price", parseFloat(cp));

        Vue.set(this.cloneItems[index], "product_id", product_id);

        Vue.set(this.cloneItems[index], "custom_product_id", custom_product_id);

        Vue.set(this.cloneItems[index], "product_name", product_name);

        Vue.set(this.cloneItems[index], "unit_id", unit_id);

        Vue.set(this.items[index], "stock_id", stock_id);

        Vue.set(this.cloneItems[index], "price", parseFloat(cp));

        // this.items[index] = this.items[index] + (this.store.profit_percentage)/100;

        // console.log(product_id);
        // console.log(product_name);
        // console.log(index);
        this.queryResultsProducts = new Array();
      } else {
        // console.log("Item exits in list so deleting the current index item to remove duplicate entry in items array");
        this.displayToastErrorMessage(
          "Opps",
          product_name +
            " already on the list. You can increase the quantity or choose different stock "
        );

        this.items.splice(index);

        this.cloneItems.splice(index);

        this.queryResultsProducts = new Array();
      }
    },
    clickSearchSuggestion(supplier_id, supplier_name) {
      Vue.set(this.info, "supplier_id", supplier_id);
      Vue.set(this.info, "supplier_name", supplier_name);
      this.queryResults = null;

      let matches = supplier_name.match(/\b(\w)/g);
      this.tempCustomPurchaseID = matches.join("");
      this.tempCustomPurchaseID =
        this.tempCustomPurchaseID + "-" + supplier_id;
      this.info.supplier_short_name = this.tempCustomPurchaseID;
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
          .post("/api/purchasess/search", {
            searchQuery: this.searchTableKey,
          })
          .then(function (response) {
            currObj.isLoading = "";

            currObj.purchasess = response.data.data;

            console.log(currObj.purchasess);

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

    fetchPurchases(page_url) {
      this.$Progress.start();
      this.isLoading = "Loading all Data";
      page_url = page_url || "/api/purchases";
      let vm = this;
      axios
        .get(page_url)
        .then(function (response) {
          vm.purchases = response.data.data;
          vm.isLoading = "";
          if (vm.purchases.length != null) {
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
      this.modalForName = "Add Purchase";
      // Vue.set(this.modalForName,"Add Purchase");
      this.modalForCode = 0; //0 for add
      // this.purchase.name = "";
      // this.purchase.description = "";
      this.errors = ""; //clearing errors
      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-purchase");
      this.clearPurchaseInput();
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addPurchase();
        // console.log("Add Purchase");
      } else if (this.modalForCode == 1) {
        this.updatePurchase();
        // console.log("Edit Purchase");
      }
    },

    addPurchase() {
      //Add
      this.info.status = "To Pay";
      let currObj = this;
      axios
        .post("/api/purchase", { info: this.info, items: this.items })
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-purchase");
          currObj.fetchPurchases();
          currObj.errors = ""; //clearing errors
          currObj.clearPurchaseInput();
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
  
    editPurchase(id) {
      this.$Progress.start();
      this.clearPurchaseInput();
      let matches;
      let tempIDS = "";
      let currObj = this;
      this.modalForName = "Edit Purchase";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-purchase");
      currObj.errors = ""; //clearing errors
      axios
        .get("/api/purchase/" + id)
        .then(function (response) {
          Vue.set(
            currObj.info,
            "purchase_no",
            response.data.purchase.id
          ),
            Vue.set(currObj.info, "note", response.data.purchase.note),
            Vue.set(
              currObj.info,
              "custom_purchase_id",
              response.data.purchase.custom_purchase_id
            ),
            Vue.set(currObj.info, "title", response.data.purchase.title),
            Vue.set(
              currObj.info,
              "supplier_id",
              response.data.purchase.supplier_id
            ),
            Vue.set(
              currObj.info,
              "supplier_name",
              response.data.purchase.supplier_name
            ),
            Vue.set(
              currObj.info,
              "due_date",
              response.data.purchase.due_date
            ),
            Vue.set(
              currObj.info,
              "purchase_date",
              response.data.purchase.purchase_date
            ),
            (tempIDS = response.data.purchase.purchase_reference_id),
            (tempIDS = tempIDS.split("-")),
            Vue.set(currObj.info, "purchase_reference_number", tempIDS[2]),
            // console.log(tempIDS[2])
            currObj.clickSearchSuggestion(
              response.data.purchase.supplier_id,
              response.data.purchase.supplier_name
            ),
            Vue.set(
              currObj.info,
              "purchase",
              response.data.purchase.due_date
            ),
            Vue.set(currObj.info, "status", response.data.purchase.status);
          let items = response.data.purchase.purchase_detail;

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

    updatePurchase() {
      let currObj = this;
      axios
        .put("/api/purchase", {
          info: this.info,
          items: this.items,
          id: this.info.purchase_no,
        })
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-purchase");
          currObj.clearPurchaseInput();

          currObj.fetchPurchases();

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

    deletePurchase(id) {
      this.$Progress.start();
      let currObj = this;
      this.$swal({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {

        if (result.value) {
          axios.delete('/api/purchase/' + id)
            .then(function(response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              // alert(currObj.status);
             
             currObj.fetchPurchases();
              currObj.$Progress.finish();
              // alert(currObj.status);
              currObj.$swal("Info", currObj.output, currObj.status);

            }).catch(function(error) {
              currObj.$Progress.fail();
              // currObj.output=error;
              // console.log(currObj.output);
            })

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
        return carry + parseFloat(item.quantity) * parseFloat(item.price);
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
.purchase {
  margin-top: 5em;
}

.purchase-body {
  margin-top: 2em;
  padding: 8px;
}

.purchase-head {
  padding: 1em;
  /*border-bottom: 1px solid #eee;*/
  background: coral;
  color: white;
  box-shadow: 1px 7px 17px -12px;
}

.purchase-foot {
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

.product-search-suggestion-purchase {
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

.product-search-suggestion-purchase ul {
  list-style: none;
  margin: 0px;
  padding: 0px;
}

.product-search-suggestion-purchase ul li {
  padding: 10px;
  cursor: pointer;
  background: #f4f3ef;
}

.product-search-suggestion-purchase ul li:hover {
  background: #51cbce;
  color: white;
}

.product-search-suggestion-purchase::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  background-color: #f5f5f5;
}

.product-search-suggestion-purchase::-webkit-scrollbar {
  width: 6px;
  background-color: #f5f5f5;
}

.product-search-suggestion-purchase::-webkit-scrollbar-thumb {
  background-color: #000000;
}
</style>

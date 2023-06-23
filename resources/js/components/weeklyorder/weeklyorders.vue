<template>
  <div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Weekly Orders</h1>
    <p class="mb-4" v-if="hasPermission('add_weeklyorders')">
      <button class="btn btn-primary" @click="showAddModal()">
        Add Weekly Order
      </button>
    </p>

    <!-- add weeklyorder model start -->
    <b-modal id="bv-modal-add-weeklyorder" hide-footer size="xl">
      <template v-slot:modal-title>
        {{ modalForName }}
      </template>
      <div class="d-block">
        <div class="card-body shadow">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label>Weekly Order No. (auto generated)</label>
                {{ weeklyorder_number }}
              </div>
              <div class="form-group" style="position: relative">
                <label>Boat Name</label>
                <input
                  type="text"
                  v-model="info.boat_name"
                  class="form-control"
                />
                <span v-if="errors['info.boat_name']" :class="['errorText']">
                  {{ errors["info.boat_name"][0] }}
                  <br />
                </span>
              </div>
              <div></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group"></div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label></label>
                <textarea v-model="info.note" class="form-control"></textarea>
                <span v-if="errors['info.note']" :class="['errorText']">{{
                  errors["info.note"][0]
                }}</span>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <label>Date Order Requested</label>
                  <date-picker
                    v-model="info.date_order_requested"
                    :config="options"
                    :class="['form-control']"
                  ></date-picker>
                  <span
                    v-if="errors['info.date_order_requested']"
                    :class="['errorText']"
                    >{{ errors["info.date_order_requested"][0] }}</span
                  >
                </div>
                <div class="col-sm-6">
                  <label>Delivery Date</label>
                  <date-picker
                    v-model="info.delivery_date"
                    :config="options"
                    :class="['form-control']"
                  ></date-picker>
                  <span
                    v-if="errors['info.delivery_date']"
                    :class="['errorText']"
                    >{{ errors["info.delivery_date"][0] }}</span
                  >
                </div>
              </div>
            </div>
          </div>

          <div class="weeklyorder">
            <div class="weeklyorder-head">
              <div class="row" style="text-align: center">
                <div class="col-md-2">
                  <h6>SHELF CODE</h6>
                </div>
                <div class="col-md-3">
                  <h6>Product Name</h6>
                </div>
                <div class="col-md-2">
                  <h6>Quantity</h6>
                </div>

                <div class="col-md-2">
                  <h6>Picked</h6>
                </div>
                <div class="col-md-2">
                  <h6>Checked</h6>
                </div>

                <div class="col-md-1">
                  <h6>Remove</h6>
                </div>
              </div>
            </div>
            <!-- end of weeklyorder head-->
            <div class="weeklyorder-body">
              <div class="items-block-asp">
                <h3 class="head-pip">CLEANING PRODUCTS</h3>

                <div
                  class="weeklyorder-cleaning_products"
                  v-for="(item, index) in cleaning_products"
                  v-bind:key="item.id"
                >
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Shelf Code"
                          v-model="item.shelf_code"
                          :class="{
                            'is-invalid':
                              errors['item.' + index + '.shelf_code'],
                          }"
                        />
                        <span
                          v-if="errors['item.' + index + '.shelf_code']"
                          :class="['errorText']"
                          >{{
                            errors["item." + index + ".shelf_code"][0]
                          }}</span
                        >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="auto-complete-product-container">
                        <div class="form-group">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Product Name"
                            v-model="item.product_name"
                            :class="{
                              'is-invalid':
                                errors['item.' + index + '.product_name'],
                            }"
                          />
                          <span
                            v-if="errors['item.' + index + '.product_name']"
                            :class="['errorText']"
                            >{{
                              errors["item." + index + ".product_name"][0]
                            }}</span
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <input
                        type="number"
                        class="form-control"
                        placeholder="Quantity"
                        v-model="item.quantity"
                        :class="{
                          'is-invalid': errors['item.' + index + '.quantity'],
                        }"
                      />
                    </div>

                    <div class="col-md-2">
                      <input
                        disabled
                        type="text"
                        class="form-control"
                        placeholder="Enter the picked"
                        v-model="item.picked"
                        :class="{
                          'is-invalid': errors['item.' + index + '.picked'],
                        }"
                      />
                    </div>
                    <div class="col-md-2">
                      <input
                        disabled
                        type="text"
                        class="form-control"
                        placeholder="Enter the checked"
                        v-model="item.checked"
                        :class="{
                          'is-invalid': errors['item.' + index + '.checked'],
                        }"
                      />
                    </div>

                    <div class="col-md-1">
                      <button
                        href
                        class="btn btn-danger"
                        style="border: none"
                        @click="removeLineC(index)"
                      >
                        <span
                          class="nc-icon nc-simple-remove"
                          style="font-size: 15px"
                        ></span>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <button
                      class="table-add_line btn btn-primary"
                      @click="addNewLineC"
                    >
                      <span class="fa fa-plus-circle"></span>
                    </button>
                  </div>

                  <div class="col-md-2"></div>
                </div>
              </div>
              <div class="items-block-asp">
                <h3 class="head-pip">Miscellaneous</h3>

                <div
                  class="weeklyorder-cleaning_products"
                  v-for="(item, index) in miscellaneous"
                  v-bind:key="item.id"
                >
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Shelf Code"
                          v-model="item.shelf_code"
                          :class="{
                            'is-invalid':
                              errors['item.' + index + '.shelf_code'],
                          }"
                        />
                        <span
                          v-if="errors['item.' + index + '.shelf_code']"
                          :class="['errorText']"
                          >{{
                            errors["item." + index + ".shelf_code"][0]
                          }}</span
                        >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="auto-complete-product-container">
                        <div class="form-group">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Product Name"
                            v-model="item.product_name"
                            :class="{
                              'is-invalid':
                                errors['item.' + index + '.product_name'],
                            }"
                          />
                          <span
                            v-if="errors['item.' + index + '.product_name']"
                            :class="['errorText']"
                            >{{
                              errors["item." + index + ".product_name"][0]
                            }}</span
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <input
                        type="number"
                        class="form-control"
                        placeholder="Quantity"
                        v-model="item.quantity"
                        :class="{
                          'is-invalid': errors['item.' + index + '.quantity'],
                        }"
                      />
                    </div>

                    <div class="col-md-2">
                      <input
                        disabled
                        type="text"
                        class="form-control"
                        placeholder="Enter the picked"
                        v-model="item.picked"
                        :class="{
                          'is-invalid': errors['item.' + index + '.picked'],
                        }"
                      />
                    </div>
                    <div class="col-md-2">
                      <input
                        disabled
                        type="text"
                        class="form-control"
                        placeholder="Enter the checked"
                        v-model="item.checked"
                        :class="{
                          'is-invalid': errors['item.' + index + '.checked'],
                        }"
                      />
                    </div>

                    <div class="col-md-1">
                      <button
                        href
                        class="btn btn-danger"
                        style="border: none"
                        @click="removeLineM(index)"
                      >
                        <span
                          class="nc-icon nc-simple-remove"
                          style="font-size: 15px"
                        ></span>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <button
                      class="table-add_line btn btn-primary"
                      @click="addNewLineM"
                    >
                      <span class="fa fa-plus-circle"></span>
                    </button>
                  </div>

                  <div class="col-md-2"></div>
                </div>
              </div>
              <div class="items-block-asp">
                <h3 class="head-pip">DOCUMENTATION</h3>

                <div
                  class="weeklyorder-cleaning_products"
                  v-for="(item, index) in documentations"
                  v-bind:key="item.id"
                >
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Shelf Code"
                          v-model="item.shelf_code"
                          :class="{
                            'is-invalid':
                              errors[
                                'cleaning_products.' + index + '.shelf_code'
                              ],
                          }"
                        />
                        <span
                          v-if="
                            errors['cleaning_products.' + index + '.shelf_code']
                          "
                          :class="['errorText']"
                          >{{
                            errors[
                              "cleaning_products." + index + ".shelf_code"
                            ][0]
                          }}</span
                        >
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="auto-complete-product-container">
                        <div class="form-group">
                          <input
                            type="text"
                            class="form-control"
                            placeholder="Product Name"
                            v-model="item.product_name"
                            :class="{
                              'is-invalid':
                                errors['item.' + index + '.product_name'],
                            }"
                          />
                          <span
                            v-if="errors['item.' + index + '.product_name']"
                            :class="['errorText']"
                            >{{
                              errors["item." + index + ".product_name"][0]
                            }}</span
                          >
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <input
                        type="number"
                        class="form-control"
                        placeholder="Quantity"
                        v-model="item.quantity"
                        :class="{
                          'is-invalid': errors['item.' + index + '.quantity'],
                        }"
                      />
                    </div>

                    <div class="col-md-2">
                      <input
                        disabled
                        type="text"
                        class="form-control"
                        placeholder="Enter the picked"
                        v-model="item.picked"
                        :class="{
                          'is-invalid': errors['item.' + index + '.picked'],
                        }"
                      />
                    </div>
                    <div class="col-md-2">
                      <input
                        disabled
                        type="text"
                        class="form-control"
                        placeholder="Enter the checked"
                        v-model="item.checked"
                        :class="{
                          'is-invalid': errors['item.' + index + '.checked'],
                        }"
                      />
                    </div>

                    <div class="col-md-1">
                      <button
                        href
                        class="btn btn-danger"
                        style="border: none"
                        @click="removeLineD(index)"
                      >
                        <span
                          class="nc-icon nc-simple-remove"
                          style="font-size: 15px"
                        ></span>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <button
                      class="table-add_line btn btn-primary"
                      @click="addNewLineD"
                    >
                      <span class="fa fa-plus-circle"></span>
                    </button>
                  </div>

                  <div class="col-md-2"></div>
                </div>
              </div>
              <!-- end of weeklyorder cleaning_products-->
            </div>
            <!-- end of weeklyorder body-->
            <!-- <div class="weeklyorder-foot">
              <div class="row">
                <div class="col-md-2">
                  <button
                    class="table-add_line btn btn-primary"
                    @click="addNewLine"
                  >
                    <span class="fa fa-plus-circle"></span>
                  </button>
                </div>
               
                <div class="col-md-2"></div>
              </div>
            </div> -->
            <!-- end of weeklyorder foot -->
          </div>
          <!-- end of weeklyorder -->
        </div>
      </div>
      <b-button class="btn-primary mt-3" block @click="callFunc">{{
        modalForName
      }}</b-button>
    </b-modal>
    <!-- add weeklyorder modal end-->

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6
          class="m-0 font-weight-bold text-primary"
          style="display: inline-block"
        >
          WeeklyOrder
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
              file-name="weeklyorders"
              :fields="weeklyorders_export_fileds"
              :data="weeklyorders"
            >
              <!-- <button class="btn btn-warning-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button> -->
              <img
                src="img/icon-red-csv.png"
                class="icon-red-csv-export"
                alt="Export data to CSV"
              />
            </vue-blob-json-csv>
          </template>

          <template>
    <img
      src="img/pdf.png"
      class="icon-red-pdf-export"
      alt="Export data to pdf"
      style="width: 41px; cursor: pointer"
      @click="exportToPDF()"
    />
  </template>

  <div
    class="bowlpdf"
    style="visibility: hidden; position: absolute"
    v-if="showbowlpdf"
  >
    <div class="element-pdf" id="element-to-convert">
      <h3>Weekly Orders</h3>
      <p>Exported on Date : {{ currentDateTime }}</p>
      <table
        class="table table-striped table-bordered"
        width="100%"
        cellspacing="0"
      >
        <thead>
          <tr>
            <template v-for="arrayKey in arrayKeys">
              <th>{{ arrayKey }}</th>
            </template>
          </tr>
        </thead>
        <tbody>
          <tr v-for="weeklyorder in weeklyorders" v-bind:key="weeklyorder.id">
            <template v-for="arrayKey in arrayKeys">
              <td>{{ weeklyorder[arrayKey] }}</td>
            </template>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
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
      <div class="card-body" v-if="weeklyorders.length > 0">
        <div class="table">
          <table
            class="table table-striped table-bordered"
            width="100%"
            cellspacing="0"
          >
            <thead>
              <tr>
                <th>WeeklyOrder No.</th>
                <th>Boat Name</th>
                <th>Date Order Requested</th>
                <th>Delivery Date</th>
                <!-- <th>Status</th> -->
                <th>Modify</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="weeklyorder in weeklyorders"
                v-bind:key="weeklyorder.id"
              >
                <td>{{ weeklyorder.id }}</td>
                <td>{{ weeklyorder.boat_name }}</td>
                <td>
                  {{ weeklyorder.date_order_requested }}
                </td>
                <td>
                  <!-- <span
                    v-if="weeklyorder.date_order_requested === weeklyorder.delivery_date"
                    class="bg-danger text-white p-2"
                    >{{  | moment("from", "now") }}</span
                  >
                  <span v-else class="bg-success text-white p-2">{{
                    weeklyorder.delivery_date | moment("from", "now")
                  }}</span> -->
                  {{ weeklyorder.delivery_date }}
                </td>

                <!--
                 <td v-if="(weeklyorder.status==='Paid')">
                         <toggle-button v-bind:status="true" @statusChanges ="updateStatus($event,weeklyorder.id)"/> 
                  </td>
                  
                  <td v-else-if="(weeklyorder.status==='To Pay')">
                         <toggle-button v-bind:status="false" @statusChanges ="updateStatus($event,weeklyorder.id)"/> 
                  </td>
                  -->

                <td>
                  <button
                    class="btn btn-danger custom_btn_table"
                    v-if="hasPermission('show_weeklyorder')"
                    @click="downloadWeeklyOrderPDF(weeklyorder.id)"
                  >
                    <span class="fa fa-file-pdf-o custom_icon_table"></span>
                  </button>
                  <button
                    class="btn btn-outline-success custom_btn_table"
                    v-if="hasPermission('edit_weeklyorder')"
                    @click="editWeeklyOrder(weeklyorder.id)"
                  >
                    <span class="fa fa-edit custom_icon_table"></span>
                  </button>

                  <button
                    class="btn btn-outline-danger custom_btn_table"
                    v-if="hasPermission('delete_weeklyorder')"
                    @click="deleteWeeklyOrder(weeklyorder.id)"
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
                  @click="fetchWeeklyOrders(pagination.first_link)"
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
                  @click="fetchWeeklyOrders(pagination.prev_link)"
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
                  @click="fetchWeeklyOrders(pagination.path_page + n)"
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
                  @click="fetchWeeklyOrders(pagination.next_link)"
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
                  @click="fetchWeeklyOrders(pagination.last_link)"
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
import Vue from "vue";
//custom toggle button
import ToggleButton from "../widgets/ToggleButton";

import html2pdf from "html2pdf.js";

export default {
  components: {
    ToggleButton,
  },
  data() {
    return {
      id: "",
      cleaning_products: [
        // {
        //   product_name: "cleaning itme1",
        //   picked: "0",
        //   quantity: "1",
        //   changed: false,
        // },
      ],

      miscellaneous: [
        // {
        //   product_name: "miscellaneous item1",
        //   picked: "0",
        //   quantity: "1",
        //   changed: false,
        // },
      ],
      documentations: [
        // {
        //   product_name: "documnetion item1",
        //   picked: "0",
        //   quantity: "1",
        //   changed: false,
        // },
      ],

      weeklyorder_number: "",
      info: {},
      preItemNameC: [
        "Washing-Up Liquid",
        "Toilet Duck",
        "Washroom Wipes",
        "Multi Surface Spray",
        "Dettoli Wipes",
        "Bathroom & Shower Spray",
        "Window Cleaner Spray",
        "Fuurniture Polish",
        "Brasso",
        "Carpet Cleaner",
        "Dishwasher Detergent (SL)",
        "Dishwasher Rinse Aide (SL)",
        "Henry Hoover Bags",
        "Floor Cleaner (Concentrate SL)",
        "Cleaning Cloths (x50)",
        "Sponge Scouter (X10)",
        "Vileda Mop Head",
        "Air Freshner",
        "Liquid Hand Soap (SL)",
        "Conditioner (SL)",
        "Hair and Body Shampoo (SL)",
        "Tissues",
        "Toilet Roll (24pk)",
        "Blue Tork Roll",
        "Kitchen Roll",
        "Swing Bin Liner (45l to 200 roll)",
        "Cabin Bin Liner (3l-20pk)",
        "Black Bin Bags(200box)",
      ],
      preItemNameM: [
        "Plastic Aprons",
        "Latex Gloves (Large)",
        "Latex Cloves (Medium)",
        "Latex Gloves (Small)",
        "Plastic Penchos",
        "Water Filters & (S micron)",
        "water Filters (5 micron)",
        "Water Filters (10 Microe)",
      ],
      preItemNameD: [
        "Comp / Galley / Crew Steee",
        "Daily Bar Account Sheet",
        "Provisions Order Form",
        "Chef - 10 Day Clean Schedule",
        "Chef - Appliance Temp",
        "Chef -Daily Cleaning",
      ],

      store: {},
      queryResults: [],
      queryResultsProducts: [],
      errors: [],
      tempCustomWeeklyOrderID: "",
      showProductSuggestion: false,
      options: {
        format: "DD-MM-YYYY",
        useCurrent: true,
        showClear: true,
        showClose: true,
      },

      weeklyorders: [],
      weeklyorders_id: "",
      pagination: {},
      edit: false,
      searchTableKey: "",
      tempStatus: {},
      modalForName: "",
      modalForCode: 0,

      isLoading: "",
      showbowlpdf: true,
      arrayKeys: [ "id",
        "boat_name",
        "date_order_requested",
        "delivery_date",],
      currentDateTime: "",
      weeklyorders_export_fileds: [
        "id",
        "boat_name",
        "date_order_requested",
        "delivery_date",
      ],
    };
  },
  created() {
    this.fetchWeeklyOrders();
    // this.fetchStore();
  },

  methods: {
    exportToPDF() {
      this.showbowlpdf = true;
      this.getDateTime();

      setTimeout(() => {
        html2pdf(document.getElementById("element-to-convert"), {
          margin: 5,
          filename: "exported.pdf",
        });
      }, 1000);

      setTimeout(() => {
        this.showbowlpdf = false;
      }, 1000);
    },
    getDateTime() {
      var currentdate = new Date();
      var datetime =
        "Last Sync: " +
        currentdate.getDate() +
        "/" +
        (currentdate.getMonth() + 1) +
        "/" +
        currentdate.getFullYear() +
        " @ " +
        currentdate.getHours() +
        ":" +
        currentdate.getMinutes() +
        ":" +
        currentdate.getSeconds();
      this.currentDateTime = datetime;
    },
    pushDefaultProductNameToC() {
      this.preItemNameC.forEach((element) => {
        this.cleaning_products.push({
          product_name: element,
          picked: "   Yes    |      No",
          quantity: "1",
          checked: "   Yes    |      No",

          changed: true,
        });
      });
    },
    pushDefaultProductNameToM() {
      this.preItemNameM.forEach((element) => {
        this.miscellaneous.push({
          product_name: element,
          picked: "   Yes    |      No",
          quantity: "1",
          line_total: "",
          checked: "   Yes    |      No",

          changed: true,
        });
      });
    },
    pushDefaultProductNameToD() {
      this.preItemNameD.forEach((element) => {
        this.documentations.push({
          product_name: element,
          picked: "   Yes    |      No",
          quantity: "1",
          checked: "   Yes    |      No",

          changed: true,
        });
      });
    },
    clearWeeklyOrderInput() {},
    downloadWeeklyOrderPDF(id) {
      console.log("download-btn-pressed");
      axios
        .get(`api/weeklyorderpdf/${id}`, {
          responseType: "blob",
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "weeklyorder.pdf"); //or any other extension
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
            "weeklyorder_id_count",
            response.data.store.weeklyorder_id_count
          );

          currObj.weeklyorder_number =
            currObj.store.weeklyorder_id_count.split("-");

          currObj.weeklyorder_number[1] =
            parseInt(currObj.weeklyorder_number[1]) + 1;

          currObj.weeklyorder_number = currObj.weeklyorder_number.join("-");
          console.log(currObj.weeklyorder_number);

          currObj.isLoading = "";
        });
    }, //

    addNewLineC() {
      this.cleaning_products.push({
        product_name: "",

        picked: "   Yes    |      No",
        quantity: "1",
        line_total: "",
        checked: "   Yes    |      No",

        line_total: "",
        changed: false,
      });
    }, // end of add new line
    addNewLineM() {
      this.miscellaneous.push({
        product_name: "",

        picked: "   Yes    |      No",
        quantity: "1",
        line_total: "",
        checked: "   Yes    |      No",
        line_total: "",
        changed: false,
      });
    },
    addNewLineD() {
      this.documentations.push({
        product_name: "",

        picked: "   Yes    |      No",
        quantity: "1",
        line_total: "",
        checked: "   Yes    |      No",
        line_total: "",
        changed: false,
      });
    },
    removeLineC(index) {
      // this.weeklyorders.remove();
      this.cleaning_products.splice(index, 1);
    }, //end of removeLine function
    removeLineM(index) {
      // this.weeklyorders.remove();
      this.miscellaneous.splice(index, 1);
    },
    removeLineD(index) {
      // this.weeklyorders.remove();
      this.documentations.splice(index, 1);
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
          .post("/api/weeklyorderss/search", {
            searchQuery: this.searchTableKey,
          })
          .then(function (response) {
            currObj.isLoading = "";

            currObj.weeklyorderss = response.data.data;

            console.log(currObj.weeklyorderss);

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

    fetchWeeklyOrders(page_url) {
      this.$Progress.start();
      this.isLoading = "Loading all Data";
      page_url = page_url || "/api/weeklyorders";
      let vm = this;
      axios
        .get(page_url)
        .then(function (response) {
          vm.weeklyorders = response.data.data;
          vm.isLoading = "";
          if (vm.weeklyorders.length != null) {
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
      this.cleaning_products = [];
      this.miscellaneous = [];
      this.documentations = [];
      // this.info.note=`Communication / Crockery /nTableware etc /nRequirements`;
      const note = `Communication Crockery\nTableware etc\nRequirements `;
      Vue.set(this.info, "note", note);
      this.pushDefaultProductNameToC();
      this.pushDefaultProductNameToM();
      this.pushDefaultProductNameToD();

      this.modalForName = "Add WeeklyOrder";
      // Vue.set(this.modalForName,"Add WeeklyOrder");
      this.modalForCode = 0; //0 for add
      // this.weeklyorder.name = "";
      // this.weeklyorder.description = "";
      this.errors = ""; //clearing errors
      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-weeklyorder");
      // this.clearWeeklyOrderInput();
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addWeeklyOrder();
        // console.log("Add WeeklyOrder");
      } else if (this.modalForCode == 1) {
        this.updateWeeklyOrder();
        // console.log("Edit WeeklyOrder");
      }
    },

    addWeeklyOrder() {
      //Add
      let currObj = this;
      axios
        .post("/api/weeklyorder", {
          info: this.info,
          cp: this.cleaning_products,
          m: this.miscellaneous,
          d: this.documentations,
        })
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-weeklyorder");
          currObj.fetchWeeklyOrders();
          currObj.errors = ""; //clearing errors
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

    editWeeklyOrder(id) {
      this.$Progress.start();

      let currObj = this;
      this.modalForName = "Edit WeeklyOrder";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-weeklyorder");
      currObj.errors = ""; //clearing errors
      axios
        .get("/api/weeklyorder/" + id)
        .then(function (response) {
          Vue.set(currObj.info, "weeklyorder_no", response.data.weeklyorder.id),
            Vue.set(currObj.info, "note", response.data.weeklyorder.note),
            Vue.set(
              currObj.info,
              "boat_name",
              response.data.weeklyorder.boat_name
            ),
            Vue.set(
              currObj.info,
              "delivery_date",
              response.data.weeklyorder.delivery_date
            ),
            Vue.set(
              currObj.info,
              "date_order_requested",
              response.data.weeklyorder.date_order_requested
            );
          Vue.set(
            currObj.info,
            "date_order_requested",
            response.data.weeklyorder.date_order_requested
          );

          let cp = response.data.weeklyorder.weekly_order_detail_c;
          let m = response.data.weeklyorder.weekly_order_detail_m;
          let d = response.data.weeklyorder.weekly_order_detail_d;
          currObj.cleaning_products = [];
          currObj.miscellaneous = [];
          currObj.documentations = [];

          for (let i = 0; i < cp.length; i++) {
            currObj.cleaning_products[i] = cp[i];
          }

          for (let i = 0; i < m.length; i++) {
            currObj.miscellaneous[i] = m[i];
          }

          for (let i = 0; i < d.length; i++) {
            currObj.documentations[i] = d[i];
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

    updateWeeklyOrder() {
      let currObj = this;
      axios
        .put("/api/weeklyorder", {
          info: this.info,
          cp: this.cleaning_products,
          m: this.miscellaneous,
          d: this.documentations,
          id: this.info.weeklyorder_no,
        })
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-weeklyorder");
          currObj.fetchWeeklyOrders();
          currObj.cleaning_products = [];
          currObj.miscellaneous = [];
          currObj.documentations = [];
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

    deleteWeeklyOrder(id) {
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
            .delete("/api/weeklyorder/" + id)
            .then(function (response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              // alert(currObj.status);

              currObj.fetchWeeklyOrders();
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
  }, //end of computed
}; //end of default
</script>
  <style scoped>
.weeklyorder {
  margin-top: 5em;
}

.weeklyorder-body {
  margin-top: 2em;
  padding: 8px;
}

.weeklyorder-head {
  padding: 1em;
  /*border-bottom: 1px solid #eee;*/
  background: var(--favblue);
  color: var(--favgold);
  box-shadow: 1px 7px 17px -12px;
}

.weeklyorder-foot {
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

.product-search-suggestion-weeklyorder {
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

.product-search-suggestion-weeklyorder ul {
  list-style: none;
  margin: 0px;
  padding: 0px;
}

.product-search-suggestion-weeklyorder ul li {
  padding: 10px;
  cursor: pointer;
  background: #f4f3ef;
}

.product-search-suggestion-weeklyorder ul li:hover {
  background: #51cbce;
  color: white;
}

.product-search-suggestion-weeklyorder::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
  background-color: #f5f5f5;
}

.product-search-suggestion-weeklyorder::-webkit-scrollbar {
  width: 6px;
  background-color: #f5f5f5;
}

.product-search-suggestion-weeklyorder::-webkit-scrollbar-thumb {
  background-color: #000000;
}

.row.wk_order_row {
  border: 1px solid #eee;
  padding: 9px;
  font-size: 16px;
  margin: 12px;
}
h3.head-pip {
  /* background: var(--favblue); */
  /* color: var(--favgold); */
  padding: 9px;
  font-size: 15px;
  text-align: center;
}

textarea.form-control {
  height: 106px;
  max-height: none;
}
</style>
  
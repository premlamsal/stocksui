<template>
  <div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Product List</h1>
    <p class="mb-4" v-if="hasPermission('add_product')">
      <b-button
        id="show-btn"
        @click="showAddModal"
        class="btn btn-success"
        style="margin-top: 8px"
      >
        <span class="fa fa-plus-circle"></span> Add New Product</b-button
      >
    </p>
    <!-- add quantity model start -->
    <b-modal id="bv-modal-add-product" hide-footer>
      <template v-slot:modal-title>
        <span class="text-primary">{{ modalForName }}</span>
      </template>
      <div class="d-block">
        <form enctype="multipart/form-data">
          <div class="form-group">
            <label for="Category">Category:</label>
            <template>
              <select class="form-control" v-model="product.product_cat_id">
                <option
                  selected=""
                  v-for="category in categories"
                  :value="category.id"
                >
                  {{ category.name }}
                </option>
              </select>
              <span v-if="errors.product_cat_id" :class="['errorText']">{{
                errors.product_cat_id[0]
              }}</span>
            </template>
          </div>
          <div class="form-group">
            <input type="hidden" v-model="product.id" />
            <label for="Name">Name:</label>
            <input
              type="text"
              v-model="product.name"
              :class="['form-control']"
            />
            <span v-if="errors.name" :class="['errorText']">{{
              errors.name[0]
            }}</span>
          </div>
          <div class="form-group">
            <label for="Qunaity">Qunaity:</label>

            <input
              type="text"
              v-model="product.quantity"
              :class="['form-control']"
            />
            <span v-if="errors.quantity" :class="['errorText']">{{
              errors.quantity[0]
            }}</span>
          </div>

          <div class="form-group">
            <div class="checkboxlabel">
              <label
                for="Stock Alert Checkbox"
                style="
                  display: flex;
                  height: 23px;
                  align-items: center;
                  margin-right: 10px;
                "
                >Low Stock Alert Required ?</label
              >

              <input
                style="cursor: pointer"
                type="checkbox"
                v-model="product.low_stock_alert_active"
                class="checkbox"
              />

              <span
                v-if="errors.low_stock_alert_active"
                :class="['errorText']"
                >{{ errors.low_stock_alert_active[0] }}</span
              >
            </div>
          </div>
          <div class="form-group" v-if="product.low_stock_alert_active">
            <label for="Stock Alert Quantity"
              >Minimum Stock Quantity for Alert</label
            >
            <input
              type="text"
              v-model="product.low_stock_alert_quantity"
              :class="['form-control']"
            />
            <span
              v-if="errors.low_stock_alert_quantity"
              :class="['errorText']"
              >{{ errors.low_stock_alert_quantity[0] }}</span
            >
          </div>

          <div class="form-group">
            <label for="Description"> Description:</label>
            <textarea :class="['form-control']" v-model="product.description">{{
              product.description
            }}</textarea>
            <span v-if="errors.description" :class="['errorText']">{{
              errors.description[0]
            }}</span>
          </div>

          <div class="form-group">
            <label>Image</label>
            <br />
            <img v-bind:src="imagePreview" class="product_logo_img" />
            <input
              type="file"
              :class="['form-control']"
              v-on:change="fileSelected"
            />
            <span v-if="errors.image" :class="['errorText']">{{
              errors.image[0]
            }}</span>
          </div>
        </form>
      </div>
      <b-button class="btn-primary mt-3" block @click="callFunc">{{
        modalForName
      }}</b-button>
    </b-modal>
    <!-- add quantity modal end-->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6
          class="m-0 font-weight-bold text-primary"
          style="display: inline-block"
        >
          Products
        </h6>
        <div class="text-center" v-if="isLoading == 'Loading all Data'">
          <b-spinner variant="success" label="Spinning"></b-spinner>
        </div>
        <!-- <div v-if="isLoading">{{isLoading}}</div> -->
        <!-- <p>As per {{new Date().toLocaleString()}}</p> -->
        <!-- <span>{{isLoading}}</span> -->
        <div class="export-block">
          <template>
            <vue-blob-json-csv
              @success="handleSuccessExportCSV"
              @error="handleErrorExportCSV"
              file-type="csv"
              file-name="products"
              :fields="products_export_fileds"
              :data="products"
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
              <h3>Products</h3>
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
                  <tr v-for="product in products" v-bind:key="product.id">
                    <template v-for="arrayKey in arrayKeys">
                      <td>{{ product[arrayKey] }}</td>
                    </template>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
     

        <div class="searchTable">
          <!-- Topbar Search -->
          <!-- <div class="input-group"> -->
          <div class="input-group no-border">
            <input
              type="text"
              value=""
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
      <div class="card-body" v-if="products.length > 0">
        <div class="table">
          <table
            class="table table-striped table-bordered"
            width="100%"
            cellspacing="0"
            v-if="isLoading != 'Loading all Data'"
          >
            <thead>
              <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Low Stock Alert Quantity</th>

                <th>Qunaity</th>

                <th  v-if="hasPermission('edit_product') || hasPermission('delete_product')">Edit</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in products" v-bind:key="product.id">
                <td>{{ product.custom_product_id }}</td>
                <td>{{ product.category.name }}</td>
                <td>
                  <img
                    :src="product.image"
                    width="100px"
                    height="100px"
                    style="border-radius: 50%"
                  />
                </td>
                <!-- <td @click="showProductDetail(product.id)">{{product.name}}</td> -->
                <td>{{ product.name }}</td>
                <td>{{ product.description }}</td>
                <td>{{ product.low_stock_alert_quantity }}</td>

                <td>{{ product.quantity }}</td>
                <td  v-if="hasPermission('edit_product') || hasPermission('delete_product')">
                  <button
                    class="btn btn-success custom_btn_table"
                    @click="editProduct(product.id)"
                    v-if="hasPermission('edit_product')"
                  >
                    <span class="fa fa-edit custom_icon_table"></span>
                  </button>
                  <button
                    class="btn btn-danger custom_btn_table"
                    @click="deleteProduct(product.id)"
                    v-if="hasPermission('delete_product')"
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
                  @click="fetchProducts(pagination.first_link)"
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
                  @click="fetchProducts(pagination.prev_link)"
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
                  @click="fetchProducts(pagination.path_page + n)"
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
                  @click="fetchProducts(pagination.next_link)"
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
                  @click="fetchProducts(pagination.last_link)"
                  class="page-link"
                >
                  Last
                </button>
              </li>
            </ul>
          </div>
          <div class="col-md-4">
            Page: {{ pagination.current_page }}-{{ pagination.last_page }} Total
            Products: {{ pagination.total_pages }}
          </div>
        </div>
      </div>
      <div class="errorDivEmptyData" v-else>No Data Found</div>
    </div>
  </div>
</template>

<script>
import html2pdf from "html2pdf.js";

export default {
  data() {
    return {
      products: [
        {
          custom_product_id: "",
          name: "",
          description: "",
          low_stock_alert_active: false,
          low_stock_alert_quantity: "",
          category: {},
          quantity: {},
        },
      ], //contains all the retrived quantitys from the database

      product: {}, //for form single quantity data

      modalForName: "",
      modalForCode: 0,

      searchTableKey: "",
      errors: [],
      pagination: {},

      isLoading: "",

      quantitys: [], //contains all the retrived quantitys from the database

      categories: [],

      errors: [],

      pagination: {},

      file: "",

      selectedFile: "",

      imagePreview: "",

      showbowlpdf: true,
      arrayKeys: [
        "custom_product_id",
        "product_name",
        // "low_stock_alert_quantity",
        "quantity",
        "category_name",
        "description",
      ],
      currentDateTime: "",

      products_export_fileds: [
        "custom_product_id",
        "product_name",
        // "low_stock_alert_quantity",
        "quantity",
        "category_name",
        "description",
      ],
    };
  },
  created() {
    //this block will execute when component created
    this.fetchProducts();
    this.fetchCategories();

    this.setAvtarUploadImage();
  },

  methods: {
    //methods codes here

    handleSuccessExportCSV() {
      console.log("success Export");
    },
    handleErrorExportCSV() {
      console.log("errorExport");
    },

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
    //methods codes here
    fetchCategories(page_url) {
      this.$Progress.start();
      this.isLoading = "Loading all Data";
      page_url = page_url || "api/categories";

      let vm = this; // current pointer instance while going inside the another functional instance
      axios
        .get(page_url)
        .then(function (response) {
          vm.categories = response.data.data;
          // console.log(response.data);
          if (vm.categories.length != null) {
            vm.makePagination(response.data.meta, response.data.links);
            vm.$Progress.finish();
          }
        })
        .catch(function (error) {
          // console.log();
          vm.$Progress.fail();
        });
    },

    //methods codes here
    fetchProducts(page_url) {
      this.$Progress.start();
      this.isLoading = "Loading all Data";

      let vm = this; // current pointer instance while going inside the another functional instance
      page_url = page_url || "api/products";
      axios
        .get(page_url)
        .then(function (response) {
          vm.isLoading = "";
          vm.products = response.data.data;
          if (vm.products.length != null) {
            vm.makePagination(response.data.meta, response.data.links);
            vm.$Progress.finish();
          }
        })
        .catch(function (error) {
          // console.log();
          vm.$Progress.fail();
        });

      //above and below code provide same result but above code need current instance pointer for value assignmnent

      //below code donot need current pointer to be save becasue it execute in current block rather then another block that need previous pointer.

      // axios.get('/api/products')
      // .then(response=>{
      //   // console.log(response.data.data)
      //   this.products=response.data.data
      // })
      // .catch(error=>{
      //   console.log(error)
      // })
    },

    setAvtarUploadImage() {
      this.imagePreview = "/img/upload_image.png";
    },

    fileSelected(e) {
      this.$Progress.start();
      // alert("File Selected");
      this.imagePreview = "/img/Rolling-1s-200px.svg";

      this.file = e.target.files[0];
      // this.file=e.target.files[0];
      let currObj = this;

      // this.product.image=this.image;

      /*
          Initialize a File Reader object
        */
      let reader = new FileReader();

      /*
          Add an event listener to the reader that when the file
          has been loaded, we flag the show preview as true and set the
          image to be what was read from the reader.
        */
      reader.addEventListener(
        "load",
        function () {
          this.imagePreview = reader.result;
        }.bind(this),
        false
      );

      /*
        Check to see if the file is not empty.
      */
      if (this.file) {
        /*
          Ensure the file is an image file.
        */
        if (/\.(jpe?g|png|gif)$/i.test(this.file.name)) {
          /*
            Fire the readAsDataURL method which will read the file in and
            upon completion fire a 'load' event which we will listen to and
            display the image in the preview.
          */
          reader.readAsDataURL(this.file);
        }
      }

      this.$Progress.finish();
    }, //end of fileSelected

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
      this.modalForName = "Add New Product";
      // Vue.set(this.modalForName,"Add Qunaity");
      this.modalForCode = 0; //0 for add

      this.product.name = "";
      this.product.product_cat_id = "";
      this.product.quantity = "";
      this.product.address = "";
      this.product.phone = "";
      this.product.description = "";
      this.low_stock_alert_active = false;
      this.low_stock_alert_quantity = "";

      this.setAvtarUploadImage();

      this.errors = ""; //clearing errors

      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-product");
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addProduct();
        // console.log("Add Qunaity");
      } else if (this.modalForCode == 1) {
        this.updateProduct();
        // console.log("Edit Qunaity");
      }
    },
    addProduct() {
      this.$Progress.start();
      let currObj = this;

      const config = {
        headers: { "content-type": "multipart/form-data" },
      };

      let formData = new FormData();
      formData.append("image", this.file);
      formData.append("_method", "POST"); //add this otherwise data won't pass to backend
      // formData.append('id',this.product.id);
      formData.append("name", this.product.name);
      formData.append("product_cat_id", this.product.product_cat_id);
      formData.append(
        "low_stock_alert_active",
        this.product.low_stock_alert_active
      );
      formData.append(
        "low_stock_alert_quantity",
        this.product.low_stock_alert_quantity
      );

      formData.append("quantity", this.product.quantity);
      formData.append("description", this.product.description);

      // posting data //using post and sending form data as PUT to match the api route name setting
      axios
        .post("/api/product", formData, config)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;

          currObj.$swal("Info", currObj.output, currObj.status);

          currObj.$bvModal.hide("bv-modal-add-product");

          currObj.product.name = "";
          currObj.product.low_stock_alert_active = false;

          currObj.product.product_cat_id = "";
          (currObj.product.quantity = ""),
            (currObj.product.address = ""),
            (currObj.product.low_stock_alert_quantity = ""),
            (currObj.product.phone = ""),
            (currObj.product.description = "");

          currObj.setAvtarUploadImage();

          currObj.errors = ""; //clearing errors
          currObj.$Progress.finish();

          currObj.fetchProducts();
        })
        .catch(function (error) {
          currObj.$Progress.fail();
          if (error.response.status == 422) {
            currObj.validationErrors = error.response.data.errors;
            currObj.errors = currObj.validationErrors;
            console.log(currObj.errors);
          }
        });
    },
    editProduct(id) {
      this.$Progress.start();
      let currObj = this;
      this.modalForName = "Edit Product";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-product");
      currObj.errors = ""; //clearing errors
      axios
        .get("/api/product/" + id)
        .then((response) => {
          // console.log(response.data.quantity)
          Vue.set(this.product, "name", response.data.product.name);
          Vue.set(
            currObj.product,
            "description",
            response.data.product.description
          );
          Vue.set(this.product, "quantity", response.data.product.quantity);

          Vue.set(
            this.product,
            "low_stock_alert_active",
            JSON.parse(response.data.product.low_stock_alert_active)
          );

          Vue.set(
            this.product,
            "low_stock_alert_quantity",
            response.data.product.low_stock_alert_quantity
          );

          // Vue.set(this.product, 'opening_stock', response.data.product.opening_stock);
          Vue.set(
            this.product,
            "product_cat_id",
            response.data.product.product_cat_id
          );

          this.imagePreview = response.data.product.image;

          this.file = response.data.product.image;
          this.errors = "";

          Vue.set(this.product, "id", id); //to send id to the update controller

          this.$Progress.finish();
        })
        .catch((error) => {
          // console.log(error)
          this.$Progress.fail();
        });
    },
    updateProduct() {
      this.$Progress.start();
      let currObj = this;

      const config = {
        headers: { "content-type": "multipart/form-data" },
      };

      let formData = new FormData();
      formData.append("image", this.file);
      formData.append("_method", "PUT"); //add this otherwise data won't pass to backend
      formData.append("id", this.product.id);
      formData.append("name", this.product.name);
      formData.append(
        "low_stock_alert_active",
        this.product.low_stock_alert_active
      );

      formData.append("product_cat_id", this.product.product_cat_id);
      formData.append("quantity", this.product.quantity);
      // formData.append('opening_stock',this.product.opening_stock);
      formData.append("description", this.product.description);
      formData.append(
        "low_stock_alert_quantity",
        this.product.low_stock_alert_quantity
      );

      // posting data //using post and sending form data as PUT to match the api route name setting
      axios
        .post("/api/product", formData, config)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);

          currObj.$bvModal.hide("bv-modal-add-product");

          currObj.product.name = "";
          currObj.product.product_cat_id = "";
          currObj.product.quantity = "";
          currObj.product.low_stock_alert_active = "";
          currObj.product.low_stock_alert_quantity = "";

          currObj.product.description = "";

          currObj.setAvtarUploadImage();

          currObj.errors = ""; //clearing errors
          currObj.$Progress.finish();

          currObj.fetchProducts();
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
    deleteProduct(id) {
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
            .delete("/api/product/" + id)
            .then(function (response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              // alert(currObj.status);

              let index_to_delete = currObj.products.findIndex(
                (product) => product.id === id
              );
              currObj.products.splice(index_to_delete, 1);
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
    }, //end of deleteQunaity()
    searchTableBtn() {
      this.autoCompleteTable();
    },
    autoCompleteTable() {
      this.searchTableKey = this.searchTableKey.toLowerCase();
      if (this.searchTableKey != "") {
        this.isLoading = "Loading Data...";
        let currObj = this;
        axios
          .post("/api/products/search", { searchQuery: this.searchTableKey })
          .then(function (response) {
            currObj.isLoading = "";

            currObj.products = response.data.data;

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
        // console.log('hello');
        this.fetchProducts();
      }
    }, //end of autoCOmpleteTable

    showProductDetail(product_id) {
      // named route
      this.$router.push({ path: `/${product_id}/showProductDetail/` });
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

    //end of methods block
  },

  computed: {},
};
</script>

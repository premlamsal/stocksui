<template>
    <div ref="document">
      <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Folder List</h1>
      <p class="mb-4" v-if="hasPermission('add_folder')">
        <b-button
          id="show-btn"
          @click="showAddModal"
          class="btn btn-success"
          style="margin-top: 8px"
        >
          <span class="fa fa-plus-circle"></span> Add New Folder</b-button
        >
      </p>
      <!-- add folder model start -->
      <b-modal id="bv-modal-add-folder" hide-footer>
        <template v-slot:modal-title>
          {{ modalForName }}
        </template>
        <div class="d-block">
          <div class="form-group">
            <label for="Name">Name</label>
            <input type="hidden" v-model="folder.id" />
            <input
              type="text"
              :class="['form-control']"
              placeholder="Folder Name"
              v-model="folder.name"
            />
            <span v-if="errors.name" :class="['errorText']">{{
              errors.name[0]
            }}</span>
          </div>
          <div class="form-group">
            <label for="Description">Description</label>
            <textarea
              :class="['form-control']"
              placeholder="Folder Description"
              v-model="folder.description"
            ></textarea>
            <span v-if="errors.description" :class="['errorText']">{{
              errors.description[0]
            }}</span>
          </div>
        </div>
        <b-button class="btn-primary mt-3" block @click="callFunc">{{
          modalForName
        }}</b-button>
      </b-modal>
      <!-- add folder modal end-->
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary" style="display: inline">
            Folders
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
                file-name="folders"
                :fields="folders_export_fileds"
                :data="folders"
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
                <h3>Folders</h3>
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
                    <tr v-for="folder in folders" v-bind:key="folder.id">
                      <template v-for="arrayKey in arrayKeys">
                        <td>{{ folder[arrayKey] }}</td>
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
  
        <div class="card-body" v-if="folders.length > 0">
          <div class="table">
            <table
              class="table table-striped table-bordered"
              width="100%"
              cellspacing="0"
            >
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Created By</th>
                  <th>Created At</th>
                  <th  v-if="hasPermission('edit_folder') || hasPermission('delete_folder')">Edit</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="folder in folders" v-bind:key="folder.id">
                  <td>{{ folder.name }}</td>
                  <td>{{ folder.description }}</td>
                  <td>{{ folder.user.name }}</td>
                  <td>{{ folder.created_at }}</td>

                  <!-- <td>{{ folder.updated_at | moment("from", "now") }}</td> -->
                  <td  v-if="hasPermission('edit_folder') || hasPermission('delete_folder')">
                    <button
                      class="btn btn-success custom_btn_table"
                      style="margin-right: 5px"
                      @click="editFolder(folder.id)"
                      v-if="hasPermission('edit_folder')"
                    >
                      <span class="fa fa-edit custom_icon_table"></span>
                    </button>
                    <button
                      class="btn btn-danger custom_btn_table"
                      @click="deleteFolder(folder.id)"
                      v-if="hasPermission('delete_folder')"
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
                    @click="fetchFolders(pagination.first_link)"
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
                    @click="fetchFolders(pagination.prev_link)"
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
                    @click="fetchFolders(pagination.path_page + n)"
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
                    @click="fetchFolders(pagination.next_link)"
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
                    @click="fetchFolders(pagination.last_link)"
                    class="page-link"
                  >
                    Last
                  </button>
                </li>
              </ul>
            </div>
            <div class="col-md-4">
              Page: {{ pagination.current_page }}-{{ pagination.last_page }} Total
              Folders: {{ pagination.total_pages }}
            </div>
          </div>
        </div>
        <div class="errorDivEmptyData" v-else>No Data Found</div>
      </div>
    </div>
  </template>
  <style scoped>
  .bowlpdf {
  }
  .bowlpdf table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
  }
  
  .bowlpdf td,
  th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
  }
  
  .bowlpdf tr:nth-child(even) {
    background-color: #dddddd;
  }
  </style>
  <script>
  import html2pdf from "html2pdf.js";
  
  export default {
    data() {
      return {
        folders: [], //contains all the retrived folders from the database
  
        folder: {}, //for form single folder data
  
        modalForName: "",
        modalForCode: 0,
  
        searchTableKey: "",
  
        showbowlpdf: true,
        arrayKeys: ["id", "name", "description"],
        currentDateTime: "",
  
  
        errors: [],
        pagination: {},
        isLoading: "",
        folders_export_fileds: ["name", "description"],
      };
    },
    created() {
      //this block will execute when component created
      this.fetchFolders();
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
      fetchFolders(page_url) {
        this.$Progress.start();
        this.isLoading = "Loading all Data";
        page_url = page_url || "api/folders";
  
        let vm = this; // current pointer instance while going inside the another functional instance
        axios
          .get(page_url)
          .then(function (response) {
            vm.folders = response.data.data;
            // vm.arrayKeys = Object.keys(vm.folders[0]);
            console.log(vm.arrayKeys);
            // console.log(response.data);
            if (vm.folders.length != null) {
              vm.makePagination(response.data.meta, response.data.links);
              vm.$Progress.finish();
            }
            vm.isLoading = "";
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
        this.modalForName = "Add Folder";
        // Vue.set(this.modalForName,"Add Folder");
        this.modalForCode = 0; //0 for add
        this.folder.name = "";
        this.folder.description = "";
        this.errors = ""; //clearing errors
        // Vue.set(this.modalForCode,0);
        this.$bvModal.show("bv-modal-add-folder");
      },
      callFunc() {
        if (this.modalForCode == 0) {
          this.addFolder();
          // console.log("Add Folder");
        } else if (this.modalForCode == 1) {
          this.updateFolder();
          // console.log("Edit Folder");
        }
      },
      addFolder() {
        this.$Progress.start();
        let currObj = this;
        axios
          .post("/api/folder", this.folder)
          .then(function (response) {
            currObj.output = response.data.msg;
            currObj.status = response.data.status;
            currObj.$swal("Info", currObj.output, currObj.status);
  
            currObj.$bvModal.hide("bv-modal-add-folder");
  
            currObj.errors = ""; //clearing errors
  
            currObj.folder.name = "";
            currObj.folder.description = "";
            currObj.$Progress.finish();
  
            currObj.fetchFolders();
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
      editFolder(id) {
        this.$Progress.start();
        // //this.$Progress.start();
        this.modalForName = "Edit Folder";
        this.modalForCode = 1; // 1 for Edit
        this.$bvModal.show("bv-modal-add-folder");
        this.errors = ""; //clearing errors
        axios
          .get("/api/folder/" + id)
          .then((response) => {
            // console.log(response.data.folder)
            Vue.set(this.folder, "name", response.data.folder.name);
            Vue.set(
              this.folder,
              "description",
              response.data.folder.description
            );
            Vue.set(this.folder, "id", id); //to send id to the update controller
            this.$Progress.finish();
          })
          .catch((error) => {
            // console.log(error)
            this.$Progress.fail();
          });
      },
      updateFolder() {
        this.$Progress.start();
        let currObj = this;
        let formData = new FormData();
        formData.append("_method", "PUT"); //add this otherwise data won't pass to backend
        formData.append("name", this.folder.name);
        formData.append("description", this.folder.description);
        formData.append("id", this.folder.id);
  
        axios
          .post("/api/folder", formData)
          .then(function (response) {
            currObj.output = response.data.msg;
            currObj.status = response.data.status;
            // alert(currObj.status);
  
            currObj.$swal("Info", currObj.output, currObj.status);
            currObj.$bvModal.hide("bv-modal-add-folder");
  
            currObj.folder.name = "";
            currObj.folder.description = "";
            currObj.errors = ""; //clearing errors
            currObj.$Progress.finish();
  
            currObj.fetchFolders();
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
      deleteFolder(id) {
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
              .delete("/api/folder/" + id)
              .then(function (response) {
                currObj.output = response.data.msg;
                currObj.status = response.data.status;
                // alert(currObj.status);
  
                let index_to_delete = currObj.folders.findIndex(
                  (folder) => folder.id === id
                );
                currObj.folders.splice(index_to_delete, 1);
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
      }, //end of deleteFolder()
      searchTableBtn() {
        this.autoCompleteTable();
      },
      autoCompleteTable() {
        this.searchTableKey = this.searchTableKey.toLowerCase();
        if (this.searchTableKey != "") {
          this.isLoading = "Loading Data...";
          let currObj = this;
          axios
            .post("/api/folder/search", { searchQuery: this.searchTableKey })
            .then(function (response) {
              currObj.isLoading = "";
  
              currObj.folders = response.data.data;
  
              console.log(currObj.folders);
  
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
          this.fetchFolders();
        }
      }, //end of autoCOmpleteTable
  
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
  };
  </script>
  
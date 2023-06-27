<template>
  <div ref="document">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">File List</h1>
    <p class="mb-4" v-if="hasPermission('add_file')">
      <b-button
        id="show-btn"
        @click="showAddModal"
        class="btn btn-success"
        style="margin-top: 8px"
      >
        <span class="fa fa-plus-circle"></span> Add New File</b-button
      >
    </p>
    <!-- add file model start -->
    <b-modal id="bv-modal-add-file" hide-footer>
      <template v-slot:modal-title>
        {{ modalForName }}
      </template>
      <div class="d-block">
        <form enctype="multipart/form-data">
          <div class="form-group">
            <label for="Category">Folder:</label>
            <template>
              <select class="form-control" v-model="file.folder_id">
                <option
                  selected=""
                  v-for="folder in folders"
                  :value="folder.id"
                  v-bind:key="folder.id"
                >
                  {{ folder.name }}
                </option>
              </select>
              <span v-if="errors.folder_id" :class="['errorText']">{{
                errors.folder_id[0]
              }}</span>
            </template>
          </div>
          <div class="form-group">
            <label for="Name">Name</label>
            <input type="hidden" v-model="file.id" />
            <input
              type="text"
              :class="['form-control']"
              placeholder="File Name"
              v-model="file.name"
            />
            <span v-if="errors.name" :class="['errorText']">{{
              errors.name[0]
            }}</span>
          </div>
          <div class="form-group">
            <label for="Description">Description</label>
            <textarea
              :class="['form-control']"
              placeholder="File Description"
              v-model="file.description"
            ></textarea>
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
    <!-- add file modal end-->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" style="display: inline">
          Files
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
              file-name="files"
              :fields="files_export_fileds"
              :data="files"
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
              <h3>Files</h3>
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
                  <tr v-for="file in files" v-bind:key="file.id">
                    <template v-for="arrayKey in arrayKeys">
                      <td>{{ file[arrayKey] }}</td>
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

      <div class="card-body" v-if="files.length > 0">
        <div class="table">
          <table
            class="table table-striped table-bordered"
            width="100%"
            cellspacing="0"
          >
            <thead>
              <tr>
                <th>File</th>
                <th>Name</th>
                <!-- <th>Original File Name</th> -->
                <th>Description</th>
                <th>Uploaded By</th>
                <th>Uploaded At</th>
                <th
                  v-if="
                    hasPermission('edit_file') || hasPermission('delete_file')
                  "
                >
                  Edit
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="file in files" v-bind:key="file.id">
                <td>
                  <div class="folder-icon-holder">
                    <img
                      src="/assets/img/folder2.svg"
                      class="icon-for-folder"
                    />
                    <div class="folder-text">../{{ file.folder.name }}</div>
                    <div class="file-name-holder">
                      /{{ file.original_file_name }}
                    </div>
                  </div>
                </td>
                <td>{{ file.name }}</td>
                <!-- <td>{{ file.original_file_name }}</td> -->
                <td>{{ file.description }}</td>
                <td>{{ file.user.name }}</td>
                <td>{{ file.created_at }}</td>

                <!-- <td>{{ file.updated_at | moment("from", "now") }}</td> -->
                <td
                  v-if="
                    hasPermission('edit_file') || hasPermission('delete_file')
                  "
                >
                  <button
                    class="btn btn-danger custom_btn_table"
                    style="margin-right: 5px"
                    @click="downloadFile(file.id,file.original_file_name)"
                    v-if="hasPermission('download_file')"
                  >
                    <span class="fa fa-download custom_icon_table"></span>
                  </button>
                  <button
                    class="btn btn-success custom_btn_table"
                    style="margin-right: 5px"
                    @click="editFile(file.id)"
                    v-if="hasPermission('edit_file')"
                  >
                    <span class="fa fa-edit custom_icon_table"></span>
                  </button>
                  <button
                    class="btn btn-danger custom_btn_table"
                    @click="deleteFile(file.id)"
                    v-if="hasPermission('delete_file')"
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
                  @click="fetchFiles(pagination.first_link)"
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
                  @click="fetchFiles(pagination.prev_link)"
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
                  @click="fetchFiles(pagination.path_page + n)"
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
                  @click="fetchFiles(pagination.next_link)"
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
                  @click="fetchFiles(pagination.last_link)"
                  class="page-link"
                >
                  Last
                </button>
              </li>
            </ul>
          </div>
          <div class="col-md-4">
            Page: {{ pagination.current_page }}-{{ pagination.last_page }} Total
            Files: {{ pagination.total_pages }}
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
      files: [], //contains all the retrived files from the database

      file: {}, //for form single file data

      modalForName: "",
      modalForCode: 0,

      searchTableKey: "",

      showbowlpdf: true,
      arrayKeys: ["id", "name", "description"],
      currentDateTime: "",

      folders: [],

      uploadFile: "",

      selectedFile: "",

      imagePreview: "",

      errors: [],
      pagination: {},
      isLoading: "",
      files_export_fileds: ["name", "description"],
    };
  },
  created() {
    //this block will execute when component created
    this.fetchFiles();
    this.fetchFolders();
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
    fetchFiles(page_url) {
      this.$Progress.start();
      this.isLoading = "Loading all Data";
      page_url = page_url || "api/files";

      let vm = this; // current pointer instance while going inside the another functional instance
      axios
        .get(page_url)
        .then(function (response) {
          vm.files = response.data.data;
          // vm.arrayKeys = Object.keys(vm.files[0]);
          console.log(vm.arrayKeys);
          // console.log(response.data);
          if (vm.files.length != null) {
            vm.makePagination(response.data.meta, response.data.links);
            vm.$Progress.finish();
          }
          vm.isLoading = "";
        })
        .catch(function (error) {
          vm.$Progress.fail();
        });
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
          // console.log(response.data);
          if (vm.folders.length != null) {
            vm.makePagination(response.data.meta, response.data.links);
            vm.$Progress.finish();
          }
        })
        .catch(function (error) {
          // console.log();
          vm.$Progress.fail();
        });
    },
    setAvtarUploadImage() {
      this.imagePreview = "/img/upload_image.png";
    },

    fileSelected(e) {
      this.$Progress.start();
      // alert("File Selected");
      this.imagePreview = "/img/Rolling-1s-200px.svg";

      this.uploadFile = e.target.files[0];
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
        if (/\.(jpe?g|png|gif)$/i.test(this.uploadFile.name)) {
          /*
            Fire the readAsDataURL method which will read the file in and
            upon completion fire a 'load' event which we will listen to and
            display the image in the preview.
          */
          reader.readAsDataURL(this.uploadFile);
        }
      }

      this.$Progress.finish();
    }, //end of fileSelected
    downloadFile(file_id,original_file_name) {

      axios
        .get(`api/filedownload/${file_id}`, {
          responseType: "blob",
        })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]));

          const file_type=response.data.type.split("/");
         
          const file_extion=file_type[1];
          
          console.log(file_extion);

          const link = document.createElement("a");
          link.href =  url;
          link.setAttribute("download",original_file_name+"."+file_extion); //or any other extension
          document.body.appendChild(link);
          link.click();
        })
        .catch((error) => {
          console.log(error);
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
      this.modalForName = "Add File";
      // Vue.set(this.modalForName,"Add File");
      this.modalForCode = 0; //0 for add
      this.file.name = "";
      this.file.description = "";
      this.file.folder_id = "";

      this.errors = ""; //clearing errors
      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-file");
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addFile();
        // console.log("Add File");
      } else if (this.modalForCode == 1) {
        this.updateFile();
        // console.log("Edit File");
      }
    },
    addFile() {
      this.$Progress.start();
      let currObj = this;

      const config = {
        headers: { "content-type": "multipart/form-data" },
      };

      let formData = new FormData();
      formData.append("upload_file", this.uploadFile);
      formData.append("_method", "POST"); //add this otherwise data won't pass to backend
      // formData.append('id',this.product.id);
      formData.append("name", this.file.name);
      formData.append("description", this.file.description);
      formData.append("folder_id", this.file.folder_id);

      axios
        .post("/api/file", formData, config)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);

          currObj.$bvModal.hide("bv-modal-add-file");

          currObj.errors = ""; //clearing errors

          currObj.file.name = "";
          currObj.file.description = "";
          currObj.file.folder_id = "";

          currObj.setAvtarUploadImage();
          currObj.$Progress.finish();

          currObj.fetchFiles();
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
    editFile(id) {
      this.$Progress.start();
      // //this.$Progress.start();
      this.modalForName = "Edit File";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-file");
      this.errors = ""; //clearing errors
      axios
        .get("/api/file/" + id)
        .then((response) => {
          // console.log(response.data.file)
          Vue.set(this.file, "name", response.data.file.name);
          Vue.set(this.file, "description", response.data.file.description);
          Vue.set(this.file, "folder_id", response.data.file.folder_id);
          this.imagePreview = response.data.file.file_location;
          this.uploadFile = response.data.file.file_location;
          Vue.set(this.file, "id", id); //to send id to the update controller
          this.$Progress.finish();
        })
        .catch((error) => {
          // console.log(error)
          this.$Progress.fail();
        });
    },
    updateFile() {
      this.$Progress.start();
      let currObj = this;

      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("_method", "PUT"); //add this otherwise data won't pass to backend
      formData.append("upload_file", this.uploadFile);
      formData.append("name", this.file.name);
      formData.append("description", this.file.description);
      formData.append("folder_id", this.file.folder_id);

      formData.append("id", this.file.id);

      axios
        .post("/api/file", formData, config)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          // alert(currObj.status);

          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-file");

          currObj.file.name = "";
          currObj.file.description = "";
          currObj.file.folder_id = "";

          currObj.errors = ""; //clearing errors
          currObj.setAvtarUploadImage();

          currObj.$Progress.finish();

          currObj.fetchFiles();
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
    deleteFile(id) {
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
            .delete("/api/file/" + id)
            .then(function (response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              // alert(currObj.status);

              let index_to_delete = currObj.files.findIndex(
                (file) => file.id === id
              );
              currObj.files.splice(index_to_delete, 1);
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
    }, //end of deleteFile()
    searchTableBtn() {
      this.autoCompleteTable();
    },
    autoCompleteTable() {
      this.searchTableKey = this.searchTableKey.toLowerCase();
      if (this.searchTableKey != "") {
        this.isLoading = "Loading Data...";
        let currObj = this;
        axios
          .post("/api/file/search", { searchQuery: this.searchTableKey })
          .then(function (response) {
            currObj.isLoading = "";

            currObj.files = response.data.data;

            console.log(currObj.files);

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
        this.fetchFiles();
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
  
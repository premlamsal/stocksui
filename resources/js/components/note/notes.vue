<template>
  <div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Note</h1>
    <p class="mb-4" v-if="hasPermission('add_note')">
      <b-button
        id="show-btn"
        @click="showAddModal"
        class="btn btn-success"
        style="margin-top: 8px"
      >
        <span class="fa fa-plus-circle"></span> Add New Note</b-button
      >
    </p>
    <!-- add unit model start -->
    <b-modal id="bv-modal-add-note" hide-footer>
      <template v-slot:modal-title>
        <span class="text-primary">{{ modalForName }}</span>
      </template>
      <div class="d-block">
        <div class="form-group">
          <input type="hidden" v-model="note.id" />
          <label for="Title">Title:</label>
          <input type="text" v-model="note.title" :class="['form-control']" />
          <span v-if="errors.title" :class="['errorText']">{{
            errors.title[0]
          }}</span>
        </div>
        <div class="form-group">
          <label for="Description">Description:</label>
          <textarea
            v-model="note.description"
            :class="['form-control']"
          ></textarea>
          <span v-if="errors.description" :class="['errorText']">{{
            errors.description[0]
          }}</span>
          </div>
        <b-button class="btn-primary mt-3" block @click="callFunc">{{modalForName}}</b-button>
      </div>
      </b-modal>
    
    <!-- add unit modal end-->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6
          class="m-0 font-weight-bold text-primary"
          style="display: inline-block"
        >
          Notes
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
              file-name="notes"
              :fields="notes_export_fileds"
              :data="notes"
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
              <h3>Notes</h3>
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
                  <tr v-for="note in notes" v-bind:key="note.id">
                    <template v-for="arrayKey in arrayKeys">
                      <td>{{ note[arrayKey] }}</td>
                    </template>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- <span>{{isLoading}}</span> -->
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
      <div class="card-body" v-if="notes.length > 0">
        <div class="table">
          <table
            class="table table-striped table-bordered"
            width="100%"
            cellspacing="0"
          >
            <thead>
              <tr>
                <!-- <th>ID</th> -->
                <th>Title</th>
                <th>Description</th>
                <th>Created By</th>
                <th>Created At</th>
                <th  v-if="hasPermission('edit_note') || hasPermission('delete_note')">Edit</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="note in notes" v-bind:key="note.id">
                <!-- <td>{{note.id}}</td> -->
                <td class="cursor">{{ note.title }}</td>
                <td>{{ note.description }}</td>
                <td>{{ note.user.name }}</td>
                <td>{{ note.date }}</td>
                <td  v-if="hasPermission('edit_note') || hasPermission('delete_note')">
                  <button
                    class="btn btn-success custom_btn_table"
                    @click="editNote(note.id)"
                    v-if="hasPermission('edit_note')"
                  >
                    <span class="fa fa-edit custom_icon_table"></span>
                  </button>
                  <button
                    class="btn btn-danger custom_btn_table"
                    @click="deleteNote(note.id)"
                    v-if="hasPermission('delete_note')"
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
                  @click="fetchNotes(pagination.first_link)"
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
                  @click="fetchNotes(pagination.prev_link)"
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
                  @click="fetchNotes(pagination.path_page + n)"
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
                  @click="fetchNotes(pagination.next_link)"
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
                  @click="fetchNotes(pagination.last_link)"
                  class="page-link"
                >
                  Last
                </button>
              </li>
            </ul>
          </div>
          <div class="col-md-4">
            Page: {{ pagination.current_page }}-{{ pagination.last_page }} Total
            Notes: {{ pagination.total_pages }}
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
      notes: [], //contains all the retrived units from the database

      note: {}, //for form single unit data

      modalForName: "",
      modalForCode: 0,

      searchTableKey: "",
      errors: [],
      pagination: {},

      isLoading: "",

      // store_id: 3 ,

      showbowlpdf: true,
      arrayKeys: ["id", "title", "description","created_at","created_by"],
      currentDateTime: "",


      notes_export_fileds: ["title", "description", "created_at", "created_by"],
    };
  },
  created() {
    this.note.store_id = 3;

    //this block will execute when component created
    this.fetchNotes();
  },

  methods: {
    noteProfile(id) {
      // this.$router.push({ path: `${id}/note-profile/` });
    },
    //methods codes here
    fetchNotes(page_url) {
      this.$Progress.start();
      this.isLoading = "Loading all Data";
      let vm = this; // current pointer instance while going inside the another functional instance
      page_url = page_url || "api/notes";
      axios
        .get(page_url)
        .then(function (response) {
          vm.notes = response.data.data;
          if (vm.notes.length != null) {
            vm.makePagination(response.data.meta, response.data.links);
            vm.$Progress.finish();
          }
          vm.isLoading = "";
        })
        .catch(function (error) {
          // console.log();
          vm.$Progress.fail();
        });

      //above and below code provide same result but above code need current instance pointer for value assignmnent

      //below code donot need current pointer to be save becasue it execute in current block rather then another block that need previous pointer.

      // axios.get('/api/notes')
      // .then(response=>{
      //   // console.log(response.data.data)
      //   this.notes=response.data.data
      // })
      // .catch(error=>{
      //   console.log(error)
      // })
    },

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
      this.modalForName = "Add Note";
      // Vue.set(this.modalForName,"Add Unit");
      this.modalForCode = 0; //0 for add

      this.note.title = "";
      this.note.description = "";

      this.errors = ""; //clearing errors

      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-note");
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addNote();
        // console.log("Add Unit");
      } else if (this.modalForCode == 1) {
        this.updateNote();
        // console.log("Edit Unit");
      }
    },
    addNote() {
      this.$Progress.start();
      let currObj = this;
      axios
        .post("/api/note", this.note)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);

          currObj.$bvModal.hide("bv-modal-add-note");

          currObj.note.title = "";
          currObj.note.description = "";

          currObj.errors = ""; //clearing errors
          currObj.$Progress.finish();

          currObj.fetchNotes();
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
    editNote(id) {
      this.$Progress.start();
      let currObj = this;
      this.modalForName = "Edit Note";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-note");
      currObj.errors = ""; //clearing errors
      axios
        .get("/api/note/" + id)
        .then((response) => {
          // console.log(response.data.unit)
          Vue.set(this.note, "id", response.data.note.id);
          Vue.set(this.note, "title", response.data.note.title);
          Vue.set(this.note, "description", response.data.note.description);
          this.$Progress.finish();
        })
        .catch((error) => {
          // console.log(error)
          this.$Progress.fail();
        });
    },
    updateNote() {
      this.$Progress.start();
      let currObj = this;
      let formData = new FormData();
      formData.append("_method", "PUT"); //add this otherwise data won't pass to backend
      formData.append("title", this.note.title);
      formData.append("description", this.note.description);
      formData.append("id", this.note.id);

      axios
        .post("/api/note", formData)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          // alert(currObj.status);

          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-note");

          currObj.note.title = "";
          currObj.note.description = "";
          currObj.errors = ""; //clearing errors
          currObj.$Progress.finish();
          currObj.fetchNotes();
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
    deleteNote(id) {
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
            .delete("/api/note/" + id)
            .then(function (response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              // alert(currObj.status);

              let index_to_delete = currObj.notes.findIndex(
                (note) => note.id === id
              );
              currObj.notes.splice(index_to_delete, 1);
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
    searchTableBtn() {
      this.autoCompleteTable();
    },
    autoCompleteTable() {
      this.searchTableKey = this.searchTableKey.toLowerCase();
      if (this.searchTableKey != "") {
        this.isLoading = "Loading Data...";
        let currObj = this;
        axios
          .post("/api/notes/search", { searchQuery: this.searchTableKey })
          .then(function (response) {
            currObj.isLoading = "";

            currObj.notes = response.data.data;
            if (response.data.data == "") {
              currObj.isLoading = "No Data Found";
            }
            // if((this.estimates.length)!=null){
            // // currObj.makePagination(res.meta,res.links);
            // }
            // currObj.status=response.data.status;
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
        this.fetchNotes();
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
  
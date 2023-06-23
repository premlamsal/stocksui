<template>
  <div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Contact</h1>
    <p class="mb-4" v-if="hasPermission('add_contact')">
      <b-button id="show-btn" @click="showAddModal" class="btn btn-success" style="margin-top: 8px;">
        <span class="fa fa-plus-circle"></span> Add New Contact</b-button>
    </p>
    <!-- add unit model start -->
    <b-modal id="bv-modal-add-contact" hide-footer>
      <template v-slot:modal-title>
        <span class="text-primary">{{modalForName}}</span>
      </template>
      <div class="d-block">
        <div class="form-group">
          <input type="hidden" v-model="contact.id">
          <label for="Name">Name:</label>
          <!--  <input type="text"  v-model="contact.name" :class="['form-control', errors.name ? 'is-invalid' : '']"> -->
          <input type="text" v-model="contact.name" :class="['form-control']">
          <span v-if="errors.name" :class="['errorText']">{{ errors.name[0] }}</span>
        </div>
        <div class="form-group">
          <label for="Email">Email:</label>
          <input type="email" v-model="contact.email" :class="['form-control']">
          <span v-if="errors.email" :class="['errorText']">{{ errors.email[0] }}</span>
        </div>
        <div class="form-group">
          <label for="Phone">Phone:</label>
          <input type="phone" v-model="contact.phone" :class="['form-control']">
          <span v-if="errors.phone" :class="['errorText']">{{ errors.phone[0] }}</span>
        </div>
        <div class="form-group">
          <label for="Contact-Person">Role:</label>
          <input type="text" v-model="contact.role" :class="['form-control']">
          <span v-if="errors.role" :class="['errorText']">{{ errors.role[0] }}</span>
        </div>
        <div class="form-group">
          <label for="Phone">Company:</label>
          <input type="text" v-model="contact.company" :class="['form-control']">
          <span v-if="errors.company" :class="['errorText']">{{ errors.company[0] }}</span>
        </div>
      </div>
      <b-button class="btn-primary mt-3" block @click="callFunc">{{modalForName}}</b-button>
    </b-modal>
    <!-- add unit modal end-->
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" style="display: inline-block;">Contacts</h6>
         <div class="text-center" v-if="isLoading=='Loading all Data'">
          <b-spinner variant="success" label="Spinning"></b-spinner>
        </div>
          <div class="export-block">
            <template>
              <vue-blob-json-csv
              @success="handleSuccessExportCSV"
              @error="handleErrorExportCSV"
              file-type="csv"
              file-name="contacts"
              :fields="contacts_export_fileds"
              :data="contacts">
              
              <!-- <button class="btn btn-warning-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button> -->
                <img src="img/icon-red-csv.png" class="icon-red-csv-export" alt="Export data to CSV">
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
      <h3>Contacts</h3>
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
          <tr v-for="contact in contacts" v-bind:key="contact.id">
            <template v-for="arrayKey in arrayKeys">
              <td>{{ contact[arrayKey] }}</td>
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
            <input type="text" value="" class="form-control" placeholder="Search..." v-model="searchTableKey" @keyup.enter="searchTableBtn">
            <div class="input-group-append">
              <div class="input-group-text">
                <i class="nc-icon nc-zoom-split" @click="searchTableBtn"></i>
              </div>
            </div>
          </div>
          <!-- </div> -->
        </div>
      </div>
      <div class="card-body" v-if="contacts.length > 0">
        <div class="table">
          <table class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
              <tr>
                <!-- <th>ID</th> -->
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Company</th>
                <th>Modify</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="contact in contacts" v-bind:key="contact.id">
                <!-- <td>{{contact.id}}</td> -->
                <td @click="contactProfile(contact.id)" class="cursor">{{contact.name}}</td>
                <td>{{contact.email}}</td>
                <td>{{contact.phone}}</td>
                <td>{{contact.role}}</td>
                <td>{{contact.company}}</td>
                <td>
                  <button class="btn btn-success custom_btn_table" @click=editContact(contact.id) v-if="hasPermission('edit_contact')"><span class="fa fa-edit custom_icon_table"></span></button>
                  <button class="btn btn-danger custom_btn_table" @click=deleteContact(contact.id) v-if="hasPermission('delete_contact')"><span class="fa fa-trash custom_icon_table"></span></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-md-8">
            <ul class="pagination">
              <li class="page-item" v-bind:class="{disabled:!pagination.first_link}">
                <button @click="fetchContacts(pagination.first_link)" class="page-link">First</button>
              </li>
              <li class="page-item" v-bind:class="{disabled:!pagination.prev_link}">
                <button @click="fetchContacts(pagination.prev_link)" class="page-link">Previous</button>
              </li>
              <li v-for="n in pagination.last_page" v-bind:key="n" class="page-item" v-bind:class="{active:pagination.current_page == n}">
                <button @click="fetchContacts(pagination.path_page + n)" class="page-link">{{n}}</button>
              </li>
              <li class="page-item" v-bind:class="{disabled:!pagination.next_link}">
                <button @click="fetchContacts(pagination.next_link)" class="page-link">Next</button>
              </li>
              <li class="page-item" v-bind:class="{disabled:!pagination.last_link}">
                <button @click="fetchContacts(pagination.last_link)" class="page-link">Last</button>
              </li>
            </ul>
          </div>
          <div class="col-md-4">
            Page: {{pagination.current_page}}-{{pagination.last_page}} Total Records: {{pagination.total_pages}}
          </div>
        </div>
      </div>
      <div class="errorDivEmptyData" v-else>
        No Data Found
      </div>
    </div>
  </div>
</template>

<script>
import html2pdf from "html2pdf.js";

export default {

  data() {
    return {

      contacts: [], //contains all the retrived units from the database

      contact: {}, //for form single unit data

      modalForName: "",
      modalForCode: 0,

      searchTableKey: '',
      errors: [],
      pagination: {},

      isLoading: '',

      // store_id: 3 ,

      showbowlpdf: true,
      arrayKeys: ["name","email","phone","company","role"],
      currentDateTime: "",

      contacts_export_fileds:["name","email","Phone","company","role"],

    }
  },
  created() {


    this.contact.store_id = 3;

    //this block will execute when component created
    this.fetchContacts();


  },

  methods: {
      contactProfile(id){
      // this.$router.push({ path: `${id}/contact-profile/` });
    },
    //methods codes here
    fetchContacts(page_url) {
      this.$Progress.start();
       this.isLoading = "Loading all Data";
      let vm = this; // current pointer instance while going inside the another functional instance
      page_url = page_url || 'api/contacts'
      axios.get(page_url)
        .then(function(response) {
          vm.contacts = response.data.data;
          if ((vm.contacts.length) != null) {
            vm.makePagination(response.data.meta, response.data.links);
            vm.$Progress.finish();
          }
          vm.isLoading = '';

        })
        .catch(function(error) {
          // console.log();
          vm.$Progress.fail();
        });

      //above and below code provide same result but above code need current instance pointer for value assignmnent 

      //below code donot need current pointer to be save becasue it execute in current block rather then another block that need previous pointer.


      // axios.get('/api/contacts')
      // .then(response=>{
      //   // console.log(response.data.data)
      //   this.contacts=response.data.data
      // })
      // .catch(error=>{
      //   console.log(error)
      // })


    },


     handleSuccessExportCSV(){
      console.log("success Export");
    },
    handleErrorExportCSV(){
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
        next_link: links.next

      }
      this.pagination = pagination;
    },
    showAddModal() {
      this.modalForName = "Add Contact";
      // Vue.set(this.modalForName,"Add Unit");
      this.modalForCode = 0; //0 for add 

      this.contact.name = '';
      this.contact.email = '';
      this.contact.phone = '';
      this.contact.role = '';

      this.contact.company = '';
      this.contact.opening_balance = '';


      this.errors = ''; //clearing errors

      // Vue.set(this.modalForCode,0);
      this.$bvModal.show('bv-modal-add-contact');

    },
    callFunc() {

      if (this.modalForCode == 0) {
        this.addContact();
        // console.log("Add Unit");
      } else if (this.modalForCode == 1) {
        this.updateContact();
        // console.log("Edit Unit");
      }

    },
    addContact() {
      this.$Progress.start();
      let currObj = this;
      axios.post('/api/contact', this.contact)
        .then(function(response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal('Info', currObj.output, currObj.status);

          currObj.$bvModal.hide('bv-modal-add-contact');


          currObj.contact.name = '';
          currObj.contact.email = '';
          currObj.contact.phone = '';
          currObj.contact.role = '';

          currObj.contact.company = '';

          currObj.errors = ''; //clearing errors
          currObj.$Progress.finish();

          currObj.fetchContacts();

        })
        .catch(function(error) {
          currObj.$Progress.fail();
          if (error.response.status == 422) {
            currObj.validationErrors = error.response.data.errors;
            currObj.errors = currObj.validationErrors;
            // console.log(currObj.errors);
          }
        });



    },
    editContact(id) {
      this.$Progress.start();
      let currObj = this;
      this.modalForName = "Edit Contact";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show('bv-modal-add-contact');
      currObj.errors = ''; //clearing errors
      axios.get('/api/contact/' + id)
        .then(response => {
          // console.log(response.data.unit)
          Vue.set(this.contact, 'name', response.data.contact.name);
          Vue.set(this.contact, 'email', response.data.contact.email);
          Vue.set(this.contact, 'role', response.data.contact.role);

          Vue.set(this.contact, 'company', response.data.contact.company);
          Vue.set(this.contact, 'phone', response.data.contact.phone);
          Vue.set(this.contact, 'id', id); //to send id to the update controller 
          this.$Progress.finish();
        })
        .catch(error => {
          // console.log(error)
          this.$Progress.fail();
        })

    },
    updateContact() {
      this.$Progress.start();
      let currObj = this;
      let formData = new FormData();
      formData.append('_method', 'PUT'); //add this otherwise data won't pass to backend
      formData.append('name', this.contact.name);
      formData.append('email', this.contact.email);
      formData.append('phone', this.contact.phone);
      formData.append('role', this.contact.role);

      formData.append('id', this.contact.id);
      formData.append('company',this.contact.company);

      axios.post('/api/contact', formData)
        .then(function(response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          // alert(currObj.status);

          currObj.$swal('Info', currObj.output, currObj.status);
          currObj.$bvModal.hide('bv-modal-add-contact');

          currObj.contact.name = '';
          currObj.contact.email = '';
          currObj.contact.role = '';

          currObj.contact.phone = '';
          currObj.contact.company = '';
          currObj.contact.opening_balance = '';
          currObj.errors = ''; //clearing errors
          currObj.$Progress.finish();
          currObj.fetchContacts();

        }).catch(function(error) {
          currObj.$Progress.fail();
          if (error.response.status == 422) {
            currObj.validationErrors = error.response.data.errors;
            currObj.errors = currObj.validationErrors;
            // console.log(currObj.errors);
          }
        })



    },
    deleteContact(id) {
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
          axios.delete('/api/contact/' + id)
            .then(function(response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              // alert(currObj.status);
             
              let index_to_delete = currObj.contacts.findIndex(contact => contact.id === id)
              currObj.contacts.splice(index_to_delete,1);
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
    searchTableBtn() {
      this.autoCompleteTable();
    },
    autoCompleteTable() {

      this.searchTableKey = this.searchTableKey.toLowerCase();
      if (this.searchTableKey != '') {
        this.isLoading = 'Loading Data...';
        let currObj = this;
        axios.post('/api/contacts/search', { searchQuery: this.searchTableKey })
          .then(function(response) {

            currObj.isLoading = '';

            currObj.contacts = response.data.data;
            if (response.data.data == "") {

              currObj.isLoading = "No Data Found";

            }
            // if((this.estimates.length)!=null){
            // // currObj.makePagination(res.meta,res.links);
            // }
            // currObj.status=response.data.status;
            currObj.errors = ''; //clearing errors

          })
          .catch(function(error) {
            if (error.response.status == '422') {
              currObj.validationErrors = error.response.data.errors;
              currObj.errors = currObj.validationErrors;
              currObj.isLoading = 'Load Failed...';
              // console.log(currObj.errors);

            }
          });
      } else {
        this.isLoading = "Loading all Data";
        this.fetchContacts();
      }

    }, //end of autoCOmpleteTable
    hasPermission(action) {
      let permissions_from_store = this.$store.getters.permissions

      if (permissions_from_store.includes(action) || permissions_from_store.includes('all')) {
        return true
      } else {
        return false
      }

    } //has permision



    //end of methods block
  }

}
</script>

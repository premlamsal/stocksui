<template>
    <div>
      <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Contact</h1>
      <p class="mb-4" v-if="hasPermission('add_contact')">
        <b-button id="show-btn" @click="showAddModal" class="btn btn-success" style="margin-top: 8px;">
          <span class="fa fa-plus-circle"></span> Add Contact</b-button>
      </p>
      <!-- add unit model start -->
   
      <!-- add unit modal end-->
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary" style="display: inline-block;">Contacts</h6>
           <div class="text-center" v-if="isLoading=='Loading all Data'">
            <b-spinner variant="success" label="Spinning"></b-spinner>
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
          
          </div>
         
        </div>
        <div class="errorDivEmptyData" v-else>
          No Data Found
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
  
    data() {
      return {
  
        isLoading: '',
  
   
      }
    },
    created() {
  
  
  
  
  
    },
  
    methods: {
      
  
  
  
  
      //end of methods block
    }
  
  }
  </script>
  
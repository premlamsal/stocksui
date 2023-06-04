<template>
  <div>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Calendar</h1>
    <p class="mb-4" v-if="hasPermission('add_event')">
      <b-button
        id="show-btn"
        @click="showAddModal()"
        class="btn btn-success"
        style="margin-top: 8px"
      >
        <span class="fa fa-plus-circle"></span> Add Event</b-button
      >
    </p>

    <!-- add event model start -->
    <b-modal id="bv-modal-add-event" hide-footer>
      <template v-slot:modal-title>
        <span class="text-primary">{{ modalForName }}</span>
      </template>
      <div class="d-block">
        <div class="form-group">
          <input type="hidden" v-model="event.id" />
          <label for="Title">Title:</label>
          <!--  <input type="text"  v-model="event.title" :class="['form-control', errors.title ? 'is-invalid' : '']"> -->
          <input type="text" v-model="event.title" :class="['form-control']" />
          <span v-if="errors.title" :class="['errorText']">{{
            errors.title[0]
          }}</span>
        </div>

        <div class="form-group">
          <label for="Date">Date:</label>

          <date-picker
            v-model="event.date"
            :config="options"
            :class="['form-control']"
          ></date-picker>

          <span v-if="errors.date" :class="['errorText']">{{
            errors.date[0]
          }}</span>
        </div>
      </div>
      <b-button class="btn-primary mt-3" block @click="callFunc">{{
        modalForName
      }}</b-button>
    </b-modal>
    <!-- add event modal end-->

    <div class="card shadow mb-4">
      <!-- <div class="card-header py-3">
        <h6
          class="m-0 font-weight-bold text-primary"
          style="display: inline-block"
        >
          Calendars
        </h6>
      </div> -->
      <div class="card-body">
        <FullCalendar :options="calendarOptions" />
      </div>
    </div>
  </div>
</template>
  
  <script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
  },
  data() {
    return {
      event: {},

      modalForName: "",
      modalForCode: 0,
      errors: [],
      options: {
        format: "YYYY-MM-DD",
        useCurrent: true,
        showClear: true,
        showClose: true,
      },
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: this.handleDateClick,
        eventClick: this.handleEventClick,
        events: [],
      },
    };
  },
  created() {
    this.fetchEvents();
  },

  methods: {
    fetchEvents() {
      this.$Progress.start();
      axios
        .get("/api/events")
        .then((response) => {
          this.calendarOptions.events = response.data.data;
          this.$Progress.finish();
        })
        .catch((error) => {
          console.log(error);
          this.$Progress.fail();
        });
    },
    showAddModal() {
      this.modalForName = "Add Event";
      // Vue.set(this.modalForName,"Add Unit");
      this.modalForCode = 0; //0 for add

      this.event.title = "";
      this.event.date = "";
      this.errors = ""; //clearing errors

      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-event");
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addEvent();
      } else if (this.modalForCode == 1) {
        this.updateEvent();
        // console.log("Edit Unit");
      }
    },
    addEvent() {
      this.$Progress.start();
      let currObj = this;
      axios
        .post("/api/event", this.event)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          currObj.$swal("Info", currObj.output, currObj.status);

          currObj.$bvModal.hide("bv-modal-add-event");

          currObj.event.title = "";
          currObj.event.date = "";

          currObj.errors = ""; //clearing errors
          currObj.$Progress.finish();

          currObj.fetchEvents();
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
    editEvent(id) {
      this.$Progress.start();
      let currObj = this;
      this.modalForName = "Edit Event";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-event");
      currObj.errors = ""; //clearing errors
      axios
        .get("/api/event/" + id)
        .then((response) => {
          // console.log(response.data.unit)
          Vue.set(this.event, "title", response.data.event.title);
          Vue.set(this.event, "date", response.data.event.date);
          Vue.set(this.event, "id", id); //to send id to the update controller
          this.$Progress.finish();
        })
        .catch((error) => {
          // console.log(error)
          this.$Progress.fail();
        });
    },
    updateEvent() {
      this.$Progress.start();
      let currObj = this;
      let formData = new FormData();
      formData.append("_method", "PUT"); //add this otherwise data won't pass to backend
      formData.append("title", this.event.name);
      formData.append("date", this.event.company);
      formData.append("id", this.event.id);

      axios
        .post("/api/event", formData)
        .then(function (response) {
          currObj.output = response.data.msg;
          currObj.status = response.data.status;
          // alert(currObj.status);

          currObj.$swal("Info", currObj.output, currObj.status);
          currObj.$bvModal.hide("bv-modal-add-event");

          currObj.event.title = "";
          currObj.event.date = "";
          currObj.event.id = "";
          currObj.$Progress.finish();
          currObj.fetchEvents();
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
    deleteEvent(id) {
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
            .delete("/api/event/" + id)
            .then(function (response) {
              currObj.output = response.data.msg;
              currObj.status = response.data.status;
              currObj.$Progress.finish();
              currObj.$swal("Info", currObj.output, currObj.status);
              currObj.fetchEvents();
            })
            .catch(function (error) {
              currObj.$Progress.fail();
            });
        }
      });
    }, //end of deleteUnit()
    handleDateClick: function (arg) {
      alert("date click! " + arg.dateStr);
    },
    handleEventClick(clickInfo) {
      //   if (
      //     confirm(
      //       `Are you sure you want to delete the event '${clickInfo.event.title}'`
      //     )
      //   ) {

      this.editEvent(clickInfo.event.id);
      // clickInfo.event.remove();
      // console.log(this.calendarOptions.events)
      // console.log(clickInfo.event.id);
      //   }
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
};
</script>
  
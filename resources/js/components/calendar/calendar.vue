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
          <input type="text" v-model="event.title" :class="['form-control']" :disabled="!hasPermission('add_event') || !hasPermission('edit_event') || !hasPermission('show_event')" />
          <span v-if="errors.title" :class="['errorText']">{{
            errors.title[0]
          }}</span>
        </div>

        <div class="form-group">
          <label for="Start">Start Date</label>

          <date-picker
            v-model="event.start"
            :config="options"
            :class="['form-control']"
            :disabled="!hasPermission('add_event') || !hasPermission('edit_event') || !hasPermission('show_event')" 
          ></date-picker>

          <span v-if="errors.start" :class="['errorText']">{{
            errors.start[0]
          }}</span>
        </div>

        <div class="form-group">
          <label for="End">End Date</label>

          <date-picker
            v-model="event.end"
            :config="options"
            :class="['form-control']"
            :disabled="!hasPermission('add_event') || !hasPermission('edit_event') || !hasPermission('show_event')" 
          ></date-picker>

          <span v-if="errors.end" :class="['errorText']">{{
            errors.end[0]
          }}</span>
        </div>

        <div class="form-group">
          <label for="Description">Description:</label>
          <textarea
            v-model="event.description"
            :class="['form-control']"
            :disabled="!hasPermission('add_event') || !hasPermission('edit_event') || !hasPermission('show_event')" 
          ></textarea>
          <span v-if="errors.description" :class="['errorText']">{{
            errors.description[0]
          }}</span>
        </div>

        <div class="form-group" >
          <label for="color-picker" v-if="hasPermission('add_event') || hasPermission('edit_event')" >Pick a color for event:</label>
          <!-- Red - Holiday- F44336
Blue - Interview-#2196F3
Green - Meeting#4CAF50
Yellow - Other #ff9800-->
          <div class="event-color-container">
            <div
              class="event-color red"
              @click="setEventColor('holiday')"
              :class="{ 'color-clicked': event.type == 'holiday' }"
            >
              Holiday
            </div>
            <div
              class="event-color blue"
              @click="setEventColor('interview')"
              :class="{ 'color-clicked': event.type == 'interview' }"
            >
              Interview
            </div>
            <div
              class="event-color green"
              @click="setEventColor('meeting')"
              :class="{ 'color-clicked': event.type == 'meeting' }"
            >
              Meeting
            </div>
            <div
              class="event-color orange"
              @click="setEventColor('other')"
              :class="{ 'color-clicked': event.type == 'other' }"
            >
              Other
            </div>
          </div>
        </div>
      </div>

      <b-button class="btn-primary mt-3" block @click="callFunc" v-if="hasPermission('add_event') || hasPermission('edit_event')">{{
        modalForName
      }}</b-button>
      <div  v-if="hasPermission('delete_event')">
      <b-button
        class="btn-danger mt-3"
        block
        @click="deleteEvent(event.id)"
        v-if="modalForCode"
        >Delete this event</b-button
      >
    </div>
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
        <FullCalendar
          :options="calendarOptions"
          :header="{
            left: 'prev, next today',
            center: 'title',
            right: 'dayGridMonth, timeGridWeek, timeGridDay, listWeek',
          }"
        />
      </div>
    </div>
  </div>
</template>
  
  <script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import ColorPicker from "./ColorPicker";
import moment from "moment";
export default {
  components: {
    timeGridPlugin,
    dayGridPlugin,
    interactionPlugin,
    ColorPicker,
    FullCalendar, // make the <FullCalendar> tag available
  },
  data() {
    return {
      event: {
        type: "",
        back_color: "rgb(104,22,22)",
        text_color: "rgb(255,255,255)",
        title: "",
        start: "",
        end: "",
        description: "",
      },

      modalForName: "",
      modalForCode: 0,
      errors: [],
      options: {
        format: "DD-MM-YYYY hh:mm:ss a",
        useCurrent: true,
        showClear: true,
        showClose: true,
      },
      calendarOptions: {
        titleFormat: { year: "numeric", month: "long" },
        eventTimeFormat: {
          hour: "numeric",
          // minute: '2-digit',
          omitZeroMinute: true,
          meridiem: "short",
          // hour: 'numeric',
          // minute: "2-digit",
          // second: "2-digit",
          // hour12: true, //this also enables am or pm if true
          // meridiem: false   this enables am or pm
        },

        headerToolbar: {
          left: "prev,next today",
          center: "title",
          right: "dayGridMonth,timeGridWeek,timeGridDay",
        },
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: this.handleDateClick,
        eventClick: this.handleEventClick,
        // dayMaxEvents: true,
        weekends: true,
        events: [],
      },
    };
  },
  created() {
    this.fetchEvents();
  },

  methods: {
    fetchEvents() {
      if (this.hasPermission("view_events")) {
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
      }
    },
    removeEventColor() {
      this.event.type = "";
      this.event.back_color = "";
    },
    setEventColor(temp) {
      if(this.hasPermission('add_event')|| this.hasPermission('edit_event')){
      // Red - Holiday #F44336
      // Blue - Interview #2196F3
      // Green - Meeting #4CAF50
      // Yellow - Other #ff9800
      if (temp === "holiday") {
        this.event.type = "holiday";
        this.event.back_color = "#F44336";
      } else if (temp === "interview") {
        this.event.back_color = "#2196F3";
        this.event.type = "interview";
      } else if (temp === "meeting") {
        this.event.back_color = "#4CAF50";
        this.event.type = "meeting";
      } else if (temp === "other") {
        this.event.back_color = "#ff9800";
        this.event.type = "other";
      } else {
        this.event.back_color = "#eee";
        this.event.type = "nothing";
      }
      console.log(this.event.back_color);
      console.log(this.event.type);
    }
  },
    // selectColor(color){
    //   this.event = {
    //     ...this.event,
    //     cssClass: color,
    //     back_color:color,
    //   }
    // },
    showAddModal(date) {
      if (this.hasPermission("add_event")) {
        this.removeEventColor();
        this.modalForName = "Add Event";
        // Vue.set(this.modalForName,"Add Unit");
        this.modalForCode = 0; //0 for add

        this.event.title = "";
        if (date) {
          this.event.start = date;
        } else {
          this.event.start = "";
        }
        this.event.end = "";
        this.event.description = "";
        this.event.back_color = "";
        this.event.text_color = "";

        this.errors = ""; //clearing errors

        // Vue.set(this.modalForCode,0);
        this.$bvModal.show("bv-modal-add-event");
      }
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
      if (this.hasPermission("add_event")) {
        // this.event.start = moment(this.event.start).format();
        // this.event.end = moment(this.event.end).format();

        // console.log(moment(date).format());

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
            currObj.event.start = "";
            currObj.event.end = "";
            currObj.event.back_color = "";
            currObj.event.text_color = "";

            currObj.event.description = "";

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
      }
    },
    editEvent(id) {
      

        this.$Progress.start();
        this.removeEventColor();

        let currObj = this;
        if(this.hasPermission('edit_event')){
          this.modalForName = "Edit Event";
        this.modalForCode = 1; // 1 for Edit
        }else{
          this.modalForName = "View Event";
        }
      
        this.$bvModal.show("bv-modal-add-event");
        currObj.errors = ""; //clearing errors
        axios
          .get("/api/event/" + id)
          .then((response) => {
            // console.log(response.data.unit)
            Vue.set(this.event, "title", response.data.event.title);
            Vue.set(this.event, "start", response.data.event.start);
            // Vue.set(this.event, "cssClass", response.data.event.back_color);

            let temp = response.data.event.back_color;
            if (temp === "#F44336") {
              Vue.set(this.event, "type", "holiday");
              Vue.set(this.event, "back_color", "#F44336");
            } else if (temp === "#2196F3") {
              Vue.set(this.event, "type", "interview");
              Vue.set(this.event, "back_color", "#2196F3");
            } else if (temp === "#4CAF50") {
              Vue.set(this.event, "type", "meeting");
              Vue.set(this.event, "back_color", "#4CAF50");
            } else if (temp === "#ff9800") {
              Vue.set(this.event, "type", "other");
              Vue.set(this.event, "back_color", "#ff9800");
            } else {
              Vue.set(this.event, "type", "nothing");
              Vue.set(this.event, "back_color", "#eee");
            }

            // Vue.set(this.event, "back_color", response.data.event.back_color);
            // Vue.set(this.event, "text_color", response.data.event.text_color);
            Vue.set(this.event, "end", response.data.event.end);
            Vue.set(this.event, "description", response.data.event.description);

            Vue.set(this.event, "id", id); //to send id to the update controller
            this.$Progress.finish();
          })
          .catch((error) => {
            // console.log(error)
            this.$Progress.fail();
          });
      
    },
    updateEvent() {
      if (this.hasPermission("edit_event")) {
        this.$Progress.start();
        // this.event.start = moment(this.event.start).format();
        // this.event.end = moment(this.event.end).format();

        let currObj = this;
        let formData = new FormData();
        formData.append("_method", "PUT"); //add this otherwise data won't pass to backend
        formData.append("title", this.event.title);
        formData.append("start", this.event.start);
        formData.append("back_color", this.event.back_color);
        formData.append("text_color", this.event.text_color);
        formData.append("end", this.event.end);
        formData.append("description", this.event.description);
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
            currObj.event.start = "";
            currObj.event.end = "";
            currObj.event.back_color = "";
            currObj.event.text_color = "";
            currObj.event.description = "";
            currObj.event.id = "";
            currObj.$Progress.finish();
            currObj.removeEventColor();

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
      }
    },
    deleteEvent(id) {
      if (this.hasPermission("delete_event")) {
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
                currObj.$bvModal.hide("bv-modal-add-event");

                currObj.event.title = "";
                currObj.event.start = "";
                currObj.event.end = "";
                currObj.event.back_color = "";
                currObj.event.text_color = "";
                currObj.event.description = "";
                currObj.event.id = "";
                currObj.removeEventColor();

                currObj.fetchEvents();
              })
              .catch(function (error) {
                currObj.$Progress.fail();
              });
          }
        });
      }
    }, //end of deleteUnit()
    handleDateClick: function (arg) {
      // alert("date click! " + arg.dateStr);
      const date = moment(arg.dateStr).format("DD-MM-YYYY");
      this.showAddModal(date);
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
<style scoped>
.verte {
  border: 2px solid #9e9e9e;
  border-radius: 15px;
}
.red {
  background: #f44336 !important;
  color: whitesmoke !important;
}
.blue {
  background: #2196f3 !important;
  color: whitesmoke !important;
}
.orange {
  background: #ff9800 !important;
  color: whitesmoke !important;
}
.green {
  background: #4caf50 !important;
  color: white !important;
}
.blue,
.orange,
.red,
.green {
  font-size: 13px;
  font-weight: 500;
  text-transform: capitalize;
}
.event-item {
  padding: 2px 0 2px 4px !important;
}
.event-color {
  padding: 20px;
  /* border-radius: 30px; */
  cursor: pointer;
  /* padding-right: 35px; */
  /* padding-left: 35px; */
  font-weight: bold;
  border: 1px solid #040f15;
  /* transition: all 0.2s ease-out; */
}
.event-color-container {
  margin-top: 10px;
  display: flex;
  justify-content: space-between;
}

.color-clicked {
  color: red;

  border: 1px solid #040f15;
  box-shadow: 2px 6px 0px -2px #000;
}
.event-color:hover {
}
.event-color:active {
  transform: translateY(4px);
}
table.fc-col-header a {
  color: #000 !important;
}
</style>
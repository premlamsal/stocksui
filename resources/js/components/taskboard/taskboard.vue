<template>
  <div>
    <p class="mb-4" v-if="hasPermission('add_task')">
      <b-button
        id="show-btn"
        @click="showAddModal()"
        class="btn btn-success"
        style="margin-top: 8px"
      >
        <span class="fa fa-plus-circle"></span> Add New Task</b-button
      >
    </p>

    <b-modal id="bv-modal-add-task" hide-footer>
      <template v-slot:modal-title>
        <span class="text-primary">{{ modalForName }}</span>
      </template>
      <div class="d-block">
        <div class="form-group">
          <input type="hidden" v-model="task.id" />
          <label for="Title">Title:</label>
          <input type="text" v-model="task.title" :class="['form-control']" />
          <span v-if="errors.title" :class="['errorText']">{{
            errors.title[0]
          }}</span>
        </div>

        <div class="form-group">
          <label for="Phone">Content:</label>
          <textarea v-model="task.content" :class="['form-control']"></textarea>
          <span v-if="errors.content" :class="['errorText']">{{
            errors.content[0]
          }}</span>
        </div>
      </div>
      <b-button class="btn-primary mt-3" block @click="callFunc">{{
        modalForName
      }}</b-button>
    </b-modal>

    <div style="background: #fff; border-radius: 10px">
      <div class="outside-status-head-container">
        <div class="status-head-container">
          <div class="status-head">
            <h5>Todos</h5>
          </div>
          <div class="status-head">
            <h5>In Progress</h5>
          </div>
          <div class="status-head">
            <h5>Done</h5>
          </div>
        </div>
      </div>
      <div class="row-bar-contianer">
        <div class="row-bar">
          <h5 style="background: #f44336">HIGH</h5>
        </div>
      </div>
      <div class="row mt-2 custom-row">
        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>To Do</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.toDo.priorityHIGH"
              group="one"
              @change="log"
            >
              <div
                v-for="element in meroTasks.toDo.priorityHIGH"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>HIGH</h4> -->
                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>

        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>IN PRGOGESS</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.arrInProgress.priorityHIGH"
              group="one"
              @change="log"
            >
              <div
                v-for="element in meroTasks.arrInProgress.priorityHIGH"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>HIGH</h4> -->
                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>
        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>DONE</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.arrDone.priorityHIGH"
              group="one"
              @change="log"
            >
              <div
                v-for="element in meroTasks.arrDone.priorityHIGH"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>HIGH</h4> -->
                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>
      </div>
      <div class="row-bar-contianer">
        <div class="row-bar">
          <h5 style="background: #ffc107">MED</h5>
        </div>
      </div>
      <div class="row mt-2 custom-row">
        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>To Do</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.toDo.priorityMED"
              group="one"
            >
              <div
                v-for="element in meroTasks.toDo.priorityMED"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>MED</h4> -->

                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>

        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>IN PRGOGESS</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.arrInProgress.priorityMED"
              group="one"
              @change="log"
            >
              <div
                v-for="element in meroTasks.arrInProgress.priorityMED"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>MED</h4> -->

                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>
        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>DONE</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.arrDone.priorityMED"
              group="one"
              @change="log"
            >
              <div
                v-for="element in meroTasks.arrDone.priorityMED"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>MED</h4> -->
                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>
      </div>
      <div class="row-bar-contianer">
        <div class="row-bar">
          <h5 style="background: #4caf50">LOW</h5>
        </div>
      </div>
      <div class="row mt-2 custom-row">
        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>To Do</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.toDo.priorityLOW"
              group="one"
              @change="log"
            >
              <div
                v-for="element in meroTasks.toDo.priorityLOW"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>LOW</h4> -->

                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>

        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>IN PRGOGESS</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.arrInProgress.priorityLOW"
              group="one"
              @change="log"
            >
              <div
                v-for="element in meroTasks.arrInProgress.priorityLOW"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>LOW</h4> -->

                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>
        <div class="col-3">
          <div class="p-2 alert alert-secondary">
            <!-- <h4>DONE</h4> -->
            <!-- Backlog draggable component. Pass toDo to list prop -->
            <draggable
              class="list-group kanban-column"
              :list="meroTasks.arrDone.priorityLOW"
              group="one"
              @change="log"
            >
              <div
                v-for="element in meroTasks.arrDone.priorityLOW"
                :key="element.id"
              >
                <div
                  class="list-group-item"
                  @click="editTask(element.id, element.title, element.content)"
                >
                  <!-- <h4>LOW</h4> -->
                  <div class="task-tittle">
                    {{ element.title }}
                  </div>
                  <div class="task-body">
                    <p>{{ element.content }}</p>
                  </div>
                </div>
              </div>
            </draggable>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
//import draggable
import draggable from "vuedraggable";
import axios from "axios";

export default {
  name: "kanban-board",
  components: {
    //import draggable as a component
    draggable,
  },
  data() {
    return {
      modalForName: "",
      modalForCode: 0,
      errors: [],
      // for new tasks
      task: {
        id: "",
        title: "",
        content: "",
      },
      // 4 arrays to keep track of our 4 statuses
      // toDo: [
      //   {
      //     id: "1",
      //     title: "Code Sign Up Page",
      //     priority: "HIGH",
      //   },
      //   {
      //     id: "2",
      //     title: "Code Sign Up janta",
      //     priority: "HIGH",
      //   },
      //   {
      //     id: "3",
      //     title: "Code Sign Up three",
      //     priority: "HIGH",
      //   },
      //   {
      //     id: "4",
      //     title: "Code Sign Up two",
      //     priority: "MED",
      //   },
      //   {
      //     id: "5",
      //     title: "Code Sign Up one",
      //     priority: "LOW",
      //   },
      //   {
      //     id: "6",
      //     title: "Code Sign Up one",
      //     priority: "LOW",
      //   },
      // ],
      // arrInProgress: [
      //   {
      //     id: "7",
      //     title: "Code Sign Up Page",
      //     priority: "HIGH",
      //   },
      //   {
      //     id: "8",
      //     title: "Code Sign Up Page",
      //     priority: "MED",
      //   },
      //   {
      //     id: "9",
      //     title: "Code Sign Up Page",
      //     priority: "LOW",
      //   },
      // ],
      // arrDone: [
      //   {
      //     id: "10",
      //     title: "Code Sign Up Page",
      //     priority: "HIGH",
      //   },
      //   {
      //     id: "11",
      //     title: "Code Sign Up Page",
      //     priority: "MED",
      //   },
      //   {
      //     id: "12",
      //     title: "Code Sign Up Page",
      //     priority: "LOW",
      //   },
      // ],
      meroTasks: {
        toDo: {
          priorityHIGH: [],
          priorityMED: [],

          priorityLOW: [],
        },
        arrInProgress: {
          priorityHIGH: [],
          priorityMED: [],

          priorityLOW: [],
        },
        arrDone: {
          priorityHIGH: [],
          priorityMED: [],

          priorityLOW: [],
        },
      },
    };
  },
  computed: {
    // highTasks() {
    //   return this.items.filter((item) => item.list === 1);
    // },
    // medTasks() {
    //   return this.items.filter((item) => item.list === 2);
    // },
    // lowTasks() {
    //   return this.items.filter((item) => item.list === 2);
    // },
  },
  watch: {
    // meroTasks: {
    //   deep: true,
    //   handler(newData) {
    //     // Perform an API call to update the data in the Laravel backend
    //     this.updateDataInBackend(newData);
    //   },
    // },
  },
  created() {
    this.getTasks();
  },
  methods: {
    getTasks() {
      axios
        .get("/api/tasks")
        .then((response) => {
          const data = JSON.parse(response.data.data.tasks);

          this.meroTasks = data;
          // Vue.set(this.meroTasks, JSON.parse(data));
        })
        .catch((error) => {
          console.log(error);
        });
    },
    updateDataInBackend: _.debounce(function () {
      try {
        axios.post("/api/update-tasks", {
          data: this.meroTasks,
        });
        console.log("Data updated in the backend successfully!");
      } catch (error) {
        console.error("Failed to update data in the backend:", error);
      }
    }, 900),
    showAddModal() {
      this.modalForName = "Add Task";
      // Vue.set(this.modalForName,"Add ");
      this.modalForCode = 0; //0 for add

      this.task.title = "";
      this.task.content = "";

      this.errors = ""; //clearing errors

      // Vue.set(this.modalForCode,0);
      this.$bvModal.show("bv-modal-add-task");
    },
    callFunc() {
      if (this.modalForCode == 0) {
        this.addTask();
        // console.log("Add ");
      } else if (this.modalForCode == 1) {
        this.updateTask();
        // console.log("Edit ");
      }
    },
    addTask() {
      this.$Progress.start();

      const pushTask = {
        id: Date.now(),
        title: this.task.title,
        content: this.task.content,
      };

      this.meroTasks.toDo.priorityHIGH.push(pushTask);
      this.$bvModal.hide("bv-modal-add-task");
      this.updateDataInBackend();
      // let currObj = this;
      // axios.post('/api/task', this.task)
      //   .then(function(response) {
      //     currObj.output = response.data.msg;
      //     currObj.status = response.data.status;
      //     currObj.$swal('Info', currObj.output, currObj.status);

      //     currObj.$bvModal.hide('bv-modal-add-task');

      //     currObj.task.title = '';
      //     currObj.task.content = '';
      //     currObj.errors = ''; //clearing errors
      //     currObj.$Progress.finish();

      //     currObj.fetchSuppliers();

      //   })
      //   .catch(function(error) {
      //     currObj.$Progress.fail();
      //     if (error.response.status == 422) {
      //       currObj.validationErrors = error.response.data.errors;
      //       currObj.errors = currObj.validationErrors;
      //       // console.log(currObj.errors);
      //     }
      //   });
    },
    editTask(id, title, content) {
      this.$Progress.start();
      this.modalForName = "Edit Task";
      this.modalForCode = 1; // 1 for Edit
      this.$bvModal.show("bv-modal-add-task");
      this.errors = ""; //clearing errors

      Vue.set(this.task, "id", id);
      Vue.set(this.task, "title", title);
      Vue.set(this.task, "content", content);
    },

    updateTask() {
      this.$Progress.start();

      // Assuming you have the ID of the task you want to edit and the updated data
      const taskId = this.task.id;
      const updatedTaskData = {
        // Updated properties of the task
        title: this.task.title,
        content: this.task.content,
      };

      // Find the task in the 'meroTasks' data structure
      for (const categoryKey in this.meroTasks) {
        const category = this.meroTasks[categoryKey];

        for (const priorityKey in category) {
          const priority = category[priorityKey];

          const foundTask = priority.find((task) => task.id === taskId);

          if (foundTask) {
            // Update the data within the found task object
            Object.assign(foundTask, updatedTaskData);
            // Optionally, you can trigger any necessary updates or save the changes to a database
            console.log(this.meroTasks);
            break; // Exit the loop since the task has been found
          }
        }
      }

      this.updateDataInBackend();
    },

    deleteSupplier(id) {
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
        // if (result.value) {
        //   axios.delete('/api/supplier/' + id)
        //     .then(function(response) {
        //       currObj.output = response.data.msg;
        //       currObj.status = response.data.status;
        //       // alert(currObj.status);
        //       let index_to_delete = currObj.suppliers.findIndex(supplier => supplier.id === id)
        //       currObj.suppliers.splice(index_to_delete,1);
        //       currObj.$Progress.finish();
        //       // alert(currObj.status);
        //       currObj.$swal("Info", currObj.output, currObj.status);
        //     }).catch(function(error) {
        //       currObj.$Progress.fail();
        //       // currObj.output=error;
        //       // console.log(currObj.output);
        //     })
        // }
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

    apicall() {
      axios
        .post(`https://jsonplaceholder.typicode.com/users`, this.meroTasks)
        .then((res) => {
          const persons = res.data;
          this.setState({ persons });
        });
    },
    log(log_data) {
      const temp_log_data = log_data;

      console.log(temp_log_data);
      // var that = this;
      this.updateDataInBackend();
      // setTimeout(function() { that.updateDataInBackend }, 5000);

      // console.log(temp_log_data.element.id);
    },

    //add new tasks method
    add: function () {
      if (this.newTask) {
        this.meroTasks.toDo.priorityHIGH.push({
          title: this.newTask,
          id: Date.now(),
        });
        this.newTask = "";
      }
    },
  },
};
</script>

<style>
/* light stylings for the kanban columns */
.kanban-column {
  min-height: 300px;
}
.status-head-container {
  margin-top: 20px;
  display: flex;
  justify-content: space-around;
  background: #673ab7;
  color: wheat;
}
.custom-row {
  display: flex;
  justify-content: space-around;
}
.row-bar h5 {
  text-align: center;
  color: white;
  letter-spacing: 10px;
}
/* .status-head-container{
  position: fixed;
    z-index: 222222;
    background: #00BCD4;
    color: white;
    top: 0;
    right: 0;
    left: 0;
} */
.task-tittle {
  font-weight: bold;
  font-size: 13px;
}
.alert-secondary {
  color: #383d41 !important;
  background-color: #e2e3e517 !important;
  border: 0 !important;
  box-shadow: 1px 1px 7px 1px #eee !important;
}
.list-group-item {
  margin-bottom: 10px !important;
  padding: 0.25rem 0.25rem !important;
}
.task-body {
  font-size: 12px;
}
.task-body p {
  margin: 0;
}
</style>

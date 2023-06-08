<template>
    <div style="background:#fff;border-radius:10px">
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
              <div class="list-group-item">
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
              <div class="list-group-item">
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
              <div class="list-group-item">
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
              <div class="list-group-item">
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
              <div class="list-group-item">
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
              <div class="list-group-item">
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
              <div class="list-group-item">
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
              <div class="list-group-item">
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
              <div class="list-group-item">
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
</template>


<script>
//import draggable
import draggable from "vuedraggable";
import axios from "axios";
import { dA } from '@fullcalendar/core/internal-common';

export default {
  name: "kanban-board",
  components: {
    //import draggable as a component
    draggable,
  },
  data() {
    return {
      // for new tasks
      newTask: "",
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
          priorityHIGH: [
           {}
          ],
          priorityMED: [
            {}
          ],

          priorityLOW: [
            {}
          ],
        },
        arrInProgress: {
          priorityHIGH: [
            {}
          ],
          priorityMED: [
            {}
          ],

          priorityLOW: [
            {}
          ],
        },
        arrDone: {
          priorityHIGH: [
            {}
          ],
          priorityMED: [
            {}
          ],

          priorityLOW: [
          {}
          ],
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
  created(){

    this.getTasks();
  },
  methods: {
    getTasks(){
      axios.get('/api/tasks')
      .then(response=>{
        const data=(response.data.data.tasks)

        console.log(JSON.parse(data));
        this.meroTasks=JSON.parse(data);
        Vue.set(this.meroTasks,JSON.parse(data));
        

      })
      .catch(error=>{
        console.log(error);
      })
    },
    async updateDataInBackend() {
      try {
        await axios.post('/api/update-tasks', { data: JSON.stringify(this.meroTasks) });
        console.log('Data updated in the backend successfully!');
      } catch (error) {
        console.error('Failed to update data in the backend:', error);
      }
    },
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

      this.updateDataInBackend();
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

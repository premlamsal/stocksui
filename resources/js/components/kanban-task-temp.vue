<template>
    <div class="container mt-2">
      <button @click="apicall()">API CALL TEST</button>
      <div class="row">
        <div class="col form-inline">
          <b-form-input
            id="input-2"
            v-model="newTask"
            required
            placeholder="Enter Task"
            @keyup.enter="add"
          ></b-form-input>
  
          <b-button @click="add" variant="primary" class="ml-3">Add</b-button>
        </div>
      </div>
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
              {
                id: 1,
                title: "Hello I am first",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 2,
                title: "Hello I am second",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
            ],
            priorityMED: [
              {
                id: 3,
                title: "Hello I am third",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 4,
                title: "Hello I am forth",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
            ],
  
            priorityLOW: [
              {
                id: 5,
                title: "Hello I am fifth",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 6,
                title: "Hello I am sixth",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
            ],
          },
          arrInProgress: {
            priorityHIGH: [
              {
                id: 7,
                title: "Hello I am seven",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 8,
                title: "Hello I am 8",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
            ],
            priorityMED: [
              {
                id: 9,
                title: "Hello I am 9",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 4,
                title: "Hello I am 10",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
            ],
  
            priorityLOW: [
              {
                id: 10,
                title: "Hello I am 11",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 11,
                title: "Hello I am 12",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
            ],
          },
          arrDone: {
            priorityHIGH: [
              {
                id: 11,
                title: "Hello I am 12",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 12,
                title: "Hello I am 13",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
            ],
            priorityMED: [
              {
                id: 13,
                title: "Hello I am 14",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 14,
                title: "Hello I am 15",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
            ],
  
            priorityLOW: [
              {
                id: 15,
                title: "Hello I am 16",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
              {
                id: 16,
                title: "Hello I am 17",
                content:
                  "Contrary to popular belief, Lorem Ipsum is not simply random text.",
              },
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
    methods: {
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
    padding: 10px;
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


<!-- main.js -->
import Vue from 'vue'
import App from './App.vue'

// Import Bootstrap Vue
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.config.productionTip = false

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

new Vue({
  render: h => h(App),
}).$mount('#app')




<!-- packageg json -->
{
    "name": "vue-kanban-board",
    "version": "0.1.0",
    "private": true,
    "scripts": {
      "serve": "vue-cli-service serve",
      "build": "vue-cli-service build",
      "lint": "vue-cli-service lint"
    },
    "dependencies": {
      "axios": "^1.4.0",
      "bootstrap-vue": "^2.9.0",
      "core-js": "^3.6.4",
      "vue": "^2.6.11",
      "vuedraggable": "^2.23.2"
    },
    "devDependencies": {
      "@vue/cli-plugin-babel": "~4.2.0",
      "@vue/cli-plugin-eslint": "~4.2.0",
      "@vue/cli-service": "~4.2.0",
      "babel-eslint": "^10.0.3",
      "eslint": "^6.7.2",
      "eslint-plugin-vue": "^6.1.2",
      "vue-template-compiler": "^2.6.11"
    },
    "eslintConfig": {
      "root": true,
      "env": {
        "node": true
      },
      "extends": [
        "plugin:vue/essential",
        "eslint:recommended"
      ],
      "parserOptions": {
        "parser": "babel-eslint"
      },
      "rules": {}
    },
    "browserslist": [
      "> 1%",
      "last 2 versions"
    ]
  }
  
  
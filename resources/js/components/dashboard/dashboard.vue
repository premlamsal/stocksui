<template>
  <div>
    <div class="row">
      <!-- <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body ">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-basket text-warning"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Purchase</p>
                  <p class="card-title">Rs. {{dash.purchase}}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer ">
            <hr>
            <div class="stats">
              <router-link to="/purchases" aria-expanded="false">
                <i class="nc-icon nc-basket"></i>
                <span>Purchases</span>
              </router-link>
            </div>
          </div>
        </div>
      </div> -->
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-map-big text-warning"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Events</p>
                  <p class="card-title">
                    {{ dash.event }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <hr />
            <div class="stats">
              <router-link to="/calendar" aria-expanded="false">
                <i class="nc-icon nc-map-big"></i>
                <span>Events</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-bullet-list-67 text-warning"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Categories</p>
                  <p class="card-title">
                    {{ dash.category }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <hr />
            <div class="stats">
              <router-link to="/users" aria-expanded="false">
                <i class="nc-icon nc-bullet-list-67"></i>
                <span>Categories</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-badge text-success"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Contacts</p>
                  <p class="card-title">{{ dash.contact }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <hr />
            <div class="stats">
              <router-link to="/contacts" aria-expanded="false">
                <i class="nc-icon nc-badge"></i>
                <span>Contacts</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-ruler-pencil text-danger"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Notes</p>
                  <p class="card-title">
                    {{ dash.note }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <hr />
            <div class="stats">
              <router-link to="/notes" aria-expanded="false">
                <i class="nc-icon nc-ruler-pencil"></i>
                <span>Notes</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-tile-56 text-success"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Products</p>
                  <p class="card-title">
                    {{ dash.product }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <hr />
            <div class="stats">
              <router-link to="/products" aria-expanded="false">
                <i class="nc-icon nc-tile-56"></i>
                <span>Products</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-delivery-fast text-danger"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Suppliers</p>
                  <p class="card-title">{{ dash.supplier }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <hr />
            <div class="stats">
              <router-link to="/suppliers" aria-expanded="false">
                <i class="nc-icon nc-delivery-fast"></i>
                <span>Suppliers</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-palette text-info"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Taskboard</p>
                  <p class="card-title">
                    {{ dash.task }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <hr />
            <div class="stats">
              <router-link to="/taskboard" aria-expanded="false">
                <i class="nc-icon nc-palette"></i>
                <span>Taskboard</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
          <div class="card-body">
            <div class="row">
              <div class="col-5 col-md-4">
                <div class="icon-big text-center icon-warning">
                  <i class="nc-icon nc-cart-simple text-info"></i>
                </div>
              </div>
              <div class="col-7 col-md-8">
                <div class="numbers">
                  <p class="card-category">Orders</p>
                  <p class="card-title">
                    {{ dash.weekly_order }}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <hr />
            <div class="stats">
              <router-link to="/weekly-orders" aria-expanded="false">
                <i class="nc-icon nc-cart-simple"></i>
                <span>Orders</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        
        <div class="weather-card" >
          <div class="weather-location-holder">
         
            <label for="weather-location" style="color:#fff">Weather Location</label>
            <div class="location-input">
            <input type="text" class="form-control" placeholder="Enter the location" v-model="inputLocation"/>
            <button class="btn btn-primary" @click="getWeatherByLocation()">Go</button>
           </div>
        </div>
        <div class="super-weather-holder" v-if="!isWeatherLoading">
          <div class="today-weather-card">
            <img
              class="weather-logo"
              :src="currentWeatherData.icon"
              alt="Weather Logo"
            />
            <div class="weather-info">
              <h2>Today's Weather</h2>
              <p style="font-size: 20px; text-transform: capitalize">
                Condition: {{ currentWeatherData.description }}
              </p>
              <p style="font-size: 24px;color:var(--favgold)">
                {{ getDayOfWeek(currentWeatherData.dt_txt) }}
              </p>
              <p style="font-size: 22px">
                Temperature: {{ currentWeatherData.temp }}°C
              </p>
              <p style="font-size: 22px">
                Feels Like: {{ currentWeatherData.feels_like }}°C
              </p>
            
              <p style="font-size: 22px">
                Humidity: {{ currentWeatherData.humidity }}
              </p>
            </div>
          </div>
          <div class="forecast">
            <div
              class="forecast-day"
              v-for="forecast in filteredForecastList"
              v-bind:key="forecast.dt"
            >
              <div class="forecast-list-image-holder">
                <img
                  class="forecast-list-weather-logo"
                  :src="getIconWithUrl(forecast.weather[0].icon)"
                  alt="Weather Logo"
                />
              </div>
              <h4>{{ getDayOfWeek(forecast.dt_txt) }}</h4>
              <p style="font-size: 20px">
                {{ Math.round(forecast.main.temp) }}°C
              </p>
              <p style="font-size: 18px; text-transform: capitalize">
                {{ forecast.weather[0].description }}
              </p>
            </div>
          </div>
        </div>
        <div class="" v-else>
          <!-- Loading Weather Data... -->
          <div class="text-center">
            <b-spinner variant="success" label="Spinning"></b-spinner>
          </div>
        </div>
        </div>
      
      </div>
    </div>

    <div class="row">
      <!-- <div class="col-md-6">
        <div v-if="currentWeatherData">
          <h2>Current Weather</h2>
          <p>Date/Time: {{ currentWeatherData.dt_txt }}</p>
          <p>Temperature: {{ currentWeatherData.main.temp }}°C</p>
          <p>Weather: {{ currentWeatherData.weather[0].description }}</p>
          ...and so on -->
      <!-- </div>
      </div> -->
      <!-- <div class="col-md-6">
        <div class="weather-list">
          <div v-for="forecast in forecastList" :key="forecast.dt">
            <p>Date/Time: {{ forecast.dt_txt }}</p>
            <p>Day: {{ getDayOfWeek(forecast.dt_txt) }}</p>
            <p>Temperature: {{ forecast.main.temp }}°C</p>
            <p>Weather: {{ forecast.weather[0].description }}</p>
          </div>
        </div>
      </div> -->
    </div>
  </div>
</template>

<script>
import BarChart from "../chart/barChart";
import LineChart from "../chart/lineChart";

export default {
  components: {
    BarChart,
    LineChart,
  },

  data() {
    return {
      store_id: "",

      showBar: false,
      showLine: true,

      loaded: false,
      inputLocation:"London",
      weatherData: {},
      isWeatherLoading: null,

      chartdata: {
        labels: "",

        datasets: [
          {
            label: "Sales over past months",
            data: "",
            borderWidth: 1,
            backgroundColor: [
              "rgb(255, 84, 64,0.6)",
              "rgba(54, 162, 235, 0.6)",
              "rgba(255, 206, 86, 0.6)",
              "rgba(75, 192, 192, 0.6)",
              "rgba(153, 102, 255, 0.6)",
              "rgba(255, 159, 64, 0.6)",
            ],

            // backgroundColor: "transparent",
            borderColor: [
              "rgba(255,99,132,1)",
              "rgba(54, 162, 235, 1)",
              "rgba(255, 206, 86, 1)",
              "rgba(75, 192, 192, 1)",
              "rgba(153, 102, 255, 1)",
              "rgba(255, 159, 64, 1)",
            ],
            // pointBackgroundColor: []
          },
        ],
      },

      options: {
        responsive: true,
        maintainAspectRatio: false,
      },

      dash: [],

      forecastList: [],
      currentWeatherData: {},
      filteredForecastList: [],

      type: "line",

      before_month: "6", //default sales chart will show 6 months records
    }; //end of return
  }, //end of data

  created() {
    if(localStorage.getItem("weather_location")){
      this.inputLocation = localStorage.getItem("weather_location");
    }
  
    this.store_id = this.$cookie.get("store_id");

    // if(!this.store_id==null){
    //   this.$Progress.start();
    this.fetchDash();
    //   this.$Progress.finish();
    // }
    // else{
    //   this.$router.push({ name: 'selectStore'});
    // }
  },

  mounted() {
    // this.getSalesChart();
    this.getWeatherData();
  },

  methods: {
    async getWeatherData() {
      this.isWeatherLoading = true;

      const url = `/api/weather`;
      
      let formData=new FormData();
      formData.append('_method','POST');
      formData.append('location',this.inputLocation);

      await axios
        .post(url,formData)
        .then((response) => {
          const forecastData = response.data;
          // const forecastList=forecastData.list;
          // this.weatherData = response.data;
          // this.forecastList = response.data.list;
          // console.log(this.weatherData);

          const filteredData = [];
          let previousDate = "";

          for (const forecast of forecastData.list) {
            const forecastDate = forecast.dt_txt.split(" ")[0];
            if (forecastDate !== previousDate) {
              filteredData.push(forecast);
              previousDate = forecastDate;
            }
          }
          this.forecastList = filteredData;
          console.log(this.forecastList);

          // //get current weather data
          // const currentDate = new Date();
          // const currentWeather = forecastData.list.filter((forecast) => {
          //   const forecastDate = new Date(forecast.dt_txt.split(" ")[0]);
          //   return forecastDate.toDateString() === currentDate.toDateString();
          // });

          // or this

          this.currentWeatherData.temp = Math.round(
            this.forecastList[0].main.temp
          );

          this.currentWeatherData.feels_like = Math.round(
            this.forecastList[0].main.feels_like
          );
          this.currentWeatherData.humidity = this.forecastList[0].main.humidity;

          this.currentWeatherData.description =
            this.forecastList[0].weather[0].description;

          this.currentWeatherData.dt_txt = this.forecastList[0].dt_txt;

          const open_weather_icon_url = "https://openweathermap.org/img/wn/";
          const open_weather_icon_url_last = "@2x.png";

          this.currentWeatherData.icon =
            open_weather_icon_url +
            this.forecastList[0].weather[0].icon +
            open_weather_icon_url_last;

          console.log(this.forecastList);

          const currentDateToday = new Date().toISOString().split("T")[0];

          const filteredForecastList = this.forecastList.filter((forecast) => {
            const forecastDate = forecast.dt_txt.split(" ")[0];
            return forecastDate !== currentDateToday;
          });

          this.filteredForecastList = filteredForecastList;
          console.log(this.filteredForecastList);
          this.isWeatherLoading = false;

      localStorage.setItem("weather_location",this.inputLocation);

        })
        .catch((error) => {
          console.error("Error fetching weather data:", error);
        });
    },
    getIconWithUrl(icon_code) {
      const open_weather_icon_url = "https://openweathermap.org/img/wn/";
      const open_weather_icon_url_last = "@2x.png";

      const custom_icon_url =
        open_weather_icon_url + icon_code + open_weather_icon_url_last;

      return custom_icon_url;
    },
    getDayOfWeek(dateString) {
      const forecastDate = new Date(dateString);
      return forecastDate.toLocaleDateString("en-US", { weekday: "long" });
    },
    changeSalesChartType() {
      if (this.type === "line") {
        this.showBar = false;
        this.showLine = true;
      } else if (this.type == "bar") {
        this.showBar = true;
        this.showLine = false;
      }
    },
    changeSalesChartMonth() {
      this.getSalesChart();
    },

    async getSalesChart() {
      this.loaded = false;
      try {
        let resp = await axios.get("/api/sales/chart/" + this.before_month);

        this.chartdata.labels = resp.data.month;

        Vue.set(this.chartdata.datasets, "data", resp.data.data);

        this.chartdata.datasets[0].data = resp.data.data;
        this.chartdata.datasets[0].label =
          "Sales Data from past " + this.before_month + " month(s)";

        this.loaded = true;
      } catch (e) {
        // console.error(e)
        // console.log('Somthing happened');
        this.$toast.error({
          title: "Opps!!",
          message: e.message.toString(),
        });
      }
    },
    getWeatherByLocation(){

      this.getWeatherData();

    },
    fetchDash() {
      let currObj = this;

      axios
        .get("/api/dashInfo")
        .then(function (response) {
          currObj.dash = response.data;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },

  computed: {
    //use {{ currentRouteName }} in template or use directly {{ $route.name }} without using computed property
    currentRouteName() {
      return this.$route.name;
    },
  },
};
</script>

<style scoped>
.weather-card {
  /* max-width: 300px; */
  border-radius: 8px;
  background-image: url("/assets/img/weather.jpg");
  padding: 20px;
  margin: 20px auto;
  background-position: right;
  background-size: cover;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.weather-info h2,
.weather-info p,
.forecast-day h4,
.forecast-day p {
  /* color: #fff; */
}
.weather-logo {
  width: 100px;
  height: 100px;
  margin: 0 auto;
  display: block;
}

.weather-info {
  text-align: center;
  margin-top: 20px;
}

.forecast {
  margin-top: 30px;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}

.forecast-day {
  width: 19%;
  padding: 10px;
  text-align: center;
  background-color: #e0e0e0e0;
  /* background-color: rgb(33 28 25 / 59%); */
  border-radius: 8px;
  margin-bottom: 10px;
  border: 1px solid #ffffff94;
}
.forecast-day:hover{
  background: #ffd700e0;
}
.today-weather-card {
  background: #000000b5;
    padding: 13px;
    color: #fff;
    border: 1px solid #ffffff63;
    border-radius: 10px;
    margin: 0 auto;
    width: 500px;

}
.weather-gear-holder {
    /* display: flex; */
    /* justify-content: right; */
    margin: 0 auto;
}
.weather-location-holder {
    color: white;
    margin: 0 auto;
    width: 380px;
    margin-bottom: 20px;
}
.location-input {
        display: flex;
        width:350px;
    justify-content: right;
    align-items: center;
}
</style>

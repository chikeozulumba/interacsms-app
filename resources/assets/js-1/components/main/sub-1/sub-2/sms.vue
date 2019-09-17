<template>
    <div class="box">
        <div class="level notification">
            <div class="level-left">
                <div class="level-item">
                    <div class="field">
                        <div class="control">
                            <div class="select is-warning is-rounded">
                                <select @change="format" ref="format">
                                    <option value="standard">SMS FORMAT</option>
                                    <option value="standard">Standard</option>
                                    <option value="template">Template</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="level-item">
                    <div class="field">
                        <div class="control">
                            <div class="select is-warning is-rounded">
                                <select @change="flash" ref="flash">
                                    <option value="no">FLASH MESSAGE</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="report" v-if="isModalActive" :class="{ 'modal': isModalActive, 'is-active': isModalActive }">
            <div class="modal-background"></div>
            <div :class="{'modal-card animated wobble': isModalActive}" style="min-height:70vh;">
                <header class="modal-card-head has-text-centered">
                    <p class="modal-card-title">Verify Message</p>
                    <button @click.prevent="isModalActive = false" class="delete" aria-label="close"></button>
                </header>
                <section class="modal-card-body">
                    <div class="box tabs is-fullwidth is-mobile help-tabs">
                        <ul>
                            <li :class="[ view === 'report' ? 'is-active' : '']">
                            <a @click="view='report'">
                                <span class="icon"><i class="far fa-newspaper" aria-hidden="true"></i></span>
                                <span @click="view='report'">Report</span>
                            </a>
                            </li>
                            <li :class="[ view === 'metrics' ? 'is-active' : '']">
                            <a @click="createChart('metrics-chart')">
                                <span class="icon"><i class="fas fa-chart-pie" aria-hidden="true"></i></span>
                                <span @click="view='metrics'">Metrics</span>
                            </a>
                            </li>
                            <li :class="[ view === 'overview' ? 'is-active' : '']">
                            <a @click="view='overview'">
                                <span @click="view='overview'">Overview</span>
                                <span class="icon"><i class="fas fa-bullhorn" aria-hidden="true"></i></span>
                            </a>
                            </li>
                        </ul>
                    </div>
                    <div class="help-content container-fluid">
                        <table class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth" v-show="showTable && view == 'report'">
                            <thead>
                                <tr>
                                    <th><abbr title="Serial Number">S/N</abbr></th>
                                    <th>Phone Number</th>
                                    <th>Network</th>
                                    <th>Cost (in Units)</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(res, key)  in results" :key="key">
                                    <td>{{ key + 1 }}</td>
                                    <td>{{ res.format.replace("+234", "0") }}</td>
                                    <td>{{ res.network }}</td>
                                    <td>{{ "Q " + res.cost.toFixed(2) }}</td>
                                    <td>{{ res.geolocation }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-show="showTable && view == 'metrics'">
                            <canvas id="metrics-chart"></canvas>
                            <div class="is-divider" data-content="FREQUENCY"></div>
                            <table class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth" v-show="showTable && view == 'metrics'">
                                <thead>
                                    <tr>
                                        <th><abbr title="Serial Number">S/N</abbr></th>
                                        <th>Network</th>
                                        <th>Frequency</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(res, key, i)  in verified.full" :key="key">
                                        <td>{{ i + 1 }}</td>
                                        <td>{{ key }}</td>
                                        <td>{{ res }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="columns is-mobile" v-show="showTable && view == 'overview'">
                            <div class="column is-12">
                                <div class="card" v-show="showTable && view == 'overview'">
                                    <header class="card-header has-text-centered">
                                        <p class="card-header-title" style="margin-left:12px">
                                            MESSAGE
                                        </p>
                                    </header>
                                    <div class="card-content">
                                        <div class="content">
                                            <p style="font-size: 14px;text-align: justify;">
                                                {{ pre.metadata.message }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <div class="card" v-show="showTable && view == 'overview'">
                                    <header class="card-header has-text-centered">
                                        <p class="card-header-title" style="margin-left:12px">
                                            SUMMARY
                                        </p>
                                    </header>
                                    <div class="card-content">
                                        <div class="content">
                                            <table class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth" v-show="showTable && view == 'overview'">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>RECIPIENTS</th>
                                                        <th>VOLUME</th>
                                                        <th>COST (In Units)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td>{{ final.results.length }}</td>
                                                        <td>{{ final.results.length * final.metadata.pages }}</td>
                                                        <td>{{ "Q " + final.metadata.cost.toFixed(2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <p style="font-size:12px" class="has-text-danger has-text-centered" v-show="final.metadata.warning !== null">
                                                {{ final.metadata.warning }}
                                            </p>
                                            <!-- <div class="divider" ></div> -->
                                            <div class="columns">
                                                <div class="column is-half">
                                                    <div class="field">
                                                        <div class="control has-icons-right">
                                                            <label style="font-size:12px" for="">Schedule Message</label>
                                                            <flat-pickr class="input" :config="config"
                                                                placeholder="Schedule date"
                                                                name="date" v-model="schedule"></flat-pickr>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <div class="field is-grouped is-right" v-show="showTable">
                        <p class="control">
                            <button @click.prevent="proceed" :disabled="!final.metadata.credit" class="button is-warning is-rounded">
                            <span class="icon is-medium" style="color: grey;">
                                <i class="fas fa-check"></i>
                            </span>
                            <span style="color: grey;">Proceed</span>
                            </button>
                        </p>
                        <p class="control">
                            <button class="button is-rounded" @click.prevent="isModalActive = false">
                            <span class="icon is-medium" style="color: grey;">
                                <i class="fas fa-ban"></i>
                            </span>
                            <span style="color: grey;">Cancel</span>
                            </button>
                        </p>
                    </div>
                </footer>
            </div>
        </div>
        <div class="is-divider"></div>
        <div class="tile">
            <div class="tile">
            <div class="tile is-child notification">
                <form @submit.prevent="SMS" action="">
                    <div class="field">
                        <label class="label">Sender Title: <span style="color:red">*</span></label>
                        <div class="control">
                            <input v-model="sms.title" class="input sender" type="text" placeholder="Sender's Name" minlength="3" maxlength="11">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Contacts:  <span style="color:red">*</span></label>
                        <div class="control">
                            <textarea v-model="sms.contacts_list" class="is-rounded textarea" placeholder="Enter phone number of recipients, seperated by a 'comma(,)', e.g. 0810000000,0700000000"></textarea>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Message: <span style="color:red">*</span></label>
                        <div class="control">
                            <textarea v-model="sms.message" class="is-rounded textarea" placeholder="Message Content"></textarea>
                        </div>
                    </div>

                    <div id="buttons" class="field is-grouped">
                        <div class="control">
                            <button style="font-family: 'Asap';" class="button is-fullwidt is-warning is-rounded">
                                <span class="icon is-small">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <span>SEND MESSAGE</span>
                            </button>
                        </div>
                        <div class="control">
                            <button @click="isDraft = true" style="font-family: 'Asap';" class="button is-fullwidt is-rounded">
                                <span class="icon is-small">
                                    <i class="fas fa-bookmark"></i>
                                </span>
                                <span>SAVE AS DRAFT</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Chart from "chart.js";
import _ from "lodash";
import flatPickr from "vue-flatpickr-component";
import swal from "sweetalert";
import moment from "moment";

export default {
  data() {
    return {
      sms: {
        title: "",
        contacts_list: "",
        message: "",
        format: "standard",
        flash: "no"
      },
      isDraft: false,
      disabled: true,
      csrf: "",
      isModalActive: false,
      chartData: {},
      results: [],
      view: "report",
      showTable: false,
      verified: {
        data: [],
        labels: [],
        chartColors: [],
        full: {},
        message: ""
      },
      pre: {},
      final: {},
      schedule: "",
      config: {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        maxDate: new Date().fp_incr(14),
        defaultHour: new Date().getHours(),
        defaultMinute: new Date().getMinutes()
      }
    };
  },
  update() {
    (function() {
      flatpickr("#date-picker", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today"
      });
    })();
  },
  mounted() {
    this.csrf = document.getElementById("csrf-token").content;
    flatpickr("#date-picker", {
      enableTime: true,
      dateFormat: "Y-m-d H:i",
      minDate: "today"
    });
  },
  watch: {
    schedule() {
      this.$store.dispatch("schedule", moment(this.schedule).format('YYYY-MM-DD HH:mm'));
    },
    view() {
      // console.log(this.view);
    }
  },
  methods: {
    draft(data) {
      // console.log(data);
      let form = new FormData();
      form.append("message", data.message);
      form.append("flash", data.flash);
      form.append("format", data.format);
      form.append("sender", data.title);
      form.append("recipients", data.contacts_list);
      form.append("_token", this.csrf);

      axios
        .post(route("draft.message"), form)
        .then(res => {
          if (res.data == 1) {
            swal("Saved!", "Your message is saved as draft", "success");
            this.sms.title = "";
            this.sms.contacts_list = "";
            this.sms.message = "";
          } else
            swal(
              "Warning!",
              "Your message could not be saved as draft",
              "error"
            );
        })
        .catch(error => {
          swal(
              "Warning!",
              "Your message could not be saved as draft",
              "error"
            );
        });
    },
    proceed() {
      let data = this.$store.getters.getMessage;
      let form = new FormData();
      form.append("message", data.metadata.message);
      form.append("flash", data.flash);
      form.append("format", data.format);
      form.append("sender", data.metadata.sender);
      form.append("schedule", data.metadata.schedule);
      form.append("pages", data.metadata.pages);
      form.append("cost", data.metadata.cost);
      //   form.append("flash", data.flash);
      //   form.append("format", data.format);
      form.append("_token", this.csrf);
      var phones = [];
      data.results.forEach(phone => {
        phones.push(phone.format.replace("+234", "0"));
      });
      form.append("recipients", phones.join(", "));

      axios
        .post(route("save.message"), form)
        .then(res => {
          // console.log(res);
          var c, t;
          switch (res.data[0].status) {
            case 200:
              c = "green";
              t = "Success";
              break;
            case 401:
              c = "red";
              t = "Error";
              break;
            case 400:
              c = "yellow";
              t = "Error";
              break;
            default:
              break;
          }
          if (res.data[0].status === 200) {
            setTimeout(() => {
              this.sms.title = "";
              this.sms.contacts_list = "";
              this.sms.message = "";
              this.isModalActive = false;
            }, 1500);
          }
          iziToast.show({
            title: `${t}`,
            message: `${res.data[0].message}`,
            color: `${c}`,
            close: true,
            closeOnEscape: false,
            closeOnClick: false,
            rtl: false,
            position: "topCenter",
            target: "",
            targetFirst: true,
            toastOnce: false,
            timeout: 5000,
            animateInside: true,
            drag: true,
            pauseOnHover: true,
            resetOnHover: false,
            progressBar: true,
            progressBarColor: "",
            progressBarEasing: "linear",
            overlay: false,
            overlayClose: false
          });
        })
        .catch(error => {
          // console.log(error);
        });
    },
    SMS() {
      for (const key in this.sms) {
        if (this.sms.hasOwnProperty(key)) {
          const el = this.sms[key];
          let check = ["title", "contacts_list", "message"];
          if (check.includes(key)) {
            if (el.length < 1 || el == "") {
              let field = key
                .replace("_", " ")
                .replace(/^\w/, c => c.toUpperCase());
              iziToast.error({
                title: `${field}`,
                message: ` field is invalid.`
              });
              this.disabled = true;
              return;
            }
            this.disabled = false;
          }
        }
      }
      if (this.disabled == false) {
        if (this.isDraft == false) {
          this.doSMS(this.sms);
        } else {
          this.draft(this.sms);
        }
      }
    },
    doSMS(data) {
      let body = new FormData();
      body.append("sender", data.title);
      body.append("recipients", data.contacts_list);
      body.append("message", data.message);
      body.append("flash", data.flash);
      body.append("format", data.format);
      body.append("_token", this.csrf);

      axios
        .post(route("verify.message"), body)
        .then(response => {
          if (response.data.statusCode == 200) {
            // console.log(response.data);
            if (response.data.results.length > 0) {
              this.$store.dispatch("addMessage", response.data);
              this.$store.dispatch("schedule", null);
              this.final = this.$store.getters.getMessage;
              this.pre = response.data;
              let networks = [];
              this.view = "report";
              this.results = response.data.results;
              this.showTable = true;
              this.isModalActive = true;
              this.results.forEach(res => {
                networks.push(res.network);
              });
              if (networks.length > 0) {
                let data = _.countBy(networks);
                this.verified.full = data;
                let l = [],
                  d = [],
                  c = [];
                Object.entries(data).forEach(([key, value]) => {
                  switch (key) {
                    case "MTN":
                      c.push("#fdcb03");
                      break;
                    case "GLO":
                      c.push("#178e0e");
                      break;
                    case "AIRTEL":
                      c.push("#e30008");
                      break;
                    case "9MOBILE":
                      c.push("#006d52");
                      break;
                    case "STARCOMMS":
                      c.push("#401349");
                      break;
                    default:
                      c.push("#FFA500");
                      break;
                  }
                  this.verified.chartColors = c;
                  l.push(key);
                  d.push(value);
                });
                this.verified.data = d;
                this.verified.labels = l;
              }
            }
          }
        })
        .catch(error => {
          // console.log(error);
        });
    },
    createChart(chartId) {
      this.view = "metrics";
      const ctx = document.getElementById(chartId);
      const myChart = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: this.verified.labels,
          datasets: [
            {
              label: "Carrier Networks",
              data: this.verified.data,
              backgroundColor: this.verified.chartColors,
              borderColor: this.verified.chartColors,
              borderWidth: 3
            }
          ],
          options: {
            responsive: true,
            lineTension: 1,
            scales: {
              yAxes: [
                {
                  ticks: {
                    beginAtZero: true
                  }
                }
              ]
            }
          }
        }
      });
    },
    format(e) {
      this.sms.format = e.target.value;
    },
    flash(e) {
      this.sms.flash = e.target.value;
    }
  },
  components: {
    flatPickr
  }
};
</script>

<style scoped>
@media screen and (max-width:500px) {
    #buttons {
        display:grid;
        grid-template-columns: 1fr;
    }
    #buttons > div {
        margin: 10px auto;
    }
}
.card-menu {
  padding: 1.5rem;
}
.card {
  border-radius: 6px;
}
.menu-label {
  color: #f0ad00;
}
.tabs li.is-active a {
  border-bottom-color: #f0ad00 !important;
  color: #f0ad00 !important;
}
.menu-list a {
  color: grey;
  font-size: 14px;
}
.select option {
  color: grey !important;
}
.is-divider {
  border-top: 1px solid #dbdbdb !important;
}
input.sender {
  width: auto !important;
}
label {
  text-transform: uppercase !important;
  color: grey;
}
td,
#sn {
  font-size: 12px !important;
  color: black;
}
th {
  color: grey !important;
  text-transform: uppercase;
  font-size: 14px;
}
</style>

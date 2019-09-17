<template>
    <div>
        <div @click.prevent="cv" class="card is-hidden-widescreen-only is-hidden-desktop-only is-hidden-tablet-only returnBack">
            <img @click.prevent="cv" id="returnBack" src="/img/icons/return.png" alt="" srcset="">
        </div>
        <div class="box">
            <div id="content" class="content">
                <a @click.prevent="cv" class="button is-hidden-mobile is-small is-rounded is-danger is-info"><span class="icon">
                            <i class="fas fa-undo"></i>
                            </span>
                            <span>RETURN</span></a>
                <h5 style="margin: 8px 0 0;" class="has-text-centered">{{ data.message.sender.toUpperCase() }}</h5>
            </div>
        </div>
        <div class="section">
            <div class="columns">
                <div class="column is-12">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                {{ "Message".toUpperCase() }}
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <p>
                                    {{ data.message.content }}
                                </p>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <p class="card-footer-item">
                                <strong>{{ data.message_logs.length }} Recipient{{ data.message_logs.length > 1 ? 's' : ''  }}</strong>
                            </p>
                        </footer>
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column is-7">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                            Network Carriers
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <canvas id="networks-chart" height="250px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-5">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Delivery Rate
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <canvas id="delivery-chart" height="370px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="columns">
                <div class="column is-12">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                Delivery Logs
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <table v-show="data.message_logs.length > 0" class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth">
                                    <thead>
                                        <tr>
                                            <th scope="col">S/N</th>
                                            <th class="is-hidden-tablet is-hidden-desktop is-hidden-widescreen is-hidden-fullhd" scope="col">Ref</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Network</th>
                                            <th scope="col">Status Desc.</th>
                                            <th scope="col">Error Desc.</th>
                                            <th scope="col">Units</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr :ref="d.id" v-for="(d, i) in data.message_logs" :key="i">
                                            <td data-label="S/N">
                                                {{ i + 1 }}
                                            </td>
                                            <td class="is-hidden-tablet is-hidden-desktop is-hidden-widescreen is-hidden-fullhd" data-label="Ref">{{ d.each_id }}</td>
                                            <td data-label="Phone">
                                                {{ d.phone_number.replace("+234", "0") }}
                                            </td>
                                            <td data-label="Network">{{ d.network }}</td>
                                            <td data-label="Status Desc."> <strong>{{ d.delivery_status_groupName }} </strong> <br> {{ d.delivery_status_description }}</td>
                                            <td style="text-overflow: ellipsis;overflow: hidden; white-space: nowrap;" data-label="Error Desc."> <strong>{{ d.delivery_error_name.replace('_', ' ') }}</strong> <br>{{ d.delivery_error_description }}</td>
                                            <td data-label="Units">{{ d.delivery_status_groupName === "REJECTED" && d.delivery_error_description === "DND blacklisted" ? 0 : d.smsCount }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import Chart from "chart.js";
import _ from "lodash";
export default {
    data () {
        return {
            view: "",
            data: {},
            moment: moment,
            verified: {
                data: [],
                labels: [],
                chartColors: [],
                full: {},
                message: ""
            },
            delivery: {}
        }
    },
    methods: {
        cv ($) {
            this.$parent._data.currentView = 'sent';
        }
    },
    mounted () {
        // Delivery Rates
        let delivery = [];
        this.data.message_logs.forEach(function (log) {
            delivery.push(log.delivery_status_groupName);
        });
        let deliveryD = _.countBy(delivery);
        let ln = [],
                dn = [],
                cn = [];
            Object.entries(deliveryD).forEach(([key, value]) => {
                ln.push(key);
                dn.push(value);
            });
        // Network Carriers
        let carriers = this.data.carriers;
        let data = _.countBy(carriers);
        if (carriers.length > 0) {
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
            const ctx = document.getElementById("networks-chart");
            const myChart = new Chart(ctx, {
                type: "pie",
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
                        responsive: false,
                        lineTension: 1,
                        scales: {
                        yAxes: [
                            {
                            ticks: {
                                beginAtZero: true
                            }
                            }
                        ]
                        },
                        legend: {
                            display: true,
                            labels: {
                                fontColor: 'rgb(255, 99, 132)'
                            }
                        }
                    }
                }
            });
            new Chart(document.getElementById("delivery-chart"), {
            type: "pie",
            data: {
                labels: ln,
                datasets: [
                    {
                        label: "Carrier Networks",
                        data: dn,
                        backgroundColor: ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6',
                        '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
                        '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A',
                        '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
                        '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC',
                        '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
                        '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680',
                        '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
                        '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3',
                        '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'],
                        borderColor: ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6',
                        '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
                        '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A',
                        '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
                        '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC',
                        '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
                        '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680',
                        '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
                        '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3',
                        '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'],
                        borderWidth: 3
                    }
                ],
                options: {
                    responsive: false,
                    lineTension: 1,
                    scales: {
                    yAxes: [
                        {
                        ticks: {
                            beginAtZero: true
                        }
                        }
                    ]
                    },
                    legend: {
                        display: true,
                        labels: {
                            fontColor: 'rgb(255, 99, 132)'
                        }
                    }
                }
            }
        });

        }
    },
    beforeMount() {
        this.data = this.$store.getters.getSingleSentMsgs;
    }
}
</script>

<style scoped>
/* #content {
  display: grid;
  grid-template-columns: 1fr 4fr;
} */
.returnBack {
  position: fixed;
  z-index: 999999;
  height: 40px;
  width: 40px;
  right: 20px;
  bottom: 20px;
  padding: 10px;
  box-shadow: 2px 2px 3px #4a4a4a91;
  cursor: pointer;
}
.content h5 {
  line-height: 1rem;
  margin-bottom: 0px;
}

.box .content {
  /* display: grid;
        grid-template-columns: 1fr 9fr; */
}

.card {
  border-radius: 5px;
}

@media screen and (max-width: 500px) {
  .content h5 {
    margin-top: 10px;
  }
}
@media screen and (min-width: 700px) {
  #content {
    display: grid;
    grid-template-columns: 1fr 4fr;
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
#message {
  text-overflow: ellipsis;
}
table {
  border: 1px solid #ccc;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  width: 100%;
  table-layout: fixed;
}

table caption {
  font-size: 1.5em;
  margin: 0.5em 0 0.75em;
}

table tr {
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  padding: 0.35em;
}

table th,
table td {
  padding: 0.625em;
  text-align: center;
}

table th {
  font-size: 0.85em;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

td {
  font-size: 12px !important;
}
@media screen and (max-width: 600px) {
  table {
    border: 0;
  }

  table caption {
    font-size: 1.3em;
  }

  table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: 0.625em;
  }

  table td {
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: 0.8em;
    text-align: right;
  }

  table td::before {
    /*
    * aria-label has no advantage, it won't be read inside a table
    content: attr(aria-label);
    */
    content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
  }

  table td:last-child {
    border-bottom: 0;
  }
}
.is-divider {
  border-top: 1px solid #dbdbdb !important;
}
table thead th {
  border-width: 0 0 2px !important;
  color: #363636 !important;
}
</style>

<template>
    <div class="box">
        <div class="content">
            <h2 class="has-text-centered">SENT MESSAGES</h2>
        </div>
        <div class="is-divider"></div>
        <table v-show="table.length > 0" class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th scope="col">Delete</th>
                    <th scope="col">Sender</th>
                    <th scope="col">Content</th>
                    <th scope="col">Pages</th>
                    <th scope="col">Volume</th>
                    <th scope="col">Cost In Units</th>
                    <th scope="col">Schedule</th>
                    <th scope="col">Forward</th>
                </tr>
            </thead>
            <tbody>
                <tr :ref="data.id" v-for="(data, index) in table" :key="index">
                    <td data-label="Delete">
                        <div class="field">
                            <div class="">
                                <input :data-parent="data.id" @click="change" type="checkbox">
                            </div>
                        </div>
                    </td>
                    <td data-label="Sender">
                        <a @click.prevent="doAnalytics" :data-analytics="data.id">{{ data.sender }}</a>
                    </td>
                    <td data-label="Content">{{ data.content }}</td>
                    <td data-label="Pages">{{ data.pages }}</td>
                    <td data-label="Volume">{{ data.volume }}</td>
                    <td data-label="Cost In Units">{{ "Q " + data.cost.toFixed(2) }}</td>
                    <td data-label="Schedule">{{ data.brodcast_time !== "None" ? moment(data.brodcast_time).format('MMMM Do YYYY, h:mm a') : "NONE" }}</td>
                    <td data-label="Forward">
                        <p>
                            <a @click.prevent="appendToSMS" :data-forward="data.id" class="button has-background-danger is-small">
                                <span :data-forward="data.id" class="icon is-small">
                                    <i :data-forward="data.id" class="fas fa-share-square has-text-white"></i>
                                </span>
                            </a>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-show="table.length > 0" class="field is-grouped">
            <p class="control">
                <a :disabled="!button"  @click.prevent="doDelete" class="button is-rounded is-danger">
                DELETE
                </a>
            </p>
        </div>
        <div v-show="table.length === 0" class="content">
            <p class="has-text-centered">
                No Sent Messages.
            </p>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import _ from "lodash";
import swal from "sweetalert";
export default {
    data () {
        return {
            sms: {
                format: "standard",
                flash: "no"
            },
            table: [],
            tables: true,
            delete: [],
            moment: moment,
            button: false
        }
    },
    watch: {
        delete () {
            if (this.delete.length > 0) {
                this.button = true;
            } else {
                this.button = false;
            }
        },
        watch () {
            this.table = this.$store.getters.getSentMsgs;
        }
    },
    methods: {
        doAnalytics ($) {
            var vm  = this;
            var id = $.target.dataset.analytics;
            axios.get(route('get.sent.record').url() + `?message=${id}`)
                .then( res => {
                    if(res.data) {
                        console.log(res.data);
                        // console.log(vm);
                        if (res.data.message_logs.length > 0) {
                            this.$store.dispatch("singleMsgs", res.data);
                            setTimeout(() => {
                                vm.$parent._data.currentView = 'analytics';
                            }, 0.666);
                        } else {
                            swal(
                                "Warning!",
                                "No delivery report generated for this message.",
                                "error"
                            );
                        }
                    } else {
                        swal(
                            "Warning!",
                            "Could not retrieve message statistics.",
                            "error"
                        );
                    }
                })
                .catch( err => {
                    swal(
                        "Warning!",
                        "Could not retrieve message statistics.",
                        "error"
                    );
                });
            console.log(id);
        },
        appendToSMS ($) {
            var id = $.target.dataset.forward;
            this.table.forEach(data => {
                if (data.id === id) {
                    var vm = this;
                    console.log(vm);
                    vm.$root.$children[0].$children[3].$children[0]._data.sms.message = data.content;
                    vm.$root.$children[0].$children[3].$children[0]._data.sms.title = data.sender;
                    setTimeout(() => {
                        this.$root.$children[0].$children[3]._data.currentView = 'sms';
                    }, 1880);
                }
            });
        },
        change ($) {
            if ($.target.checked == true) {
                this.delete.push($.target.dataset.parent);
            } else if ($.target.checked == false) {
                var index = this.delete.indexOf($.target.dataset.parent);
                if (index > -1) {
                    this.delete.splice(index, 1);
                }
            }
        },
        doDelete (e) {
            axios.get(route('delete.message').url() + `?id=${this.delete.toString()}`)
            .then( res => {
                if (res.data > 0) {
                    for (var i = this.delete.length -1; i >= 0; i--) {
                        const el = this.delete[i];
                        let ll = this.$refs[el][0];
                        ll.classList.add('animated');
                        ll.classList.add('bounceOutLeft');
                        setTimeout(() => {
                            ll.style.display = 'none';
                            const index = this.delete.indexOf(el);
                            this.delete.splice( index, 1 );
                        }, 500);
                    }
                    return;
                    this.addMessages();
                }
            })
            .catch( err => {
                // console.log(err);
            });
        },
        addMessages() {
            axios.get(route('get.messages'))
            .then( res => {
                if (res.data !== "") {
                    this.getSentMsgs(res.data);
                }
            })
            .catch( error => {
                // console.log(error);
            });
        },
        getSentMsgs (data) {
            this.$store.dispatch("updateSentMsgs", data);
            this.table = this.$store.getters.getSentMsgs;
        }
    },
    created () {
        axios.get(route('get.messages'))
            .then( res => {
                if (res.data !== "") {
                    this.$store.dispatch("addSentMsgs", res.data);
                    this.table = this.$store.getters.getSentMsgs;
                }
            })
            .catch( error => {
                // console.log(error);
            });
    },
    mounted () {

    }
}
</script>

<style scoped>
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
  margin: .5em 0 .75em;
}

table tr {
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  padding: .35em;
}

table th,
table td {
  padding: .625em;
  text-align: center;
}

table th {
  font-size: .85em;
  letter-spacing: .1em;
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
    margin-bottom: .625em;
  }

  table td {
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: .8em;
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
</style>

<template>
    <div class="box">
        <div class="content">
            <h2 class="has-text-centered">SCHEDULED MESSAGES</h2>
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
                    <!-- <th scope="col">Cost</th> -->
                    <th scope="col">Scheduled Date</th>
                    <th scope="col">Created Date</th>
                    <!-- <th scope="col">Forward</th> -->
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
                    <td data-label="Sender">{{ data.sender }}</td>
                    <td data-label="Content">{{ data.content }}</td>
                    <td data-label="Pages">{{ data.pages }}</td>
                    <td data-label="Volume">{{ data.volume }}</td>
                    <!-- <td data-label="Cost">{{ "NGN " + data.cost.toFixed(2) }}</td> -->
                    <td data-label="Scheduled Date">{{ data.brodcast_time !== "null" ? moment(data.brodcast_time).format('MMMM Do YYYY, h:mm a') : "NONE" }}</td>
                    <td data-label="Created Date">{{ moment(data.updated_at).add(1, 'hours').format('MMMM Do YYYY, h:mm a') }}</td>
                    <!-- <td data-label="Forward">
                        <p class="">
                            <a :data-forward="data.id" class="button has-background-danger is-small">
                                <span class="icon is-small">
                                    <i class="fas fa-redo-alt has-text-white"></i>
                                    <i class="fas fa-share-square has-text-white"></i>
                                </span>
                            </a>
                        </p>
                    </td> -->
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
        <div v-show="table.length === 0 && isLoading == true" class="content">
            <p class="has-text-centered">
                No Scheduled Messages.
            </p>
        </div>
        <div class="preloader" v-show="table.length === 0 && isLoading == false">
            <img src="/img/home/preloader.gif">
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import _ from "lodash";
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
            button: false,
            isLoading: false
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
                iziToast.error({
                    title: `Error`,
                    message: `Could't delete. Try again`,
                    close: true,
                    closeOnEscape: false,
                    closeOnClick: false,
                    rtl: false,
                    position: "topRight",
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
            });
        },
        addMessages() {
            axios.get(route('get.scheduled.messages'))
            .then( res => {
                if (res.data !== "") {
                    this.getSentMsgs(res.data);
                }
            })
            .catch( error => {
                iziToast.error({
                    title: `Error`,
                    message: `Could't retrieve scheduled messages. Try again`,
                    close: true,
                    closeOnEscape: false,
                    closeOnClick: false,
                    rtl: false,
                    position: "topRight",
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
            });
        },
        getSentMsgs (data) {
            this.$store.dispatch("updateSentMsgs", data);
            this.table = this.$store.getters.getSentMsgs;
        }
    },
    created () {
        axios.get(route('get.scheduled.messages'))
            .then( res => {
                if (res.data !== "") {
                    this.$store.dispatch("addSentMsgs", res.data);
                    this.table = this.$store.getters.getSentMsgs;
                    this.isLoading = true;
                }
            })
            .catch( error => {
                iziToast.error({
                    title: `Error`,
                    message: `Could't retrieve scheduled messages. Try again`,
                    close: true,
                    closeOnEscape: false,
                    closeOnClick: false,
                    rtl: false,
                    position: "topRight",
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
</style>

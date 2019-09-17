<template>
    <div class="box">
        <div class="content">
            <h2 class="has-text-centered">DRAFT MESSAGES</h2>
        </div>
        <div>
            <nav class="level" style="margin-bottom: 0;">
                <!-- Left side -->
                <div class="level-left">
                    <div class="level-item">
                        <a @click.prevent="doDelete" class="button is-small is-rounded is-danger is-info"><span class="icon">
                            <i class="fas fa-trash-alt"></i>
                            </span>
                            <span>DELETE DRAFT</span></a>
                    </div>
                </div>
            </nav>

            <div class="is-divider"></div>
            <div class="content">
                <table-component
                    :data="data"
                    sort-by="SENDER"
                    sort-order="asc"
                    show-filter= false
                    show-caption= false
                    filterPlaceholder = ''
                    filterNoResults = ''
                    table-class=" modtable table is-mobile is-borderedd is-striped is-hoverable is-hidden-mobile"
                    >
                    <table-column show="id" label="REMOVE">
                        <template slot-scope="row">
                            <input @click="change" type="checkbox" :data-ref="row.id">
                        </template>
                    </table-column>
                    <table-column show="sender" label="SENDER"></table-column>
                    <table-column show="message" label="MESSAGE"></table-column>
                    <table-column show="contacts_list" label="CONTACTS" data-type="numeric"></table-column>
                    <table-column show="updated_at" label="MODIFIED ON" :filterable="false" :formatter="formatter" data-type="date:DD-MM-YYYY"></table-column>
                    <table-column show="d_id" label="SEND MESSAGE">
                        <template class="has-text-centered" slot-scope="row">
                            <a @click="appendToSMS" class="button is-rounded is-info is-small" :data-forward="row.id">
                                <span :data-forward="row.id" class="icon">
                                    <i :data-forward="row.id" class="fas fa-envelope-open"></i>
                                </span>
                                <!-- <span :data-edit="row.d_id">{{ row.first_name }}</span> -->
                            </a>
                            <!-- <button @click="edit" type="checkbox" :data-edit="row.id">EDIT</button> -->
                        </template>
                    </table-column>
                </table-component>
                <br>
                <table id="table" class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth is-hidden-desktop is-hidden-tablet">
                    <thead>
                        <tr>
                            <th scope="col">REMOVE</th>
                            <th scope="col">SENDER</th>
                            <th scope="col">MESSAGE</th>
                            <th scope="col">CONTACTS</th>
                            <th scope="col">MODIFIED ON</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :ref="data.id" v-for="(data, index) in data" :key="index">
                            <td data-label="Delete">
                                <div class="field">
                                    <div class="">
                                        <input :data-ref="data.id" @click="change" type="checkbox">
                                    </div>
                                </div>
                            </td>
                            <td data-label="SENDER">{{ data.sender }}</td>
                            <td data-label="MESSAGE">{{ data.message }}</td>
                            <td data-label="CONTACTS">{{ data.contacts_list }}</td>
                            <td data-label="MODIFIED ON">{{ data.updated_at !== "null" ? m(data.updated_at).format('MMMM Do YYYY, h:mm a') : "NONE" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import { TableComponent, TableColumn } from "vue-table-component";
export default {
  data() {
    return {
      view: "contacts",
      data: [],
      delete: [],
      els: [],
      table: [],
      m: moment
    };
  },
  methods: {
    appendToSMS($) {
      this.data.forEach(data => {
        if (data.id === $.target.dataset.forward) {
          var vm = this;
          vm.$root.$children[0].$children[3].$children[0]._data.sms.message =
            data.message;
          vm.$root.$children[0].$children[3].$children[0]._data.sms.title =
            data.sender;
          vm.$root.$children[0].$children[3].$children[0]._data.sms.contacts_list =
            data.contacts_list;
          setTimeout(() => {
            this.$root.$children[0].$children[3]._data.currentView = "sms";
          }, 1880);
        }
      });
    },
    formatter(value, rowProperties) {
      return moment(value).format("LLLL");
    },
    change($) {
      this.els.push($.target.parentElement.parentElement);
      if ($.target.checked == true) {
        this.delete.push($.target.dataset.ref);
      } else if ($.target.checked == false) {
        var index = this.delete.indexOf($.target.dataset.ref);
        if (index > -1) {
          this.delete.splice(index, 1);
        }
      }
    },
    doDelete() {
      axios
        .get(route("delete.draft").url() + `?id=${this.delete.toString()}`)
        .then(res => {
          if (res.data > 0) {
            for (var i = this.delete.length - 1; i >= 0; i--) {
              const el = this.delete[i];
              const index = this.delete.indexOf(el);
              this.delete.splice(index, 1);
              this.els.forEach(el => {
                el.classList.add("animated");
                el.classList.add("bounceOutLeft");
                setTimeout(() => {
                  el.style.display = "none";
                }, 500);
              });
              let ll = this.$refs[el][0];
              if (ll != "undefined" || ll !== null) {
                ll.classList.add("animated");
                ll.classList.add("bounceOutLeft");
                setTimeout(() => {
                  ll.style.display = "none";
                }, 500);
              }
            }
            this.els = [];
            this.delete = [];
          }
        })
        .catch(err => {
          // console.log(err);
        });
    }
  },
  mounted() {
    axios
      .get(route("get.drafts"))
      .then(res => {
        // console.log(res);
        this.data = res.data;
      })
      .catch();
  },
  components: {
    TableComponent,
    TableColumn
  }
};
</script>

<style>
.modal-card-title {
  font-family: "Asap";
}
.tabs li.is-active a {
  color: #f0ad00;
}
.tabs span {
  font-family: "Asap" !important;
  /* font-size: 12px; */
  text-transform: uppercase;
}
.tabs.is-toggle li.is-active a {
  background-color: #f0ad00;
  border-color: #f0ad00;
  color: #fff;
  z-index: 1;
}
.VueTables__heading,
.table-component__th {
  font-size: 12px;
  color: grey !important;
  text-align: center;
}
.VueTables__table > tbody > tr > td,
.table-component__table__body > tr > td {
  font-size: 12px;
}
.table-component__table__caption,
.table-component__filter {
  display: none;
}
#table {
  border: 1px solid #ccc;
  border-collapse: collapse;
  margin: 0;
  padding: 0;
  width: 100%;
  table-layout: fixed;
}

#table caption {
  font-size: 1.5em;
  margin: 0.5em 0 0.75em;
}

#table tr {
  background-color: #f8f8f8;
  border: 1px solid #ddd;
  padding: 0.35em;
}

#table th,
#table td {
  padding: 0.625em;
  text-align: center;
}

#table th {
  font-size: 0.85em;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

td {
  font-size: 12px !important;
}
@media screen and (max-width: 600px) {
  #table {
    border: 0;
  }

  #table caption {
    font-size: 1.3em;
  }

  #table thead {
    border: none;
    clip: rect(0 0 0 0);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
  }

  #table tr {
    border-bottom: 3px solid #ddd;
    display: block;
    margin-bottom: 0.625em;
  }

  #table td {
    border-bottom: 1px solid #ddd;
    display: block;
    font-size: 0.8em;
    text-align: right;
  }

  #table td::before {
    /*
    * aria-label has no advantage, it won't be read inside a #table
    content: attr(aria-label);
    */
    content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
  }

  #table td:last-child {
    border-bottom: 0;
  }
}
.is-divider {
  border-top: 1px solid #dbdbdb !important;
}
.table-component__table__body > tr > td:last-child {
  text-align: center !important;
}
</style>

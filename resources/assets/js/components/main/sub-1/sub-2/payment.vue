<template>
    <div class="box">
        <div class="content">
            <h2 class="has-text-centered">TRANSACTION HISTORY</h2>
        </div>
        <div>
            <div class="is-divider"></div>
            <div v-show="data.length > 0" class="content">
                <table-component
                    :data="data"
                    sort-by="DATE"
                    sort-order="desc"
                    show-filter= false
                    show-caption= false
                    filterPlaceholder = ''
                    filterNoResults = ''
                    table-class=" modtable table is-mobile is-borderedd is-striped is-hoverable is-hidden-mobile"
                    >
                    <table-column show="trx_ref" label="REFERENCE CODE"></table-column>
                    <table-column show="amount" label="AMOUNT"></table-column>
                    <table-column show="units" label="UNITS PURCHASED" data-type="numeric"></table-column>
                    <table-column show="status" label="STATUS">
                        <template class="has-text-centered" slot-scope="row">
                            <span :class="[ row.status === 'success' ? 'is-primary' : 'is-danger']" class="tag is-small">{{ row.status !== 'success' ? 'Failed' : 'Success' }}</span>
                        </template>
                    </table-column>
                    <table-column show="updated_at" label="DATE" :filterable="false" :formatter="formatter" data-type="date:DD-MM-YYYY"></table-column>
                </table-component>
                <br>
                <table id="table" class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth is-hidden-desktop is-hidden-tablet">
                    <thead>
                        <tr>
                            <th scope="col">REFERENCE CODE</th>
                            <th scope="col">AMOUNT</th>
                            <th scope="col">UNITS PURCHASED</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">DATE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :ref="data.id" v-for="(data, index) in data" :key="index">
                            <td data-label="REFERENCE CODE">{{ data.trx_ref }}</td>
                            <td data-label="AMOUNT">{{ data.amount }}</td>
                            <td data-label="UNITS PURCHASED">{{ data.units }}</td>
                            <td data-label="STATUS">
                                <span :class="[ data.status === 'success' ? 'is-primary' : 'is-danger']" class="tag is-small">{{ data.status !== 'success' ? 'Failed' : 'Success' }}</span>
                            </td>
                            <td data-label="DATE">{{ data.updated_at !== "null" ? m(data.updated_at).format('MMMM Do YYYY, h:mm a') : "NONE" }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-show="data.length === 0 && isLoading == true" class="content">
                <p class="has-text-centered">
                    No payment history.
                </p>
            </div>
            <div class="preloader" v-show="data.length === 0 && isLoading == false">
                <img src="/img/home/preloader.gif">
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
      data: [],
      m: moment,
      isLoading: false
    };
  },
  methods: {
    formatter(value, rowProperties) {
      return moment(value).format("LLLL");
    }
  },
  mounted() {
      axios.get(route('get.payments'))
            .then(res => {
                this.data = res.data;
                this.isLoading = true;
            })
            .catch(err => {
                iziToast.error({
                    title: `Error`,
                    message: `Could't retrieve payment history. Try again`,
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

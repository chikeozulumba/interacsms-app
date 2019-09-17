<template>
    <div class="column">
        <div class="columns is-desktop is-centered is-narrow-tablet">
            <div class="column is-half">
                <div class="card">
                    <header class="card-header has-text-centered">
                        <p class="card-header-title is-centered">
                            Recharge your Account
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <div class="field">
                                <p class="control has-icons-left has-icons-right">
                                    <input v-model="Units" class="input" type="email" placeholder="Enter Units">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-database"></i>
                                    </span>
                                </p>
                            </div>
                            <div class="field">
                                <p class="control has-icons-left">
                                    <input class="input" type="number" placeholder="0.0" readonly :value="amount">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-money-bill-alt"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="card-footer-item">
                            <div class="field">
                                <p class="buttons">
                                    <a @click.prevent="doPAY" class="button is-warning is-rounded">
                                        <span class="icon">
                                        <i class="fas fa-credit-card"></i>
                                        </span>
                                        <span>Make Payment</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
            <div class="column is-half">
                <div class="card">
                    <header class="card-header has-text-centered">
                        <p class="card-header-title is-centered">
                            Purchase Quantity
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <table class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth">
                                <thead>
                                    <tr>
                                        <th scope="col">Bundle Name</th>
                                        <th scope="col">Unit Charge</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-label="Bundle Name">{{ BundleName }}</td>
                                        <td data-label="Unit Charge">{{ UnitChargeST }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <form id="paymentForm" method="POST" :action="payment_route">
                            <input type="hidden" name="_token" :value="token">
                            <input type="hidden" name="email" :value="useremail">
                            <input type="hidden" name="orderID" :value="orderid">
                            <input type="hidden" name="amount" :value="Cost * 100">
                            <input type="hidden" name="quantity" :value="Units">
                            <input type="hidden" name="reference" :value="reference">
                        </form>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import paystack from "vue-paystack";
const uuidv4 = require('uuid/v4');
const NodeRSA = require('node-rsa');
export default {
    props: ['email'],
  data() {
    return {
      Units: 50,
      BundleName: "Enter bundle quantity",
      UnitCharge: 0.0,
      UnitChargeST: "Please wait...",
      Cost: 165.00,
      paystackBtnText: "Pay Me, My Money",
      paystackkey: "pk_test_a1c855a5c9ce5d4e5662abab53ae6d5229128e3c",
      useremail: "",
      amount: parseInt((165.0), 10),
      payment_route: route('make.payment'),
      key_code: "",
      token: "",
      reference: ""
    };
  },
  computed: {
    orderid () {
      let text = "";
      let possible =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      for (let i = 0; i < 10; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));
      return uuidv4();
    }
  },
  mounted () {
      this.token = window.token;
      this.useremail = this.$parent.$parent._props.email;
      this.key_code = this.$parent.$parent._props.keycode;
      this.reference = this.generate();
  },
  methods: {
    doPAY: function() {
        let paymentForm = document.getElementById("paymentForm");
        if (this.Units >= 50 && this.Cost >= 165.00) {
            let form = new FormData (paymentForm);
            paymentForm.submit();
        }
    },
    generate () {
        return uuidv4();
    },
    close: function() {
      // console.log("Payment closed");
    }
  },
  watch: {
    Units() {
      if (this.Units < 50) {
        this.BundleName = "Units quantity not permitted";
        this.UnitCharge = "Invalid";
        this.UnitChargeST = "Invalid";
        this.Cost = parseInt((100 * this.Units * this.UnitCharge).toFixed(), 10);
        this.amount = (this.Cost / 100).toFixed(2);
      } else if (this.Units >= 50 && this.Units <= 999) {
        this.BundleName = "Standard";
        this.UnitCharge = 3.3;
        this.UnitChargeST = `NGN ${this.UnitCharge}`;
        this.Cost = parseInt((100 * this.Units * this.UnitCharge).toFixed(), 10);
        this.amount = (this.Cost / 100).toFixed(2);
      } else if (this.Units >= 1000 && this.Units <= 9999) {
        this.BundleName = "Business";
        this.UnitCharge = 2.2;
        this.UnitChargeST = `NGN ${this.UnitCharge}`;
        this.Cost = parseInt((100 * this.Units * this.UnitCharge).toFixed(), 10);
        this.amount = (this.Cost / 100).toFixed(2);
      } else if (this.Units >= 10000 && this.Units <= 49999) {
        this.BundleName = "Campaign";
        this.UnitCharge = 2.0;
        this.UnitChargeST = `NGN ${this.UnitCharge}`;
        this.Cost = parseInt((100 * this.Units * this.UnitCharge).toFixed(), 10);
        this.amount = (this.Cost / 100).toFixed(2);
      } else if (this.Units >= 50000) {
        this.BundleName = "Professional";
        this.UnitCharge = 1.8;
        this.UnitChargeST = `NGN ${this.UnitCharge}`;
        this.Cost = parseInt((100 * this.Units * this.UnitCharge).toFixed(), 10);
        this.amount = (this.Cost / 100).toFixed(2);
      }
    }
  },
  components: {
    paystack
  }
};
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

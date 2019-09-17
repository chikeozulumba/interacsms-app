<template>
    <div class="column">
        <div class="columns is-desktop is-centered is-mobile">
            <div class="column is-narrow">
                <div class="card">
                    <header class="card-header has-text-centered">
                        <p class="card-header-title is-centered">
                            Change Profile Details
                        </p>
                    </header>
                    <div class="card-content">
                        <div class="content">
                            <div class="field">
                                <label class="label">Email Address: </label>
                                <p class="control has-icons-left has-icons-right">
                                    <input class="input" type="email" :value="Email" disabled>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope-open"></i>
                                    </span>
                                </p>
                            </div>
                            <div class="field">
                                <label class="label">Phone Number: </label>
                                <p class="control has-icons-left has-icons-right">
                                    <input v-model="Phone" class="input" type="text" disabled>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </p>
                            </div>
                            <div class="field">
                                <label class="label">Name: </label>
                                <p class="control has-icons-left has-icons-right">
                                    <input v-model="Name" class="input" type="text">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-file-signature"></i>
                                    </span>
                                </p>
                            </div>
                            <div class="field">
                                <label class="label">Password: </label>
                                <p class="control has-icons-left has-icons-right">
                                    <input v-model="Password" class="input" type="password" placeholder="Enter password to update.">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-unlock-alt"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="card-footer-item">
                            <div class="field">
                                <p class="buttons">
                                    <a @click.prevent="doUpdate" class="button is-warning is-rounded">
                                        <span class="icon">
                                        <i class="fas fa-highlighter"></i>
                                        </span>
                                        <span>Update</span>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import swal from "sweetalert";

export default {
  props: ["email"],
  data() {
    return {
      Email: "",
      Phone: "",
      Name: "",
      Password: "",
    };
  },
  computed: {},
  beforeMount () {
      axios.get(route('get.profile'))
                .then( res => {
                    this.Email = res.data.email;
                    this.Phone = res.data.phone;
                    this.Name = res.data.name;
                })
                .catch( err => {
                    // console.log(err);
                });
  },
  methods: {
      doUpdate () {
          let body = new FormData();
          body.append('password', this.Password);
          body.append('name', this.Name);
          body.append('_token', window.token);
          axios.post(route('update.profile'), body)
                .then( res => {
                    if (res.data === 1) {
                        swal("Updated!", "Profile update successful!", "success");
                    } else {
                        swal("Error!", "Invalid Password Entered, Re-check!", "warning");
                    }
                })
                .catch( err => {
                    swal("Error!", "Profile update unsuccessful!", "warning");
                });
      }
  },
  watch: {},
  components: {}
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
input {
    font-size: 14px;
}
</style>

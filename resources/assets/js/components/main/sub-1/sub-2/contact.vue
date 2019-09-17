<template>
    <div class="box">
        <div class="tabs is-centered is-toggle is-toggle-rounded is-small">
            <ul>
                <li :class="[ view === 'contacts' ? 'is-active' : '']">
                <a @click="view = 'contacts'">
                    <span class="icon is-small"><i class="far fa-address-book" aria-hidden="true"></i></span>
                    <span>Contacts</span>
                </a>
                </li>
                <li :class="[ view === 'groups' ? 'is-active' : '']">
                <a @click="view = 'groups'">
                    <span class="icon is-small"><i class="fas fa-users" aria-hidden="true"></i></span>
                    <span>Groups</span>
                </a>
                </li>
            </ul>
        </div>
        <div v-show="view == 'contacts'">
            <nav class="level">
                <!-- Left side -->
                <div class="level-left">
                    <div class="level-item">
                        <a @click.prevent="doDelete" class="button is-small is-rounded is-danger"><span class="icon">
                            <i class="fas fa-user-times"></i>
                            </span>
                            <span>DELETE CONTACT</span></a>
                    </div>

                    <div class="level-item">
                        <a :disabled="messageGroup.length < 1" @click.prevent="appendToSMS" class="button is-small is-rounded">
                                <span class="icon">
                                    <i class="fas fa-share-square"></i>
                                </span>
                                <span>SEND MESSAGE</span>
                            </a>
                    </div>
                </div>

                <!-- Right side -->
                <div class="level-right">
                    <p class="level-item">
                        <a @click.prevent="isModalAddActive = true" class="button is-small is-success is-rounded">
                            <span class="icon" @click.prevent="isModalAddActive = true">
                            <i @click.prevent="isModalAddActive = true" class="fas fa-user-plus"></i>
                            </span>
                            <span @click.prevent="isModalAddActive = true">ADD CONTACT</span></a>
                    </p>
                </div>
            </nav>
            <div class="content" v-show="data.length > 0">
                <table-component
                    :data="data"
                    sort-by="songs"
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
                    <table-column show="m_id" label="MESSAGE">
                        <template slot-scope="row">
                            <input @click="message" type="checkbox" :data-ref="row.m_id">
                        </template>
                    </table-column>
                    <table-column show="first_name" label="FIRST NAME"></table-column>
                    <table-column show="last_name" label="LAST NAME"></table-column>
                    <table-column show="phone_1" label="PHONE NUMBER" data-type="numeric"></table-column>
                    <table-column show="location" label="LOCATION"></table-column>
                    <table-column show="created_at" label="DATE ADDED" :filterable="false" :formatter="formatter" data-type="date:DD-MM-YYYY"></table-column>
                    <table-column label="MODIFY">
                        <template class="has-text-centered" slot-scope="row">
                            <a @click="edit" class="button is-rounded is-info is-small" :data-edit="row.id">
                                <span :data-edit="row.id" class="icon">
                                <i :data-edit="row.id" class="fas fa-user-edit"></i>
                                </span>
                            </a>
                        </template>
                    </table-column>
                </table-component>
                <br>
                <table id="table" class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth is-hidden-desktop is-hidden-tablet">
                    <thead>
                        <tr>
                            <th scope="col">Delete</th>
                            <th scope="col">Message</th>
                            <th scope="col">FIRST NAME</th>
                            <th scope="col">LAST NAME</th>
                            <th scope="col">PHONE NUMBER</th>
                            <th scope="col">LOCATION</th>
                            <th scope="col">DATE ADDED</th>
                            <th scope="col">MODIFY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :ref="data.id" v-for="(data, index) in table" :key="index">
                            <td data-label="Delete">
                                <div class="field">
                                    <div class="">
                                        <input :data-ref="data.id" @click="change" type="checkbox">
                                    </div>
                                </div>
                            </td>
                            <td data-label="Message">
                                <div class="field">
                                    <div class="">
                                        <input :data-ref="data.m_id" @click="message" type="checkbox">
                                    </div>
                                </div>
                            </td>
                            <td data-label="FIRST NAME">{{ data.first_name }}</td>
                            <td data-label="LAST NAME">{{ data.last_name }}</td>
                            <td data-label="PHONE NUMBER">{{ data.phone_1 }}</td>
                            <td data-label="LOCATION">{{ data.location }}</td>
                            <td data-label="DATE ADDED">{{ data.created_at !== "null" ? m(data.created_at).format('MMMM Do YYYY, h:mm a') : "NONE" }}</td>
                            <td data-label="MODIFY">
                                <a @click="edit" class="button is-rounded is-info is-small" :data-edit="data.id">
                                    <span :data-edit="data.id" class="icon">
                                    <i :data-edit="data.id" class="fas fa-user-edit"></i>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="content" v-show="data.length == 0 && isLoading == true">
                <p class="has-text-centered">
                    No contacts available.
                </p>
            </div>
            <div class="preloader" v-show="data.length == 0 && isLoading == false">
                <img src="/img/home/preloader.gif">
            </div>
        </div>

        <!-- GROOUUUUUPS -->
        <div v-show="view == 'groups'">
            <nav class="level">
                <!-- Left side -->
                <div class="level-left">
                    <div class="level-item">
                        <a @click.prevent="doDeleteGroup" class="button is-small is-rounded is-danger is-info"><span class="icon">
                            <i class="fas fa-user-times"></i>
                            </span>
                            <span>DELETE GROUP</span></a>
                    </div>
                </div>

                <!-- Right side -->
                <div class="level-right">
                    <p class="level-item">
                        <a @click.prevent="isModalGroupActive = true" class="button is-small is-success is-rounded">
                            <span class="icon" @click.prevent="isModalGroupActive = true">
                            <i @click.prevent="isModalGroupActive = true" class="fas fa-user-plus"></i>
                            </span>
                            <span @click.prevent="isModalGroupActive = true">ADD GROUP</span></a>
                    </p>
                </div>
            </nav>
            <div class="content" v-show="groups.length > 0">
                <table-component
                    :data="groups"
                    sort-by="songs"
                    sort-order="asc"
                    show-filter= false
                    show-caption= false
                    filterPlaceholder = ''
                    filterNoResults = ''
                    table-class=" modtable table is-mobile is-borderedd is-striped is-hoverable is-hidden-mobile"
                    >
                    <table-column show="id" label="REMOVE">
                        <template slot-scope="row">
                            <input @click="changeForG" type="checkbox" :data-groupref="row.id">
                        </template>
                    </table-column>
                    <table-column show="name" label="GROUP NAME"></table-column>
                    <table-column show="description" label="DESCRIPTION" style="text-overflow: ellipsis;overflow: hidden; white-space: nowrap;"></table-column>
                    <table-column label="MODIFY">
                        <template class="has-text-centered" slot-scope="row">
                            <a @click="editGroup" class="button is-rounded is-info is-small" :data-groupedit="row.id">
                                <span :data-groupedit="row.id" class="icon">
                                <i :data-groupedit="row.id" class="fas fa-user-edit"></i>
                                </span>
                            </a>
                        </template>
                    </table-column>
                </table-component>
                <br>
                <table id="table" class="table is-fullwidth table is-borderedd is-striped is-narrow is-hoverable is-fullwidth is-hidden-desktop is-hidden-tablet">
                    <thead>
                        <tr>
                            <th scope="col">DELETE</th>
                            <th scope="col">NAME</th>
                            <th scope="col">DESCRIPTION</th>
                            <th scope="col">MODIFY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr :ref="data.id" v-for="(data, index) in groups" :key="index">
                            <td data-label="Delete">
                                <div class="field">
                                    <div class="">
                                        <input :data-ref="data.id" @click="changeForG" type="checkbox">
                                    </div>
                                </div>
                            </td>
                            <td style="text-overflow: ellipsis;overflow: hidden; white-space: nowrap;" data-label="NAME">{{ data.name }}</td>
                            <td style="text-overflow: ellipsis;overflow: hidden; white-space: nowrap;" data-label="DESCRIPTION">{{ data.description }}</td>
                            <td data-label="MODIFY">
                                <a @click="editGroup" class="button is-rounded is-info is-small" :data-groupedit="data.id">
                                    <span :data-groupedit="data.id" class="icon">
                                    <i :data-groupedit="data.id" class="fas fa-user-edit"></i>
                                    </span>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="content" v-show="groups.length == 0 && isLoading == true">
                <p class="has-text-centered">
                    No groups available.
                </p>
            </div>
            <div class="preloader" v-show="data.length == 0 && isLoading == false">
                <img src="/img/home/preloader.gif">
            </div>
        </div>
        <!-- CONTACTS MODALS -->
        <div :class="[ isModalActive === true ? 'is-active' : '']" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card" :class="[ isModalActive === true ? 'animated bounceInLeft' : '']">
                <header class="modal-card-head">
                <p class="modal-card-title has-text-centered">EDIT CONTACT</p>
                <button class="delete" aria-label="close" @click="isModalActive = false"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                            <label class="label">First Name</label>
                                <div class="control">
                                    <input class="input" name="first_name" type="text" v-model="contact.first_name">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                            <label class="label">Last Name</label>
                                <div class="control">
                                    <input class="input" type="text" name="last_name" v-model="contact.last_name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                            <label class="label">Phone Number</label>
                                <div class="control">
                                    <input class="input" type="text"  name="phone" v-model="contact.phone">
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="field">
                                <label class="label">Change Location</label>
                                <p class="control has-icons-left">
                                    <span class="select is-fullwidth">
                                    <select v-model="contact.location">
                                        <option selected>Location</option>
                                        <option v-for="s in states" :value="s" v-bind:key="s">{{ s }}</option>
                                    </select>
                                    </span>
                                    <span class="icon is-small is-left">
                                    <i class="fas fa-globe"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button @click="update" class="button is-success" :data-update="contact.id">UPDATE</button>
                    <button class="button" @click="isModalActive = false">Cancel</button>
                </footer>
            </div>
        </div>
        <div :class="[ isModalAddActive === true ? 'is-active' : '']" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card" :class="[ isModalAddActive === true ? 'animated bounceInLeft' : '']">
                <header class="modal-card-head">
                <p class="modal-card-title has-text-centered">ADD CONTACT</p>
                <button class="delete" aria-label="close" @click="isModalAddActive = false"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                            <label class="label">First Name</label>
                                <div class="control">
                                    <input class="input" name="first_name" type="text" v-model="newContact.first_name">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                            <label class="label">Last Name</label>
                                <div class="control">
                                    <input class="input" type="text" name="last_name" v-model="newContact.last_name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                            <label class="label">Phone Number</label>
                                <div class="control">
                                    <input class="input" type="text"  name="phone" v-model="newContact.phone">
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <div class="field">
                                <label class="label">Change Location</label>
                                <p class="control has-icons-left">
                                    <span class="select is-fullwidth">
                                    <select v-model="newContact.location">
                                        <option selected>Location</option>
                                        <option v-for="s in states" :value="s" v-bind:key="s">{{ s }}</option>
                                    </select>
                                    </span>
                                    <span class="icon is-small is-left">
                                    <i class="fas fa-globe"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-12">
                            <div class="field">
                                <label class="label">Group Name</label>
                                <p class="control has-icons-left">
                                    <span class="select is-fullwidth">
                                    <select v-model="newContact.group">
                                        <option selected>Group</option>
                                        <option v-for="s in groups" :value="s.id" v-bind:key="s.id">{{ s.name }}</option>
                                    </select>
                                    </span>
                                    <span class="icon is-small is-left">
                                    <i class="fas fa-users"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button @click="addNewContact" class="button is-success" :data-update="contact.id">ADD NEW CONTACT</button>
                    <button class="button" @click="isModalAddActive = false">Cancel</button>
                </footer>
            </div>
        </div>

        <!-- GROUP MODALS -->
        <div :class="[ isModalGroupActiveA === true ? 'is-active' : '']" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card" :class="[ isModalGroupActiveA === true ? 'animated bounceInLeft' : '']">
                <header class="modal-card-head">
                <p class="modal-card-title has-text-centered">UPDATE GROUP</p>
                <button class="delete" aria-label="close" @click="isModalGroupActiveA = false; group.name = ''; group.description = ''"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-12">
                            <div class="field">
                            <label class="label">Title</label>
                                <div class="control">
                                    <input class="input" name="name" type="text" v-model="group.name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-12">
                            <div class="field">
                            <label class="label">Description</label>
                                <div class="control">
                                    <textarea class="input" type="text" name="description" v-model="group.description" style="height:210px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button @click="updateGroup" class="button is-success" :data-update="group.id">UPDATE</button>
                    <button class="button" @click="isModalGroupActiveA = false; group.name = ''; group.description = ''">Cancel</button>
                </footer>
            </div>
        </div>

        <!-- 2 -->
        <div :class="[ isModalGroupActive === true ? 'is-active' : '']" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card" :class="[ isModalGroupActive === true ? 'animated bounceInLeft' : '']">
                <header class="modal-card-head">
                <p class="modal-card-title has-text-centered">ADD GROUP</p>
                <button class="delete" aria-label="close" @click="isModalGroupActive = false; group.title = ''; group.description = ''"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-12">
                            <div class="field">
                            <label class="label">Title</label>
                                <div class="control">
                                    <input class="input" name="name" type="text" v-model="group.name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-12">
                            <div class="field">
                            <label class="label">Description</label>
                                <div class="control">
                                    <textarea class="input" type="text" name="description" v-model="group.description" style="height:210px"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button @click="addNewGroup" class="button is-success" :data-update="contact.id">ADD</button>
                    <button class="button" @click="isModalGroupActive = false; group.name = ''; group.description = ''">Cancel</button>
                </footer>
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
      messageGroup: [],
      els: [],
      table: [],
      m: moment,
      isModalActive: false,
      isModalAddActive: false,
      isModalGroupActive: false,
      isModalGroupActiveA: false,
      contact: {},
      singleGroup: {},
      newContact: [],
      states: [
        "Abia",
        "Adamawa",
        "Anambra",
        "Akwa Ibom",
        "Bauchi",
        "Bayelsa",
        "Benue",
        "Borno",
        "Cross River",
        "Delta",
        "Ebonyi",
        "Enugu",
        "Edo",
        "Ekiti",
        "FCT - Abuja",
        "Gombe",
        "Imo",
        "Jigawa",
        "Kaduna",
        "Kano",
        "Katsina",
        "Kebbi",
        "Kogi",
        "Kwara",
        "Lagos",
        "Nasarawa",
        "Niger",
        "Ogun",
        "Ondo",
        "Osun",
        "Oyo",
        "Plateau",
        "Rivers",
        "Sokoto",
        "Taraba",
        "Yobe",
        "Zamfara"
      ],
      groups: [],
      group: {},
      isLoading: false
    };
  },
  methods: {
    formatter(value, rowProperties) {
      return moment(value).format("LLLL");
    },
    appendToSMS($) {
      if (this.messageGroup.length === 0) {
        return;
      }
      let vm = this;
    //   vm.$root.$children[0].$children[3].$children[0]._data.sms.contacts_list = this.messageGroup.join(',');
        let payload = {
            "message": "",
            "title": "",
            "contacts": this.messageGroup.join(',')
        }
        this.$vueEventBus.$emit('input', payload);
      setTimeout(() => {
        this.$root.$children[0].$children[3]._data.currentView = "sms";
      }, 1880);
    },
    addNewContact($) {
      $.target.classList.add("is-loading");
      $.target.disabled = true;
      let form = new FormData();
      form.append("_token", window.token);
      form.append("first_name", this.newContact.first_name);
      form.append("last_name", this.newContact.last_name);
      form.append("phone", this.newContact.phone);
      form.append("location", this.newContact.location);
      form.append("group", this.newContact.group);

      axios
        .post(route("add.new.contact"), form)
        .then(res => {
          if (res.data.status === 1) {
            setTimeout(() => {
              this.isModalAddActive = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.success({
                title: `Success`,
                message: `Contact added successfully.`,
                color: `green`,
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomRight",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
            }, 800);
            this.$set(this, 'data', res.data.contacts);
            this.$set(this, 'table', res.data.contacts);
            this.$set(this, 'groups', res.data.groups);
          } else if (res.data.status === 2) {
            setTimeout(() => {
              this.isModalAddActive = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.warning({
                title: `Error`,
                message: `Contact exists.`,
                color: `yellow`,
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomRight",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
            }, 800);
          } else {
            setTimeout(() => {
              this.isModalAddActive = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.success({
                title: `Error`,
                message: `Operation could not be completed.`,
                color: `red`,
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomRight",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
            }, 800);
          }
        })
        .catch(err => {
          $.target.disabled = false;
          $.target.classList.remove("is-loading");
        });
    },
    addNewGroup($) {
      $.target.classList.add("is-loading");
      $.target.disabled = true;
      let form = new FormData();
      form.append("_token", window.token);
      form.append("name", this.group.name);
      form.append("description", this.group.description);

      axios
        .post(route("add.new.group"), form)
        .then(res => {
          if (res.data.status === 1) {
            setTimeout(() => {
              this.isModalAddActiveA = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.success({
                title: `Success`,
                message: `Group added successfully.`,
                color: `green`,
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomRight",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
            }, 800);
            this.$set(this, 'data', res.data.contacts);
            this.$set(this, 'table', res.data.contacts);
            this.$set(this, 'groups', res.data.groups);
          } else if (res.data.status === 2) {
            setTimeout(() => {
              this.isModalAddActiveA = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.warning({
                title: `Error`,
                message: `Group exists.`,
                color: `yellow`,
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomRight",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
            }, 800);
          } else {
            setTimeout(() => {
              this.isModalAddActiveA = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.success({
                title: `Error`,
                message: `Operation could not be completed.`,
                color: `red`,
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomRight",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
            }, 800);
          }
        })
        .catch(err => {
          this.isModalAddActiveA = false;
          $.target.disabled = false;
          $.target.classList.remove("is-loading");
        });
    },
    update($) {
      $.target.classList.add("is-loading");
      $.target.disabled = true;
      let data = new FormData();
      data.append("id", this.contact.id);
      data.append("first_name", this.contact.first_name);
      data.append("last_name", this.contact.last_name);
      data.append("location", this.contact.location);
      data.append("phone", this.contact.phone);
      axios
        .post(route("update.contact"), data)
        .then(res => {
          if (res.data.status == 1) {
            setTimeout(() => {
              this.isModalActive = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.success({
                title: `Success`,
                message: `Updated successfully.`,
                color: `green`,
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomRight",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
            }, 800);
            this.$set(this, 'data', res.data.contacts);
            this.$set(this, 'table', res.data.contacts);
            this.$set(this, 'groups', res.data.groups);
          } else if (res.data.status == 0) {
              this.isModalActive = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.info({
                title: `Done`,
                message: `No changes made.`,
                color: 'blue',
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomCenter",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
          } else {
            $.target.disabled = false;
            $.target.classList.remove("is-loading");
            iziToast.error({
              title: `Error`,
              message: `occured while updating contact.`,
              color: `red`,
              close: true,
              closeOnEscape: false,
              closeOnClick: false,
              rtl: false,
              position: "bottomRight",
              target: "",
              targetFirst: true,
              toastOnce: false,
              timeout: 5000,
              animateInside: true,
              drag: true,
              pauseOnHover: true,
              resetOnHover: false,
              progressBar: false,
              progressBarColor: "",
              progressBarEasing: "linear",
              overlay: false,
              overlayClose: false
            });
          }
        })
        .catch(err => {
          $.target.disabled = false;
          $.target.classList.remove("is-loading");
        });
    },
    updateGroup($) {
      $.target.classList.add("is-loading");
      $.target.disabled = true;
      let data = new FormData();
      data.append("id", $.target.dataset.update);
      data.append("_token", window.token);
      data.append("name", this.group.name);
      data.append("description", this.group.description);
      axios
        .post(route("update.group"), data)
        .then(res => {
          if (res.data.status === 1) {
            this.isModalGroupActiveA = false;
            $.target.disabled = false;
            $.target.classList.remove("is-loading");
            setTimeout(() => {
              iziToast.success({
                title: `Success`,
                message: `Updated successfully.`,
                color: `green`,
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomRight",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
            }, 800);
            this.$set(this, 'data', res.data.contacts);
            this.$set(this, 'table', res.data.contacts);
            this.$set(this, 'groups', res.data.groups);
          } else if (res.data.status === 0) {
              this.isModalGroupActiveA = false;
              $.target.disabled = false;
              $.target.classList.remove("is-loading");
              iziToast.info({
                title: `Done`,
                message: `No changes made.`,
                color: 'blue',
                close: true,
                closeOnEscape: false,
                closeOnClick: false,
                rtl: false,
                position: "bottomCenter",
                target: "",
                targetFirst: true,
                toastOnce: false,
                timeout: 5000,
                animateInside: true,
                drag: true,
                pauseOnHover: true,
                resetOnHover: false,
                progressBar: false,
                progressBarColor: "",
                progressBarEasing: "linear",
                overlay: false,
                overlayClose: false
              });
          } else {
            $.target.disabled = false;
            $.target.classList.remove("is-loading");
            iziToast.error({
              title: `Error`,
              message: `Could not update, try again.`,
              color: `red`,
              close: true,
              closeOnEscape: false,
              closeOnClick: false,
              rtl: false,
              position: "bottomRight",
              target: "",
              targetFirst: true,
              toastOnce: false,
              timeout: 5000,
              animateInside: true,
              drag: true,
              pauseOnHover: true,
              resetOnHover: false,
              progressBar: false,
              progressBarColor: "",
              progressBarEasing: "linear",
              overlay: false,
              overlayClose: false
            });
          }
        })
        .catch(err => {
          $.target.disabled = false;
          $.target.classList.remove("is-loading");
          $.target.disabled = false;
            $.target.classList.remove("is-loading");
            iziToast.error({
              title: `Error`,
              message: `Could not update, try again.`,
              color: `red`,
              close: true,
              closeOnEscape: false,
              closeOnClick: false,
              rtl: false,
              position: "bottomRight",
              target: "",
              targetFirst: true,
              toastOnce: false,
              timeout: 5000,
              animateInside: true,
              drag: true,
              pauseOnHover: true,
              resetOnHover: false,
              progressBar: false,
              progressBarColor: "",
              progressBarEasing: "linear",
              overlay: false,
              overlayClose: false
            });
        });
    },
    edit($) {
      axios
        .get(route("single.contact").url() + `?id=${$.target.dataset.edit}`)
        .then(res => {
          this.contact = res.data;
          setTimeout(() => {
            this.isModalActive = true;
          }, 800);
        })
        .catch(err => {
          iziToast.error({
              title: `Error`,
              message: `Could not retrieve contact.`,
              color: `red`,
              close: true,
              closeOnEscape: false,
              closeOnClick: false,
              rtl: false,
              position: "bottomRight",
              target: "",
              targetFirst: true,
              toastOnce: false,
              timeout: 5000,
              animateInside: true,
              drag: true,
              pauseOnHover: true,
              resetOnHover: false,
              progressBar: false,
              progressBarColor: "",
              progressBarEasing: "linear",
              overlay: false,
              overlayClose: false
            });
        });
    },
    editGroup($) {
      axios
        .get(route("edit.group").url() + `?id=${$.target.dataset.groupedit}`)
        .then(res => {
          this.group = res.data;
          setTimeout(() => {
            this.isModalGroupActiveA = true;
          }, 800);
        })
        .catch(err => {
            iziToast.error({
              title: `Error`,
              message: `Could not retrieve group..`,
              color: `red`,
              close: true,
              closeOnEscape: false,
              closeOnClick: false,
              rtl: false,
              position: "bottomRight",
              target: "",
              targetFirst: true,
              toastOnce: false,
              timeout: 5000,
              animateInside: true,
              drag: true,
              pauseOnHover: true,
              resetOnHover: false,
              progressBar: false,
              progressBarColor: "",
              progressBarEasing: "linear",
              overlay: false,
              overlayClose: false
            });
        });
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
    message($) {
      if ($.target.checked === true) {
        this.data.forEach(d => {
          if (d.id === $.target.dataset.ref) {
            if (this.messageGroup.includes(d.phone_1)) return;
            this.messageGroup.push(d.phone_1);
          }
        });
      } else if ($.target.checked === false) {
        this.data.forEach(d => {
          if (d.id === $.target.dataset.ref) {
            var index = this.messageGroup.indexOf(d.phone_1);
            if (index > -1) {
                this.messageGroup.splice(index, 1);
            }
          }
        });
      }
    },
    changeForG($) {
      if ($.target.checked == true) {
        this.delete.push($.target.dataset.ref);
        this.els.push($.target.parentElement.parentElement);
      } else if ($.target.checked == false) {
        var index = this.delete.indexOf($.target.dataset.groupref);
        if (index > -1) {
          this.delete.splice(index, 1);
        }
      }
    },
    doDelete() {
        if (this.delete.length < 1) return;
      axios
        .get(route("delete.contact").url() + `?id=${this.delete.toString()}`)
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
            iziToast.error({
              title: `Error`,
              message: `Could not delete contact.`,
              color: `red`,
              close: true,
              closeOnEscape: false,
              closeOnClick: false,
              rtl: false,
              position: "bottomRight",
              target: "",
              targetFirst: true,
              toastOnce: false,
              timeout: 5000,
              animateInside: true,
              drag: true,
              pauseOnHover: true,
              resetOnHover: false,
              progressBar: false,
              progressBarColor: "",
              progressBarEasing: "linear",
              overlay: false,
              overlayClose: false
            });
        });
    },
    doDeleteGroup() {
      axios
        .get(route("delete.group").url() + `?id=${this.delete.toString()}`)
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
          iziToast.error({
              title: `Error`,
              message: `Could not delete group.`,
              color: `red`,
              close: true,
              closeOnEscape: false,
              closeOnClick: false,
              rtl: false,
              position: "bottomRight",
              target: "",
              targetFirst: true,
              toastOnce: false,
              timeout: 5000,
              animateInside: true,
              drag: true,
              pauseOnHover: true,
              resetOnHover: false,
              progressBar: false,
              progressBarColor: "",
              progressBarEasing: "linear",
              overlay: false,
              overlayClose: false
            });
        });
    }
  },
  mounted() {
    axios
      .get(route("view.contacts.data"))
      .then(res => {
        this.isLoading = true;
        this.data = res.data.contacts;
        this.table = res.data.contacts;
        this.groups = res.data.groups;
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

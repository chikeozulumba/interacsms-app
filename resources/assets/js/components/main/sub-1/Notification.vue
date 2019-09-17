<template>
    <nav id="nav" class="level is-mobile box">
        <div id="notyfications">
            <div style="border-radius: 15px; border-bottom: 0px solid rgb(237, 173, 0)" v-for="(not, i) in notifications" :key="i" class="iziModal noty-group" data-izimodal-group="alerts" :data-izimodal-title="not.title" :data-izimodal-subtitle="moment(not.time_log).format('dddd MMMM Do YYYY, h:mm:ss a')">
                <div style="padding: 10px;font-size: 1rem;text-align: center;" class="content">
                    {{ not.message }}
                </div>
            </div>
        </div>

        <div @click.prevent="notification" style="cursor: pointer;" class="trigger-noty noty">
            <span class="tag is-danger">{{ count }}</span>
            <img class="" id="noty" src="/img/svgs/alert.svg" alt="" srcset="">
        </div>
        <div class="level-item has-text-centered">
            <div>
            <p class="heading  has-text-danger">SENT</p>
            <p class="title">{{ messages }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
            <p class="heading  has-text-danger">CONTACTS</p>
            <p class="title">{{ contacts }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
            <p class="heading  has-text-danger">UNITS</p>
            <p class="title">{{ units }}</p>
            </div>
        </div>
        <div id="snackbar" class="card">Some text some message..</div>
    </nav>
</template>

<script>
import axios from 'axios';
import $ from "jquery";
import iziModal from "izimodal";
import moment from "moment";
export default {
    data () {
        return {
            count: 0,
            units: 0,
            messages: 0,
            contacts: 0,
            notifications: [],
            moment: moment
        }
    },
    methods : {
        meta() {
            const vm = this;
            axios.get(route('meta.update'))
                .then( function (res) {
                    if (res) {
                        vm.units = res.data.units;
                        vm.contacts = res.data.contacts;
                        vm.messages = res.data.messages;
                        vm.notifications = res.data.notifications;
                    }
                })
                .catch( error => {
                    // console.log(error);
                });
        },
        notification (e) {
            if (this.notifications.length < 1) {
                return;
            } else {
                $('.noty-group').iziModal('open');
                $(".noty-group").iziModal({
                    title: 'A random title',
                    subtitle: 'For a random text',
                    theme: '',
                    headerColor: '#edad00',
                    overlayColor: 'rgba(0, 0, 0, 0.4)',
                    iconColor: '',
                    iconClass: null,
                    width: 800,
                    padding: 0,
                    overlayClose: true,
                    closeOnEscape: true,
                    bodyOverflow: false,
                    top: null,
                    bottom: null,
                    borderBottom: true,
                    padding: 0,
                    radius: 3,
                    group: 'notifications',
                    onClosed: this.markAsRead,
                });
            }
        },
        markAsRead : function () {
            let ids = [];
            let drop = this.notifications;
            let length = drop.length;
            if (drop.length > 0) {
                drop.forEach( (el, i) => {
                    ids.push(el.id);
                    if (i === (length - 1)) {
                        axios.get(route('close.notifications').url() + `?ids=${ids.join(',')}`)
                            .then( res => {
                                if (res.data === 1) {
                                    let el = document.getElementsByClassName('iziModal-overlay')[0];
                                    if (el !== 'undefined') el.style.display = 'none';
                                    this.meta();
                                }
                            })
                            .catch();
                    }
                });
            }
        },
        dodo_ : function () {
            let not_parent = document.getElementById('notyfications');
            while (not_parent.firstNode) {
                not_parent.removeChild(not_parent.firstNode);
                console.log('i');
            }
            console.log('closed');
        },
        meta() {
            axios.get(route('meta.update'))
                .then( function (res) {
                    if (res) {
                        this.units = res.data.units;
                        this.contacts = res.data.contacts;
                        this.messages = res.data.messages;
                        this.notifications = res.data.notifications;
                        this.count = res.data.notifications.length;
                    }
                    console.log('otobi');
                })
                .catch( error => {
                    // console.log(error);
                });
        }
    },
    beforeMount () {
        const vm = this;
        function meta() {
            axios.get(route('meta.update'))
                .then( function (res) {
                    if (res) {
                        vm.units = res.data.units;
                        vm.contacts = res.data.contacts;
                        vm.messages = res.data.messages;
                        vm.notifications = res.data.notifications;
                        vm.count = res.data.notifications.length;
                    }
                })
                .catch( error => {
                    // console.log(error);
                });
        }
        meta();
        setInterval(function() {
            meta();
        }, 60000);
        $.fn.iziModal = iziModal;
    }
}
</script>

<style scoped>
.iziModal-header-subtitle {
  color: white !important;
  font-style: italic !important;
}
@media screen and (max-width: 400px) {
  .noty img {
    display: none;
  }
}
@media screen and (max-width: 823px) {
  .noty-group {
    margin: auto 10% !important;
  }
}
/* @media screen and (max-width: 1350px) { */
.noty img {
  height: 70%;
  width: 70%;
  margin-top: -30%;
}
.noty span {
  position: relative;
  top: 5%;
  right: 15%;
}
/* } */
@media screen and (min-width: 1350px) {
  .noty img {
    width: 60%;
    height: 60%;
    margin-top: 0 !important;
  }
  .noty span {
    position: relative;
    top: 10%;
    right: -60%;
  }
}
.noty {
  width: 10%;
  text-align: center;
}
.icons-notifications > img {
  height: 30px;
  width: 35px;
}

.icons-notifications > span {
  position: absolute;
  z-index: 10;
  margin-left: 3.3vw;
  margin-top: 0.5vw;
  border-radius: 9px;
}
.title {
  font-size: 1.5em !important;
}
/* The snackbar - position it at the bottom and in the middle of the screen */
#snackbar {
  visibility: hidden; /* Hidden by default. Visible on click */
  min-width: 250px; /* Set a default minimum width */
  margin-left: -125px; /* Divide value of min-width by 2 */
  background-color: #333; /* Black background color */
  color: #fff; /* White text color */
  text-align: center; /* Centered text */
  border-radius: 2px; /* Rounded borders */
  padding: 16px; /* Padding */
  position: fixed; /* Sit on top of the screen */
  z-index: 1; /* Add a z-index if needed */
  left: 10px; /* Center the snackbar */
  bottom: 30px; /* 30px from the bottom */
}

/* Show the snackbar when clicking on a button (class added with JavaScript) */
#snackbar.show {
  visibility: visible; /* Show the snackbar */
}

/* /* Add animation: Take 0.5 seconds to fade in and out the snackbar.
However, delay the fade out process for 2.5 seconds
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
} */

/* Animations to fade the snackbar in and out */
@-webkit-keyframes fadein {
  from {
    bottom: 0;
    opacity: 0;
  }
  to {
    bottom: 30px;
    opacity: 1;
  }
}

@keyframes fadein {
  from {
    bottom: 0;
    opacity: 0;
  }
  to {
    bottom: 30px;
    opacity: 1;
  }
}

@-webkit-keyframes fadeout {
  from {
    bottom: 30px;
    opacity: 1;
  }
  to {
    bottom: 0;
    opacity: 0;
  }
}

@keyframes fadeout {
  from {
    bottom: 30px;
    opacity: 1;
  }
  to {
    bottom: 0;
    opacity: 0;
  }
}
</style>

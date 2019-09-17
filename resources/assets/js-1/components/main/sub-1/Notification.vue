<template>
    <nav class="level is-mobilei box">
        <div class="level-item has-text-centered">
            <div>
            <p class="heading">SENT MESSAGES</p>
            <p class="title icons-notifications">
                <span class="tag is-success is-pulled-right">
                {{ messages }}
                </span>
                <img src="/img/svgs/paper-plane.svg" alt="" srcset="">
            </p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
            <p class="heading">CONTACTS</p>
            <p class="title icons-notifications">
                <span class="tag is-success is-pulled-right">
                {{ contacts }}
                </span>
                <img src="/img/svgs/contacts.svg" alt="" srcset="">
            </p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
            <p class="heading">UNITS</p>
            <p class="title icons-notifications">
                <span class="tag is-success is-pulled-right">
                {{ units }}
                </span>
                <img src="/img/svgs/coins.svg" class="badge is-badge-success" data-badge="88" alt="" srcset="">
            </p>
            </div>
        </div>
        <!-- <div class="level-item has-text-centered">
            <div>
            <p class="heading">NOTIFICATIONS</p>
            <p class="title icons-notifications">
                <span class="tag is-success is-pulled-right">
                4
                </span>
                <img src="/img/svgs/mail.svg" alt="" srcset="">
            </p>
            </div>
        </div> -->
    </nav>
</template>

<script>
import axios from 'axios';
export default {
    data () {
        return {
            units: 0,
            messages: 0,
            contacts: 0,
            notifications: 0
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
                    }
                })
                .catch( error => {
                    // console.log(error);
                });
        }
    },
    mounted () {
        const vm = this;
        function meta() {
            axios.get(route('meta.update'))
                .then( function (res) {
                    if (res) {
                        vm.units = res.data.units;
                        vm.contacts = res.data.contacts;
                        vm.messages = res.data.messages;
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
    }
}
</script>

<style scoped>
.icons-notifications > img {
    height: 30px;
    width: 35px;
}

.icons-notifications > span{
    position: absolute;
    z-index: 10;
    margin-left: 3.3vw;
    margin-top: 0.5vw;
    border-radius: 9px;
}
</style>

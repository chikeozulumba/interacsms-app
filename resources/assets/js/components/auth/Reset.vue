<template>
    <section class="section">
        <div class="container is-fullhd">
            <div class="columns is-mobile is-centered">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title is-centered">
                                RESET PASSWORD
                            </p>
                            <a href="#" class="card-header-icon" aria-label="more options">
                                <span class="icon">
                                    <i class="fas fa-file-signature" aria-hidden="true"></i>
                                </span>
                            </a>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <form @submit.prevent="sendVerification" method="POST" :action="url">
                                    <input type="hidden" :value="csrf" name="_token">
                                    <input type="hidden" :value="uri" name="token">
                                    <div class="field">
                                        <label class="label">New Password</label>
                                        <div class="control has-icons-left has-icons-right">
                                            <input name="password" v-model="password" class="input is-rounded" type="password" placeholder="**********">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="label">Confirm Password</label>
                                        <div class="control has-icons-left has-icons-right">
                                            <input name="confirm_password" v-model="newPassword" class="input is-rounded" type="password" placeholder="**********">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control" style="text-align: center;">
                                            <button class="button is-warning is-rounded is-link">Update Password</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";
import { sentenceCase } from '../../helpers/index.js';
export default {
    data () {
        return {
            csrf: "",
            newPassword: "",
            password: "",
            check: false,
            url: route('update.password'),
            uri: ''

        }
    },
    mounted() {
        this.csrf = document.getElementById("csrf-token").content;
        var qs = (function(a) {
            if (a == "") return {};
            var b = {};
            for (var i = 0; i < a.length; ++i)
            {
                var p=a[i].split('=', 2);
                if (p.length == 1)
                    b[p[0]] = "";
                else
                    b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
            }
            return b;
        })(window.location.search.substr(1).split('&'));
        this.uri = qs.email;
    },
    methods: {
        changeView (e) {
            this.$parent.isView = e;
        },
        sendVerification ($) {
            let user = {
                password: this.password,
                confirm_password: this.newPassword
            };
            for (var key in user) {
                if (user.hasOwnProperty(key)) {
                    if(user[key].length < 1) {
                        iziToast.error({
                            title: `${sentenceCase(key)}`,
                            message: ' field required.'
                        });
                        return;
                    }
                }
            }
            for (var key in user) {
                if (user.hasOwnProperty(key)) {
                    if((user[key].length < 6 && user[key].length > 0)) {
                        iziToast.error({
                            title: `${sentenceCase(key)}`,
                            message: 'should not be less than 6 characters.'
                        });
                        return;
                    }
                }
            }
            if (this.password !== this.newPassword) {
                iziToast.error({
                    title: `Error`,
                    message: 'Passwords don\'t match.'
                });
                return;
            } else {
                $.target.submit();
            }
        }
    }
}
</script>

<style scoped>
.navbar-item img {
  height: 100px;
}

.navbar-item img {
  max-height: 2.75rem !important;
}
.card {
  border-radius: 5px;
}
</style>

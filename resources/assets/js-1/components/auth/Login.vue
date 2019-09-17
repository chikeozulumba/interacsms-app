<template>
    <section class="section">
        <div class="container is-fullhd">
            <div class="columns is-mobile is-centered">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title is-centered">
                                LOGIN
                            </p>
                            <a href="#" class="card-header-icon" aria-label="more options">
                                <span class="icon">
                                    <i class="fas fa-file-signature" aria-hidden="true"></i>
                                </span>
                            </a>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <form method="POST" ref="login" :action="routes.login">
                                    <input type="hidden" :value="csrf" name="_token">
                                    <div class="field">
                                        <label class="label">Email</label>
                                        <div class="control has-icons-left has-icons-right">
                                            <input name="email" v-model="email" class="input is-rounded" type="email" placeholder="hello@">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <!-- <span class="icon is-small is-right">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span> -->
                                        </div>
                                        <!-- <p class="help is-danger">This email is invalid</p> -->
                                    </div>

                                    <div class="field">
                                        <label class="label">Password</label>
                                        <div class="control has-icons-left has-icons-right">
                                            <input class="input is-rounded" type="password" name="password" v-model="password" placeholder="Enter Password">
                                            <span class="icon is-small is-left">
                                                    <i class="fas fa-key"></i>
                                            </span>
                                            <!-- <span class="icon is-small is-right">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span> -->
                                        </div>
                                        <!-- <p class="help is-danger">This email is invalid</p> -->
                                    </div>

                                    <div class="field">
                                        <div class="control">
                                            <label class="checkbox" style="font-size:12px">
                                            <input name="remember" type="checkbox" v-model="check">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control" style="text-align: center;">
                                            <button @click.prevent="login" class="button is-warning is-rounded is-link">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <p class="card-footer-item">
                            <span>
                                <a style="font-size:12px" @click.prevent="changeView">Create an account</a>
                            </span>
                            </p>
                        </footer>
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
            email: "",
            password: "",
            check: false,
            routes: {
                register: route('register'),
                login: route('auth')
            }

        }
    },
    mounted() {
        this.csrf = document.getElementById("csrf-token").content;
    },
    methods: {
        changeView (e) {
            this.$parent.isView = "Register";
        },
        login (e) {
            let user = {
                email: this.email,
                password: this.password
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
            this.$refs.login.submit();
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

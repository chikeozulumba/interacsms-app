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
                                <form @submit.prevent="sendVerification" method="POST" ref="login">
                                    <input type="hidden" :value="csrf" name="_token">
                                    <div class="field">
                                        <label class="label">Email</label>
                                        <div class="control has-icons-left has-icons-right">
                                            <input name="email" v-model="email" class="input is-rounded" type="email" placeholder="hello@">
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="control" style="text-align: center;">
                                            <button class="button is-warning is-rounded is-link">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <p class="card-footer-item">
                                <span>
                                    <a style="font-size:12px" @click.prevent="changeView('Login')">Login</a>
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
            check: false

        }
    },
    mounted() {
        this.csrf = document.getElementById("csrf-token").content;
    },
    methods: {
        changeView (e) {
            this.$parent.isView = e;
        },
        sendVerification (e) {
            let user = {
                email: this.email
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
            let form = new FormData();
            form.append('email', user.email);
            form.append('_token', this.csrf);
            axios.post(route('reset.password'), form)
                    .then(res => {
                        if (res.data.status === 200) {
                            iziToast.success({
                                title: `Success`,
                                message: `${res.data.message}`
                            });
                        } else {
                            iziToast.error({
                                title: `Error`,
                                message: `${res.data.message}`
                            });
                        }
                    })
                    .catch( err => {
                        iziToast.error({
                            title: `Error`,
                            message: `An unexpected error encountered!.`
                        });
                    });
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

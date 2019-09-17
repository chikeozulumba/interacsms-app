<template>
    <section class="section">
        <div class="container is-fullhd">
            <div class="columns is-mobile is-centered">
                <div class="column is-mobile is-two-fifths is-11">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title is-centered">
                                VERIFY EMAIL ADDRESS
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <p v-show="disable == false" class="has-text-centered" style="font-size:14px">
                                    A confirmation email will be sent to your email address - <span class="has-text-danger">{{ email }}</span>
                                </p>
                                <p v-show="disable == true" class="has-text-centered" style="font-size:14px">
                                    Email sent, kindly check your Mail Box.
                                </p>
                            </div>
                        </div>
                        <footer v-show="disable == false" class="card-footer">
                            <p class="card-footer-item">
                            <span>
                                <a @click.prevent="verify" style="font-size:12px" class="button is-warning is-rounded">Continue</a>
                            </span>
                            </p>
                        </footer>
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
    props: ['email'],
    data () {
        return {
            disable: false,
            routes: {
                verify: route('auth.verify.email')
            }

        }
    },
    mounted() {
        this.csrf = document.getElementById("csrf-token").content;
    },
    methods: {
        verify () {
            axios.get(this.routes.verify)
                .then( response => {
                    if (response.data.status == 200) {
                        iziToast.success({
                            title: `Success`,
                            message: response.data.message
                        });
                        this.disable = true;
                    } else {
                        iziToast.error({
                            title: `Error`,
                            message: 'Verification Email, could\'t be sent.'
                        });
                    }
                })
                .catch( error => {
                    iziToast.error({
                        title: `Error`,
                        message: 'Network error encountered.'
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
    margin-top: 20vh;
}
</style>

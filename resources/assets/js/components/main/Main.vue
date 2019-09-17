<template>
    <div id="appWrapper">
        <navbar></navbar>
        <section class="section">
            <div class="container is-fullhd">
                <div class="columns">
                    <side></side>
                    <div class="column is-8">
                        <notification class="i-hidden-mobile"></notification>
                        <keep-alive>
                            <component v-on:change-view="currentView = $event" :is="currentView"></component>
                        </keep-alive>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import axios from "axios";
import { sentenceCase } from '../../helpers/index.js';

// COMPONENTS
import Notification from "./sub-1/Notification";
import Side from "./sub-1/Side";
import Container from "./sub-1/Container";
import Navbar from "./sub-1/Navbar";

export default {
    props: ['email', 'reference', 'userid'],
    data () {
        return {
            currentView: "Container"
        }
    },
    components: {
        Side,
        Container,
        Notification,
        Navbar
    },
    mounted() {
        Echo.private('user.fb5f3ab0-9671-11e8-a608-f7709d2ec7c0')
        .listen('App\\Events\\MessageProcessed', (e) => {
            console.log(e);
        });
    }
}
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

#app {
  background-color: #eead00 !important;
  min-height: 100vh !important;
}
</style>

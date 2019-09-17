<template class="t">
    <transition
        name="custom-classes-transition"
        enter-active-class="animatedz  animated fadeInRightBig"
        leave-active-class="animatedy animated fadeOutRightBig"
    >
        <!-- <keep-alive> -->
            <component :is="currentView"></component>
        <!-- </keep-alive> -->
    </transition>
</template>

<script>
import sms from './sub-2/sms';
import inbox from './sub-2/inbox';
import sent from './sub-2/sent';
import recharge from './sub-2/recharge';
import contact from './sub-2/contact';
import draft from './sub-2/draft';
import profile from './sub-2/profile';
import schedule from './sub-2/schedule';
import payment from './sub-2/payment';
export default {
    data () {
        return {
            currentView: "draft",
            name: "container-sub-1"
        }
    },
    components: {
        sms,
        inbox,
        sent,
        recharge,
        contact,
        draft,
        profile,
        schedule,
        payment
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
.animatedy {
    /* display: none !important; */
    position: absolute;
    /* z-index: -999999999; */
}

.animatedz {
    position: relative;
    /* z-index: 999999999; */
}
</style>

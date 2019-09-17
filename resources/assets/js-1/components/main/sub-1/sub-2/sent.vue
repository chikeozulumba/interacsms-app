<template>
    <transition
            name="custom-classes-transition"
            enter-active-class="animatedz  animated fadeInRightBig"
            leave-active-class="animatedy animated fadeOutRightBig"
    >
        <keep-alive>
            <component :is="currentView"></component>
        </keep-alive>
    </transition>
</template>

<script>
import sent from './sub-3/sent.vue'
import analytics from './sub-3/analytics.vue'
export default {
    data () {
        return {
            currentView: "sent",
            name: "sent-sub-2"
        }
    },
    components: {
        sent,
        analytics
    }
}
</script>

<style scoped>

</style>

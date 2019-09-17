<template>
    <keep-alive>
        <component :is="isView"></component>
    </keep-alive>
</template>

<script>
import Login from "./Login";
import Register from "./Register";
export default {
    data () {
        return {
            isView: Login
        }
    },
    components : {
        Login,
        Register
    }
}
</script>
<style scoped>

</style>


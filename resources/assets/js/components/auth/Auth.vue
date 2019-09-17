<template>
    <keep-alive>
        <component :is="isView"></component>
    </keep-alive>
</template>

<script>
import Login from "./Login";
import Register from "./Register";
import Forgot from "./Forgot";
import Reset from "./Reset";
export default {
    data () {
        return {
            isView: Login
        }
    },
    components : {
        Login,
        Register,
        Forgot,
        Reset,
    },
    mounted () {
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
        if (qs.email) this.isView = 'Reset';
        if (qs.register) this.isView = 'Register';
    }
}
</script>
<style scoped>
</style>


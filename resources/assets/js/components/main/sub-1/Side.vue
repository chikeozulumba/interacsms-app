<template>
    <div class="column is-one-quarter is-hidden-mobile">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title is-centered">
                    <a @click.prevent="change('sms')" style="font-size:12px;" class="button is-larger is-warning is-rounded c-sms">COMPOSE SMS</a>
                </p>
            </header>
            <aside class="card-menu menu">
                <p class="menu-label">
                    Main Menu
                </p>
                <ul class="menu-list">
                    <li>
                        <span class="icon has-text-success">
                            <i class="fas fa-envelope-open"></i>
                        </span>
                        <a @click.prevent="change('sent')">SENT HISTORY</a>
                    </li>
                    <li>
                        <span class="icon has-text-info">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <a @click.prevent="change('schedule')">SCHEDULED SMS</a>
                    </li>
                    <li>
                        <span class="icon has-text-warning">
                            <i class="fas fa-pen-square"></i>
                        </span>
                        <a @click.prevent="change('draft')">DRAFTS</a>
                    </li>
                    <li>
                        <span class="icon has-text-danger">
                            <i class="fas fa-users"></i>
                        </span>
                        <a @click.prevent="change('contact')">PHONE GROUPS</a>
                    </li>
                </ul>
                <p class="menu-label">
                    Manage Account
                </p>
                <ul class="menu-list">
                    <li>
                        <span class="icon has-text-info">
                            <i class="fas fa-money-check"></i>
                        </span>
                        <a @click.prevent="change('recharge')">PURCHASE UNITS</a>
                    </li>
                    <li>
                        <span class="icon" style="color:#ff5252;">
                            <i class="fas fa-history"></i>
                        </span>
                        <a @click.prevent="change('payment')">PAYMENT HISTORY</a>
                    </li>
                    <li>
                        <span class="icon" style="color:#2979ff;">
                            <i class="fas fa-user-circle"></i>
                        </span>
                        <a @click.prevent="change('profile')">MANAGE PROFILE</a>
                    </li>
                </ul>
                <p class="menu-label">
                    Support
                </p>
                <ul class="menu-list">
                    <li>
                        <span class="icon" style="color:rgb(150, 37, 87);">
                            <i class="fas fa-question-circle"></i>
                        </span>
                        <a @click.prevent="change('faq')">FAQs</a>
                    </li>
                </ul>
                </aside>
                <form style="display:none;" :action="logout" method="POST" id="logout-form">
                    <input type="hidden" name="_token" :value="token">
                </form>
            <footer class="card-footer">
                <p class="card-footer-item">
                <span>
                    <a @click.prevent="logoutF" style="font-size:12px" class="button is-larger is-warning is-rounded">LOGOUT</a>
                </span>
                </p>
            </footer>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            token: window.token,
            logout: route('logout')
        }
    },
    methods: {
        change ($) {
            this.$root.$children[0].$children[3]._data.currentView = $;
            localStorage.setItem('view', $);
        },
        logoutF () {
            event.preventDefault();
            document.getElementById('logout-form').submit();
            localStorage.removeItem('view');
        }
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
.c-sms {
  padding: 2rem;
  margin: 1rem 0;
}
li > span.icon {
  margin: 2.2px 0;
}
.menu-list > li {
  display: grid;
  grid-template-columns: 0.1fr 1fr;
}
.menu-label {
  font-size: 15px;
}
</style>

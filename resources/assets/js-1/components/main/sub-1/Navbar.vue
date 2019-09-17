<template>
    <section class="navWrapper">
            <div class="container is-fullhd">
                <nav class="navbar nav-container container" role="navigation" aria-label="dropdown navigation">
                    <div class="navbar-brand">
                        <a class="navbar-item" href="/">
                            <img src="/img/interacsms.png" alt="Interactive Sms system also know as InteracSms, is built to meeting client's demand to engaging its users via their mobile using their local Sms system." width="112" height="auto">
                        </a>
                        <a data-target="slide-out" style="float:right; color: grey;" role="button" class="sidenav-trigger navbar-burger is-hidden-widescreen is-hidden-tablet is-hidden-desktop is-hidden-fullhd" aria-label="menu" aria-expanded="false">
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                            <span aria-hidden="true"></span>
                        </a>
                    </div>
                </nav>
            </div>
            <ul id="slide-out" class="sidenav is-hidden-desktop">
                <li>
                    <div class="level">
                            <!-- Right side -->
                            <div class="level-left">
                                <p class="level-item">
                                    <a @click.prevent="closeside" data-target="slide-out" style="float:right;float: right;margin-left: 20px;color: #f0ad00;" role="button" id="closeside" class="is-active navbar-burger sidenav-close" aria-label="menu" aria-expanded="false">
                                        <span aria-hidden="true"></span>
                                        <span aria-hidden="true"></span>
                                        <span aria-hidden="true"></span>
                                    </a>
                            </p>
                        </div>
                    </div>
                </li>
                <form style="display:none;" method="POST" :action="logout" id="logout-form">
                    <input type="hidden" name="_token" :value="token">
                </form>
                <aside class="menu">
                    <ul class="menu-list">
                        <li><a class="menu-items-a" @click="view = 'sms'" @click.prevent="change">Compose SMS</a></li>
                        <li><a class="menu-items-a" @click="view = 'schedule'" @click.prevent="change">Scheduled SMS</a></li>
                        <li><a class="menu-items-a" @click="view = 'sent'" @click.prevent="change">Sent SMS</a></li>
                        <li><a class="menu-items-a" @click="view = 'draft'" @click.prevent="change">Drafts</a></li>
                        <li><a class="menu-items-a" @click="view = 'contact'" @click.prevent="change">Contacts</a></li>
                        <li><a class="menu-items-a" @click="view = 'recharge'" @click.prevent="change">Purchase Units</a></li>
                        <li><a class="menu-items-a" @click="view = 'payment'" @click.prevent="change">Payment History</a></li>
                        <!-- <li><a class="menu-items-a" @click="view = 'recharge'" @click.prevent="change">Recharge Account</a></li> -->
                        <li><a class="menu-items-a" @click="view = 'profile'" @click.prevent="change">Manage Profile</a></li>
                        <div class="is-divider" style="display: block;position: relative;border-top: 1px solid rgb(240, 173, 0);margin: 0rem 2rem;text-align: center; color:rgb(240, 173, 0)"></div>
                        <li><a @click.prevent="logoutF">Logout</a></li>
                        <!-- <li><a @click.prevent="change('sms')">Payments</a></li>
                        <li><a @click.prevent="change('sms')">Transfers</a></li>
                        <li><a @click.prevent="change('sms')">Balance</a></li> -->
                    </ul>
                </aside>
            </ul>
        </section>
</template>

<script>
export default {
  data() {
    return {
      view: "sms",
      token: window.token,
    logout: route('logout')
    };
  },
  mounted() {
    document.addEventListener("DOMContentLoaded", function() {
      var elems = document.querySelectorAll(".sidenav");
      var instance = M.Sidenav.init(elems, {
        edge: "right"
      });
      let close = document.getElementById("closeside");
      if (close.classList.contains("is-active")) {
        return;
      } else {
        close.classList.add("is-active");
      }
    });
  },
  methods: {
    change($) {
      var aa = document.querySelectorAll(".menu-items-a");
      aa.forEach(a => {
        a.classList.remove("is-active");
      });
      $.target.classList.add('is-active');
      setTimeout(() => {
        this.$root.$children[0].$children[3]._data.currentView = this.view;
      }, 500);
    },
    logoutF () {
        event.preventDefault();
        document.getElementById('logout-form').submit();
    },
    closeside($) {
      var elem = document.querySelectorAll(".sidenav")[0];
      var elems = document.querySelectorAll(".sidenav-trigger")[0];
      var instance = M.Sidenav.getInstance(elem);
      instance.close();
      let close = document.getElementById("closeside");
      if (close.classList.contains("is-active")) {
        close.classList.remove("is-active");
      } else {
        close.classList.add("is-active");
      }
      elems.classList.toggle("is-active");
      $.target.classList.remove("is-active");
    }
  }
};
</script>
<style scoped>
.menu-list a.is-active {
  background-color: #f1ad02 !important;
  color: #fff !important;
}
.sidenav {
  -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
  box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
    0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
}
.sidenav {
  margin-top: calc(24px - 11px);
}

@media only screen and (min-width: 993px) {
  nav a.sidenav-trigger {
    display: none;
  }
}

/* nav .sidenav-trigger {
  float: left;
  position: relative;
  z-index: 1;
  height: 56px;
  margin: 0 18px;
} */

nav .sidenav-trigger i {
  height: 56px;
  line-height: 56px;
}

@media only screen and (min-width: 601px) {
  nav a.sidenav-trigger,
  nav a.sidenav-trigger i {
    height: 64px;
    line-height: 64px;
  }
}

.sidenav .collapsible,
.sidenav.fixed .collapsible {
  border: none;
  -webkit-box-shadow: none;
  box-shadow: none;
}

.sidenav .collapsible li,
.sidenav.fixed .collapsible li {
  padding: 0;
}

.sidenav .collapsible-header,
.sidenav.fixed .collapsible-header {
  background-color: transparent;
  border: none;
  line-height: inherit;
  height: inherit;
  padding: 0 16px;
}

.sidenav .collapsible-header:hover,
.sidenav.fixed .collapsible-header:hover {
  background-color: rgba(0, 0, 0, 0.05);
}

.sidenav .collapsible-header i,
.sidenav.fixed .collapsible-header i {
  line-height: inherit;
}

.sidenav .collapsible-body,
.sidenav.fixed .collapsible-body {
  border: 0;
  background-color: #fff;
}

.sidenav .collapsible-body li a,
.sidenav.fixed .collapsible-body li a {
  padding: 0 23.5px 0 31px;
}

.sidenav {
  position: fixed;
  width: 300px;
  left: 0;
  top: 0;
  margin: 0;
  -webkit-transform: translateX(-100%);
  transform: translateX(-100%);
  height: 100%;
  height: calc(100% + 60px);
  height: -moz-calc(100%);
  padding-bottom: 60px;
  background-color: #fff;
  z-index: 999;
  overflow-y: auto;
  will-change: transform;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -webkit-transform: translateX(-105%);
  transform: translateX(-105%);
}

.sidenav.right-aligned {
  right: 0;
  -webkit-transform: translateX(105%);
  transform: translateX(105%);
  left: auto;
  -webkit-transform: translateX(100%);
  transform: translateX(100%);
}

.sidenav .collapsible {
  margin: 0;
}

.sidenav li {
  float: none;
  line-height: 48px;
}

.sidenav li.active {
  background-color: rgba(0, 0, 0, 0.05);
}

.sidenav li > a {
  color: rgba(0, 0, 0, 0.87);
  display: block;
  font-size: 14px;
  font-weight: 500;
  height: 48px;
  line-height: 48px;
  padding: 0 32px;
}

.sidenav li > a:hover {
  background-color: rgba(0, 0, 0, 0.05);
}

.sidenav li > a.btn,
.sidenav li > a.btn-large,
.sidenav li > a.btn-small,
.sidenav li > a.btn-large,
.sidenav li > a.btn-flat,
.sidenav li > a.btn-floating {
  margin: 10px 15px;
}

.sidenav li > a.btn,
.sidenav li > a.btn-large,
.sidenav li > a.btn-small,
.sidenav li > a.btn-large,
.sidenav li > a.btn-floating {
  color: #fff;
}

.sidenav li > a.btn-flat {
  color: #343434;
}

.sidenav li > a.btn:hover,
.sidenav li > a.btn-large:hover,
.sidenav li > a.btn-small:hover,
.sidenav li > a.btn-large:hover {
  background-color: #2bbbad;
}

.sidenav li > a.btn-floating:hover {
  background-color: #26a69a;
}

.sidenav li > a > i,
.sidenav li > a > [class^="mdi-"],
.sidenav li > a li > a > [class*="mdi-"],
.sidenav li > a > i.material-icons {
  float: left;
  height: 48px;
  line-height: 48px;
  margin: 0 32px 0 0;
  width: 24px;
  color: rgba(0, 0, 0, 0.54);
}

.sidenav .divider {
  margin: 8px 0 0 0;
}

.sidenav .subheader {
  cursor: initial;
  pointer-events: none;
  color: rgba(0, 0, 0, 0.54);
  font-size: 14px;
  font-weight: 500;
  line-height: 48px;
}

.sidenav .subheader:hover {
  background-color: transparent;
}

.sidenav .user-view {
  position: relative;
  padding: 32px 32px 0;
  margin-bottom: 8px;
}

.sidenav .user-view > a {
  height: auto;
  padding: 0;
}

.sidenav .user-view > a:hover {
  background-color: transparent;
}

.sidenav .user-view .background {
  overflow: hidden;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: -1;
}

.sidenav .user-view .circle,
.sidenav .user-view .name,
.sidenav .user-view .email {
  display: block;
}

.sidenav .user-view .circle {
  height: 64px;
  width: 64px;
}

.sidenav .user-view .name,
.sidenav .user-view .email {
  font-size: 14px;
  line-height: 24px;
}

.sidenav .user-view .name {
  margin-top: 16px;
  font-weight: 500;
}

.sidenav .user-view .email {
  padding-bottom: 16px;
  font-weight: 400;
}

.sidenav.sidenav-fixed {
  left: 0;
  -webkit-transform: translateX(0);
  transform: translateX(0);
  position: fixed;
}

.sidenav.sidenav-fixed.right-aligned {
  right: 0;
  left: auto;
}

@media only screen and (max-width: 992px) {
  .sidenav.sidenav-fixed {
    -webkit-transform: translateX(-105%);
    transform: translateX(-105%);
  }
  .sidenav.sidenav-fixed.right-aligned {
    -webkit-transform: translateX(105%);
    transform: translateX(105%);
  }
  .sidenav > a {
    padding: 0 16px;
  }
  .sidenav .user-view {
    padding: 16px 16px 0;
  }
}

.sidenav .collapsible-body > ul:not(.collapsible) > li.active,
.sidenav.sidenav-fixed .collapsible-body > ul:not(.collapsible) > li.active {
  background-color: #ee6e73;
}

.sidenav .collapsible-body > ul:not(.collapsible) > li.active a,
.sidenav.sidenav-fixed .collapsible-body > ul:not(.collapsible) > li.active a {
  color: #fff;
}

.sidenav .collapsible-body {
  padding: 0;
}

.sidenav-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  opacity: 0;
  height: 120vh;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 997;
  display: none;
}
</style>


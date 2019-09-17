<template>
    <div class="box">
        <table class="table is-responsive is-fullwidth">
            <thead>
                <tr>
                    <th>Delete</th>
                    <th><abbr title="Serial Number">S/N</abbr></th>
                    <th>Sender</th>
                    <th>Message</th>
                    <th>Recipients</th>
                    <th>Pages</th>
                    <th>Volume</th>
                    <th>Time Sent</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Delete</th>
                    <th><abbr title="Serial Number">S/N</abbr></th>
                    <th>Sender</th>
                    <th>Message</th>
                    <th>Recipients</th>
                    <th>Pages</th>
                    <th>Volume</th>
                    <th>Time Sent</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>
                        <div class="field">
                            <div class="control">
                                <input type="checkbox">
                            </div>
                        </div>
                    </td>
                    <th>1</th>
                    <td><a href="#!" title="Sample">Sample</a></td>
                    <td id="message">Dear Senator: You've spoken and I give glory to God. It's well with UNN. Best wishes. Prof Obinna Onwujekwe</td>
                    <td>23</td>
                    <td>12</td>
                    <td>3</td>
                    <td>68</td>
                    <td>
                        <p class="buttons">
                            <a class="button has-background-danger">
                                <span class="icon is-small">
                                    <i class="fas fa-redo-alt has-text-white"></i>
                                </span>
                            </a>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data () {
        return {
            sms: {
                format: "standard",
                flash: "no"
            }
        }
    },
    methods: {
        format (e) {
            this.sms.format = e.target.value;
        },
        flash (e) {
            this.sms.flash = e.target.value;
        }
    },
    mounted () {

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
.select option {
    color: grey !important;
}
.is-divider {
    border-top: 1px solid #dbdbdb !important;
}
input.sender {
    width: auto !important;
}
label {
    text-transform: uppercase !important;
    color: grey;
}
#message {
    text-overflow: ellipsis;
}
</style>

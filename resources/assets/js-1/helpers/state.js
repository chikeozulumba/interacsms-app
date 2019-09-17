let Vue = require('vue');
let Vuex = require('vuex');
Vue.use(Vuex);
module.exports = {
    store: new Vuex.Store({
        strict: true,
        state: {
            message: {},
            sent: [],
            singleSentMgs: {}
        },
        mutations: {
            SAVE_MESSAGE: function(state, message) {
                state.message = message;
            },
            SCHEDULE: function(state, schedule) {
                state.message.metadata.schedule = schedule;
            },
            SAVE_SENT_MSGS: function(state, payload) {
                state.sent = payload;
            },
            UPDATE_SENT_MSGS: function(state, payload) {
                state.sent = payload;
            },
            SINGLE_SENT_MSGS: function(state, payload) {
                state.singleSentMgs = payload;
            }
        },
        getters: {
            getMessage: state => state.message,
            getSentMsgs: state => state.sent,
            getSingleSentMsgs: state => state.singleSentMgs
        },
        actions: {
            addMessage: function(context, payload) {
                context.commit("SAVE_MESSAGE", payload);
            },
            updateSentMsgs: function(context, payload) {
                context.commit("UPDATE_SENT_MSGS", payload);
            },
            addSentMsgs: function(context, payload) {
                context.commit("SAVE_SENT_MSGS", payload);
            },
            schedule: function(context, payload) {
                context.commit("SCHEDULE", payload);
            },
            singleMsgs: function(context, payload) {
                context.commit("SINGLE_SENT_MSGS", payload);
            },
            updateSingleMsgs: function(context, payload) {
                context.commit("SINGLE_SENT_MSGS", payload);
            },
        }
    })
}
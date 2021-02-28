require('./bootstrap');

import Vue from  'vue'
import Vuex from 'vuex'
import store from './store'
import VueSocketIO from 'vue-socket.io'

Vue.use(Vuex);
Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://localhost:3000'
}))

Vue.component('my-team', require('./pages/MyTeam.vue').default);

const app = new Vue({
    el: '#app',
    store
});
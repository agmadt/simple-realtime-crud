require('./bootstrap');

import Vue from  'vue'
import Vuex from 'vuex'
import store from './store'

Vue.component('my-team', require('./pages/MyTeam.vue').default);

Vue.use(Vuex)

const app = new Vue({
    el: '#app',
    store
});
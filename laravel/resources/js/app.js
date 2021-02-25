require('./bootstrap');

import Vue from  'vue'

Vue.component('my-team', require('./pages/MyTeam.vue').default);

const app = new Vue({
    el: '#app'
});
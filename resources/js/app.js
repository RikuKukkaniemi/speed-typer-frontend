require('./bootstrap');

window.Vue = require('vue').default;

Vue.component('game-layout-component', require('./components/GameLayoutComponent.vue').default);

const app = new Vue({
    el: '#app',
});

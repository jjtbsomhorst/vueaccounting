import Vue from 'vue'
import UUID from 'vue-uuid';
import App from './App.vue'


Vue.config.productionTip = false
Vue.use(UUID);

new Vue({
   render: h => h(App),
}).$mount('#app')

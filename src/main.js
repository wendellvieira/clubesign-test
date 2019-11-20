import Vue from 'vue'
import router from './router'
import store from './vuex/index'
store.dispatch("application/init")

import 'bootstrap/dist/css/bootstrap.min.css'
import 'toastr/build/toastr.min.css'
import 'font-awesome/css/font-awesome.min.css'
import './assets/font/stylesheet.css'
import './assets/fa5/css/fontawesome-all.min.css'
import './assets/css/theme-bootstrap.css'
import './assets/css/animate.css'
import './assets/css/style.css'

import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask)

Vue.config.productionTip = false
window.$=window.jQuery=require('jquery')
require('bootstrap/dist/js/bootstrap.min.js')

import WBS from 'js/http'
Vue.prototype.$getUrl = file => {
  return `${WBS.url_base}/uploaded_files/public/${file}`
}

new Vue({
  el: '#app',
  router, store,
  template: '<router-view/>'
})

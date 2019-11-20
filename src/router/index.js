import Vue from 'vue'
import Router from 'vue-router'

import BeforeEach from './beforeEach.js'
Vue.use(Router)

import ClubeV2 from '../layout/ClubeV2/index.vue'
  import AppProfileSettings from '../modules/profile-settings/index.vue'
import AppLogin from '../modules/login/index.vue'

let router = new Router({
  routes: [
    { path: '/master', component: ClubeV2, children: [
      
      { path: 'profile-settings', component: AppProfileSettings, meta: { requireAuth: true } },


    ]},
    { path: '/login', component: AppLogin },
  ]
})

router.beforeEach(BeforeEach)
export default router


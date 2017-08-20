import Vue from 'vue'
import Router from 'vue-router'

import Hello from '@/components/Hello'
import DbSetup from '@/components/DbSetup'
import Report from '@/components/Report.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: Hello
    },
    {
      path: '/report',
      component: Report
    },
    {
      path: '/db',
      component: DbSetup
    }
  ]
})

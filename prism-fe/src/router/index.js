import Vue from 'vue'
import Router from 'vue-router'
import Dashboard from '@/components/Dashboard'
import DbSetup from '@/components/DbSetup'
import Report from '@/components/Report.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: Dashboard
    },
    {
      path: '/dashboard',
      component: Dashboard
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

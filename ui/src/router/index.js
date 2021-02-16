import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'

import money from 'v-money'

// Dependency Injection Service
import { Helper } from './../services/helper'
Vue.prototype.$Helper = Helper

import { ModuleConfig } from './../services/ModuleConfig'
Vue.prototype.$ModuleConfig = ModuleConfig

import { Config } from '../config'
Vue.prototype.$Config = Config

import Api from './../services/Api'
Vue.prototype.$Api = new Api()

Vue.use(VueRouter)
Vue.use(money, { precision: 4 })

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default function (/* { store, ssrContext } */) {
  const Router = new VueRouter({
    scrollBehavior: () => ({ x: 0, y: 0 }),
    routes,

    // Leave these as they are and change in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    mode: process.env.VUE_ROUTER_MODE,
    base: process.env.VUE_ROUTER_BASE
  })

  return Router
}

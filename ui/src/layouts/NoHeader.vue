<template>
  <q-layout view="lHh lpR lFr" transition-show="jump-down" transition-hide="jump-up">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer />

    <q-page-container>
      <transition  appear
        enter-active-class="animated fadeIn "
        leave-active-class="animated fadeOut"
      >
      <router-view class="layout-view"></router-view>
      </transition>

    </q-page-container>

  </q-layout>
</template>
<script>
// import { Config } from '../config'

export default {
  name: 'UserLayout',
  data () {
    return {
    }
  },
  mounted () {
    // check session login
    // var credentials = Config.credentials()
    // if (!credentials) this.$router.push({ name: 'login' })
  },

  methods: {
  }
}
</script>
<style>
</style>

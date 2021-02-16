<template>
  <q-layout view="lHh Lpr lFf">

    <q-page-container>
      <transition  appear mode="out-in"
        enter-active-class="animated-page fadeIn "
        leave-active-class="animated-page fadeOut"
      >
         <router-view class="layout-view"></router-view>
      </transition>

    </q-page-container>
  </q-layout>
</template>

<script>
import { Config } from '../config'

export default {
  name: 'UserPageLayout',
  data () {
    return {
      thumbStyle: Config.scrollOptions('thumb'),
      barStyle: Config.scrollOptions('bar'),
      drawer: false,
      fabPos: [18, 18],
      draggingFab: false
    }
  },
  mounted () {
    // check session login
    // var credentials = Config.credentials()
    // if (!credentials) this.$router.push({ name: 'login' })
  },

  methods: {
    drawerCtrl () {
      this.drawer = !this.drawer
      console.log('drawerCtrl: ', this.drawer)
    }
  }
}
</script>

<template>
  <!-- Lhh Lpr ffr -->
  <q-layout view="Lhh Lpr ffr">

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
export default {
  data () {
    return {
      left: false,
      right: false
    }
  }
}
</script>

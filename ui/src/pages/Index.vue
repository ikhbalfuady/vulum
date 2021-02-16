<template >

  <div class="root">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

    <div class="row">
      <div class="col-12 text-center pt-5 img-responsive" >
        <q-img src="assets/icons/logo-md.png" style="max-width:520px" > </q-img>
      </div>

      <div class="col-12 text-center pt-5">
        <span class="text-h4 bold">Welcome to Vulum Framwork.</span> <br>
        <span class="text-grey-8">This default page of Vulum App</span>
      </div>
    </div>

  </div>
</template>

<script>

export default {
  name: 'Index',
  data () {
    return {
      Meta: {
        name: 'Dashboard',
        icon: 'stop_circle',
        module: 'users',
        topBarMenu: []
      },
      mining: {
        percentage: 69
      }
    }
  },

  created () {

  },

  mounted () {

  },

  methods: {

  }
}
</script>

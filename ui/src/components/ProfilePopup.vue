<template>
  <div>
    <div class="row no-wrap q-pa-md">
      <div class="column items-center">
        <q-avatar size="72px">
        <img src="assets/avatar.jpg">
        </q-avatar>

        <div class="text-subtitle1 q-mt-md q-mb-xs">
          {{dataModel.name}} <br>
          <small class="text-primary bold">{{dataModel.username}}</small>
        </div>

        <q-btn @click="logout" color="primary" label="Logout" push size="sm" v-close-popup />
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: 'profilePopup',
  props: ['data'],
  data () {
    return {
      dataModel: null
    }
  },

  created () {
    var credentials = this.$Config.credentials()
    // console.log('credentials Profile', credentials)
    this.dataModel = credentials
  },

  mounted () {
    if (this.data !== undefined) this.dataModel = this.data
  },

  methods: {
    onRefresh () {
      console.log('activate')
    },

    logout () {
      this.$Config.credentials('destroy')
      this.$router.push({
        name: 'login'
      })
    }
  }

}

</script>

<template>
  <div>
    <q-avatar class="cursor ml-2" size="28px">
      <img src="assets/avatar.jpg">
      <q-menu fit
      transition-show="jump-left"
      transition-hide="jump-right"
      >
        <div class="no-wrap q-pa-md">

          <div class="text-center">
            <q-avatar size="72px">
              <img src="assets/avatar.jpg">
            </q-avatar>

            <div class="text-subtitle1 q-mt-md q-mb-xs">
              <q-skeleton v-if="data.role === undefined" class="animated fadeIn" type="text" />
              <q-badge color="yellow-10" :label="dataModel.role ? dataModel.role.name : '-'" class="capital animated fadeIn" />
            </div>

            <div class="q-mb-md text-center">
              <q-skeleton v-if="dataModel.name === ''" class="animated fadeIn" type="text" />
              <span class="animated fadeIn text-h6 " v-if="dataModel.name !== ''" >{{dataModel.name}}</span>
              <br>
              <q-skeleton v-if="dataModel.email === ''" class="animated fadeIn" type="text" />
              <span class="animated fadeIn  " v-if="dataModel.email !== ''" >{{dataModel.email}}</span>
            </div>
          </div>

          <div class="">
            <q-toggle size="sm" @input="changeMiniMode()" v-model="miniModeMenu" left-label label="Mini Mode Menu" class="mb-1"/>
            <q-btn @click="openLink('profile')" flat color="primary" size="sm" class="bg-grey-2 capital mb-1 full-width" icon="person" label="Profile" /> <br>
            <q-btn @click="openLink('change-password')" flat color="primary" size="sm" class="bg-grey-2 capital mb-1 full-width" icon="gpp_good" label="Change Password" /> <br>
            <q-btn unelevated color="primary" size="sm" class="capital mb-1 full-width"  icon="lock" label="Logout" v-close-popup @click="logout"/>
          </div>

        </div>
      </q-menu>
    </q-avatar>

  </div>
</template>

<script>
export default {
  name: 'profilePopup',
  props: ['data'],
  data () {
    return {
      dataModel: {
        name: 'John Doe',
        username: 'sijohn',
        email: 'johndoe@mail.com',
        role: null
      },
      miniModeMenu: false,
      miniState: false
    }
  },

  created () {
    var credentials = this.$Config.credentials()
    // console.log('credentials Profile', credentials)
    this.dataModel = credentials
  },

  updated () {
    console.log('profilePopup:data', this.data)
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
    },

    changeMiniMode () {
      var current = this.miniModeMenu
      // console.log('changeMiniMode', current)
      this.$Helper.saveLdb('miniModeMenu', current)
      if (!current) this.miniState = false
    },

    openLink (val) {
      var path = 'change-password-users'
      var data = { id: 1 }
      if (val === 'profile') path = 'update-profile-users'
      this.$router.push({ name: path, params: data })
    }
  }

}

</script>

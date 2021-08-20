<template>
  <div>
    <q-avatar class="cursor ml-2" size="28px">
      <img src="assets/avatar.jpg">
      <q-menu fit @before-show="onRefresh"
      transition-show="jump-left"
      transition-hide="jump-right"
      >
        <div class="no-wrap bg-grad ">

          <div class="text-center q-pa-sm">
            <q-avatar size="72px">
              <img src="assets/avatar.jpg">
            </q-avatar>

            <div class="text-subtitle1 q-mt-md q-mb-xs text-light">
              <q-badge color="yellow-10" :label="(user.role.name) ? user.role.name : user.role " class="capital animated fadeIn" />
            </div>

            <div class="q-mb-md text-center text-light">
              <span class="animated fadeIn text-h6 " >{{user.name}}</span>
              <br>
              <span class="animated fadeIn" >{{user.email}}</span>
            </div>
          </div>

          <div class="q-pa-md bg-light">
            <q-toggle size="sm" @input="changeMiniMode()" v-model="miniModeMenu" left-label label="Mini Mode Menu" class="mb-1"/>
            <br>
            <q-toggle size="sm" @input="switchAction()" v-model="actionModal" left-label label="Action on Modal" class="mb-1"/>
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
      miniModeMenu: false,
      actionModal: false
    }
  },

  created () {
    var credentials = this.$Config.credentials()
    // console.log('credentials Profile', credentials)
    this.dataModel = credentials
  },

  mounted () {
    //
  },

  computed: {
    user: {
      set: function (val) {
        this.$store.dispatch('GlobalState/userInfoAction', val)
      },
      get: function () {
        var res = this.$store.state.GlobalState.userInfo
        console.log('get', res)
        return res
      }
    }
    // actionModal: {
    //   set: function (val) {
    //     this.actionModal = val
    //   },
    //   get: function () {
    //     var res = this.$Handler.actionMode()
    //     if (res === 'MODAL') return true
    //     else return false
    //   }
    // }
  },

  methods: {
    onRefresh () {
      var cre = this.$Config.credentials()
      this.user = (cre.user) ? cre.user : cre

      this.miniModeMenu = this.$Handler.drawerMini()
      if (this.$Handler.actionMode() === 'MODAL') this.actionModal = true
      else this.actionModal = false
    },

    logout () {
      this.$Config.credentials('destroy')
      this.$router.push({
        name: 'login'
      })
    },

    changeMiniMode () {
      this.$Handler.drawerMini(this.miniModeMenu)
      this.$store.dispatch('GlobalState/drawerMiniAction', this.miniModeMenu)
    },

    switchAction () {
      var res = 'PAGE'
      if (this.actionModal === true) res = 'MODAL'
      this.$Handler.actionMode(res)
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

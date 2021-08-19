<template>
  <div>
    <q-header reveal class="bg-secondary text-white">
      <q-toolbar>
        <q-btn flat @click="drawerCtrl" round dense icon="menu" />
        <div class="cursor-pointer bold capital" @click="drawerCtrl" >{{ title }}</div>

        <!-- Get Top Menu List -->
        <div v-for="(row, index) in menu" v-bind:key="index" class="top-menu-list">
          <span v-html="row.name" @click="MENU_callEvent(menu, index)" :class="(row.class !== undefined) ? row.class : ''"></span>
          <q-menu v-if="MENU_checkSub(row)">
            <TopMenuList :data="row" />
          </q-menu>
        </div>

        <q-space />

        <q-btn dense flat round icon="notifications" @click="notificationsCtrl" >
          <div class="animated fadeIn" v-if="notifList.length !== 0">
            <q-badge style="animation-duration: 1.5s" class="animated tada infinite" v-if="notifList.length !== 0" color="red" floating >{{notifList.length}}</q-badge>
          </div>
        </q-btn>

        <profile-popup/>

      </q-toolbar>
    </q-header>
  </div>
</template>

<script>
import TopMenuList from './TopMenuList'

export default {
  name: 'TopMenu',
  props: ['data'],
  components: {
    TopMenuList
  },
  data () {
    return {
      topBar: {
        info: {
          name: 'Vulum',
          icon: 'offline_bolt',
          iconClass: 'cursor-pointer'
        },
        menu: this.topMenu
      },
      notifList: [],
      userInfo: {
        name: 'John Doe',
        username: 'sijohn',
        email: 'johndoe@mail.com',
        role: 'system'
      }
    }
  },

  created () {
    //
    this.menuList = this.$Helper.getFromLdb('menu', [])
  },

  mounted () {
    //
  },

  computed: {
    drawer: {
      get () {
        return this.$store.state.GlobalState.drawer
      },
      set (val) {
        this.$store.dispatch('GlobalState/drawerAction', val)
      }
    },

    notifications: {
      get () {
        return this.$store.state.GlobalState.drawer
      },
      set (val) {
        this.$store.dispatch('GlobalState/notificationsAction', val)
      }
    },

    // data reference in meta.js on module
    title () {
      var title = 'Vulum Framework'
      if (this.data) {
        title = (this.data.name) ? this.data.name : title
      }
      return title
    },

    menu () {
      var res = []
      if (this.data) {
        res = (this.data.topBarMenu) ? this.data.topBarMenu : res
      }
      return res
    }
  },

  methods: {

    drawerCtrl () {
      this.drawer = !this.drawer
      this.$store.dispatch('GlobalState/drawerAction', this.drawer)
    },

    notificationsCtrl () {
      this.notifications = !this.notifications
      this.$store.dispatch('GlobalState/notificationsAction', this.notifications)
    },

    MENU_callEvent (params, index) {
      //  console.log('MENU_callEvent', params[index])
      if (params[index].event !== null && params[index].event !== undefined) return params[index].event()
    },

    MENU_checkSub (params) {
      if (params.sub !== undefined) {
        if (params.sub.length !== 0) return true
        else return false
      } else return false
    }
  }
}
</script>

<style lang="scss">
  .top-menu-list {
    margin-left: 20px !important;
    cursor: pointer;
    font-size: 13px !important;
  }
</style>

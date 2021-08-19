<template>
  <div>
    <q-drawer show-if-above v-model="drawer" class="side-menu"
      :width="260"
      :breakpoint="500"
      :mini="miniState"
      @mouseover="miniStateMenu(false)"
      @mouseout="miniStateMenu(true)"
      :behavior="menuBehavior"
      >
      <q-scroll-area style="height: calc(100% - 120px); margin-top: 117px;">
        <q-list padding >
          <SideMenuList :data="menuList" :mini-state="miniState"/>
        </q-list>
      </q-scroll-area>

      <q-img class="absolute-top head-menu" style="height: 125px">
        <!-- Full Mode -->
        <div class="bg-transparent text-center full-width" v-if="!miniState">
          <div class="logo animated">
            <img src="assets/icons/logo-xs-light.png" /> <br>
            <span class="text-grey-1">v.{{$Config.version()}}</span>
          </div>
          <q-chip size="sm">
            <q-avatar icon="dns" color="primary" text-color="white" />
            {{$Config.getApiRoot()}}
          </q-chip>
        </div>
        <!-- Mini Mode -->
        <div class="bg-transparent full-width" v-if="miniState">
          <div class="animated zoomIn">
            <img src="assets/icons/icon.png" width="32px"/> <br>
          </div>
        </div>

      </q-img>

    </q-drawer>
    <q-resize-observer @resize="onResize" />
  </div>
</template>

<script>
import SideMenuList from './SideMenuList'

export default {
  name: 'SideMenu',
  components: {
    SideMenuList
  },
  props: ['data'],
  data () {
    return {
      menuList: [],
      // handle automation menu behaviour
      menuBehavior: 'desktop',
      currentBreakPoint: '',
      pauseOnResize: false
    }
  },

  created () {
    //
    this.menuList = this.$Helper.getFromLdb('menu', [])
  },

  mounted () {
    //
    this.miniState = this.$Handler.drawerMini()
  },

  computed: {
    drawer: {
      set: function (val) {
        this.$store.dispatch('GlobalState/drawerAction', val)
      },
      get: function () {
        return this.$store.state.GlobalState.drawer
      }
    },
    miniState: {
      set: function (val) {
        this.$store.dispatch('GlobalState/drawerMiniAction', val)
      },
      get: function () {
        return this.$store.state.GlobalState.drawerMini
      }
    }
  },

  methods: {

    onResize (size) {
      this.currentBreakPoint = this.$q.screen.name

      if (!this.pauseOnResize) {
        if (this.currentBreakPoint === 'sm' || this.currentBreakPoint === 'xs') {
          this.menuBehavior = 'mobile'
          // this.drawer = false
          // this.drawer = this.$Helper.getFromLdb('drawerOpen', false)
        } else {
          this.menuBehavior = 'desktop'
          // this.drawer = true
        }
      }
    },

    getMenu () {
      this.$Helper.loading()
      this.API.get('me/menus', (status, data, message, response, full) => {
        this.$Helper.loading(false)
        if (status === 200) {
          this.menuList = data
          this.$Helper.saveLdb('menu', data)
          // console.log('menu', this.menuList)
          // this.getUserInfo()
        }
      })
    },

    miniStateMenu (val) {
      var miniModeMenu = this.$Handler.drawerMini()
      if (miniModeMenu) this.miniState = val
    }

  }
}
</script>

<style lang="scss">
.logo span {
  position: relative;
  top: -5px;
  left: 5px;
}

.side-menu aside {
  box-shadow: 0 0 28px 0 rgba(82,63,105, 0.1);
}

.side-menu-item div div .q-item__section--avatar {
  min-width: 20px !important;
}

.side-menu-item div div .q-item__section--main {
  font-size: 0.9em;
}

.side-menu-item div div .q-item__section--avatar .q-icon {
  min-width: 15px !important;
  font-size:1.2em !important;
}

.side-menu-item div div .q-focusable .q-expansion-item__toggle-icon {
  font-size:1.2em !important;
}

.ic-fiber_manual_record div div .q-item__section--avatar .q-icon {
  font-size:0.7em !important;
  position: relative;
  left: 1px;
}

/* coloring
dark : #1e1e1e
light : #fff
soft : #fff
*/

.side-menu aside { /* bg menu */
  background: $dark;
}

.side-menu aside div .head-menu { /* bg head menu */
  background: $dark;
}

.side-menu-item div div .q-item__section--main { /* text */
  color: #fff;
}
.side-menu-item div div .q-item__section--avatar .q-icon { /* icon */
  color: #fff;
}

.menu-overline { /* overline */
  color: #ff9900 !important;
}

</style>

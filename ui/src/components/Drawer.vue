<template>
  <div>
    <q-header elevated class="bg-grad" >
        <q-toolbar>
          <q-btn flat @click="drawerCtrl" round dense icon="menu" />
        <div class="cursor-pointer bold capital top-bar-title" @click="drawerCtrl" >{{ topBar.info.name }}</div>

        <!-- Get Top Menu List -->
        <div v-for="(row, index) in topBar.menu" v-bind:key="index" class="menu-separate  top-bar-title">
          <span v-html="row.name" @click="MENU_callEvent(topBar.menu, index)" :class="(row.class !== undefined) ? row.class : ''"></span>
          <!-- Menu LV 1 -->
          <q-menu v-if="MENU_checkSub(row)">
            <q-list dense style="min-width: 100px">
              <!-- Menu List LV 1 -->
              <q-item v-for="(rowSub, indexSub) in row.sub" v-bind:key="indexSub" clickable>
                <q-item-section>
                  <span v-html="rowSub.name" @click="MENU_callEvent(row.sub, indexSub)"></span>
                </q-item-section>
                <q-item-section v-if="MENU_checkSub(rowSub)" side><q-icon name="keyboard_arrow_right" /></q-item-section>
                <!-- Menu LV 2 -->
                <q-menu anchor="top right" self="top left"  v-if="MENU_checkSub(rowSub)" >
                  <q-list>
                     <!-- Menu List LV 2 -->
                    <q-item v-for="(rowSubDeep, indexSubDeep) in rowSub.sub" v-bind:key="indexSubDeep" clickable>
                      <q-item-section>
                        <span v-html="rowSubDeep.name" @click="MENU_callEvent(rowSub.sub, indexSubDeep)"></span>
                      </q-item-section>
                    </q-item>
                  </q-list>
                </q-menu>
              </q-item>

            </q-list>
          </q-menu>
        </div>

        <q-space />

        </q-toolbar>
      </q-header>

      <q-drawer @before-show="activate" class="drawer-primary" :behavior="menuBehavior"
        v-model="drawer"
        show-if-above
        :width="241"
        :breakpoint="500"
      >
        <q-scroll-area class="drawer-area" style="height: calc(100% - 150px); margin-top: 140px;">
          <q-list v-for="(menuItem, index) in menuList" :key="index">

            <q-expansion-item class="animated fadeIn" v-if="!is_parent(menuItem)" @click="actionMenu(menuItem)" expand-separator :icon="menuItem.icon" :label="menuItem.name" header-class="menuList text-primary bold" >

              <q-item v-for="(menuSubItem, index) in menuItem.sub" :key="index+'-'+menuSubItem.icon"  :class="isCurrentMenu(menuSubItem, 'sub')"
                clickable @click="actionMenu(menuSubItem)" v-ripple>
                <q-item-section avatar> <q-icon  style="font-size: 1.3em;"  :name="menuSubItem.icon" color="grey-9" /> </q-item-section>
                <q-item-section class="text-grey-9 "> {{ menuSubItem.name }} </q-item-section>
              </q-item>

            </q-expansion-item>

            <q-item class="animated fadeIn" v-if="is_parent(menuItem)" clickable @click="actionMenu(menuItem)" v-ripple>
              <q-item-section avatar> <q-icon style="font-size: 1.5em;"  :name="menuItem.icon" color="primary" /> </q-item-section>
              <q-item-section class="text-primary bold"> {{ menuItem.name }} </q-item-section>
            </q-item>

          </q-list>
        </q-scroll-area>

        <q-img class="absolute-top bg-grad" style="height: 140px; !important;">
          <q-list class="bg-transparent" style="width: 100%;padding: 5px 0px 0px 1px;">
            <q-item >
              <div class="text-center" style="width: 100%;">
                <img src="assets/icons/logo-md-light.png" width="130">
              </div>
            </q-item>
            <q-item >
              <q-item-section top avatar>
                <q-avatar  style="border:2px solid #fff" >
                  <q-icon name="person_pin" style="padding: 1px 4px 4px 0px;"/>
                </q-avatar>
              </q-item-section>

              <q-item-section>
                <q-item-label>
                  <q-skeleton v-if="userInfo.name === ''" class="animated fadeIn" type="text" />
                  <span class="animated fadeIn" v-if="userInfo.name !== ''" >{{userInfo.name}}</span>
                </q-item-label>
                <q-item-label caption class="text-yellow">
                  <q-skeleton v-if="userInfo.email === ''" class="animated fadeIn" type="text" />
                  <span class="animated fadeIn" v-if="userInfo.email !== ''" >{{userInfo.email}}</span>
                </q-item-label>
                <q-item-label caption class="text-yellow">
                  <q-skeleton v-if="userInfo.role === ''" class="animated fadeIn" type="text" />
                  <q-badge color="yellow-10" :label="userInfo.role" class="capital animated fadeIn" />
                </q-item-label>
              </q-item-section>
            </q-item>

          </q-list>
        </q-img>
      </q-drawer>
      <q-resize-observer @resize="onResize" />

  </div>

</template>

<script>

export default {
  name: 'Drawer',
  props: ['topBarMenu', 'topBarInfo', 'isOpenDrawer', 'display', 'reload'],
  data () {
    return {
      moduleName: 'Drawer',
      appName: this.$Config.appName(),
      API: this.$Api,
      menuList: [],
      showHeader: true,
      drawer: false,
      menuBehavior: 'desktop',
      user: {
        CompanyName: '',
        name: '',
        email: ''
      },
      windowActionProp: {
        minimize: true,
        maximize: true,
        close: true
      },
      topBar: {
        info: {
          name: 'Vulum',
          icon: 'offline_bolt',
          iconClass: 'cursor-pointer'
        },
        menu: this.topMenu
      },
      serverIp: '',
      userInfo: {
        name: 'John Doe',
        email: 'johndoe@mail.com',
        role: 'system'
      },
      platForm: '',
      currentBreakPoint: '',
      pauseOnResize: false
    }
  },

  created () {
    this.$q.addressbarColor.set()
    var screenSize = this.$q.screen.name
    console.log('screenSize', screenSize)
    if (screenSize === 'sm' || screenSize === 'xs') this.onResize(screenSize)
  },

  mounted () {
    var credentials = this.$Config.credentials()
    if (credentials !== false) {
      this.drawerCtrl()
      this.drawer = false
      this.user = credentials
      this.serverIp = this.$Config.getApiRoot()
      this.reloadTopBar()
    }
  },

  updated () {
    this.reloadTopBar() // toggle menu
    this.platForm = this.$q.platform.is
  },

  methods: {

    onResize (size) {
      this.currentBreakPoint = this.$q.screen.name

      if (!this.pauseOnResize) {
        if (this.currentBreakPoint === 'sm' || this.currentBreakPoint === 'xs') {
          this.menuBehavior = 'mobile'
          this.drawer = false
        } else {
          this.menuBehavior = 'desktop'
          this.drawer = true
        }
      }
    },

    reloadTopBar () {
      // console.log('reloadTopBar', this.reload)
      if (this.isOpenDrawer !== undefined) this.drawer = this.isOpenDrawer // toggle menu
      if (this.topBarInfo !== undefined) this.topBar.info = this.topBarInfo // init menu information
      if (this.topBarMenu !== undefined) this.topBar.menu = this.topBarMenu // init top menu list
      if (this.display !== undefined) this.showHeader = this.display // init top menu list
    },

    activate () {
      this.getMenu()
    },

    getUserInfo () {
      this.$Helper.loading()
      this.API.get('me', (status, data, message, response, full) => {
        this.$Helper.loading(false)
        if (status === 200) {
          setTimeout(() => {
            this.$Config.credentials({ user: data })
            this.userInfo = data
            //
          }, 100)
        }
      })
    },

    getMenu () {
      this.$Helper.loading()
      this.API.get('me/menu', (status, data, message, response, full) => {
        this.$Helper.loading(false)
        if (status === 200) {
          this.menuList = data
          this.getUserInfo()
        }
      })
    },

    isCurrentMenu (props, type) {
      // console.log('props', props)
      var menu = props.name.split('-')
      menu = menu[1]

      var current = ''
      var splited = name.split('_')

      var extend = ''
      var explodeExt = this.$route.path.split('/')
      if (explodeExt[1] === 'meta-list') extend = '/' + explodeExt[1]

      if (splited !== 1) current = extend + '/' + this.$Helper.replace('_', '/', menu)
      console.log('current', current)

      // console.log('splited:' + name, splited.length)
      // console.warn('isCurrentMenu', this.$route.path, current)

      if (props.page !== undefined) {
        if (type === 'sub') {
          // if (this.$route.path === current) return 'bg-grey-5 text-white'
          // else return 'bg-grey-2 submenu'
        } else {
          // if (this.$route.path === current) return 'bg-primary text-white'
          // else return 'bg-white'
        }
      }
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
    },

    drawerCtrl () {
      this.getMenu()
      this.pauseOnResize = true // agar tidak looping buka menu nya
      this.drawer = !this.drawer
      setTimeout(() => {
        this.pauseOnResize = false // agar tidak looping buka menu nya
      }, 300)
      // console.log('drawerCtrl: ', this.drawer)
    },

    windowAction (type) {
      // console.log(type)
      if (type === 'minimize') this.$Helper.setFullscreen(false)
      if (type === 'fullscreen') this.$Helper.setFullscreen()
    },

    actionMenu (props) {
      console.log(props)
      var p = props.page

      if (props.sub === undefined || props.sub.length === 0) { // jika tidak ada sub, jadikan link
        if (p === 'logout') this.logout()
        else if (p === 'ApiRoot') this.ApiRoot()
        else if (p === 'logout') this.logout()
        else {
          if (props.params !== undefined) this.$router.push({ name: p, params: props.params })
          else this.$router.push({ name: p })
        }
      }
    },

    is_parent (props) {
      if (props.sub === undefined) return true
      else if (props.sub.length === 0) return true
      else return false
    },

    logout () {
      this.$Config.credentials('destroy')
      this.$router.push({ name: 'login' })
    },

    ApiRoot () {
      this.$Config.setApiRoot()
    }
  }
}
</script>

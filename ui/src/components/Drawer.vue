<template>
  <div>
    <q-header reveal elevated class="bg-grad" >
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

        <q-btn dense flat round icon="notifications" @click="showNotif" >
          <div class="animated fadeIn" v-if="notifList.length !== 0">
            <q-badge style="animation-duration: 1.5s" class="animated tada infinite" v-if="notifList.length !== 0" color="red" floating >{{notifList.length}}</q-badge>
          </div>
        </q-btn>

        <profile-popup :data="userInfo" />
        <!-- <q-avatar class="cursor ml-2" size="28px">
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
                  <q-skeleton v-if="userInfo.role === !undefined" class="animated fadeIn" type="text" />
                  <q-badge color="yellow-10" :label="userInfo.role.name" class="capital animated fadeIn" />
                </div>

                <div class="q-mb-md text-center">
                  <q-skeleton v-if="userInfo.name === ''" class="animated fadeIn" type="text" />
                  <span class="animated fadeIn text-h6 " v-if="userInfo.name !== ''" >{{userInfo.name}}</span>
                  <br>
                  <q-skeleton v-if="userInfo.email === ''" class="animated fadeIn" type="text" />
                  <span class="animated fadeIn  " v-if="userInfo.email !== ''" >{{userInfo.email}}</span>
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
        </q-avatar>-->

      </q-toolbar>
    </q-header>

    <q-drawer @before-show="activate" class="drawer-primary" :behavior="menuBehavior"

      :mini="miniState"
      @mouseover="miniStateMenu(false)"
      @mouseout="miniStateMenu(true)"

      v-model="drawer"
      show-if-above
      :width="241"
      :breakpoint="500"
    >
      <q-scroll-area class="drawer-area" style="height: calc(100% - 80px); margin-top: 75px;">
        <q-list v-for="(menuItem, index) in menuList" :key="index">

          <q-item v-if="menuItem.overline" dense class="animated fadeIn single-line-menu" >
            <q-item-section avatar v-if="miniState" >
              <small class="text-orange capital" style="font-size:11px;" >---------</small>
            </q-item-section>
            <q-item-section v-if="!miniState" class="text-orange capital " style="font-size:11px;">{{ menuItem.overline }} </q-item-section>
          </q-item>

          <q-expansion-item v-if="menuItem.sub.length !== 0"
            dense class="animated fadeIn"
            @click="actionMenu(menuItem)" expand-separator
            :default-opened="checkExpandMenu(menuItem)"
            :icon="menuIcon(menuItem.detail.icon)" :label="menuItem.detail.name"
            :header-class="isCurrentMenu(menuItem.detail, 'menuList text-primary animated fadeIn')" >

            <!-- sub -->
            <q-list v-for="(menuSubItem, index) in menuItem.sub" :key="index">

              <q-item v-if="menuSubItem.overline">
                <q-item-section>
                  <small class="text-orange capital" overline caption>{{menuSubItem.overline}}</small>
                </q-item-section>
              </q-item>

              <q-expansion-item v-if="menuSubItem.sub.length !== 0"
                dense class="animated fadeIn"
                @click="actionMenu(menuSubItem)" expand-separator
                :icon="menuIcon(menuSubItem.detail.icon)"
                :label="menuSubItem.detail.name"
                :header-class="isCurrentMenu(menuSubItem.detail, 'menuList text-primary animated fadeIn')" >

                <q-item dense v-for="(menuSubItem, index) in menuSubItem.sub" :key="index+'-'+menuSubItem.id" :class="isCurrentMenu(menuSubItem.detail, 'menuList')"
                  clickable @click="actionMenu(menuSubItem)" v-ripple>
                  <q-item-section avatar> <q-icon :name="menuIcon(menuSubItem.detail.icon)" color="grey-9" /> </q-item-section>
                  <q-item-section :class="isCurrentMenu(menuSubItem.detail, 'text-light')" > {{ menuSubItem.detail.name }} </q-item-section>
                </q-item>

              </q-expansion-item>

              <q-item dense class="animated fadeIn single-line-menu" v-if="menuSubItem.sub.length === 0" clickable @click="actionMenu(menuSubItem)" v-ripple>
                <q-item-section avatar> <q-icon style="font-size: 1.1em;"  :name="menuIcon(menuSubItem.detail.icon)" color="light" /> </q-item-section>
                <q-item-section class="text-light ">{{ menuSubItem.detail.name }} </q-item-section>
              </q-item>

            </q-list>
            <!-- end sub -->

          </q-expansion-item>

          <q-item dense class="animated fadeIn single-line-menu" v-if="menuItem.sub.length === 0" clickable @click="actionMenu(menuItem)" v-ripple>
            <q-item-section avatar> <q-icon style="font-size: 1.1em;"  :name="menuIcon(menuItem.detail.icon)" color="light" /> </q-item-section>
            <q-item-section class="text-light ">{{ menuItem.detail.name }} </q-item-section>
          </q-item>

        </q-list>
      </q-scroll-area>

      <q-img class="absolute-top bg-secondary animated fade-in" style="height: 75px; !important;">
        <q-list class="bg-transparent" style="width: 100%;padding: 7px 0px 0px 1px;">
          <q-item >
            <div class="text-center" style="width: 100%;">
              <img v-if="!miniState" class="" src="assets/icons/logo-md-light.png" width="182">
              <img v-if="miniState" class="animated fadeIn" src="assets/icons/icon.png" width="42">
            </div>
          </q-item>

        </q-list>
      </q-img>
    </q-drawer>

    <q-drawer v-model="notifArea" side="right" >
      <!-- drawer content -->
      <div class="bg-secondary">
        <div class=" ph-2 pv-2">
          <q-btn @click="getNotif()" icon="refresh" flat color="light" size="lg" dense />
          <q-btn @click="getNotif()" flat size="lg" dense color="light" label="Notifications" class="capital">
            <q-badge v-if="notifList.length !== 0" color="red" floating transparent>{{notifList.length}}</q-badge>
          </q-btn>
        </div>
      </div>
      <div class="pt" style="max-width: 350px">

        <div v-if="notifSkeleton">
          <q-item v-for="item in 4" :key="item.id">
            <q-item-section avatar> <q-skeleton type="QAvatar" animation="fade" /> </q-item-section>
            <q-item-section>
              <q-item-label> <q-skeleton type="rect" animation="fade" /> </q-item-label>
              <q-item-label caption> <q-skeleton type="text" animation="fade" /> </q-item-label>
            </q-item-section>
          </q-item>
        </div>

        <q-item v-if="notifList.length === 0 && !notifSkeleton" class="animated fadeIn">
          <q-item-section>
            <q-item-label caption>There are no recent notifications for you </q-item-label>
          </q-item-section>
        </q-item>

        <q-list v-for="(notif, index) in notifList" :key="index+'notif'" class="animated fadeIn">

          <q-item clickable v-ripple @click="readNotif(notif.id)" class="animated fadeIn">
            <q-item-section avatar top>
              <q-avatar :icon="notif.icon" color="light-blue" text-color="white" />
            </q-item-section>

            <q-item-section>
              <q-item-label>{{notif.title}}</q-item-label>
              <q-item-label caption lines="4" v-html="notif.description"></q-item-label>
              <q-item-label caption lines="4">
                <q-btn color="light-blue-1" unelevated size="xs" @click="openLinkNotif(notif.link_path, notif.link_params)" >
                <span class="text-light-blue-10 capital">View Detail</span>
                </q-btn>
              </q-item-label>
              <q-item-label class="text-orange-10 italic" caption>{{notif.time}}</q-item-label>
            </q-item-section>

          </q-item>

          <q-separator inset="item" />

        </q-list>

      </div>
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
      miniModeMenu: false,
      miniState: false,
      notifArea: false,
      notifSkeleton: false,
      notifList: [],
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
        username: 'sijohn',
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
    // console.log('screenSize', screenSize)
    if (screenSize === 'sm' || screenSize === 'xs') this.onResize(screenSize)

    // if (this.$Helper.checkLdb('menu')) {
    //   if (this.$Helper.getLdb('menu') !== null) this.menuList = this.$Helper.getLdb('menu')
    // }
    this.menuList = this.$Helper.getFromLdb('menu', [])
  },

  mounted () {
    var credentials = this.$Config.credentials()
    if (credentials !== false) {
      this.drawerCtrl(false)
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
          this.getNotif()
        }
      })
    },

    getMenu () {
      this.$Helper.loading()
      this.API.get('me/menus', (status, data, message, response, full) => {
        this.$Helper.loading(false)
        if (status === 200) {
          this.menuList = data
          this.$Helper.saveLdb('menu', data)
          // console.log('menu', this.menuList)
          this.getUserInfo()
        }
      })
    },

    isCurrentMenu (menu, cssClass) {
      var route = this.$route
      // console.log(menu.slug, route.name)
      if (menu.slug === route.name) {
        return cssClass + ' bold'
      } else return cssClass
    },

    checkExpandMenu (menu) {
      let active = false
      menu.sub.map(r => {
        if (this.$route.name === r.detail.slug) active = true
      })
      // console.log(this.$route.name, menu.detail.slug)
      return active
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

    drawerCtrl (ctrl = true) {
      this.getMenu()
      this.pauseOnResize = true // agar tidak looping buka menu nya
      if (ctrl) this.drawer = !this.drawer
      this.notifArea = false
      setTimeout(() => {
        this.pauseOnResize = false // agar tidak looping buka menu nya
      }, 300)
      // console.log('drawerCtrl: ', this.drawer)
    },

    showNotif () {
      this.getNotif()
      this.pauseOnResize = true // agar tidak looping buka menu nya
      this.drawer = false
      this.notifArea = !this.notifArea
      setTimeout(() => {
        this.pauseOnResize = false // agar tidak looping buka menu nya
      }, 300)
      // console.log('drawerCtrl: ', this.drawer)
    },

    getNotif () {
      this.notifList = []
      this.notifSkeleton = true
      this.$Helper.loading()
      this.API.get('me/notifications', (status, data, message, response, full) => {
        this.notifSkeleton = false
        this.$Helper.loading(false)
        if (status === 200) {
          this.notifList = data
          // console.log('notif', data)
        }
      })
    },

    openLinkNotif (path, params) {
      this.$router.push({ name: path, params: params })
    },

    readNotif (id) {
      this.$Helper.loading()
      this.API.get(`/user-notifications/${id}/read`, (status, data, message, response, full) => {
        this.$Helper.loading(false)
      })
    },

    windowAction (type) {
      // console.log(type)
      if (type === 'minimize') this.$Helper.setFullscreen(false)
      if (type === 'fullscreen') this.$Helper.setFullscreen()
    },

    actionMenu (menu) {
      // console.log('actionMenu', menu)
      // console.log('router', this.$router)

      if (menu.sub === undefined || menu.sub.length === 0) { // jika tidak ada sub, jadikan link
        // var p = menu.detail.slug
        var p = menu.detail.path
        if (p === 'logout') this.logout()
        else if (p === 'ApiRoot') this.ApiRoot()
        else {
          // this.$router.push({ name: p })
          this.$router.push(p)
        }
      }
    },

    is_parent (props) {
      if (props.sub === undefined) return true
      else if (props.sub.length === 0) return true
      else return false
    },

    logout () {
      this.$Helper.loadingOverlay(true, 'Loging out..')
      this.API.cache = false
      this.API.post('user/logout', this.dataModel,
        (status, data, message, response, full) => {
          this.$Helper.loadingOverlay(false)
          console.log({ status, data, message, response, full })
          if (status === 200) {
            this.$Config.credentials('destroy')
            this.$router.push({ name: 'login' })
          }
        }, {})
    },

    ApiRoot () {
      this.$Config.setApiRoot()
    },

    menuIcon (icon) {
      if (icon === null) return 'label_important'
      else if (icon === '') return 'label_important'
      else return icon
    },

    miniStateMenu (val) {
      var miniModeMenu = false
      if (this.$Helper.checkLdb('miniModeMenu')) {
        miniModeMenu = this.$Helper.getLdb('miniModeMenu')
      }

      if (miniModeMenu) this.miniState = val
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

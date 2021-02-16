<template>
  <div>
    <q-header elevated class="bg-grad" >
        <q-toolbar>
          <img @click="drawerCtrl" class="cursor" src="assets/icons/logo-md-light.png" width="130">

          <!-- Menu Desktop -->
          <div class="gt-xs" style="width:100%; text-align:right">
            <!-- Menu Top -->
            <q-btn flat color="primary" :icon="menuItem.icon" class="capital text-light"
            v-for="(menuItem, index) in menuList" :key="index" :label="menuItem.name">
              <q-menu fit class="dd-menu-contain" >
                <q-item v-for="(menuSubItem, index) in menuItem.sub" :key="index+'-'+menuSubItem.icon"  :class="isCurrentMenu(menuSubItem, 'sub')"
                  @click="actionMenu(menuSubItem)" clickable v-ripple>
                  <q-item-section avatar top>
                    <q-avatar size="md" :icon="menuSubItem.icon" color="primary" text-color="white" />
                  </q-item-section>

                  <q-item-section>
                    <q-item-label lines="1">{{ menuSubItem.name }}</q-item-label>
                  </q-item-section>

                </q-item>

              </q-menu>
            </q-btn>

            <!-- Profil -->
            <q-btn flat class="text-light capital" icon="person" label="Profil">
              <q-menu>
                <div class="row no-wrap q-pa-md">
                  <div class="column">
                    <div class="text-h6 q-mb-md">Info</div>
                    <q-list >
                      <q-item clickable v-ripple>
                        <q-item-section avatar top>
                          <q-avatar size="md" icon="person" color="primary" text-color="white" />
                        </q-item-section>

                        <q-item-section>
                          <q-item-label lines="1">Profil</q-item-label>
                        </q-item-section>

                      </q-item>

                      <q-item clickable v-ripple>
                        <q-item-section avatar top>
                          <q-avatar size="md" icon="schema" color="primary" text-color="white" />
                        </q-item-section>

                        <q-item-section>
                          <q-item-label lines="1">Referal Saya</q-item-label>
                        </q-item-section>

                      </q-item>

                    </q-list>

                  </div>

                  <q-separator vertical inset class="q-mx-lg" />

                  <div class="column items-center">
                    <q-avatar size="72px">
                      <img src="https://cdn.quasar.dev/img/avatar4.jpg">
                    </q-avatar>

                    <div class="text-subtitle1 q-mt-md q-mb-xs">Ikhbalfuady</div>

                    <q-btn
                      color="primary"
                      label="Logout"
                      push
                      size="sm"
                      v-close-popup
                    />
                  </div>
                </div>
              </q-menu>
            </q-btn>
          </div>

          <!-- Menu Mobile -->
          <div class="lt-sm" style="width:100%; text-align:right">
            <q-btn flat @click="drawerCtrl" round dense icon="menu" >
              <q-menu fit class="dd-menu-contain" >
                <q-list >
                  <!-- Menu -->
                  <q-item clickable class="text-primary bold"
                  v-for="(menuItem, index) in menuList" :key="index" >
                    <q-item-section>{{menuItem.name}} </q-item-section>
                    <q-item-section avatar v-if="!is_parent(menuItem)" >
                      <q-icon color="grey" name="keyboard_arrow_right" />
                    </q-item-section>
                    <q-menu fit class="dd-menu-contain" >
                      <q-item v-close-popup v-for="(menuSubItem, index) in menuItem.sub" :key="index+'-'+menuSubItem.icon"  :class="isCurrentMenu(menuSubItem, 'sub')"
                        @click="actionMenu(menuSubItem)" clickable v-ripple>
                        <q-item-section avatar top>
                          <q-avatar size="md" :icon="menuSubItem.icon" color="primary" text-color="white" />
                        </q-item-section>

                        <q-item-section>
                          <q-item-label lines="1">{{ menuSubItem.name }}</q-item-label>
                        </q-item-section>

                      </q-item>

                    </q-menu>
                  </q-item>
                  <!-- Profil -->
                  <q-item clickable class="text-primary bold">
                    <q-item-section>Profil</q-item-section>
                    <q-menu>
                      <div class="row no-wrap q-pa-md">
                        <div class="column">
                          <div class="text-h6 q-mb-md">Info</div>
                          <q-list >
                            <q-item clickable v-ripple>
                              <q-item-section avatar top>
                                <q-avatar size="md" icon="person" color="primary" text-color="white" />
                              </q-item-section>

                              <q-item-section>
                                <q-item-label lines="1">Profil</q-item-label>
                              </q-item-section>

                            </q-item>

                            <q-item clickable v-ripple>
                              <q-item-section avatar top>
                                <q-avatar size="md" icon="schema" color="primary" text-color="white" />
                              </q-item-section>

                              <q-item-section>
                                <q-item-label lines="1">Referal Saya</q-item-label>
                              </q-item-section>

                            </q-item>

                          </q-list>

                        </div>

                        <q-separator vertical inset class="q-mx-lg" />

                        <div class="column items-center">
                          <q-avatar size="72px">
                            <img src="https://cdn.quasar.dev/img/avatar4.jpg">
                          </q-avatar>

                          <div class="text-subtitle1 q-mt-md q-mb-xs">Ikhbalfuady</div>

                          <q-btn
                            color="primary"
                            label="Logout"
                            push
                            size="sm"
                            v-close-popup
                          />
                        </div>
                      </div>
                    </q-menu>
                  </q-item>
                </q-list>
              </q-menu>

            </q-btn>

          </div>

        </q-toolbar>
      </q-header>

      <q-resize-observer @resize="onResize" />

  </div>

</template>

<script>

export default {
  name: 'Tabs',
  tab: 'index',
  props: ['topBarMenu', 'topBarInfo', 'isOpenDrawer', 'display', 'reload'],
  data () {
    return {
      moduleName: 'Drawer',
      appName: this.$Config.appName(),
      API: this.$Api,
      miniState: true,
      loading: false,
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
          name: 'Diginomic',
          icon: 'offline_bolt',
          iconClass: 'cursor-pointer'
        },
        menu: this.topMenu
      },
      serverIp: '',
      userInfo: this.$Model.userInfo(),
      platForm: '',
      fabPos: [18, 18],
      draggingFab: false,
      appConfig: null,
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

    this.appConfig = this.$ModuleConfig.getDefault('config')
  },

  updated () {
    // var screen = this.$q.screen.lt
    // console.log('screen', screen)
    this.reloadTopBar() // toggle menu
    this.platForm = this.$q.platform.is
    // console.log('Platform : ', this.platForm)
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
      // console.log('activate')
      this.getMenu()
    },

    getUserInfo () {
      this.userInfo = this.$Model.userInfo()
      this.$Helper.loading()
      this.API.get('staffs/info', (status, data, message, response, full) => {
        if (status === 200) {
          setTimeout(() => {
            this.$Config.credentials({ user: data })
            this.userInfo = data
            //
          }, 100)
        }
        this.$Helper.loading(false)
      })
    },

    getMenus () {
      this.$Helper.loading()
      this.API.get('me/menu', (status, data, message, response, full) => {
        if (status === 200) {
          this.menuList = data
          this.getUserInfo()
        }
        this.$Helper.loading(false)
      })
    },

    getMenu () {
      this.menuList = [
        {
          name: 'Dashboard',
          icon: 'home',
          path: '/',
          page: 'index',
          separator: false,
          params: null
        },
        {
          name: 'DigiWallet',
          icon: 'account_balance_wallet',
          path: '/users',
          page: 'wallet',
          separator: false,
          params: null,
          sub: [
            {
              name: 'Deposit',
              icon: 'archive',
              path: '/users',
              page: 'users',
              separator: false,
              params: null
            },
            {
              name: 'Withdraw',
              icon: 'unarchive',
              path: '/users/form',
              page: 'users-add',
              separator: false,
              params: null
            },
            {
              name: 'Transfer',
              icon: 'swap_horizontal_circle',
              path: '/users/form',
              page: 'users-add',
              separator: false,
              params: null
            },
            {
              name: 'Log Transaksi',
              icon: 'list_alt',
              path: '/users/form',
              page: 'users-add',
              separator: false,
              params: null
            }
          ]
        },
        {
          name: 'DigiCoin',
          icon: 'copyright',
          path: '/users',
          page: 'wallet',
          separator: false,
          params: null,
          sub: [
            {
              name: 'Topup',
              icon: 'post_add',
              path: '/users',
              page: 'users',
              separator: false,
              params: null
            },
            {
              name: 'Transfer',
              icon: 'swap_horizontal_circle',
              path: '/users/form',
              page: 'users-add',
              separator: false,
              params: null
            },
            {
              name: 'Jual',
              icon: 'switch_camera',
              path: '/users/form',
              page: 'users-add',
              separator: false,
              params: null
            },
            {
              name: 'Withdraw',
              icon: 'money',
              path: '/users/form',
              page: 'users-add',
              separator: false,
              params: null
            },
            {
              name: 'Bayar Cloud',
              icon: 'emoji_objects',
              path: '/users/form',
              page: 'users-add',
              separator: false,
              params: null
            },
            {
              name: 'Log Transaksi',
              icon: 'article',
              path: '/users/form',
              page: 'users-add',
              separator: false,
              params: null
            }
          ]
        }
      ]
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

    extractToPermission (data) {
      if (data !== undefined) {
        var pecah = data.split('-')
        var perms = true

        if (data !== 'module-kas' || data !== 'module-inventory') {
          perms = this.$ModuleConfig.getPermission(pecah[1], pecah[0])
        }

        if (perms === null) this.$router.push({ name: 'login' })
        else return perms
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
        else if (p === 'sync') this.bmsService()
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
    },

    bmsService () {
      this.$Helper.showToast('Synchronize data..')
      var apiRoot = this.$Config.getApiRoot()
      var bmsServiceUrl = this.$Config.getApiTemp()
      bmsServiceUrl = bmsServiceUrl + 'service.php?base=' + btoa(apiRoot)
      this.$Helper.openLink(bmsServiceUrl)
      // this.API.offlineData = true
      // this.API.get('url', (status, data, message, response, full) => {
      //   this.processData(data, type)
      //   Helper.loading(false)
      // })
    }
  }
}
</script>

<template>
  <div>
  <template
    v-for="(menuItem, index) in menuList">

    <!-- Overline -->
    <q-item  :key="'i'+index" v-if="menuItem.overline" dense :class="isSub+'animated fadeIn single-line-menu'" >
      <q-item-section avatar v-if="miniState" >
        <small class="menu-overline capital" style="font-size:11px;" >---------</small>
      </q-item-section>
      <q-item-section v-if="!miniState" class="menu-overline  capital " style="font-size:11px;">{{ menuItem.overline }} </q-item-section>
    </q-item>

    <q-expansion-item :key="'ii'+index" v-if="menuItem.sub.length !== 0"
      :icon="menuIcon(menuItem.detail.icon)" :label="menuItem.detail.name"
      :header-inset-level="insetLevel" :class="isSub+' side-menu-item ic-'+menuIcon(menuItem.detail.icon)"
      :default-opened="checkOpen(menuItem.sub, menuItem.detail)"
      >
      <SideMenuList :data="menuItem.sub" :mini-mode="miniState" :inset-level="insetLv" sub />
    </q-expansion-item>

    <q-expansion-item @click="actionMenu(menuItem)" :key="'iii'+index" v-if="menuItem.sub.length === 0"
      expand-icon="none" :class="isSub+' side-menu-item '+isActive(menuItem.detail.slug)+' ic-'+menuIcon(menuItem.detail.icon)"
      :header-inset-level="insetLevel"
      :icon="menuIcon(menuItem.detail.icon)" :label="menuItem.detail.name">
    </q-expansion-item>

  </template>
  </div>
</template>

<style lang="scss">
.isSub {
  background: rgba(0, 0, 0, 0.1);
}
.side-menu-item.active-menu {
  border-left: 6px solid $primary;

}

.side-menu-item.active-menu .q-expansion-item__container {
  background: rgba(208, 208, 208, 0.08);
}

.side-menu-item.active-menu div div {
  margin-left:-2px;
}

.side-menu-item.active-menu div, .side-menu-item.active-menu div i {
  color: $primary !important;
}

</style>
<script>
import SideMenuList from './SideMenuList'

export default {
  name: 'SideMenuList',
  components: {
    SideMenuList
  },
  props: [
    'data',
    'sub',
    'insetLevel',
    'miniMode'
  ],
  data () {
    return {
      left: false
    }
  },

  created () {
    //
  },

  mounted () {
    //
  },

  computed: {
    menuList () {
      var menu = (this.data) ?? []
      return menu
    },

    miniState () {
      var mode = (this.miniMode) ?? false
      return mode
    },

    insetLv () {
      var res = 0.2
      if (this.insetLevel) res = this.insetLevel + res
      return res
    },

    isSub () {
      var res = ''
      if (this.sub === '') res = 'isSub '
      return res
    }
  },

  methods: {
    //
    menuIcon (icon) {
      if (icon === null) return 'fiber_manual_record'
      else if (icon === '') return 'fiber_manual_record'
      else return icon
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

    checkOpen (subMenu, current) {
      var route = this.$route.name
      var menuRoute = current.slug
      // console.log('active Route', route)

      // var res = false
      var area = [current.slug]
      if (route === menuRoute) return true
      for (const item of subMenu) {
        area.push(item.detail.slug)
      }

      for (const menu of area) {
        if (route === menu) {
          return true
        }
      }

      // console.log(`[${current.name} : ${current.slug}] ${route}`, area)
      // return res
    },

    isActive (current) {
      var route = this.$route.name
      if (route === current) return 'active-menu'
    }

  }
}
</script>

<template>
  <div>
  <template
    v-for="(menuItem, index) in menuList">

    <!-- Overline -->
    <q-item  :key="'i'+index" v-if="menuItem.overline" dense class="animated fadeIn single-line-menu" >
      <q-item-section avatar v-if="miniState" >
        <small class="menu-overline capital" style="font-size:11px;" >---------</small>
      </q-item-section>
      <q-item-section v-if="!miniState" class="menu-overline  capital " style="font-size:11px;">{{ menuItem.overline }} </q-item-section>
    </q-item>

    <q-expansion-item :key="'ii'+index" v-if="menuItem.sub.length !== 0"
      :icon="menuIcon(menuItem.detail.icon)" :label="menuItem.detail.name"
      :header-inset-level="insetLevel" :class="'side-menu-item ic-'+menuIcon(menuItem.detail.icon)"
      >
      <SideMenuList :data="menuItem.sub" :mini-state="miniState" :inset-level="insetLv"/>
    </q-expansion-item>

    <q-expansion-item @click="actionMenu(menuItem)" :key="'iii'+index" v-if="menuItem.sub.length === 0"
      expand-icon="none" :class="'side-menu-item ic-'+menuIcon(menuItem.detail.icon)"
      :header-inset-level="insetLevel + 0.3"
      :icon="menuIcon(menuItem.detail.icon)" :label="menuItem.detail.name">
    </q-expansion-item>

  </template>
  </div>
</template>

<script>
import SideMenuList from './SideMenuList'

export default {
  name: 'SideMenuList',
  components: {
    SideMenuList
  },
  props: [
    'data',
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
      var res = 0.3
      // if (this.insetLevel) res = this.insetLevel + res
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
    }

  }
}
</script>

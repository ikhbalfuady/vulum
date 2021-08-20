<template>
  <div>
    <q-list dense style="min-width: 100px">
      <q-item v-for="(rowSub, indexSub) in menu.sub" v-bind:key="indexSub" clickable>
        <q-item-section>
          <span v-html="rowSub.name" @click="MENU_callEvent(menu.sub, indexSub)"></span>
        </q-item-section>
        <q-item-section v-if="MENU_checkSub(rowSub)" side><q-icon name="keyboard_arrow_right" /></q-item-section>

        <q-menu anchor="top right" self="top left"  v-if="MENU_checkSub(rowSub)" >
          <TopMenuList :data="rowSub" />
        </q-menu>
      </q-item>
    </q-list>
  </div>
</template>

<script>
import TopMenuList from './TopMenuList'

export default {
  name: 'TopMenuList',
  components: {
    TopMenuList
  },
  props: [
    'data'
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
    menu () {
      var menu = (this.data) ?? []
      return menu
    }

  },

  methods: {
    //
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

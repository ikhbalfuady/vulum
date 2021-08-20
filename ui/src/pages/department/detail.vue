
<template >

  <div class="root bg-soft">

    <!-- drawer & top menu -->
    <top-menu v-if="!isModal" :data="Meta"  />
    <side-menu v-if="!isModal" :data="Meta" />

    <!-- Header Title -->
    <header-title :meta="Meta" :title="Meta.name + 'Detail'" :isModal="isModal" :backToRoot="backToRoot" half-slot >
      <template v-slot:half>
        <q-btn v-if="Meta.permission.update" @click="edit" label="edit" icon="edit" flat dense class="animated slideInRight mr-1 capital bg-green-1 text-green-9 pv-1 fix-icon-btn" color="green"/>
      </template>
    </header-title>

    <q-card :class="classArea">
      <q-card-section class=" pb-2">
        <loading v-if="dataModel.id === null" />
        <q-markup-table style="width:100%" class="no-shadow animated fadeIn" v-if="dataModel.id">
          <tbody>
            <template v-for="(props, key) in viewList" >

              <!-- default -->
              <tr :key="key" >
                <td class="bold text-primary capital">{{key}}</td>
                <td v-ripple>{{props}}</td>
              </tr>

            </template>
            <tr >
              <td class="bold text-primary capital">Log Info</td>
              <td v-ripple><log-info :data="dataModel" /></td>
            </tr>
          </tbody>
        </q-markup-table>
      </q-card-section>
    </q-card>

  </div>
</template>

<script>
import Meta from './meta'

export default {
  name: 'Department',
  props: [
    'fromModal'
  ],
  data () {
    return {
      Meta,
      API: this.$Api,
      // default data
      dataModel: {},
      viewList: null
    }
  },

  created () {
    this.dataModel = this.$Helper.unReactive(this.Meta.model)
    this.initTopBar()
    this.$Handler.permissions(this, 'read', Meta, (status, data) => {
      this.Meta.permission = data // update current permissions of this module
      this.onRefresh()
    })
  },

  mounted () {
    this.handleFromModal()
  },

  computed: {
    isModal () {
      return (this.fromModal) ?? false
    },
    classArea () {
      return (this.fromModal) ? 'mt-2 no-shadow' : 'box-shadow mv-2 mt-2'
    }
  },

  methods: {

    handleFromModal () {
      if (this.fromModal && this.fromModal.params) {
        if (this.fromModal.params.id !== undefined && this.fromModal.params.id !== null) {
          this.getData(this.fromModal.params.id)
        }
      }
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    onRefresh () {
      var id = this.$Handler.getParamId(this)
      if (id) this.getData(id)
    },

    getData (id) {
      console.log('getData')
      this.$Helper.loading()
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loading(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
          this.viewList = this.$Handler.viewList(this.dataModel, [
            // except / remove property from list
          ])
        }
      })
    },

    edit () {
      this.$router.push({ name: 'edit-' + this.Meta.module, params: this.dataModel })
    },

    backToRoot () {
      if (this.fromModal) {
        this.fromModal.show = false
      } else this.$router.push({ name: this.Meta.module })
    }
  }
}
</script>

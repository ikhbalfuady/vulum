
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />-->

      <!-- Header Title -->
      <div class="row pl-2 pt-2 bg-light" v-if="!isModal">
        <div class="col-6 pb-1 pv info-page">
          <div class="title animated fadeIn">
            <span class="text-caption text-grey-7">Master {{Meta.name}}</span><br>
            <q-btn @click="backToRoot" rounded icon="arrow_back" flat dense class="mr-1 animated slideInLeft" color="grey-9"/>
            <span class="text-h5 bold text-dark capital ">Detail {{Meta.name}} </span>
          </div>
        </div>

        <div class="col-6 pb-1 pr-1-5 right">
            <q-btn v-if="Meta.permission.update" @click="edit" label="edit" icon="edit" flat dense class="animated slideInRight mr-1 capital bg-green-1 text-green-9 pv-1 fix-icon-btn" color="green"/>
        </div>
      </div>

      <q-card :class="classArea">
        <q-card-section class=" pb-2">
          <div class="text-center mt-5 pt-5 mb-5 pb-5" v-if="dataModel.id === null">
            <q-spinner-facebook class="animated fadeIn"
              color="primary"
              size="7em"
            /> <br>
            <div class="text-grey-7 animated fadeIn" >Loading...</div>
          </div>

          <q-markup-table style="width:100%" class="no-shadow animated fadeIn" v-if="dataModel.id">
            <tbody>
              <tr v-for="(props, index) in dataModel" :key="index" >
                <td class="bold text-primary capital">{{index}}</td>
                <td v-ripple>{{props}}</td>
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
      dataModel: this.$Helper.unReactive(Meta.model),
      rules: {
        permission: Meta.permission
      }
    }
  },

  created () {
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
      if (this.fromModal.params) {
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
      this.$Helper.loading()
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loading(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
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

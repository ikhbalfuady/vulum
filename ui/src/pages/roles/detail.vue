
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">Detail</span><br>
            <span class="text-h5 bold text-primary capital">{{Meta.name}}</span>
          </div>
          <q-btn @click="edit" unelevated color="green" class="capital" icon="edit" label="Edit" />
        </div>
      </div>

      <div class="row mv-2">

        <q-list bordered separator class="box-shadow bg-white" style="width:100%">

          <q-item  v-for="(props, index) in dataModel" :key="index" v-ripple>
            <q-item-section>
              <q-item-label caption>{{index}}</q-item-label>
              <q-item-section>{{props}}</q-item-section>
            </q-item-section>
          </q-item>

        </q-list>

      </div>

  </div>
</template>

<script>
import { Helper } from '../../services/helper'
import Api from '../../services/Api'
import { Model } from '../../services/model'
import Meta from './meta'

export default {
  name: 'Roles',
  data () {
    return {
      Meta,
      API: new Api(),
      // default data
      rules: {
        permission: {}
      },
      dataModel: Model.Roles()
    }
  },

  created () {
    this.initTopBar()
    this.initialize()
  },

  mounted () {

  },

  methods: {

    callbackForm (params = null) {
      this.onRefresh()
    },

    onRefresh () {
      this.initRules()
    },

    initialize () {
      this.rules.permission = this.$ModuleConfig.getDefault('permission', this.Meta.module)
      console.log('permission', this.rules.permission)
      this.onRefresh()
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    initRules () {
      this.$ModuleConfig.loadAppConfig((status, data) => {
        // code
      }, this.Meta.module)

      this.$ModuleConfig.getCurrentPermission((status, data) => {
        // console.log('loadPermission', data, status)
        console.log('akses', data[this.Meta.module])
        if (data[this.Meta.module] !== undefined) {
          var akses = data[this.Meta.module]
          this.rules.permission = akses
          if (!akses.view) this.$router.push({ name: '401' }) // view module
          else {
            var params = this.$route.params
            console.log('params', params)
            setTimeout(() => {
              if (Helper.checkParams(params)) {
                if (params.id !== undefined) this.getData(params.id)
                else this.backToRoot()
              }
            }, 200)
          }
        } else this.$router.push({ name: '401' })
      }, this.Meta.module)
    },

    getData (id) {
      console.log('getData')
      Helper.loading()
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        Helper.loading(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
        }
      })
    },

    edit () {
      this.$router.push({ name: this.Meta.module + '-edit', params: this.dataModel })
    },

    backToRoot () {
      this.$router.push({ name: this.Meta.module })
    }
  }
}
</script>


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
import Meta from './meta'

export default {
  name: 'UserNotifications',
  data () {
    return {
      Meta,
      API: this.$Api,
      // default data
      dataModel: Meta.model,
      rules: {
        permission: Meta.permission
      }
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

    checkPermission (mode = 'create') {
      var access = this.$ModuleConfig.checkPermission(this.$router, this.Meta.module + '-' + mode)
      if (access) return true
      else this.$router.push({ name: '403' })
    },

    initialize () {
      var params = this.$route.params
      if (this.$Helper.checkParams(params)) { // checking access update
        if (this.checkPermission('read')) {
          if (params.id !== undefined) this.getData(params.id)
          else this.backToRoot()
        }
      } else this.backToRoot()
    },

    onRefresh () {
      this.initialize()
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
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
        }
      })
    },

    edit () {
      this.$router.push({ name: 'edit-' + this.Meta.module, params: this.dataModel })
    },

    backToRoot () {
      this.$router.push({ name: this.Meta.module })
    }
  }
}
</script>

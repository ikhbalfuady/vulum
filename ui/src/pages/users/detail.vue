
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
             <div class="flex">
              <div>
                <div class="" style="margin-left:35px"><span class="text-h5-caption text-grey-8">Detail</span><br></div>
                <span class="text-h5 bold text-primary capital"><q-btn class="capital bold" dense unelevated flat color="text-primary" label="" icon="arrow_back" @click="backToRoot" />
                {{Meta.name}} <q-btn @click="edit" unelevated color="green" class="capital" icon="edit" label="Edit" /></span>
              </div>
            </div>
          </div>
          <!-- <q-btn @click="edit" unelevated color="green" class="capital" icon="edit" label="Edit" /> -->
        </div>
      </div>

      <div class="row mv-2">
        <div class="col-12 col-sm-6 pv ph">
          <q-card class="box-shadow full-height">
            <q-card-section>
              <q-list separator>
                <q-item>
                  <q-item-section>
                    <q-item-label caption>Name</q-item-label>
                    <q-item-section>{{ dataModel.name }}</q-item-section>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section>
                    <q-item-label caption>Username</q-item-label>
                    <q-item-section>{{ dataModel.username }}</q-item-section>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section>
                    <q-item-label caption>Email</q-item-label>
                    <q-item-section>{{ dataModel.email }}</q-item-section>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section>
                    <q-item-label caption>Menu</q-item-label>
                    <q-item-section>{{ dataModel.menu ? dataModel.menu.name : '' }}</q-item-section>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section>
                    <q-item-label caption>Role</q-item-label>
                    <q-item-section>{{ dataModel.role ? dataModel.role.name : '' }}</q-item-section>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section>
                    <q-item-label caption>Department</q-item-label>
                    <q-item-section>{{ dataModel.department ? dataModel.department.name : '' }}</q-item-section>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section>
                    <q-item-label caption>Active</q-item-label>
                    <q-item-section>
                      {{ dataModel.active ? 'Active' : 'Non Active' }}
                    </q-item-section>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section>
                    <q-item-label caption>Log Info</q-item-label>
                    <q-item-section>
                      <log-info :data="dataModel" />
                    </q-item-section>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>
          </q-card>
        </div>
      </div>

  </div>
</template>

<script>
import Meta from './meta'

export default {
  name: 'Users',
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

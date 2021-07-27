
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-3 pb-2 mb-2 box-shadow bg-white">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">{{Meta.parent}}</span><br>
            <span class="text-h5 bold text-primary capital">{{title}} {{Meta.name}} <span v-if="title === 'Update'" >#{{dataModel.id}}</span></span>
          </div>
        </div>
      </div>

      <q-card class="box-shadow mv-2">
          <q-card-section>
            <q-form @submit="submit">
              <q-card-section class="row">

                <div class="col-12 col-sm-4 col-md-4 pv ph" >
                  <q-input v-model="dataModel.name" label="name" dense filled square
                  lazy-rules :rules="[
                    val => val !== null && val !== '' || 'Name must be filled!',
                  ]" />
                </div>

                <div class="col-12 col-sm-4 col-md-4 pv ph" >
                  <q-input v-model="dataModel.username" label="username" dense filled square
                  lazy-rules :rules="[
                    val => val !== null && val !== '' || 'username must be filled!',
                  ]" />
                </div>

                <div class="col-12 col-sm-4 col-md-4 pv ph" >
                  <q-input v-model="dataModel.email" label="email" dense filled square
                  lazy-rules :rules="[
                    val => val !== null && val !== '' || 'email must be filled!',
                  ]" />
                </div>

                <div class="col-12 col-sm-4 col-md-4 pv ph" >
                  <q-input v-model="dataModel.password" label="password" dense filled square
                  :hint="(dataModel.id === null) ? 'Password is required' : 'Leave blank if dont want to change' " />
                </div>

                <div class="col-12 col-sm-4 col-md-4 pv ph" >
                  <q-select label="Menu" dense filled square
                    :options="select.menus"
                    v-model="dataModel.menu_id"
                    option-value="id" option-label="name"
                    emit-value map-options  use-input clearable
                    @filter="(val, update) => filterSelect(val, update, 'menus')"
                    lazy-rules :rules="[
                      val => val !== null && val !== '' || 'Menu is required!',
                    ]"
                  >
                    <template v-slot:selected-item="row"> <span class="ellipsis">{{ row.opt.name }}</span> </template>
                    <template v-slot:no-option>
                      <q-item> <q-item-section class="text-grey"> Data not found </q-item-section> </q-item>
                    </template>
                  </q-select>
                </div>

                <div class="col-12 col-sm-4 col-md-4 pv ph" >
                  <q-select label="Role" dense filled square
                    :options="select.roles"
                    v-model="dataModel.role_id"
                    option-value="id" option-label="name"
                    emit-value map-options  use-input clearable
                    @filter="(val, update) => filterSelect(val, update, 'roles')"
                    lazy-rules :rules="[
                      val => val !== null && val !== '' || 'Role is required!',
                    ]"
                  >
                    <template v-slot:selected-item="row"> <span class="ellipsis">{{ row.opt.name }}</span> </template>
                    <template v-slot:no-option>
                      <q-item> <q-item-section class="text-grey"> Data not found </q-item-section> </q-item>
                    </template>
                  </q-select>
                </div>

              </q-card-section>

              <q-card-actions align="right" class="">
                <q-btn class="capital bold" unelevated flat color="red" label="Cancel" icon="cancel" @click="backToRoot" />
                <q-btn class="capital bold" unelevated color="green" label="Save" :disable="disableSubmit" type="submit" icon="check_circle"/>
              </q-card-actions>
            </q-form>
          </q-card-section>
      </q-card>

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
      title: 'Create',
      dataModel: {},
      rules: {
        permission: Meta.permission
      },
      disableSubmit: false,
      select: {
        roles: [],
        rolesTmp: [],
        menus: [],
        menusTmp: []
      }
    }
  },

  created () {
    this.dataModel = this.$Helper.unReactive(this.Meta.model)
    this.initTopBar()
    this.$ModuleConfig.getCurrentPermissions((status, data) => {
      console.log('initPermissionPage:' + Meta.module, data)
      this.initialize()
    }, 'user-form')
  },

  mounted () {

  },

  watch: {
    $route (to, from) {
      this.dataModel = Meta.model
    }
  },

  methods: {

    checkPermission (mode = 'create') {
      var access = this.$ModuleConfig.checkPermission(this.$router, this.Meta.module + '-' + mode)
      if (access) return true
      else this.$router.push({ name: '403' })
    },

    initialize () {
      var params = this.$route.params
      if (this.$Helper.checkParams(params)) { // checking access update
        if (this.checkPermission('update')) {
          if (params.id !== undefined) {
            this.onRefresh()
            this.getData(params.id)
          } else this.backToRoot()
        }
      } else { // checking access create
        if (this.checkPermission('create')) {
          this.onRefresh()
        }
      }
    },

    onRefresh () {
      //
      this.getListSelect('master-menus', 'menus')
      this.getListSelect('roles')
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    getData (id) {
      console.log('getData')
      this.$Helper.loadingOverlay(true, 'Loading..')
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
          this.title = 'Edit'
        }
      })
    },

    edit (data) {
      this.triggerForm(data)
    },

    backToRoot () {
      this.$router.push({ name: this.Meta.module })
    },

    emitModel (target, val) {
      this.dataModel[target] = val
    },

    submit () {
      if (this.validateSubmit()) {
        this.disableSubmit = true
        if (this.dataModel.id !== null) this.update()
        else this.save()
      }
    },

    validateSubmit () {
      if (this.dataModel.id === null && this.dataModel.password === null) {
        this.$Helper.showAlert('Password Empty!', 'Password must be filled!')
        return false
      } else return true
    },

    save () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.post(this.Meta.module, this.dataModel, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.messageSubmit('Saving', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    update () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.put(this.Meta.module + '/' + this.dataModel.id, this.dataModel, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.messageSubmit('Update', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    messageSubmit (titleAdd = '', msg) {
      this.$Helper.showAlert(titleAdd + ' Succesfully', msg)
    },

    async filterSelect (val, update, target) {
      this.select = await this.$Helper.filterSelect(val, update, target, this.select)
    },

    getListSelect (endpoint, selectSource = null) {
      this.$Helper.loadingOverlay(true)
      // var endpoint = 'menus'
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          var targetSource = endpoint
          if (selectSource) targetSource = selectSource
          var tmpName = targetSource + 'Tmp'

          this.select[targetSource] = data
          this.select[tmpName] = data
        }
      })
    }

  }
}
</script>

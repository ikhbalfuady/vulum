
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">Master {{Meta.name}}</span><br>
            <span class="text-h5 bold text-primary capital">{{title}} {{Meta.name}} <span v-if="title === 'Update'" >#{{dataModel.id}}</span></span>
          </div>
        </div>
      </div>

      <q-card class="box-shadow mv-2">
          <q-card-section>
            <q-form @submit="submit">
              <q-card-section class="row">

                <div class="col-12 col-sm-6 col-md-6 pv ph" >
                  <q-input type="password" v-model="dataModel.current_password" label="current password *" dense filled square
                  lazy-rules :rules="[
                    val => val !== null && val !== '' || 'current password must be filled!',
                  ]" />
                </div>

                <div class="col-12 col-sm-6 col-md-6 pv ph" >
                  <q-input type="password" v-model="dataModel.password" label="new password *" dense filled square
                  lazy-rules :rules="[
                    val => val !== null && val !== '' || 'new password must be filled!',
                  ]" />
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
      title: 'Change Password',
      dataModel: {
        id: null,
        current_password: null,
        password: null
      },
      rules: {
        permission: Meta.permission
      },
      disableSubmit: false,
      select: {
        departments: [],
        departmentsTmp: [],
        roles: [],
        rolesTmp: [],
        menus: [],
        menusTmp: []
      }
    }
  },

  created () {
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
      // this.dataModel = Meta.model
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
            this.setData(params.id)
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
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    setData (id) {
      this.dataModel.id = id
    },

    edit (data) {
      this.triggerForm(data)
    },

    backToRoot () {
      this.$router.push({ name: 'change-password-users' })
    },

    emitModel (target, val) {
      this.dataModel[target] = val
    },

    submit () {
      if (this.validateSubmit()) {
        this.disableSubmit = true
        this.update()
      }
    },

    validateSubmit () {
      if (this.dataModel.current_password === null && this.dataModel.password === null) {
        this.$Helper.showAlert('Password Empty!', 'Password must be filled!')
        return false
      } else return true
    },

    update () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.post('me/change-password/' + this.dataModel.id, this.dataModel, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.messageSubmit('Update', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    messageSubmit (titleAdd = '', msg) {
      this.$Helper.showSuccess(titleAdd + ' Succesfully', msg)
    }

  }
}
</script>

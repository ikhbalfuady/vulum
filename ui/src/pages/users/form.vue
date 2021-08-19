
<template >

  <div class="root bg-soft">

    <!-- drawer & top menu -->
    <top-menu v-if="!isModal" :data="Meta"  />
    <side-menu v-if="!isModal" :data="Meta" />

    <!-- Header Title -->
    <header-title :meta="Meta" :isModal="isModal" :backToRoot="backToRoot" form-mode />

    <q-card :class="classArea">
      <q-card-section>
        <q-form @submit="submit">
          <q-card-section class="row">

            <vl-input col="6" v-model="dataModel.name" label="name" :rules="[
                val => val !== null && val !== '' || 'name must be filled!',
              ]"
            />

            <vl-input col="6" v-model="dataModel.username" label="username" :rules="[
                val => val !== null && val !== '' || 'username must be filled!',
              ]"
            />

            <vl-input col="6" v-model="dataModel.email" label="email *" :rules="[
                val => val !== null && val !== '' || 'email must be filled!',
              ]"
            />

            <vl-input col="6" v-model="dataModel.password" :label="(dataModel.id === null) ? 'password *' : 'password' "
              :hint="(dataModel.id === null) ? 'Password is required' : 'Leave blank if dont want to change' "/>

            <vl-select-serverside col="6" label="Role"
              url="roles"
              :option-label="opt => `${opt.code} | ${opt.name}`"
              :default-data="dataModel.role"
              v-model="dataModel.role_id"
            />

            <vl-select-serverside col="6" label="Menu"
              url="master-menus"
              :default-data="dataModel.menu"
              v-model="dataModel.menu_id"
            />

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
  props: [
    'fromModal'
  ],
  data () {
    return {
      Meta,
      API: this.$Api,
      // default data
      title: 'Create',
      loading: false,
      dataModel: {},
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

    var action = this.$Handler.formMode(this)

    this.$Handler.permissions(this, action.mode, Meta, (status, data) => {
      this.Meta.permission = data // update current permissions of this module
      if (action.mode === 'update' && action.params.id !== undefined) this.getData(action.params.id)
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
          this.onRefresh()
        }
      }
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    onRefresh () {
      //
      // this.getListSelect('master-menus?limit=0', 'menus')
      // this.getListSelect('roles?limit=0', 'roles')
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

    backToRoot () {
      if (this.fromModal) {
        this.fromModal.show = false
      } else this.$router.push({ name: this.Meta.module })
    },

    submit () {
      if (this.validateSubmit()) {
        this.disableSubmit = true
        if (this.dataModel.id !== null) this.update()
        else this.save()
      }
    },

    validateSubmit () {
      // if (this.dataModel.name === null) {
      //   this.$Helper.showAlert('Nama Kosong!', 'Nama harus di isi!')
      //   return false
      // } else return true
      return true
    },

    save () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.post(this.Meta.module, this.dataModel, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.$Helper.showSuccess('Saving Succesfully', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    update () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.put(this.Meta.module + '/' + this.dataModel.id, this.dataModel, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.$Helper.showSuccess('Update Succesfully', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
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

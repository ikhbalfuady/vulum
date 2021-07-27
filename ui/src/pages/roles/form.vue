
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-3 pb-2 mb-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">{{Meta.parent}}</span><br>
            <span class="text-h5 bold text-primary capital">{{title}} {{Meta.name}} <span v-if="title === 'Update'" >#{{dataModel.id}}</span></span>
          </div>
        </div>

        <div v-if="!dataModel.id" class="col-12 mt-1">
          <q-banner class="text-white bg-blue">
            <span class="text-h5 bold">Attention!</span><br>
            <p>Before define the permission, please create name of this role first</p>
          </q-banner>
        </div>
      </div>

      <q-form @submit="submit" class="pb-3">
        <q-table class="mv-2 mt-2 box-shadow pb-1"
          title="Permissions"
          :data="permissions"
          :columns="table.columns"
          row-key="id"
          :filter="table.filter"
          :loading="table.loading"
        >

          <template v-slot:top>
            <div class="row" style="width:100%">
              <div class="col-12 col-sm-6 col-md-4 pv ph" >
                <q-input v-model="dataModel.name" label="Role Name" dense filled square />
              </div>

              <div class="col-12 col-sm-6 col-md-4 pv ph" >
                <q-input v-model="dataModel.slug" label="Slug Name" placeholder="empty for auto generate" dense filled square />
              </div>

              <div v-if="dataModel.id" class="col-12 col-sm-12 col-md-4 pv ph">
                <q-input filled dense debounce="300" color="primary" v-model="table.filter" placeholder="Search permission..">
                  <template v-slot:append>
                    <q-icon name="search" />
                  </template>
                </q-input>
              </div>
            </div>

          </template>

          <template v-slot:body-cell-allow="props">
            <q-td :props="props">
              <q-toggle v-model="props.row.allow"
              unchecked-icon="clear"
              checked-icon="check"
              size="lg"
              />
            </q-td>
          </template>

          <template v-slot:body-cell-name="props">
            <q-td :props="props">
              <b>{{props.row.name}}</b>
            </q-td>
          </template>

          <template v-slot:body-cell-slug="props">
            <q-td :props="props">
              <span>{{props.row.slug}}</span>
            </q-td>
          </template>

        </q-table>

        <q-card class="box-shadow mv-2 mt-2 mb-4">
            <q-card-section>
                <q-card-actions align="right" class="">
                  <q-btn class="capital bold" unelevated flat color="red" label="Cancel" icon="cancel" @click="backToRoot" />
                  <q-btn class="capital bold" unelevated color="green" label="Save" :disable="disableSubmit" type="submit" icon="check_circle"/>
                </q-card-actions>
            </q-card-section>
        </q-card>

      </q-form>

  </div>
</template>

<script>
import Meta from './meta'

export default {
  name: 'Roles',
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
        permission: [],
        permissionTmp: [], // need for base when use filter search
        permissionModel: null
      },
      permissions: [],
      table: {
        loading: false,
        filter: '',
        selected: [],
        columns: [
          { name: 'allow', field: 'allow', align: 'center', label: 'Allow', style: 'width:20px;', sortable: true },
          { name: 'name', field: 'name', align: 'left', label: 'Name', sortable: true },
          { name: 'slug', field: 'slug', align: 'center', label: 'slug', sortable: true }
        ]
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

    add () {
      var data = this.select.permissionModel
      if (data) {
        this.permissions.push(data)
        this.select.permissionModel = null
      } else this.$Helper.showToast('Please pick permission first!')
    },

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
          this.dataModel = Meta.model
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

    getData (id) {
      console.log('getData')
      this.$Helper.loadingOverlay(true, 'Loading..')
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
          this.permissions = data.detail
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
          this.messageSubmit('Saving', message)
          // this.backToRoot()
          this.$router.push({ name: 'edit-' + this.Meta.module, params: { id: data.id } })
          this.disableSubmit = false
          this.getData(data.id)
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

    filterSelect (val, update, target) {
      var targetNameTmp = target + 'Tmp'
      // console.log('select.' + targetNameTmp, this.select[targetNameTmp])
      update(() => { this.select[target] = this.select[targetNameTmp] })

      update(() => {
        const needle = val.toLowerCase()
        var tmp = this.select[targetNameTmp]
        this.select[target] = tmp.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
      })
    }
  }
}
</script>

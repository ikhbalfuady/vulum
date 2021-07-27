
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

        <div class="col-12">

          <q-expansion-item
            class="shadow-1 overflow-hidden mh-2"
            icon="help"
            label="Icon References"
            header-class="bg-primary text-white"
            expand-icon-class="text-white"
          >
            <q-card>
              <q-card-section>
                <div class="q-markup-table q-table__container q-table__card q-table--horizontal-separator q-table--flat q-table--bordered" style="max-width: 100%;">
                  <table class="q-table">
                    <thead>
                      <tr>
                        <th class="text-left">Quasar IconSet name</th>
                        <th class="text-left">Name prefix</th>
                        <th class="text-left">Examples</th>
                        <th class="text-left">Sources</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>material-icons</td>
                        <td>
                          <em>None</em>
                        </td>
                        <td>thumb_up</td>
                        <td><a href="https://material.io/resources/icons/?style=baseline" target="_blank" >https://material.io/resources/icons/?style=baseline</a></td>
                      </tr>
                      <tr>
                        <td>material-icons-outlined</td>
                        <td>o_</td>
                        <td>o_thumb_up</td>
                        <td><a href="https://material.io/resources/icons/?style=baseline" target="_blank" >https://material.io/resources/icons/?style=baseline</a></td>
                      </tr>
                      <tr>
                        <td>ionicons-v4</td>
                        <td>ion-, ion-md-, ion-ios-, ion-logo-</td>
                        <td>ion-heart, ion-logo-npm, ion-md-airplane</td>
                         <td><a href="https://ionicons.com/v4/" target="_blank" >https://ionicons.com/v4/</a></td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </q-card-section>
            </q-card>
          </q-expansion-item>

        </div>

      </div>

      <q-card class="box-shadow mv-2">
          <q-card-section>
            <q-form @submit="submit">
              <q-card-section class="row">

                <div class="col-12 pv ph" v-if="dataModel.id === null" >
                  <q-toggle size="md" floating dense
                    v-model="generate_default"
                    icon="monetization_on"
                    label="Generate Default"
                    class="bold  text-caption"
                    emit-value
                  /> <br>
                  <p class="text-grey-8 pt-1">
                  "<b>Generate Default</b>" automatically create menu defaults link menu like  : <br>
                  - Header (Menu) <br>
                  - List (List Menu) <br>
                  - Add (Add Menu) <br>
                  <small class="text-primary">Example you do generate default for "Role", system will be generate menu : <br>
                  - Role<br>
                  - Role List<br>
                  - Add Role<br>
                  </small>
                  <small>Genrate default only effective when create menu</small>
                  </p>
                </div>

                <div class="col-12 col-sm-6 col-md-6 pv ph" >
                  <q-input v-model="dataModel.name" label="Name" dense filled square
                  lazy-rules :rules="[
                    val => val !== null && val !== '' || 'Name must be filled!',
                  ]" />
                </div>

                <div class="col-12 col-sm-6 col-md-6 pv ph" v-if="generate_default === false" >
                  <q-input v-model="dataModel.slug" label="Slug Name" placeholder="empty for auto generate" dense filled square />
                </div>

                <div class="col-12 col-sm-6 col-md-6 pv ph" v-if="generate_default === false" >
                  <q-input v-model="dataModel.path" label="Path" dense filled square placeholder="ex: /menu/detail" />
                </div>

                <div class="col-12 col-sm-6 col-md-6 pv ph" v-if="generate_default === false" >
                  <q-input v-model="dataModel.icon" label="Icon" dense filled square placeholder="icon name" >
                  <template v-slot:prepend> <q-icon :name="dataModel.icon" /> </template>
                  </q-input>
                </div>

              </q-card-section>

              <q-card-actions align="right" class="">
                <q-btn class="capital bold" unelevated flat color="red" label="Cancel" icon="cancel" @click="backToRoot" />
                <q-btn class="capital bold" unelevated color="green" label="Save" :disable="disableSubmit" type="submit" icon="check_circle"/>
              </q-card-actions>
            </q-form>
          </q-card-section>
      </q-card>

      <div class="mb-2">&nbsp;</div>

  </div>
</template>

<script>
import Meta from './meta'

export default {
  name: 'MenuItems',
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
      generate_default: false,
      disableSubmit: false
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
          //
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
      this.dataModel.generate_default = this.generate_default
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
    }
  }
}
</script>

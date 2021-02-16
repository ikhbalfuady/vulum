
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">{{title}}</span><br>
            <span class="text-h5 bold text-primary capital">{{Meta.name}}</span>
          </div>
        </div>
      </div>

      <q-card class="box-shadow mv-2">
          <q-card-section>
            <q-form @submit="submit">
              <q-card-section class="row">

                <div class="col-12 col-sm-6 col-md-6 pv ph"
                  v-for="(props, index) in dataModel" :key="index">
                  <q-input v-model="dataModel[index]" :label="index" dense filled square />
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
import { Helper } from '../../services/helper'
import { Model } from '../../services/model'
import Api from '../../services/Api'
import Meta from './meta'

export default {
  name: 'UserSessions',
  data () {
    return {
      Meta,
      API: new Api(),
      // default data
      dataModel: Model.UserSessions(),
      title: 'Add',
      rules: {
        permission: {}
      },
      disableSubmit: false
    }
  },

  created () {
    this.initTopBar()
    this.initialize()
  },

  mounted () {

  },

  methods: {

    initialize () {
      this.rules.permission = this.$ModuleConfig.getDefault('permission', this.Meta.module)
      console.log('permission', this.rules.permission)
      this.onRefresh()
    },

    onRefresh () {
      this.initRules()
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    initRules () {
      this.$ModuleConfig.loadAppConfig((status, data) => {
      }, this.Meta.module)

      this.$ModuleConfig.getCurrentPermission((status, data) => {
        var access = data[this.Meta.module]
        if (access !== undefined) {
          this.rules.permission = access
          if (!access.view) this.$router.push({ name: '401' }) // view module
          else {
            var params = this.$route.params
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
      //   Helper.showAlert('Nama Kosong!', 'Nama harus di isi!')
      //   return false
      // } else return true
      return true
    },

    save () {
      Helper.loadingOverlay()
      this.API.post(this.Meta.module, this.dataModel, (status, data, message, response, full) => {
        Helper.loadingOverlay(false)
        if (status === 200) {
          this.messageSubmit('Saving', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    update () {
      Helper.loadingOverlay()
      this.API.put(this.Meta.module + '/' + this.dataModel.id, this.dataModel, (status, data, message, response, full) => {
        Helper.loadingOverlay(false)
        if (response.result === true && status === 200) {
          this.messageSubmit('Update', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    messageSubmit (titleAdd = '', msg) {
      Helper.showAlert(titleAdd + ' Succesfully', msg)
    }
  }
}
</script>

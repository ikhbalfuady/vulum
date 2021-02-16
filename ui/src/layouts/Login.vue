<template>
  <transition  appear
    enter-active-class="animated fadeIn "
    leave-active-class="animated fadeOut"
  >
  <q-layout view="lHh Lpr lFf" class="">
    <q-page-container class="bg-grad" >

      <div class=" items-center" style="height: 150px">

        <div class="row colpad">
          <div class="ph pv-2 mt-5 col-12 offset-md-4 col-md-4 offset-sm-3 col-sm-6">
            <div class="bg-white form-login shadow-8" >

              <div style="text-align: center;padding-top: 3px;" v-touch-hold.mouse="ApiRoot" class="mt-3 pb-2">

                <q-img src="/assets/icons/digipro.png" spinner-color="white" style="width: 220px;" >
                    <template v-slot:error>
                      <div class="absolute-full bg-white flex flex-center text-grey-4 text-caption text-center">
                        <q-icon name="broken_image" style="font-size: 42px;"/>
                      </div>
                    </template>
                </q-img>
              </div>

              <q-form class="form-area row" @submit.prevent="login" >

                <div class="col-12 col-md-12 pv-2 pb-1">
                    <q-input square filled v-model="dataModel.email" ref="email" placeholder="username / email" lazy-rules :rules="[
                      val => val !== null && val !== '' || 'username / email belum di isi!',
                      ]">
                        <template v-slot:prepend>
                        <q-icon name="person" />
                        </template>
                    </q-input>
                </div>

                <div class="col-12 col-md-12 pv-2 pb-1" style="margin-top: 0;">
                  <q-input placeholder="Password" square filled v-model="dataModel.password" :type="isPwd ? 'password' : 'text'"  lazy-rules :rules="[
                      val => val !== null && val !== '' || 'Password belum di isi!',
                      ]">
                      <template v-slot:prepend>
                        <q-icon name="lock" />
                      </template>
                    <template v-slot:append>
                      <q-icon
                        :name="isPwd ? 'visibility_off' : 'visibility'"
                        class="cursor-pointer"
                        @click="isPwd = !isPwd"
                      />
                    </template>
                  </q-input>
                </div>

                <div class="col-12 col-md-12 pv-2 pb-1">
                  <q-btn type="submit" color="primary" class="full-width" label="Login" size="lg" :loading="loading" >
                    <template v-slot:loading>
                      <q-spinner-facebook />
                    </template>
                  </q-btn>
                </div>

                <div class="col-12 text-center text-caption pointer text-grey-9" @click="openSite()">
                  Powered By : <br>
                  <img src="https://www.diginomic.id/public/images/logo/logo.png" style="width: 100px" alt="sopeus.com"  />
                </div>
                <div class="col-12 col-md-12 pb-2 text-center">
                  <small class="bold text-grey-7">
                    <span class="">App {{version}} </span> -
                    <span class="">Backend {{version_be}}</span>

                  </small>
                </div>

              </q-form>
            </div>
          </div>
        </div>

      </div>

    </q-page-container>
  </q-layout>
  </transition>
</template>

<script>
import { Helper } from '../services/helper'
import { Config } from '../config'
import Api from '../services/Api'

export default {
  name: 'login',
  data () {
    return {
      // required config var
      pageName: 'Digipro',
      API: new Api(this.$router),
      // custom var
      appName: '',
      loading: false,
      dataModel: {
        email: '',
        password: ''
      },
      isPwd: true,
      disableSubmit: false,
      version: Config.version(),
      version_be: '',
      appLogo: '../assets/images/logo.png'
    }
  },

  beforeCreate () {
    // this.$ModuleConfig.init(false, 'login')

  },

  mounted () {
    // init default
    this.$ModuleConfig.getCurrentAppConfig((success, data) => {
      if (success) {
        this.$ModuleConfig.loadDefaultPermission((success, data) => {
          Helper.console('SUCCESS INIT : App Config & Permission')
        }, 'Login')
      }
    }, 'Login')
  },

  methods: {

    auth () {
      this.$ModuleConfig.init(true, 'login sucess')
      this.$router.push({ name: 'digipro' })
    },

    login () {
      this.disableSubmit = true
      Helper.loadingOverlay()
      this.API.cache = false
      this.API.post('user/login', this.dataModel,
        (status, data, message, response, full) => {
          Helper.loadingOverlay(false)
          console.assert({ status, data, message, response, full })
          if (status === 200) {
            Config.credentials(data)
            this.auth()
            this.disableSubmit = false
          } else if (status === 599) {
            Helper.showAlert('SERVER ERROR', 'Gagal menghubungkan ke SERVER, pastikan server bisa di akses atau hubungi Sopeus Support!')
          }
        }, {})
      // end
    },

    ApiRoot () {
      Config.setApiRoot()
    },

    ApiTmp () {
      Config.setApiTemp()
    },

    openSite () {
      this.$Helper.openLink('https://www.diginomic.id/')
    }
  }
}
</script>

<style>
</style>

import {
  LocalStorage,
  Dialog
} from 'quasar'
import { Helper } from './services/helper'

export const Config = {
  test () {
    console.log('Config berhasil di panggil')
  },

  appName () {
    return 'Vulum'
  },

  version () {
    return '2.0.1'
  },

  setApiRoot () {
    var api = Config.getApiRoot()
    Dialog.create({
      title: 'Set API Root',
      message: 'ex: http://diginomic.id/',
      prompt: {
        model: api,
        type: 'text' // optional
      },
      cancel: true,
      persistent: true
    }).onOk(data => {
      var val = data
      const last = val.charAt(val.length - 1)
      let endpoint = val
      if (last !== '/') endpoint = endpoint + '/'

      Config.saveApiRoot(endpoint)
      window.location.reload()
    }).onCancel(() => {
      // console.log('>>>> Cancel')
    }).onDismiss(() => {
      // console.log('I am triggered on both OK and Cancel')
    })
  },

  setApiTemp () {
    var api = Config.getApiTemp()
    Dialog.create({
      title: 'Set BMS Service url',
      message: 'ex: http://192.168.43.227/bms_service/',
      prompt: {
        model: api,
        type: 'text' // optional
      },
      cancel: true,
      persistent: true
    }).onOk(data => {
      var val = data
      const last = val.charAt(val.length - 1)
      let endpoint = val
      if (last !== '/') endpoint = endpoint + '/'

      Config.saveApiTemp(endpoint)
    }).onCancel(() => {
      // console.log('>>>> Cancel')
    }).onDismiss(() => {
      // console.log('I am triggered on both OK and Cancel')
    })
  },

  getApiRoot () {
    if (LocalStorage.has('apiroot') === false) {
      var url = 'http://bms-api.sopeus.com/'
      var icon = '<i aria-hidden="true" role="img" class="material-icons q-icon notranslate text-primary">settings</i>'
      Helper.showAlert('API Root Not Defined', 'Api Root aplikasi belum diatur di perangkat ini, sistem otomatis mengarahkan Api Root ke :  <br> <span class="text-primary">' + url + ' </span> <br> Klik icon roda gigi (' + icon + ') di kiri atas form login untuk mengatur Api Root. ')
      LocalStorage.set('apiroot', url)
    }
    return LocalStorage.getItem('apiroot')
  },

  saveApiRoot (val) {
    LocalStorage.set('apiroot', val)
  },

  saveApiTemp (val) {
    LocalStorage.set('apitmp', val)
  },

  getMsgCofirm (type) {
    if (type === 'delete') return 'Yakin akan menghapus data ?'
    else return 'Haloo..'
  },

  labelSearchProduk () {
    return 'Ketik 3 digit nama produk untuk mencari, gunakan awalan (:) untuk cari by kode & (~) untuk cari by alias ..'
  },

  currencyConfig () {
    var d = {
      decimal: ',',
      thousands: '.',
      prefix: '',
      suffix: '',
      precision: 2,
      masked: false,
      max: 16
    }
    return d
  },

  numberOnly () {
    var d = {
      decimal: ',',
      thousands: '.',
      prefix: '',
      suffix: '',
      precision: 0,
      masked: false,
      max: 16
    }
    return d
  },

  // credentials method
  credentials (saving) {
    if (saving === 'destroy') {
      const apiroot = this.getApiRoot()

      localStorage.clear()
      this.saveApiRoot(apiroot)
    } else if (saving !== undefined) {
      console.log('saving cre', saving)
      if (saving.token !== undefined) Helper.saveLdb('token', saving.token)
      // saving.token = ''
      // saving.remember_token = ''
      Helper.saveLdb('credentials', saving)
      return
    }

    // retreive data
    if (Helper.checkLdb('credentials')) {
      var credentials = Helper.getLdb('credentials')
      return credentials
    } else return false
  },

  scrollOptions (type) {
    // var thumbStyle = {
    //   right: '4px',
    //   borderRadius: '5px',
    //   backgroundColor: '#424242',
    //   width: '7px',
    //   opacity: 0.75
    // }
    // var barStyle = {
    //   right: '2px',
    //   borderRadius: '9px',
    //   backgroundColor: '#424242',
    //   width: '11px',
    //   opacity: 0.2
    // }

    // if (type === 'bar') return barStyle
    // else return thumbStyle
    return null
  },

  noOptionLabel (modeulName = 'data') {
    return 'Type 2 character to find ' + modeulName + '..'
  },

  actionMode () {
    var mode = 'PAGE'
    if (LocalStorage.has('actionmode') === false) {
      LocalStorage.set('actionmode', mode)
    }
    return LocalStorage.getItem('actionmode')
  }
}

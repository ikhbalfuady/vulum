/* eslint-disable indent */
/* eslint-disable no-unused-vars */
import { Helper } from './helper'
import Api from './Api'

export const ModuleConfig = {

  init (permission, from) {
    this.loadAppConfig((status, data) => {
      console.log('from')
    }, from)

    this.getCurrentPermissions((status, data) => {
      console.log('getCurrentPermissions', data)
    }, from)
  },

  loadAppConfig (callback, from = null) {
    if (from !== null) Helper.console('ModuleConfig[loadAppConfig]::' + from, '#17b360', '#1b2024')
    var ldbName = 'moduleConfig'
    var result = null
    var isSucccess = false
    if (Helper.checkLdb(ldbName)) {
      result = Helper.getLdb(ldbName)
      isSucccess = true
      callback(isSucccess, result)
    } else callback(isSucccess, result)
  },

  getCurrentAppConfig (callback, from = null) {
    if (from !== null) Helper.console('ModuleConfig[getCurrentAppConfig]::' + from, '#17b360', '#1b2024')
    var API = new Api()
    API.cache = false
    var isSucccess = false
    API.get('meta/config', (status, data, message, response, full) => {
      if (status === 200) {
        Helper.saveLdb('moduleConfig', data)
        isSucccess = true
        callback(isSucccess, data)
      } else {
        data = Helper.getLdb('moduleConfig')
        callback(isSucccess, data)
      }
    }, {})
  },

  getAppConfig (type, attr = null) {
    var name = 'moduleConfig'
    // if (!Helper.checkLdb(name)) this.init()
    var moduleConfig = Helper.getLdb(name)
    if (moduleConfig !== null) {
      var getType = moduleConfig[type]
      // console.log('getAppConfig', moduleConfig)
      if (getType === undefined) {
        console.error('AppConfi[' + type + '] not found!')
        return null
      } else {
        if (attr === null) {
          return getType
        } else {
          var getAttr = getType[attr]
          if (getAttr === undefined) {
            console.error('AppConfi[' + type + '].' + attr + ' not found!')
            return null
          } else {
            if (type === 'shop_info' && attr === 'logo') {
              if (getAttr === '' || getAttr === '-') return '../assets/images/logo.png'
              else return getAttr
            } else return getAttr
          }
        }
      }
    } else return null
  },

  permissionDB () {
    return 'permissions'
  },

  loadPermissions (router, mode = 'load', from = '') {
    if (from !== '') Helper.console('ModuleConfig[loadPermissions]::' + from, '#17b360', '#1b2024')
    var ldbName = this.permissionDB()
    if (Helper.checkLdb(ldbName)) {
      return Helper.getLdb(ldbName)
    } else {
      if (mode === 'load') {
        Helper.showAlert('Permission Needed', 'please login again, to continue!')
        router.push({ name: 'login' })
      }
      return false
    }
  },

  getCurrentPermissions (callback, from = null) {
    if (from !== null) Helper.console('ModuleConfig[getCurrentPermissions]::' + from, '#17b360', '#1b2024')
    var API = new Api()
    API.cache = false
    var isSucccess = false
    var ldbName = this.permissionDB()
    API.get('me/permissions', (status, data, message, response, full) => {
      if (status === 200) {
        // console.log('getPerm', data)
        Helper.saveLdb(ldbName, data)
        isSucccess = true
        callback(isSucccess, data)
      } else {
        callback(isSucccess, Helper.getLdb(ldbName))
      }
    }, {})
  },

  checkPermission (router, moduleName) {
    var permission = this.loadPermissions(router, 'check')
    var check = Helper.findObjectByKey(permission, 'slug', moduleName)
    if (check) return true
    else return false
  }

}

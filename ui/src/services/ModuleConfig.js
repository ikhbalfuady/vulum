/* eslint-disable indent */
/* eslint-disable no-unused-vars */
import { Helper } from './helper'
import Api from './Api'

export const ModuleConfig = {

  init (permission, from) {
    this.loadAppConfig((status, data) => {
      if (permission) {
        this.loadPermission((status, data) => {}, from)
      }
    }, from)
  },

  loadAppConfig (callback, from = null) {
    if (from !== null) Helper.console('ModuleConfig[loadAppConfig]::' + from, '#17b360', '#1b2024')

    var hasComplete = false
    var ldbName = 'moduleConfig'
    var result = null
    if (Helper.checkLdb(ldbName)) {
      result = Helper.getLdb(ldbName)
      // console.log(result)
      hasComplete = true
      callback(hasComplete, result)
    } else callback(hasComplete, result)
  },

  getCurrentAppConfig (callback, from = null) {
    if (from !== null) Helper.console('ModuleConfig[getCurrentAppConfig]::' + from, '#17b360', '#1b2024')
    var isSuccess = true
    var API = new Api()
    API.cache = false
    API.get('meta/config', (status, data, message, response, full) => {
      if (status === 200) {
        Helper.saveLdb('moduleConfig', data)
        callback(isSuccess, data)
      } else {
        data = Helper.getLdb('moduleConfig')
        isSuccess = false
        callback(isSuccess, data)
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

  loadPermission (callback, from = null) {
    if (from !== null) Helper.console('ModuleConfig[loadPermission]::' + from, '#c29e00', '#1b2024')

    var hasComplete = false
    var ldbName = 'staffPermission'
    var result = null
    if (Helper.checkLdb(ldbName)) {
      result = Helper.getLdb(ldbName)
      // console.log(result)
      hasComplete = true
      callback(hasComplete, result)
    } else callback(hasComplete, result)
  },

  getCurrentPermission (callback, from = null) {
    if (from !== null) Helper.console('ModuleConfig[getCurrentPermission]::' + from, '#c29e00', '#1b2024')
    var hasComplete = true
    var API = new Api()
    // API.cache = false
    API.get('staffs/permission', (status, data, message, response, full) => {
      // console.log('loadperm - ' + from, data)
      var result = data
      Helper.saveLdb('staffPermission', data)
      if (status === 200) {
        callback(hasComplete, result)
      } else {
        hasComplete = true
        callback(hasComplete, result)
      }
    }, {})
  },

  getPermission (type, attr = null) {
    if (type === 'home') return true
    else if (type === 'notification') return true
    else if (type === 'data') return true
    else if (type === 'apiconfig') return true
    else if (type === 'logout') return true
    else {
      var name = 'staffPermission'
      // if (!Helper.checkLdb(name)) this.init()
      var moduleConfig = Helper.getLdb(name)
      if (moduleConfig !== null) {
        var getType = moduleConfig[type]
        if (getType === undefined) {
          console.error('Permission[' + type + '].' + attr + ' not found!')
          return false
        } else {
          if (attr === null) {
            return getType
          } else {
            var getAttr = getType[attr]
            if (getAttr === undefined) {
              console.error('Permission[' + type + '].' + attr + ' not found!')
              return false
            } else {
              return getAttr
            }
          }
        }
      } else {
        return false
      }
      //
    }
  },

  getDefault (type, mode = null, condition = null) {
    var list = Helper.getLdb('moduleConfig')
    if (type === 'permission') list = Helper.getLdb('staffPermission')

    if (list === null) {
      if (type === 'permission') {
        return {
          add: true,
          edit: true,
          delete: true,
          view: true
        }
      } else return null
    }

    var _mode = list[mode]
    if (mode === null) return list
    else if (_mode === undefined) {
      console.error('[' + type + '].' + mode + ' not found!'); return false
    } else {
      if (condition !== null) {
        var cond = _mode[condition]
        if (cond === undefined) {
          console.error('[' + type + '].' + mode + '::' + condition + ' not found!')
          return false
        } else return cond
      } else return _mode
    }
  },

  // kebutuhan init default ketika login
  loadDefaultPermission (callback, from = null, userType = 'staff') {
    if (from !== null) Helper.console('ModuleConfig[loadDefaultPermission]::' + from, '#c29e00', '#1b2024')
    var isSuccess = true
    var API = new Api()
    API.cache = false
    var endpoint = 'users/permission'
    if (userType === 'staff') endpoint = 'staffs/permission'
    API.get(endpoint, (status, data, message, response, full) => {
      if (status === 200) {
        Helper.saveLdb('defaultPermission', data)
        callback(isSuccess, data)
      } else {
        data = Helper.getLdb('defaultPermission')
        isSuccess = false
        callback(isSuccess, data)
      }
    }, {})
  }

}

/* eslint-disable no-unused-vars */
import axios from 'axios'
import { Notify } from 'quasar'
import { Helper } from './helper'
import { Config } from '../config'
import { ModuleConfig } from './ModuleConfig'

export default class Api {
  constructor () {
    const cache = false
    const offlineData = false
    const apiService = axios.create({})
    apiService.interceptors.response.use(this.handleSuccess, this.handleError)
    this.cache = cache
    this.offlineData = offlineData
    this.apiService = apiService
    this.loading = Object
    this.showMessage = false
  }

  setToken (token) {
    this.apiService.defaults.headers.common.Authorization = `Bearer ${token}`
    this.apiService.interceptors.request.use(config => {
      config.headers.post.Authorization = `Bearer ${token}`
      return config
    })
  }

  setFormMultipart () {
    this.apiService.defaults.headers['Content-Type'] = 'application/x-www-form-urlencoded'
  }

  showLoading (Message, showMessage) {
    // set global show message variable
    this.showMessage = showMessage
    if (!showMessage) { return }
    if (Message === '' || Message === undefined) Message = 'Please wait ...'

    this.loading = Notify.create({
      message: Message
    })
  }

  hideLoading () {
    setTimeout(() => {
      this.loading()
    }, 500)
  }

  get (path, callback, defaultValue = null, loadingMessage, showMessage = false) {
    return this.requestValidate('GET', path, null, callback, defaultValue, loadingMessage, showMessage, false)
  }

  post (path, payload, callback, defaultValue = null, loadingMessage, showMessage = false, isMultipart = false) {
    return this.requestValidate('POST', path, payload, callback, defaultValue, loadingMessage, showMessage, isMultipart)
  }

  put (path, payload, callback, defaultValue = null, loadingMessage, showMessage = false, isMultipart = false) {
    return this.requestValidate('PUT', path, payload, callback, defaultValue, loadingMessage, showMessage, isMultipart)
  }

  delete (path, callback, defaultValue = null, loadingMessage, showMessage = false, isMultipart = false) {
    return this.requestValidate('DELETE', path, null, callback, defaultValue, loadingMessage, showMessage, false)
  }

  requestValidate (type, path, payload, callback, defaultValue = null, loadingMessage, showMessage = false, isMultipart = false) {
    // console.log('Request Cache ' + path + ' : ', this.cache)
    // callback default is : (status, data, message, response)
    //
    this.apiService.defaults.headers['Access-Control-Allow-Origin'] = '*'
    if (isMultipart) this.setFormMultipart()
    else this.apiService.defaults.headers['Content-Type'] = 'application/json'
    this.showLoading(loadingMessage, showMessage)

    // default configuration
    var apiRoot = Config.getApiRoot()
    if (this.offlineData) apiRoot = Config.getApiTemp()
    this.offlineData = false

    var config = {
      method: type,
      url: apiRoot + path,
      responseType: 'json'
    }

    if (path !== 'login' && this.offlineData === false) {
      var token = Helper.getLdb('token')
      Object.assign(config, { headers: { Authorization: `Bearer ${token}` } })
    }

    if (type !== 'DELETE' || type !== 'GET') Object.assign(config, { data: payload }) // attach payload for data

    return this.apiService.request(config).then((response) => {
      var reCompile = {
        status: response.status,
        data: response.data.data,
        message: response.data.message,
        response: response
      }
      var full = this.validateResponseData(reCompile) // validate data to get notif if have error
      if (full === false) full = null
      // saving cache
      if (this.cache === true && response.status === 200) Helper.saveLdb(full.config.method + ':' + full.config.url, reCompile.data)

      // config data cache
      var returnData = response.data.data
      if (response.status !== 200) returnData = this.getCache(full, defaultValue)

      callback(response.status, returnData, response.data.message, response.data, full)
      this.hideLoading()
      // console.info('Request: success')
      // end processing
    }).catch((error) => {
      // console.table(error)

      var reCompile = {
        request: {
          responseURL: 'error'
        },
        status: 599,
        data: null,
        message: error.message,
        response: { result: false }
      }

      var _response = reCompile

      if (error !== null && error.response !== undefined) _response = error.response

      if (error.message !== 'Network Error') {
        var url = '<small class="text-primary code">' + _response.request.responseURL + '</small> <br>'
        var msgErr = error.message
        if (_response.data !== null && typeof _response.data === 'object') msgErr = _response.data.message

        if (_response.status !== 500) url = ''
        reCompile = {
          status: _response.status,
          data: _response.data,
          message: url + msgErr,
          response: _response
        }
        var full = this.validateResponseData(reCompile) // validate data to get notif if have error
        if (full === false) full = null

        callback(reCompile.status, reCompile.data, reCompile.message, reCompile.response, error)
        this.hideLoading()
        this.showLoading('Request Failed', error)
      } else {
        var usingCache = ''
        if (this.cache) usingCache = ' --[USING CACHE DATA]'
        this.showLoading('Failed to connect Server API' + usingCache + '.', error)

        callback(reCompile.status, this.getCache(error, defaultValue), reCompile.message, reCompile.response, reCompile)
      }

      // console.error('Request: catch')
    })
  }

  request (type, path, payload, callback, loadingMessage, showMessage = true, isMultipart = false) {
    // callback default is : (status, data, message, response)
    // costum configuration
    if (isMultipart) this.setFormMultipart()
    this.showLoading(loadingMessage, showMessage)

    // default configuration
    var config = {
      method: type,
      url: Config.getApiRoot() + path,
      responseType: 'json'
    }

    if (type !== 'DELETE' || type !== 'GET') Object.assign(config, { data: payload }) // attach payload for data

    return this.apiService.request(config).then((response) => {
      callback(response.status, response.data.data, response.data.message, response.data, response)
      this.hideLoading()
    }).catch((error) => {
      callback(error.response.status, error.response.data, error.response.data.error, error.response, error)
      this.hideLoading()
    })
  }

  getReport (path, callback) {
    // callback default is : (status, data, message, response)
    //
    this.apiService.defaults.headers['Access-Control-Allow-Origin'] = '*'
    this.apiService.defaults.headers['Content-Type'] = 'application/json'

    // default configuration
    var config = {
      method: 'GET',
      url: Config.getApiRoot() + path,
      responseType: 'json'
    }

    var token = Helper.getLdb('token')
    Object.assign(config, { headers: { Authorization: `Bearer ${token}` } })

    return this.apiService.request(config).then((response) => {
      callback(response.status, response.data.data, response.data.message, response.data, response)
      this.hideLoading()
    }).catch((error) => {
      // console.table(error)
      var reCompile = {
        request: {
          responseURL: 'error'
        },
        status: 599,
        data: null,
        message: error.message,
        response: { result: false }
      }
      callback(reCompile.status, reCompile.data, reCompile.message, reCompile.response, reCompile)
    })
  }

  validateResponseData (reCompile) {
    // console.log('validateResponse', reCompile)
    return this.validateByStatus(reCompile)
  }

  validateByStatus (reCompile) {
    if (reCompile === null) {
      return null
    } else if (reCompile.status === 200) {
      return reCompile.response
    } else if (reCompile.status === 400) {
      Helper.showAlert('Opps!', reCompile.message)
      return false
    } else if (reCompile.status === 401) {
      Helper.showAlert('Unauthorized', reCompile.message + '<br> Please Login to continue')
      window.location = '/login'
      return false
    } else if (reCompile.status === 404) {
      Helper.showAlert('Resource 404', reCompile.message)
      return false
    } else if (reCompile.status === 403) {
      Helper.showAlert('Forbidden 403', reCompile.message)
      return false
    } else if (reCompile.status === 500) {
      if (reCompile.data !== null) {
        if (reCompile.data.data === 'info') Helper.showNotif('Server ERROR', reCompile.data.message)
        else if (reCompile.data.data === 'skip') console.error('SERVER ERROR', reCompile.data.message)
        else Helper.showAlert('Server ERROR', reCompile.message)
      } else Helper.showAlert('Server ERROR', reCompile.message)
      return false
    } else return reCompile.data
  }

  getCache (full, defaultVaule = '') {
    // console.log('getCache :', this.cache)
    var cache = this.cache
    var cacheCOnfig = ModuleConfig.getAppConfig('app_config', 'cache_data')
    if (cacheCOnfig !== null) cache = cacheCOnfig

    if (cache) {
      if (full.config !== undefined) {
        var checkCacheLDB = Helper.checkLdb(full.config.method + ':' + full.config.url)
        var res = Helper.getLdb(full.config.method + ':' + full.config.url)
        if (!checkCacheLDB) res = defaultVaule
      } else res = defaultVaule

      // console.log('getCache:', typeof defaultVaule)
      // console.log('getCache LDB ' + full.config.url + ' :', checkCacheLDB)
      // console.log('getCache FILTER ' + full.config.url + ' :', res)
      return res
    } else {
      console.warn('API.getCache: false')
      return null
    }
  }

  getTmpData () {

  }
}

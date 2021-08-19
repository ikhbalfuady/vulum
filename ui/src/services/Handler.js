import { Helper } from './helper'
import Api from './Api'
import {
  LocalStorage
} from 'quasar'

export const Handler = {

  // default config table
  table (columns = []) {
    var table = {
      search: '',
      data: [],
      searchBy: [],
      searchBySelected: null,
      columns: [
        ...columns
        // { name: 'id', label: 'id', field: 'id', align: 'left', style: 'width: 20px' }
      ],
      pagination: {
        page: 1,
        rowsPerPage: 15,
        rowsNumber: 0
      },
      selected: []
    }

    for (const col of table.columns) {
      if (col.name !== 'action') table.searchBy.push(col)
    }
    if (table.searchBy.length > 0) table.searchBySelected = table.searchBy[0]

    return table
  },

  tableSelectedLabel (table) {
    return table.selected.length === 0 ? '' : `${table.selected.length} record${table.selected.length > 1 ? 's' : ''} selected of ${table.data.length}`
  },

  permissions (_this, currentPage, meta, callback) {
    Helper.console(`Handler[permissions]:: ${meta.module}-${currentPage}`, '#17b360', '#1b2024')
    var API = new Api()
    API.cache = false
    var isSucccess = false

    API.get('me/permissions', (status, data, message, response, full) => {
      if (status === 200) {
        // collecting permissions
        for (const perm in meta.permission) {
          meta.permission[perm] = (Helper.findObjectByKey(data, 'slug', `${meta.module}-${perm}`)) ?? false
        }

        // checking permissions in curent page
        var allow = meta.permission[currentPage]
        if (!allow) _this.$router.push({ name: '403' }) // force redirect to forbiden page

        // send default
        isSucccess = true
        callback(isSucccess, meta.permission)
      } else {
        callback(isSucccess, null)
      }
    }, {})
  },

  toObjectSelect (arr) {
    var temp = []
    for (let i = 0; i < arr.length; i++) {
      temp.push({ id: arr[i], name: arr[i] })
    }
    return temp
  },

  formMode (_this) {
    var params = _this.$route.params
    var res = {
      mode: 'create',
      params: params
    }
    if (_this.$Helper.checkParams(params)) { // validate params is set
      if (params.id !== undefined) res.mode = 'update'
      else _this.backToRoot()
    }
    return res
  },

  getParamId (_this) {
    var params = _this.$route.params
    var res = null
    if (_this.$Helper.checkParams(params)) { // validate params is set
      if (params.id !== undefined) res = params.id
      else _this.backToRoot()
    }
    return res
  },

  drawer (val = '') {
    if (LocalStorage.has('openDrawer') === false) {
      LocalStorage.set('openDrawer', true)
    }

    var mode = LocalStorage.getItem('openDrawer')
    mode = !mode

    if (val !== '') mode = val
    // console.log('handler drawers', val, mode)

    LocalStorage.set('openDrawer', mode)
    return mode
  },

  drawerMini (val = '') {
    if (LocalStorage.has('miniModeMenu') === false) {
      LocalStorage.set('miniModeMenu', true)
    }
    if (val !== '') LocalStorage.set('miniModeMenu', val)
    return LocalStorage.getItem('miniModeMenu')
  },

  notifications (save = null) {
    if (LocalStorage.has('notifications') === false) {
      LocalStorage.set('notifications', [])
    }
    if (save) LocalStorage.set('notifications', save)
    return LocalStorage.getItem('notifications')
  },

  menu (save = null) {
    if (LocalStorage.has('menu') === false) {
      LocalStorage.set('menu', [])
    }
    if (save) LocalStorage.set('menu', save)
    return LocalStorage.getItem('menu')
  },

  viewList (data, addExclude = []) {
    var exlude = [
      ...addExclude,
      'created_at',
      'updated_at',
      'deleted_at',
      'created_by',
      'updated_by',
      'deleted_by',
      'created_by_user',
      'updated_by_user',
      'deleted_by_user'
    ]
    var list = {}
    for (const key in data) {
      console.log(key, data)
      if (exlude.indexOf(key) < 0) list[key] = data[key]
    }
    // console.log('list', list)
    return list
  }
}

import { Helper } from './helper'
import Api from './Api'

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
  }

}

<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-2">
        <div class="col-12 col-sm-3 col-md-5 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">Master {{Meta.name}}</span><br>
            <span class="text-h5 bold text-primary capital">{{Meta.name}}</span>
          </div>
        </div>

        <div class="col-6 col-sm-3 col-md-2 pb-1 pr-1 ">
          <q-select :options="select.status" dense outlined  @input="val => { onRefresh() }"
            v-model="dataModel.status" label="Status"
            class="bg-white box-shadow" style="border-radius:5px; " transition-show="jump-up" transition-hide="jump-down"
            >
          </q-select>
        </div>

        <div class="col-6 col-sm-3 col-md-2 pb-1 pr-1-5">
          <q-select :options="table.searchBy" dense outlined
            v-model="dataModel.searchBy" label="Searc By" class="bg-white box-shadow"
            style="border-radius:5px; " transition-show="jump-up" transition-hide="jump-down" />
        </div>

        <div class="col-12 col-sm-3 col-md-3 pb-1 pr-1-5">
          <q-input debounce="300" placeholder="Search..." v-model="table.search" outlined dense class="fix-after bg-white box-shadow " style="border-radius:5px; " >
              <template v-slot:append>
                <q-icon v-if="table.search !== ''" name="close" @click="table.search = ''" class="cursor-pointer" />
                <q-icon name="search" />
              </template>
            </q-input>
        </div>

      </div>

      <div class="q-pa-md">
        <q-table class="box-shadow th-lg "
          title="Treats"
          :data="table.data"
          :columns="table.columns"
          row-key="id"
          :selected-rows-label="getTableSelected"
          selection="multiple"
          :pagination.sync="table.pagination"
          :loading="table.loading"
          :filter="table.search"
          @request="getList"
          :rows-per-page-options="[8, 16, 20, 50, 75, 100]"
          :selected.sync="table.selected"
          binary-state-sort
        >

          <template v-slot:body-cell-action="props">
            <q-td :props="props">
              <q-btn v-if="rules.permission.update" class="bg-soft" dense round flat color="green" @click="edit(props.row)" icon="edit"></q-btn>
              <q-btn class="bg-soft" dense round flat color="primary" @click="detail(props.row)" icon="visibility"></q-btn>
            </q-td>
          </template>

          <template v-slot:no-data="{icon}">
            <div class="full-width row flex-center text-primary q-gutter-sm">
              <q-icon size="2em" :name="icon" /><span class="bold text-h6"> Belum ada data {{Meta.name}} </span>
              <q-btn v-if="rules.permission.create" @click="add" unelevated outline color="primary" label="Buat baru" />
            </div>
          </template>

          <template v-slot:top>
            <div v-if="rules.permission.create" class="action animated zoomIn" >
              <q-btn @click="add" unelevated color="primary" class="capital  mr-1" icon="add" label="Add" />
            </div>

            <div v-if="rules.permission.delete && table.selected.length !== 0 ">
              <q-btn @click="deleteSelected" unelevated class="capital "
                :label="(dataModel.status === 'TRASH') ? 'Re-Activate' : 'Delete' "
                :color="(dataModel.status === 'TRASH') ? 'green' : 'negative' "
                :icon="(dataModel.status === 'TRASH') ? 'check' : 'delete' "
              />

            </div>

            <q-space />

          </template>

        </q-table>
      </div>

  </div>
</template>

<script>
import Meta from './meta'

export default {
  name: 'Menus',
  data () {
    return {
      Meta,
      API: this.$Api,
      // default data
      dataModel: {
        searchBy: null,
        status: 'ACTIVE'
      },
      rules: {
        permission: Meta.permission
      },
      table: {
        search: '',
        data: [],
        searchBy: [],
        columns: [
          { name: 'action', label: '#', align: 'left', style: 'width: 20px' },
          { name: 'id', label: 'id', field: 'id', align: 'left' },
          { name: 'parent_id', label: 'parent_id', field: 'parent_id', align: 'left' },
          { name: 'menu_item_id', label: 'menu_item_id', field: 'menu_item_id', align: 'left' },
          { name: 'master_menu_id', label: 'master_menu_id', field: 'master_menu_id', align: 'left' }

        ],
        pagination: {
          page: 1,
          rowsPerPage: 8,
          rowsNumber: 0
        },
        selected: [],
        inFocus: false
      },
      select: {
        status: ['ACTIVE', 'TRASH']

      }
    }
  },

  created () {
    this.initTopBar()
    this.$ModuleConfig.getCurrentPermissions((status, data) => {
      console.log('initPermissionPage:' + Meta.module, data)
      this.initialize()
    }, 'user-index')
  },

  mounted () {
    // generate filter search
    for (const col of this.table.columns) {
      if (col.name !== 'action') this.table.searchBy.push(col)
    }
    this.dataModel.searchBy = this.table.columns[1].name
  },

  methods: {

    checkPermission () {
      for (const perm in this.rules.permission) {
        this.rules.permission[perm] = this.$ModuleConfig.checkPermission(this.$router, this.Meta.module + '-' + perm)
      }
    },

    onRefresh () {
      this.refreshList()
    },

    initialize () {
      var viewAccess = this.$ModuleConfig.checkPermission(this.$router, this.Meta.module + '-browse')
      if (viewAccess) {
        this.checkPermission()
        this.onRefresh()
      } else {
        this.$router.push({ name: '403' })
      }
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    getTableSelected () {
      return this.table.selected.length === 0 ? '' : `${this.table.selected.length} record${this.table.selected.length > 1 ? 's' : ''} selected of ${this.table.data.length}`
    },

    refreshList () {
      this.getList({
        pagination: this.table.pagination,
        search: this.table.search
      })
    },

    getList (props) {
      this.$Helper.loading()
      this.table.loading = true
      this.table.selected = []
      const { page, rowsPerPage } = props.pagination
      const perpage = rowsPerPage === 0 ? this.table.pagination.rowsNumber : rowsPerPage

      var endpoint = this.Meta.module + '?table'
      endpoint = endpoint + '&page=' + page
      endpoint = endpoint + '&limit=' + perpage
      if (this.table.search !== '') endpoint = endpoint + '&search=' + this.dataModel.searchBy.field + ':' + this.table.search
      if (this.dataModel.status === 'TRASH') endpoint = endpoint + '&trash=true'

      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loading(false)
        this.table.loading = false
        if (status === 200) {
          // inject data
          this.table.data.splice(0, this.table.data.length, ...data.data)
          // updating table
          this.table.pagination.rowsNumber = data.total
          this.table.pagination.page = page
          this.table.pagination.rowsPerPage = perpage
        }
      })
    },

    add () {
      this.$router.push({ name: this.Meta.module + '-create' })
    },

    edit (data) {
      this.$router.push({ name: this.Meta.module + '-update', params: data })
    },

    detail (data) {
      this.$router.push({ name: this.Meta.module + '-detail', params: data })
    },

    deleteSelected () {
      var that = this
      var totalData = this.table.selected.length
      var type = 'Delete'
      if (this.dataModel.status === 'TRASH') type = 'Restore'
      this.$q.dialog({
        title: type + ' ' + totalData + ' data selected',
        message: 'Are you sure want to ' + type + ' data ' + this.Meta.name + ' ?',
        persistent: true,
        ok: type,
        cancel: 'Cancel'
      }).onOk(() => {
        that.deleteDataSelected(type)
      }).onCancel(() => {
        // action
      }).onDismiss(() => {
        // action
      })
    },

    deleteDataSelected (type) {
      for (var row of this.table.selected) {
        if (type === 'Delete') this.delete(row.id)
        else this.restore(row.id)
      }
      this.table.selected = []
      setTimeout(() => { this.onRefresh() }, 500)
    },

    delete (id) {
      this.$Helper.loading()
      this.API.delete(this.Meta.module + '/' + id, (status, data, message, response, full) => {
        this.$Helper.loading(false)
        if (status === 200) this.$Helper.showToast(message)
      })
    },

    restore (id) {
      this.$Helper.loading()
      this.API.put(this.Meta.module + '/' + id + '/restore', (status, data, message, response, full) => {
        this.$Helper.loading(false)
        if (status === 200) this.$Helper.showToast(message)
      })
    }

  }
}
</script>

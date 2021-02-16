<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">Master Data</span><br>
            <span class="text-h5 bold text-primary capital">{{Meta.name}}</span>
          </div>
        </div>

        <div class="col-6 col-sm-3 col-md-2 pb-1 pr-1">
          <q-select :options="table.searchBy" dense outlined
            v-model="dataModel.searchBy" label="Searc By" class="bg-white box-shadow"
            style="border-radius:5px; " transition-show="jump-up" transition-hide="jump-down" />
        </div>

        <div class="col-6 col-sm-6 col-md-3 pb-1 pr-1-5">
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
              <q-btn v-if="rules.permission.edit" class="bg-soft" dense round flat color="green" @click="edit(props.row)" icon="edit"></q-btn>
              <q-btn class="bg-soft" dense round flat color="primary" @click="detail(props.row)" icon="visibility"></q-btn>
            </q-td>
          </template>

          <template v-slot:no-data="{icon}">
            <div class="full-width row flex-center text-primary q-gutter-sm">
              <q-icon size="2em" :name="icon" /><span class="bold text-h6"> Belum ada data {{Meta.name}} </span>
              <q-btn v-if="rules.permission.add" @click="add" unelevated outline color="primary" label="Buat baru" />
            </div>
          </template>

          <template v-slot:top>
            <div class="action animated zoomIn" >
              <q-btn @click="add" unelevated color="primary" class="capital  mr-1" icon="add" label="Add" />
            </div>

            <div v-if="rules.permission.delete && table.selected.length !== 0 ">
              <q-btn @click="deleteSelected" unelevated class="capital " label="Delete"
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
import { Helper } from '../../services/helper'
import Api from '../../services/Api'
import Meta from './meta'

export default {
  name: 'UserSessions',
  data () {
    return {
      Meta,
      API: new Api(),
      // default data
      dataModel: {
        searchBy: null
      },
      rules: {
        permission: {}
      },
      table: {
        search: '',
        data: [],
        currentPage: 5,
        searchBy: [],
        columns: [
          { name: 'action', label: '#', align: 'left', style: 'width: 20px' },
          { name: 'id', label: 'id', field: 'id', align: 'left' },
          { name: 'user_id', label: 'user_id', field: 'user_id', align: 'left' },
          { name: 'token', label: 'token', field: 'token', align: 'left' },
          { name: 'ip', label: 'ip', field: 'ip', align: 'left' },
          { name: 'agent', label: 'agent', field: 'agent', align: 'left' },
          { name: 'platform', label: 'platform', field: 'platform', align: 'left' }

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
        status: ['ACTIVE', 'TRASH', 'ALL']

      }
    }
  },

  created () {
    this.initTopBar()
    this.initialize()
  },

  mounted () {
    // generate filter search
    for (const col of this.table.columns) {
      if (col.name !== 'action') this.table.searchBy.push(col)
    }
    this.dataModel.searchBy = this.table.columns[1].name
  },

  methods: {

    onRefresh () {
      this.initRules()
    },

    initialize () {
      this.rules.permission = this.$ModuleConfig.getDefault('permission', this.Meta.module)
      this.onRefresh()
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    initRules () {
      this.$ModuleConfig.loadAppConfig((status, data) => {
        // code
      }, this.Meta.module)

      this.$ModuleConfig.getCurrentPermission((status, data) => {
        var access = data[this.Meta.module]
        if (data[this.Meta.module] !== undefined) {
          this.rules.permission = access
          if (!access.view) this.$router.push({ name: '401' }) // view module
          else {
            this.refreshList()
          }
        } else this.$router.push({ name: '401' })
      }, this.Meta.module)
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
      Helper.loading()
      this.table.loading = true
      this.table.selected = []
      const { page, rowsPerPage } = props.pagination
      const perpage = rowsPerPage === 0 ? this.table.pagination.rowsNumber : rowsPerPage

      var endpoint = this.Meta.module + '?table'
      endpoint = endpoint + '&page=' + page
      endpoint = endpoint + '&limit=' + perpage
      if (this.table.search !== '') endpoint = endpoint + '&search=' + this.dataModel.searchBy.field + ':' + this.table.search

      this.API.get(endpoint, (status, data, message, response, full) => {
        Helper.loading(false)
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
      this.$router.push({ name: this.Meta.module + '-add' })
    },

    edit (data) {
      this.$router.push({ name: this.Meta.module + '-edit', params: data })
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
        that.deleteDataSelected()
      }).onCancel(() => {
        // action
      }).onDismiss(() => {
        // action
      })
    },

    deleteDataSelected () {
      for (var row of this.table.selected) {
        this.delete(row.id)
      }
      this.table.selected = []
      setTimeout(() => { this.onRefresh() }, 500)
    },

    delete (id) {
      Helper.loading()
      this.API.delete(this.Meta.module + '/' + id, (status, data, message, response, full) => {
        Helper.loading(false)
        if (status === 200) Helper.showToast(message)
      })
    }

  }
}
</script>

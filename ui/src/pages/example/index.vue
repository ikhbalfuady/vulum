<template >

  <div class="root bg-soft">

    <!-- drawer & top menu -->
    <top-menu :data="Meta" />
    <side-menu :data="Meta" />

    <!-- Header Title -->
    <header-title :meta="Meta" cofig-label="Settings" cofig-icon="settings" >
      <template v-slot:config>
        <vl-select col="12" label="Status" v-model="dataModel.status" :options="select.status" @input="val => { onRefresh() }"/>
        <vl-select col="12" label="Searc By" v-model="table.searchBySelected" :options="table.searchBy" />
      </template>
      <template v-slot:right>
        <search-table v-model="table.search" :table="table" />
      </template>
    </header-title>

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
            <q-btn v-if="Meta.permission.update" class="bg-soft" dense round flat color="green" @click="edit(props.row)" icon="edit"><q-tooltip>Edit</q-tooltip></q-btn>
            <q-btn class="bg-soft" dense round flat color="primary" @click="detail(props.row)" icon="visibility"><q-tooltip>View</q-tooltip></q-btn>
          </q-td>
        </template>

        <template v-slot:no-data="{icon}">
          <div class="full-width row flex-center text-primary q-gutter-sm">
            <q-icon size="2em" :name="icon" /><span class="bold text-h6"> There are no data {{Meta.name}} yet</span>
          </div>
        </template>

        <template v-slot:top>
          <div v-if="Meta.permission.create" class="action animated zoomIn" >
            <q-btn @click="add" unelevated color="primary" class="capital  mr-1" icon="add" label="Add" />
          </div>

          <div v-if="Meta.permission.delete && table.selected.length !== 0 ">
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

    <Modal :config="modal">
      <Form v-if="modal.mode === 'form'" :from-modal="modal" />
      <Detail v-if="modal.mode === 'detail'" :from-modal="modal" />
    </Modal>
  </div>
</template>

<script>
import Meta from './meta'
import Form from './form'
import Detail from './detail'
import Modal from '../../components/Modal'

export default {
  name: 'Department',
  components: {
    Modal,
    Detail,
    Form
  },
  data () {
    return {
      Meta,
      API: this.$Api,
      // default data
      dataModel: {
        actionMode: 'PAGE',
        status: 'ACTIVE'
      },
      table: this.$Handler.table([
        { name: 'action', label: '#', align: 'left', style: 'width: 20px' },
        { name: 'name', label: 'name', field: 'name', align: 'left' },
        { name: 'slug', label: 'slug', field: 'slug', align: 'left' }
      ]),
      select: {
        status: this.$Handler.toObjectSelect(['ACTIVE', 'TRASH'])
      },
      modal: {
        show: false,
        mode: 'form',
        title: Meta.name,
        params: null
      }
    }
  },

  created () {
    this.initTopBar()
    this.$Handler.permissions(this, 'browse', Meta, (status, data) => {
      this.Meta.permission = data // update current permissions of this module
      this.onRefresh()
    })
  },

  mounted () {
    // generate filter search
    // for (const col of this.table.columns) {
    //   if (col.name !== 'action') this.table.searchBy.push(col)
    // }
    // this.dataModel.searchBy = this.table.columns[1].name
  },

  methods: {

    initTopBar () {
      // this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
      this.Meta.topBarMenu = [
        { name: 'Refresh', event: this.onRefresh },
        {
          name: 'Setting',
          sub: [
            { name: 'Refresh', event: this.onRefresh },
            { name: 'Reload', event: this.onRefresh },
            {
              name: 'Config',
              sub: [
                { name: 'c Refresh', event: this.onRefresh },
                { name: 'c Reload', event: this.onRefresh },
                {
                  name: 'Config 3',
                  sub: [
                    { name: 'c3 Refresh', event: this.onRefresh },
                    { name: 'c3 Reload', event: this.onRefresh },
                    {
                      name: 'Config 4',
                      sub: [
                        { name: 'c3 Refresh', event: this.onRefresh },
                        { name: 'c3 Reload', event: this.onRefresh }
                      ]
                    }
                  ]
                }
              ]
            }
          ]
        }
      ]
    },

    onRefresh () {
      this.refreshList()
    },

    getTableSelected () {
      return this.$Handler.tableSelectedLabel(this.table)
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
      if (this.table.search !== '') endpoint = endpoint + '&search=' + this.table.searchBySelected.field + ':' + this.table.search
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
      if (this.$Config.actionMode() === 'PAGE') this.$router.push({ name: 'add-' + this.Meta.module })
      else {
        this.modal.show = true
        this.modal.mode = 'form'
        this.modal.title = 'Create ' + Meta.name
        this.modal.params = null
      }
    },

    edit (data) {
      if (this.$Config.actionMode() === 'PAGE') this.$router.push({ name: 'edit-' + this.Meta.module, params: data })
      else {
        this.modal.show = true
        this.modal.mode = 'form'
        this.modal.title = 'Update ' + Meta.name
        this.modal.params = data
      }
    },

    detail (data) {
      if (this.$Config.actionMode() === 'PAGE') this.$router.push({ name: 'view-' + this.Meta.module, params: data })
      else {
        this.modal.show = true
        this.modal.mode = 'detail'
        this.modal.title = 'View ' + Meta.name
        this.modal.params = data
      }
    },

    deleteSelected () {
      var that = this
      var type = (this.dataModel.status === 'TRASH') ? 'Delete' : 'Restore'
      this.$q.dialog({
        title: `${type} ${this.table.selected.length} data selected`,
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

<template >

  <div class="root bg-soft">

    <!-- drawer & top menu -->
    <top-menu :data="Meta" />
    <side-menu :data="Meta" />

    <div class="q-pa-md">
      <q-table class="box-shadow th-lg "
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
            <q-btn v-if="rules.permission.create" @click="add" unelevated outline color="primary" label="Add New" />
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

    <modal :config="modal">
      <Form v-if="modal.mode === 'form'" :from-modal="modal" />
      <Detail v-if="modal.mode === 'detail'" :from-modal="modal" />
    </modal>

  </div>
</template>

<script>
import Meta from './meta'
import Form from './form'
import Detail from './detail'

export default {
  name: 'Department',
  components: {
    Detail,
    Form
  },
  data () {
    return {
      Meta,
      API: this.$Api,
      // default data
      dataModel: {
        status: 'ACTIVE'
      },
      table: this.$Handler.table([
        { name: 'action', label: '#', align: 'left', style: 'width: 20px' },
        { name: 'id', label: 'id', field: 'id', search: 'id', align: 'left' },
        { name: 'name', label: 'name', field: 'name', search: 'name', align: 'left' },
        { name: 'parent', label: 'parent', field: 'parent', search: 'parent', align: 'left' },
        { name: 'rate', label: 'rate', field: 'rate', search: 'rate', align: 'left' },
        { name: 'created_by', label: 'created_by', field: 'created_by', search: 'created_by', align: 'left' },
        { name: 'updated_by', label: 'updated_by', field: 'updated_by', search: 'updated_by', align: 'left' },
        { name: 'deleted_by', label: 'deleted_by', field: 'deleted_by', search: 'deleted_by', align: 'left' }

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
    //
  },

  methods: {

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
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
      if (this.$Handler.actionMode() === 'PAGE') this.$router.push({ name: 'add-' + this.Meta.module })
      else {
        this.modal.show = true
        this.modal.mode = 'form'
        this.modal.title = 'Create ' + Meta.name
        this.modal.params = null
      }
    },

    edit (data) {
      if (this.$Handler.actionMode() === 'PAGE') this.$router.push({ name: 'edit-' + this.Meta.module, params: data })
      else {
        this.modal.show = true
        this.modal.mode = 'form'
        this.modal.title = 'Update ' + Meta.name
        this.modal.params = data
      }
    },

    detail (data) {
      if (this.$Handler.actionMode() === 'PAGE') this.$router.push({ name: 'view-' + this.Meta.module, params: data })
      else {
        this.modal.show = true
        this.modal.mode = 'detail'
        this.modal.title = 'View ' + Meta.name
        this.modal.params = data
      }
    },

    deleteSelected () {
      var that = this
      var type = 'Delete'
      if (this.dataModel.status === 'TRASH') type = 'Restore'
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

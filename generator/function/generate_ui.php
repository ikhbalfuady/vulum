<?php

function generateUi($list, $outputDir = '') {

foreach($list as $item){

$name = $item->name;
$module = strtolower(splitUppercaseToStrip($name));
$slug = strtolower(splitUppercaseToUnderscore($name));

$objectName = '';
$no = 1;
$last = count($item->column);

$tableColumns = "          { name: 'action', label: '#', align: 'left', style: 'width: 20px' },\r\n";
foreach ($item->column as $col) {

  $coma = ',';
  if ($no == $last) $coma = '';

  // for table : index
  $tableColumns .= "          { name: '".$col->name."', label: '".$col->name."', field: '".$col->name."', align: 'left' }$coma\r\n";

$no++;}

$dq = '"'; // double quotes
$sq = "'"; // single quotes
$sq2 = "''"; // single double quotes
$dlr = "$"; // single double quotes

// INDEX --------------------------------------------------
$indexScript = "<script>
import { Helper } from '../../services/helper'
import Api from '../../services/Api'
import Meta from './meta'

export default {
  name: '$name',
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
$tableColumns
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
      this.rules.permission = this.".$dlr."ModuleConfig.getDefault('permission', this.Meta.module)
      this.onRefresh()
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    initRules () {
      this.".$dlr."ModuleConfig.loadAppConfig((status, data) => {
        // code
      }, this.Meta.module)

      this.".$dlr."ModuleConfig.getCurrentPermission((status, data) => {
        var access = data[this.Meta.module]
        if (data[this.Meta.module] !== undefined) {
          this.rules.permission = access
          if (!access.view) this.".$dlr."router.push({ name: '401' }) // view module
          else {
            this.refreshList()
          }
        } else this.".$dlr."router.push({ name: '401' })
      }, this.Meta.module)
    },

    getTableSelected () {
      return this.table.selected.length === 0 ? '' : `".$dlr."{this.table.selected.length} record".$dlr."{this.table.selected.length > 1 ? 's' : ''} selected of ".$dlr."{this.table.data.length}`
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
      this.".$dlr."router.push({ name: this.Meta.module + '-add' })
    },

    edit (data) {
      this.".$dlr."router.push({ name: this.Meta.module + '-edit', params: data })
    },

    detail (data) {
      this.".$dlr."router.push({ name: this.Meta.module + '-detail', params: data })
    },

    deleteSelected () {
      var that = this
      var totalData = this.table.selected.length
      var type = 'Delete'
      if (this.dataModel.status === 'TRASH') type = 'Restore'
      this.".$dlr."q.dialog({
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
";

$index = '<template >

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
                <q-icon v-if="table.search !== '.$sq2.'" name="close" @click="table.search = '.$sq2.'" class="cursor-pointer" />
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
                :color="(dataModel.status === '.$sq.'TRASH'.$sq.') ? '.$sq.'green'.$sq.' : '.$sq.'negative'.$sq.' "
                :icon="(dataModel.status === '.$sq.'TRASH'.$sq.') ? '.$sq.'check'.$sq.' : '.$sq.'delete'.$sq.' "
              />

            </div>

            <q-space />

          </template>

        </q-table>
      </div>

  </div>
</template>

'.$indexScript.'';

// FORM --------------------------------------------------
$formScript = "<script>
import { Helper } from '../../services/helper'
import { Model } from '../../services/model'
import Api from '../../services/Api'
import Meta from './meta'

export default {
  name: '$name',
  data () {
    return {
      Meta,
      API: new Api(),
      // default data
      dataModel: Model.$name(),
      title: 'Add',
      rules: {
        permission: {}
      },
      disableSubmit: false
    }
  },

  created () {
    this.initTopBar()
    this.initialize()
  },

  mounted () {

  },

  methods: {

    initialize () {
      this.rules.permission = this.".$dlr."ModuleConfig.getDefault('permission', this.Meta.module)
      console.log('permission', this.rules.permission)
      this.onRefresh()
    },

    onRefresh () {
      this.initRules()
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    initRules () {
      this.".$dlr."ModuleConfig.loadAppConfig((status, data) => {
      }, this.Meta.module)

      this.".$dlr."ModuleConfig.getCurrentPermission((status, data) => {
        var access = data[this.Meta.module]
        if (access !== undefined) {
          this.rules.permission = access
          if (!access.view) this.".$dlr."router.push({ name: '401' }) // view module
          else {
            var params = this.".$dlr."route.params
            setTimeout(() => {
              if (Helper.checkParams(params)) {
                if (params.id !== undefined) this.getData(params.id)
                else this.backToRoot()
              }
            }, 200)
          }
        } else this.".$dlr."router.push({ name: '401' })
      }, this.Meta.module)
    },

    getData (id) {
      console.log('getData')
      Helper.loading()
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        Helper.loading(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
          this.title = 'Edit'
        }
      })
    },

    edit (data) {
      this.triggerForm(data)
    },

    backToRoot () {
      this.".$dlr."router.push({ name: this.Meta.module })
    },

    emitModel (target, val) {
      this.dataModel[target] = val
    },

    submit () {
      if (this.validateSubmit()) {
        this.disableSubmit = true
        if (this.dataModel.id !== null) this.update()
        else this.save()
      }
    },

    validateSubmit () {
      // if (this.dataModel.name === null) {
      //   Helper.showAlert('Nama Kosong!', 'Nama harus di isi!')
      //   return false
      // } else return true
      return true
    },

    save () {
      Helper.loadingOverlay()
      this.API.post(this.Meta.module, this.dataModel, (status, data, message, response, full) => {
        Helper.loadingOverlay(false)
        if (status === 200) {
          this.messageSubmit('Saving', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    update () {
      Helper.loadingOverlay()
      this.API.put(this.Meta.module + '/' + this.dataModel.id, this.dataModel, (status, data, message, response, full) => {
        Helper.loadingOverlay(false)
        if (response.result === true && status === 200) {
          this.messageSubmit('Update', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    messageSubmit (titleAdd = '', msg) {
      Helper.showAlert(titleAdd + ' Succesfully', msg)
    }
  }
}
</script>
";

$form = '
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">{{title}}</span><br>
            <span class="text-h5 bold text-primary capital">{{Meta.name}}</span>
          </div>
        </div>
      </div>

      <q-card class="box-shadow mv-2">
          <q-card-section>
            <q-form @submit="submit">
              <q-card-section class="row">

                <div class="col-12 col-sm-6 col-md-6 pv ph"
                  v-for="(props, index) in dataModel" :key="index">
                  <q-input v-model="dataModel[index]" :label="index" dense filled square />
                </div>

              </q-card-section>

              <q-card-actions align="right" class="">
                <q-btn class="capital bold" unelevated flat color="red" label="Cancel" icon="cancel" @click="backToRoot" />
                <q-btn class="capital bold" unelevated color="green" label="Save" :disable="disableSubmit" type="submit" icon="check_circle"/>
              </q-card-actions>
            </q-form>
          </q-card-section>
      </q-card>

  </div>
</template>

'.$formScript.'';

// DETAIL --------------------------------------------------
$detailScript = "<script>
import { Helper } from '../../services/helper'
import Api from '../../services/Api'
import { Model } from '../../services/model'
import Meta from './meta'

export default {
  name: '$name',
  data () {
    return {
      Meta,
      API: new Api(),
      // default data
      rules: {
        permission: {}
      },
      dataModel: Model.$name()
    }
  },

  created () {
    this.initTopBar()
    this.initialize()
  },

  mounted () {

  },

  methods: {

    callbackForm (params = null) {
      this.onRefresh()
    },

    onRefresh () {
      this.initRules()
    },

    initialize () {
      this.rules.permission = this.".$dlr."ModuleConfig.getDefault('permission', this.Meta.module)
      console.log('permission', this.rules.permission)
      this.onRefresh()
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    initRules () {
      this.".$dlr."ModuleConfig.loadAppConfig((status, data) => {
        // code
      }, this.Meta.module)

      this.".$dlr."ModuleConfig.getCurrentPermission((status, data) => {
        // console.log('loadPermission', data, status)
        console.log('akses', data[this.Meta.module])
        if (data[this.Meta.module] !== undefined) {
          var akses = data[this.Meta.module]
          this.rules.permission = akses
          if (!akses.view) this.".$dlr."router.push({ name: '401' }) // view module
          else {
            var params = this.".$dlr."route.params
            console.log('params', params)
            setTimeout(() => {
              if (Helper.checkParams(params)) {
                if (params.id !== undefined) this.getData(params.id)
                else this.backToRoot()
              }
            }, 200)
          }
        } else this.".$dlr."router.push({ name: '401' })
      }, this.Meta.module)
    },

    getData (id) {
      console.log('getData')
      Helper.loading()
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        Helper.loading(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
        }
      })
    },

    edit () {
      this.".$dlr."router.push({ name: this.Meta.module + '-edit', params: this.dataModel })
    },

    backToRoot () {
      this.".$dlr."router.push({ name: this.Meta.module })
    }
  }
}
</script>
";

$detail ='
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">Detail</span><br>
            <span class="text-h5 bold text-primary capital">{{Meta.name}}</span>
          </div>
          <q-btn @click="edit" unelevated color="green" class="capital" icon="edit" label="Edit" />
        </div>
      </div>

      <div class="row mv-2">

        <q-list bordered separator class="box-shadow bg-white" style="width:100%">

          <q-item  v-for="(props, index) in dataModel" :key="index" v-ripple>
            <q-item-section>
              <q-item-label caption>{{index}}</q-item-label>
              <q-item-section>{{props}}</q-item-section>
            </q-item-section>
          </q-item>

        </q-list>

      </div>

  </div>
</template>

'.$detailScript.'';

// META --------------------------------------------------
$meta = "const Meta = {
  name: '$name',
  icon: 'stop_circle',
  module: '$module',
  topBarMenu: []
}

export default Meta
";

// Routes --------------------------------------------------

if($module != null || $module != ''){
    $target = $outputDir."UI/$module";
    if (!file_exists($target)) mkdir($target, 0777, true); // generate folder module

    $create_index = fopen($target."/index.vue", "w") or die("Unable to open file index.vue!");
    fwrite($create_index, $index);
    fclose($create_index);
    
    $create_form = fopen($target."/form.vue", "w") or die("Unable to open file form.vue!");
    fwrite($create_form, $form);
    fclose($create_form);

    $create_detail = fopen($target."/detail.vue", "w") or die("Unable to open file detail.vue!");
    fwrite($create_detail, $detail);
    fclose($create_detail);

    $create_meta = fopen($target."/meta.js", "w") or die("Unable to open file meta.js!");
    fwrite($create_meta, $meta);
    fclose($create_meta);
} 

} // end foreach


}
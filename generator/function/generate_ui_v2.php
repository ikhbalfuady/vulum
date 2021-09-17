<?php

function generateUiV2($list, $outputDir = '') {

foreach($list as $item){

$name = $item->name;
$module = strtolower(splitUppercaseToStrip($name));
$slug = strtolower(splitUppercaseToUnderscore($name));
$moduleName = splitUppercaseToSpace($name);

$objectName = '';
$no = 1;

$last = count($item->column);

// get last column
$realTotalCol = count($item->column);
$realCol = $item->column;
unset($realCol[$realTotalCol-3]);
unset($realCol[$realTotalCol-2]);
unset($realCol[$realTotalCol-1]);
$lastCol = count($realCol);

$tableColumns2 = "        { name: 'action', label: '#', align: 'left', style: 'width: 20px' },\r\n";
$model = '';
foreach ($item->column as $col) {

  $coma = ',';
  $comaMeta = ',';
  if ($no == $lastCol) $coma = '';
  if ($no == $last) $comaMeta = '';

  $labelCol = str_replace("_", " ", $col->name);
  $format = '';
  $field = $col->name;
  if (isset($col->belongsTo) && isset($col->belongsTo->model)) {
    $labelCol = str_replace("id", "", $labelCol);
    $format = ", format: (val) => (val) ? val.name : ''";
    $field = $col->belongsTo->name ?? $col->belongsTo->model;
    $field = strtolower(splitUppercaseToUnderscore($field));
  }
  
  // for table : index
  if ( $col->name == 'created_by') {}
  elseif ( $col->name == 'updated_by') {}
  elseif ( $col->name == 'deleted_by') {}
  elseif ( $col->name == 'id') {}
  else $tableColumns2 .= "        { name: '".$col->name."', label: '".$labelCol."', field: '".$field."', search: '".$col->name."', align: 'left'".$format." }$coma\r\n";
  
  $defaultValue = 'null';
  if ($col->type == 'integer' || $col->type == 'double') $defaultValue = '0';
  if ($col->type == 'tinyInteger' || $col->type == 'boolean') $defaultValue = 'false';

  $model .= "    ".$col->name.": $defaultValue"."$comaMeta\r\n";
$no++;}

$dq = '"'; // double quotes
$sq = "'"; // single quotes
$sq2 = "''"; // single double quotes
$dlr = "$"; // dollar

// INDEX --------------------------------------------------
$indexScript = "<script>
import Meta from './meta'
import Form from './form'
import Detail from './detail'

export default {
  name: '$name',
  components: {
    Detail,
    Form
  },
  data () {
    return {
      Meta,
      API: this.".$dlr."Api,
      // default data
      dataModel: {
        status: 'ACTIVE'
      },
      table: this.".$dlr."Handler.table([
$tableColumns2
      ]),
      select: {
        status: this.".$dlr."Handler.toObjectSelect(['ACTIVE', 'TRASH'])
      },
      modal: {
        show: false,
        mode: 'form',
        title: Meta.name,
        params: null,
        onClose: this.refreshList
      }
    }
  },

  created () {
    this.initTopBar()
    this.".$dlr."Handler.permissions(this, 'browse', Meta, (status, data) => {
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
      return this.".$dlr."Handler.tableSelectedLabel(this.table)
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
      if (this.".$dlr."Handler.actionMode() === 'PAGE') this.".$dlr."router.push({ name: 'add-' + this.Meta.module })
      else {
        this.modal.show = true
        this.modal.mode = 'form'
        this.modal.title = 'Create ' + Meta.name
        this.modal.params = null
      }
    },

    edit (data) {
      if (this.".$dlr."Handler.actionMode() === 'PAGE') this.".$dlr."router.push({ name: 'edit-' + this.Meta.module, params: data })
      else {
        this.modal.show = true
        this.modal.mode = 'form'
        this.modal.title = 'Update ' + Meta.name
        this.modal.params = data
      }
    },

    detail (data) {
      if (this.".$dlr."Handler.actionMode() === 'PAGE') this.".$dlr."router.push({ name: 'view-' + this.Meta.module, params: data })
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
      this.".$dlr."q.dialog({
        title: `".$dlr."{type} ".$dlr."{this.table.selected.length} data selected`,
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
      this.".$dlr."Helper.loading()
      this.API.delete(this.Meta.module + '/' + id, (status, data, message, response, full) => {
        this.".$dlr."Helper.loading(false)
        if (status === 200) this.".$dlr."Helper.showToast(message)
      })
    },

    restore (id) {
      this.".$dlr."Helper.loading()
      this.API.put(this.Meta.module + '/' + id + '/restore', (status, data, message, response, full) => {
        this.".$dlr."Helper.loading(false)
        if (status === 200) this.".$dlr."Helper.showToast(message)
      })
    }

  }
}
</script>
";

$index = '<template >

  <div class="root bg-soft">

    <!-- drawer & top menu -->
    <top-menu :data="Meta" />
    <side-menu :data="Meta" />

    <!-- Header Title -->
    <header-title :meta="Meta">
      <template v-slot:config>
        <vl-select col="12" label="Status" v-model="dataModel.status" :options="select.status" @input="val => { onRefresh() }"/>
        <vl-select col="12" label="Search By" v-model="table.searchBySelected" :options="table.searchBy" raw />
      </template>
      <template v-slot:right>
        <search-table v-model="table.search" :table="table" />
      </template>
    </header-title>

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
            <q-icon size="2em" :name="icon" /><span class="bold"> There are no data {{Meta.name}} yet</span>
          </div>
        </template>

        <template v-slot:top>
          <div v-if="Meta.permission.create" class="action animated zoomIn" >
            <q-btn @click="add" unelevated color="primary" class="capital  mr-1" icon="add" label="Add" />
          </div>

          <div v-if="Meta.permission.delete && table.selected.length !== 0 ">
            <q-btn @click="deleteSelected" unelevated class="capital "
              :label="(dataModel.status === '.$sq.'TRASH'.$sq.') ? '.$sq.'Re-Activate'.$sq.' : '.$sq.'Delete'.$sq.' "
              :color="(dataModel.status === '.$sq.'TRASH'.$sq.') ? '.$sq.'green'.$sq.' : '.$sq.'negative'.$sq.' "
              :icon="(dataModel.status === '.$sq.'TRASH'.$sq.') ? '.$sq.'check'.$sq.' : '.$sq.'delete'.$sq.' "
            />

          </div>

          <q-space />

        </template>

      </q-table>
    </div>

    <modal :config="modal">
      <Form v-if="modal.mode === '.$sq.'form'.$sq.'" :from-modal="modal" />
      <Detail v-if="modal.mode === '.$sq.'detail'.$sq.'" :from-modal="modal" />
    </modal>

  </div>
</template>

'.$indexScript.'';

// FORM --------------------------------------------------

$inputComps = '';
$selectSources = '';

$totalSelect = 0;
foreach ($item->column as $col) {
  if ($col->type == 'enum') $totalSelect ++;
}

$currentSelect = 0;

foreach ($item->column as $col) {
  if ( $col->name == 'created_by') {}
  elseif ( $col->name == 'updated_by') {}
  elseif ( $col->name == 'deleted_by') {}
  elseif ( $col->name == 'id') {}
  else {
    $label = splitUnderscoreToSpace($col->name);
    $url = '';
    if (isset($col->belongsTo) && isset($col->belongsTo->model)) {
      $url =  strtolower(splitUppercaseToStrip($col->belongsTo->model));
    }

    $required = '';
    $defaultValidation = '!!val || '.$sq . $label .' is required'.$sq.'';

    if ($col->type == 'integer' || $col->type == 'double' ) $defaultValidation = 'val !== 0 || '.$sq . $label .' must be above 0'.$sq.'';

    if (isset($col->attributes) && !in_array("nullable", $col->attributes)) {
      $required = ':rules="[ val => '.$defaultValidation.' ]" ';
    } else if (!isset($col->attributes)) {
      $required = ':rules="[ val => '.$defaultValidation.' ]" ';
    }


    $string = '              <vl-input col="4" label="'.$label.'" v-model="dataModel.'.$col->name.'" '.$required.'/>';
    $integer = '              <vl-number col="4" label="'.$label.'" v-model="dataModel.'.$col->name.'" '.$required.'/>';
    $decimal = '              <vl-number col="4" label="'.$label.'" v-model="dataModel.'.$col->name.'" currency '.$required.'/>';
    $selectApi = '              <vl-select-serverside col="4" label="'.$label.'" v-model="dataModel.'.$col->name.'" url="'.$url.'" searchable '.$required.'/>';
    $select = '              <vl-select col="4" label="'.$label.'" v-model="dataModel.'.$col->name.'" :options="select.'.$col->name.'" searchable '.$required.'/>';
    $textarea = '              <vl-textarea col="12" label="'.$label.'" v-model="dataModel.'.$col->name.'" '.$required.'/>';
    $toggle = '              <vl-toggle col="4" label="'.$label.'" v-model="dataModel.'.$col->name.'" />';
    $dateTime = '              <vl-datepicker col="4" label="'.$label.'" v-model="dataModel.'.$col->name.'" />';
    $date = '              <vl-datepicker col="4" label="'.$label.'" v-model="dataModel.'.$col->name.'" dateonly />';

    if ($col->type == 'string') $inputComps .= "\n".$string."\n";
    if ($col->type == 'date') $inputComps .= "\n".$date."\n";
    if ($col->type == 'dateTime') $inputComps .= "\n".$dateTime."\n";
    if ($col->type == 'integer') $inputComps .= "\n".$integer."\n";
    if ($col->type == 'double') $inputComps .= "\n".$decimal."\n";
    if ($col->type == 'unsignedBigInteger') $inputComps .= "\n".$selectApi."\n";
    if ($col->type == 'text' || $col->type == 'longtext') $inputComps .= "\n".$textarea."\n";
    if ($col->type == 'tinyInteger' || $col->type == 'boolean') $inputComps .= "\n".$toggle."\n";
    if ($col->type == 'enum') {
      $inputComps .= "\n".$select."\n";
      $enumList = str_replace('"',"'", $col->enum_list);
      $coma = ',';
      $currentSelect ++;
      if ($currentSelect == $totalSelect) $coma = '';
      $selectSources .= "        ".$col->name.": this.".$dlr."Handler.toObjectSelect($enumList)".$coma."\n";
    }
  }
}

$formScript = "<script>
import Meta from './meta'

export default {
  name: '$name',
  props: [
    'fromModal'
  ],
  data () {
    return {
      Meta,
      API: this.".$dlr."Api,
      // default data
      title: 'Create',
      loading: false,
      dataModel: {},
      disableSubmit: false,
      select: {
".$selectSources."
      }
    }
  },

  created () {
    this.dataModel = this.".$dlr."Helper.unReactive(this.Meta.model)
    this.initTopBar()

    var action = this.".$dlr."Handler.formMode(this)

    this.".$dlr."Handler.permissions(this, action.mode, Meta, (status, data) => {
      this.Meta.permission = data // update current permissions of this module
      if (action.mode === 'update' && action.params.id !== undefined) this.getData(action.params.id)
      this.onRefresh()
    })
  },

  mounted () {
    this.handleFromModal()
  },

  computed: {
    isModal () {
      return (this.fromModal) ?? false
    },
    classArea () {
      return (this.fromModal) ? 'mt-2 no-shadow' : 'box-shadow mv-2 mt-2'
    }
  },

  methods: {

    handleFromModal () {
      if (this.fromModal && this.fromModal.params) {
        if (this.fromModal.params.id !== undefined && this.fromModal.params.id !== null) {
          this.getData(this.fromModal.params.id)
          this.onRefresh()
        }
      }
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    onRefresh () {
      //
    },

    getData (id) {
      console.log('getData')
      this.loading = true
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.loading = false
        if (status === 200) {
          // inject data
          this.dataModel = data
          this.title = 'Edit'
        }
      })
    },

    backToRoot () {
      if (this.fromModal) {
        this.fromModal.show = false
        if (this.fromModal.onClose) this.fromModal.onClose()
      } else this.".$dlr."router.push({ name: this.Meta.module })
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
      //   this.".$dlr."Helper.showAlert('Nama Kosong!', 'Nama harus di isi!')
      //   return false
      // } else return true
      return true
    },

    save () {
      this.".$dlr."Helper.loadingOverlay(true, 'Saving..')
      this.API.post(this.Meta.module, this.dataModel, (status, data, message, response, full) => {
        this.".$dlr."Helper.loadingOverlay(false)
        if (status === 200) {
          this.".$dlr."Helper.showSuccess('Save Succesfully', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    update () {
      this.".$dlr."Helper.loadingOverlay(true, 'Saving..')
      this.API.put(this.Meta.module + '/' + this.dataModel.id, this.dataModel, (status, data, message, response, full) => {
        this.".$dlr."Helper.loadingOverlay(false)
        if (status === 200) {
          this.".$dlr."Helper.showSuccess('Update Succesfully', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    }
  }
}
</script>
";

$form = '
<template >

  <div class="root bg-soft">

    <!-- drawer & top menu -->
    <top-menu v-if="!isModal" :data="Meta"  />
    <side-menu v-if="!isModal" :data="Meta" />

    <!-- Header Title -->
    <header-title :meta="Meta" :isModal="isModal" :backToRoot="backToRoot" form-mode />

    <q-card :class="classArea">
        <q-card-section>
          <loading v-if="loading" />
          <q-form @submit="submit" v-if="!loading" class="animated fadeIn">
            <q-card-section class="row">

            <!--<div class="col-12 col-sm-6 col-md-6 pv ph"
                v-for="(props, index) in dataModel" :key="index">
                <vl-input v-model="dataModel[index]" :label="index" />
              </div>-->
'.$inputComps.'
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
import Meta from './meta'

export default {
  name: '$name',
  props: [
    'fromModal'
  ],
  data () {
    return {
      Meta,
      API: this.".$dlr."Api,
      // default data
      dataModel: {},
      viewList: null
    }
  },

  created () {
    this.dataModel = this.".$dlr."Helper.unReactive(this.Meta.model)
    this.initTopBar()
    this.".$dlr."Handler.permissions(this, 'read', Meta, (status, data) => {
      this.Meta.permission = data // update current permissions of this module
      this.onRefresh()
    })
  },

  mounted () {
    this.handleFromModal()
  },

  computed: {
    isModal () {
      return (this.fromModal) ?? false
    },
    classArea () {
      return (this.fromModal) ? 'mt-2 no-shadow' : 'box-shadow mv-2 mt-2'
    }
  },

  methods: {

    handleFromModal () {
      if (this.fromModal && this.fromModal.params) {
        if (this.fromModal.params.id !== undefined && this.fromModal.params.id !== null) {
          this.getData(this.fromModal.params.id)
        }
      }
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    onRefresh () {
      var id = this.".$dlr."Handler.getParamId(this)
      if (id) this.getData(id)
    },

    getData (id) {
      console.log('getData')
      this.".$dlr."Helper.loading()
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.".$dlr."Helper.loading(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
          this.viewList = this.".$dlr."Handler.viewList(this.dataModel, [
            // except / remove property from list
          ])
        }
      })
    },

    edit () {
      this.".$dlr."router.push({ name: 'edit-' + this.Meta.module, params: this.dataModel })
    },

    backToRoot () {
      if (this.fromModal) {
        this.fromModal.show = false
      } else this.".$dlr."router.push({ name: this.Meta.module })
    }
  }
}
</script>
";

$detail ='
<template >

  <div class="root bg-soft">

    <!-- drawer & top menu -->
    <top-menu v-if="!isModal" :data="Meta"  />
    <side-menu v-if="!isModal" :data="Meta" />

    <!-- Header Title -->
    <header-title :meta="Meta" :title="Meta.name + '.$sq.' Detail'.$sq.'" :isModal="isModal" :backToRoot="backToRoot" half-slot >
      <template v-slot:half>
        <q-btn v-if="Meta.permission.update" @click="edit" label="edit" icon="edit" flat dense class="animated slideInRight mr-1 capital bg-green-1 text-green-9 pv-1 fix-icon-btn" color="green"/>
      </template>
    </header-title>

    <q-card :class="classArea">
      <q-card-section class=" pb-2">
        <loading v-if="dataModel.id === null" />
        <q-markup-table style="width:100%" class="no-shadow animated fadeIn" v-if="dataModel.id">
          <tbody>
            <template v-for="(props, key) in viewList" >

              <!-- default -->
              <tr :key="key" >
                <td class="bold text-primary capital">{{key}}</td>
                <td v-ripple>{{props}}</td>
              </tr>

            </template>
            <tr >
              <td class="bold text-primary capital">Log Info</td>
              <td v-ripple><log-info :data="dataModel" /></td>
            </tr>
          </tbody>
        </q-markup-table>
      </q-card-section>
    </q-card>

  </div>
</template>

'.$detailScript.'';

// META --------------------------------------------------
$meta = "const Meta = {
  name: '$moduleName',
  icon: 'stop_circle',
  module: '$module',
  topBarMenu: [],
  permission: {
    browse: true,
    create: true,
    read: true,
    update: true,
    delete: true,
    restore: true
  },
  model: {
$model
  }
}

export default Meta
";

// Routes --------------------------------------------------

if($module != null || $module != ''){
    $target = $outputDir."UI-v2/$module";
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
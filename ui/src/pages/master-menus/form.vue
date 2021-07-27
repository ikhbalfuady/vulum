
<template >

  <div class="root bg-soft">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

      <!-- Header Title -->
      <div class="row pl-2 pt-3 pb-2 mb-2">
        <div class="col-12 col-sm-3 col-md-7 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-8">{{Meta.parent}}</span><br>
            <span class="text-h5 bold text-primary capital">{{title}} {{Meta.name}} <span v-if="title === 'Update'" >#{{dataModel.id}}</span></span>
          </div>
        </div>
      </div>

      <q-form @submit="submit">
        <div class="col-12 col-sm-6 col-md-6 box-shadow mv-2 mb-2  bg-white pv ph" >
          <q-input v-model="dataModel.name" label="Menu Name" dense filled square />
        </div>

        <q-card class="box-shadow mv-2">
            <q-card-section>
                <q-card-section class="row">

                <div class="col-9">
                  <q-select label="Pick menu items.." dense filled square
                    :options="select.menuItems"
                    v-model="select.menuItemsModel"
                    option-label="name"
                    use-input
                    clearable
                    @filter="(val, update) => filterSelect(val, update, 'menuItems')"
                  >
                    <template v-slot:selected-item="row">
                      <span class="ellipsis">{{ row.opt.name }}</span>
                    </template>
                    <template v-slot:no-option>
                      <q-item>
                        <q-item-section class="text-grey"> Not found </q-item-section>
                      </q-item>
                    </template>
                  </q-select>
                </div>

                <div class="col-3 pl-1">
                  <q-btn v-if="select.menuItemsModel" unelevated color="green" icon="add" class="ml-1 full-width" @click="addMenu()" >
                  <span class="gt-sm ml-1">Add to Menu</span>
                  </q-btn>
                </div>

                <div class="col-12 mt-1">
                <q-banner class="text-white bg-blue">
                  <span class="text-h5 bold">Attention!</span><br>
                  <p>The depth of the menu only reaches level 3, above level 3 will not be able to appear on the side menu</p>
                </q-banner>
                </div>

                <div class="col-12 pv ph mt-2" >
                  <Tree class="animated fadeIn" :maxLevel="3" :value="treeData" >
                  <!-- slot-scope="{node, index, path}" -->
                    <div class="animated fadeIn row" slot-scope="{node, index, path}">
                      <q-icon name="drag_indicator" class="pointer text-grey-7 mr-1 pt" />
                      <q-input style="height:20px;" v-if="node.overline !== null" size="sm"  class="bg-grey-2" placeholder="Separator label.." v-model="node.overline" dense />
                      <div>
                        <b class="pl-1 pointer">
                          <q-icon :name="(node.detail.icon) ? node.detail.icon : 'stop_circle' " />
                          {{node.detail.name}}
                        </b>
                      </div>
                      <q-btn v-if="node.overline === null" @click="node.overline = ''" icon="add" label="Separator" dense unelevated color="primary"  size="sm" class="capital ml-1 pr-1" />
                      <q-btn v-if="node.overline !== null" @click="node.overline = null" icon="close" label="Separator" dense unelevated color="secondary"  size="sm" class="capital ml-1 pr-1" />
                      <q-btn @click="removeItem(node, path)" icon="close" unelevated color="red" dense size="xs" class="ml-1" />
                    </div>
                  </Tree>
                </div>

                <div class="col-12 pv ph" v-if="treeData.length === 0" >
                  <span class="text-primary">Pick some menu items...</span>
                </div>

                </q-card-section>

                <q-card-actions align="right" class="">
                  <q-btn class="capital bold" unelevated flat color="red" label="Cancel" icon="cancel" @click="backToRoot" />
                  <q-btn class="capital bold" unelevated color="green" label="Save" :disable="disableSubmit" type="submit" icon="check_circle"/>
                </q-card-actions>
            </q-card-section>
        </q-card>
      </q-form>

  </div>
</template>

<style>
.tree-node:hover {
  background: #ebebeb;
}
</style>

<script>
import Meta from './meta'
import { Tree, Draggable } from 'he-tree-vue'
import 'he-tree-vue/dist/he-tree-vue.css' // base style
import { TreeData } from 'helper-js'

export default {
  components: { Tree: Tree.mixPlugins([Draggable]) },
  name: 'MasterMenus',
  data () {
    return {
      Meta,
      API: this.$Api,
      // default data
      title: 'Create',
      dataModel: {},
      rules: {
        permission: Meta.permission
      },
      disableSubmit: false,
      treeDatas: [
        {
          id: 0,
          name: 'Dashboard',
          children: []
        },
        {
          id: 3,
          name: 'Users',
          children: [
            {
              id: 4,
              name: 'Users List',
              children: []
            },
            {
              id: 10,
              name: 'Add User',
              children: []
            }
          ]
        },
        {
          id: 13,
          name: 'Menus',
          children: []
        }
      ],
      treeData: [],
      select: {
        menuItems: [],
        menuItemsTmp: [], // need for base when use filter search
        menuItemsModel: null
      }
    }
  },

  created () {
    this.dataModel = this.$Helper.unReactive(this.Meta.model)
    this.initTopBar()
    this.$ModuleConfig.getCurrentPermissions((status, data) => {
      console.log('initPermissionPage:' + Meta.module, data)
      this.initialize()
    }, 'user-form')
  },

  mounted () {

  },

  watch: {
    $route (to, from) {
      this.dataModel = Meta.model
    },
    treeData: {
      immediate: true,
      handler: function handler (treeData) {
        this._TreeDataHelper = new TreeData(this.treeData)
      }
    }
  },

  methods: {

    addMenu () {
      var data = this.select.menuItemsModel
      if (data) {
        this.treeData.push(data)
        this.select.menuItemsModel = null
      } else this.$Helper.showToast('Please pick menu item first!')
    },

    removeItem (node, path) {
      this._TreeDataHelper.removeNode(path)
      if (node.id) {
        this.dataModel.del_menu = [
          ...this.dataModel.del_menu,
          node.id
        ]
      }
    },

    checkPermission (mode = 'create') {
      var access = this.$ModuleConfig.checkPermission(this.$router, this.Meta.module + '-' + mode)
      if (access) return true
      else this.$router.push({ name: '403' })
    },

    initialize () {
      var params = this.$route.params
      if (this.$Helper.checkParams(params)) { // checking access update
        if (this.checkPermission('update')) {
          if (params.id !== undefined) {
            this.onRefresh()
            this.getData(params.id)
          } else this.backToRoot()
        }
      } else { // checking access create
        if (this.checkPermission('create')) {
          //
          this.onRefresh()
        }
      }
    },

    onRefresh () {
      //
      this.getListMenuItems()
    },

    initTopBar () {
      this.Meta.topBarMenu = [{ name: 'Refresh', event: this.onRefresh }]
    },

    getListMenuItems () {
      console.log('getListMenuItems')
      this.$Helper.loadingOverlay(true, 'Loading..')
      var endpoint = 'menu-items/picker?limit=0'
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.select.menuItems = data
          this.select.menuItemsTmp = data
        }
      })
    },

    getData (id) {
      console.log('getData')
      this.$Helper.loadingOverlay(true, 'Loading..')
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          // inject data
          this.dataModel = data
          this.dataModel.del_menu = []
          this.treeData = data.detail
          this.title = 'Edit'
        }
      })
    },

    edit (data) {
      this.triggerForm(data)
    },

    backToRoot () {
      this.$router.push({ name: this.Meta.module })
    },

    emitModel (target, val) {
      this.dataModel[target] = val
    },

    submit () {
      if (this.validateSubmit()) {
        this.disableSubmit = true
        this.dataModel.detail = this.treeData
        if (this.dataModel.id !== null) this.update()
        else this.save()
      }
    },

    validateSubmit () {
      // if (this.dataModel.name === null) {
      //   this.$Helper.showAlert('Nama Kosong!', 'Nama harus di isi!')
      //   return false
      // } else return true
      return true
    },

    save () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.post(this.Meta.module, this.dataModel, (status, data, message) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.messageSubmit('Saving', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    update () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.put(this.Meta.module + '/' + this.dataModel.id, this.dataModel, (status, data, message) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.messageSubmit('Update', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    messageSubmit (titleAdd = '', msg) {
      this.$Helper.showAlert(titleAdd + ' Succesfully', msg)
    },

    filterSelect (val, update, target) {
      var targetNameTmp = target + 'Tmp'
      // console.log('select.' + targetNameTmp, this.select[targetNameTmp])
      update(() => { this.select[target] = this.select[targetNameTmp] })

      update(() => {
        const needle = val.toLowerCase()
        var tmp = this.select[targetNameTmp]
        this.select[target] = tmp.filter(v => v.name.toLowerCase().indexOf(needle) > -1)
      })
    }
  }
}
</script>

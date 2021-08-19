
<template >

  <div class="root bg-soft" @show="onShow()">

    <!-- drawer & top menu -->
    <top-menu :data="Meta"  />
    <side-menu v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

    <!-- Header Title -->
    <header-title :meta="Meta" :isModal="isModal" :backToRoot="backToRoot" form-mode />

      <!-- Header Title -->
      <div class="row pl-2 pt-2 bg-light" v-if="false">
        <div class="col-11 pb-1 pv info-page">
          <div class="title">
            <span class="text-caption text-grey-7">Master {{Meta.name}}</span><br>
            <q-btn @click="backToRoot" rounded icon="arrow_back" flat dense class="mr-1" color="grey-9"/>
            <span class="text-h5 bold text-dark capital">{{title}} {{Meta.name}} <span v-if="title === 'Update'" >#{{dataModel.id}}</span></span>
          </div>
        </div>
      </div>

      <q-card :class="classArea">
        <q-card-section>
          <loading v-if="loading" />
          <q-form @submit="submit" v-if="!loading" >
            <q-card-section class="row animated fadeIn">

              <template v-for="(props, index) in dataModel" >
                <vl-input v-model="dataModel[index]" :label="index" :key="index" toplabel/>
              </template>

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

<script>
import Meta from './meta'

export default {
  name: 'Department',
  props: [
    'fromModal'
  ],
  data () {
    return {
      Meta,
      API: this.$Api,
      // default data
      title: 'Create',
      loading: false,
      dataModel: {},
      disableSubmit: false
    }
  },

  created () {
    this.dataModel = this.$Helper.unReactive(this.Meta.model)
    this.initTopBar()

    var action = this.$Handler.formMode(this)

    this.$Handler.permissions(this, action.mode, Meta, (status, data) => {
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
      // this.$Helper.loadingOverlay(true, 'Loading..')
      this.loading = true
      var endpoint = this.Meta.module + '/' + id
      this.API.get(endpoint, (status, data, message, response, full) => {
        // this.$Helper.loadingOverlay(false)
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
      } else this.$router.push({ name: this.Meta.module })
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
      //   this.$Helper.showAlert('Nama Kosong!', 'Nama harus di isi!')
      //   return false
      // } else return true
      return true
    },

    save () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.post(this.Meta.module, this.dataModel, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.$Helper.showSuccess('Saving Succesfully', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    },

    update () {
      this.$Helper.loadingOverlay(true, 'Saving..')
      this.API.put(this.Meta.module + '/' + this.dataModel.id, this.dataModel, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.$Helper.showSuccess('Update Succesfully', message)
          this.backToRoot()
        } else this.disableSubmit = false
      })
    }

  }
}
</script>

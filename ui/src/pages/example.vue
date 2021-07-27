<template >

  <div class="root">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

    <div class="row ph-1 pv-1">
      <div class="col-12 text-center pt-5 pb-3">
        <span class="text-h4 bold">Example Component.</span> <br>
        <span class="text-grey-8">this is a sample of ready component of this framework!</span>
      </div>

      <!-- Select -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">

          <div class="bold text-h5 pb-1">Selects</div>
          <q-select label="Select List" dense filled square class=""
            :options="select.options"
            v-model="select.model"
            option-value="id" option-label="name"
            emit-value map-options
            lazy-rules :rules="[
              val => val !== null && val !== '' || 'Opps pilih salah satu boss!',
            ]"
          >
            <template v-slot:selected-item="row"> <span class="ellipsis">{{ row.opt.name }}</span> </template>
            <template v-slot:no-option>
              <q-item> <q-item-section class="text-grey"> Not found </q-item-section> </q-item>
            </template>
          </q-select>

          <div class="bold text-h5 pb-1">Select with search</div>
          <q-select label="Select with search" dense filled square
            :options="select.options"
            v-model="select.model"
            option-value="id" option-label="name"
            emit-value map-options  use-input clearable
            @filter="(val, update) => filterSelect(val, update, 'options')"
            lazy-rules :rules="[
              val => val !== null && val !== '' || 'Opps pilih salah satu boss!',
            ]"
          >
            <template v-slot:selected-item="row"> <span class="ellipsis">{{ row.opt.name }}</span> </template>
            <template v-slot:no-option>
              <q-item> <q-item-section class="text-grey"> Not found </q-item-section> </q-item>
            </template>
          </q-select>

          <div>
          "[input] Model : {{select.model}}"
          </div>

        </div>
      </div>

      <!-- Input -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Input</div>

          <q-input v-model="input" label="Input Default" dense filled square
          lazy-rules :rules="[
              val => val !== null && val !== '' || 'Harus di isi boss!',
            ]"
          />

          <q-input v-model="input" label="Input withoud validation" dense filled square
          />

          <div class="pt-2">
          "[input] Model : {{input}}"
          </div>

        </div>
      </div>

      <!-- Currency -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Input Currency</div>
          <q-field v-model="currency" dense filled square label="Decimal" >
            <template v-slot:control="{ id, floatingLabel, value }">
              <money :id="id" class="q-field__input text-right" :value="value" @input="val => emitModel('currency', val)" v-bind="moneyFormat" v-show="floatingLabel" />
            </template>
          </q-field>

          <hr>
          <q-field v-model="currency" dense filled square label="Number Only" >
            <template v-slot:control="{ id, floatingLabel, value }">
              <money :id="id" class="q-field__input text-right" :value="value" @input="val => emitModel('currency', val)" v-bind="numberOnly" v-show="floatingLabel" />
            </template>
          </q-field>

          <div class="pt-2">
          "[input] Model : {{currency}}"
          </div>

        </div>
      </div>

      <!-- toggle -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Toggle</div>

          <q-toggle size="md" floating dense
            v-model="toggle"
            icon="monetization_on"
            label="Horizontal"
            class="bold toggle-block text-caption"
            emit-value
          />

          <hr>

          <q-toggle size="md" floating dense
            v-model="toggle"
            icon="monetization_on"
            label="Vertical"
            emit-value
          />

          <div class="pt-2">
          "[input] Model : {{toggle}}"
          </div>

        </div>
      </div>

      <!-- datePicker -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Date Picker</div>
            <q-input label="Tanggal" filled v-model="date" dense square mask="date" >
              <q-popup-proxy ref="popupTanggal" transition-show="scale" transition-hide="scale">
                <q-date v-model="date" @input="() => $refs.popupTanggal.hide()" ></q-date>
              </q-popup-proxy>
            </q-input>

          <div class="pt-2">
          "[input] Model : {{date}}"
          </div>

        </div>
      </div>

      <!-- TimePicker -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Time Picker</div>
            <q-input label="Jam" filled v-model="time" dense square >
              <q-popup-proxy ref="popupJam" transition-show="scale" transition-hide="scale">
                <q-time v-model="time" mask="HH:mm" format24h>
                    <div class="row items-center justify-end"><q-btn v-close-popup label="Oke" color="primary" flat /></div>
                  </q-time>
              </q-popup-proxy>
            </q-input>

          <div class="pt-2">
          "[input] Model : {{time}}"
          </div>

        </div>
      </div>

      <!-- dateTimePicker -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">DateTime Picker</div>

            <q-input label="Tanggal" filled v-model="dateTime" dense square >
              <!-- Tanggal -->
              <q-popup-proxy persistent ref="popupDateTime" transition-show="scale" transition-hide="scale">
                <q-date v-model="dateTime" @input="() =>{ $refs.popupDateTime.hide(); $refs.popupJam.show() }" mask="YYYY-MM-DD HH:mm"></q-date>
              </q-popup-proxy>
              <!-- Jam -->
              <div>
                <q-popup-proxy persistent ref="popupJam" transition-show="scale" transition-hide="scale">
                  <q-time v-model="dateTime" mask="YYYY-MM-DD HH:mm" format24h>
                    <div class="row items-center justify-end"><q-btn v-close-popup label="Oke" color="primary" flat /></div>
                  </q-time>
                </q-popup-proxy>
              </div>
            </q-input>

          <div class="pt-2">
          "[input] Model : {{dateTime}}"
          </div>

        </div>
      </div>

      <!-- upload file -->
      <div class="col-12 col-sm-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Upload File component</div>

          <img-uploader v-if="uploadImage.show" :data="uploadImage" class="animated fadeIn" />
          <q-btn @click="upload" v-if="uploadImage.file" unelevated color="primary" class="mh-1" label="upload" />

          <div class="pt-2">
          "[input] Model : {{uploadImage}}"
          </div>

          <div v-if="uploadRes" class="animated fadeIn">
            <hr>
            <span class="text-h4 text-primary">Response : </span>
            <echo :data="uploadRes" />
            <hr>
            Imgae Source: <br>
            <img :src="uploadRes.url" />
          </div>

        </div>
      </div>

      <!-- dynamic list input -->
      <div class="col-12 pv-1 ph-1 mb-4">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Dynamic List Input</div>

          <q-btn label="Add list" color="primary" unelevated @click="addList" />

          <div v-for="(item, index) in dynamicList"  :key="index + '-lists'" class="row mt-1 ph-1" style="border-top:1px solid #ccc" >
            <div class="bold col-8" >
              <q-input :label="'Input-' + index" v-model="item.name"/>
            </div>
            <div class="col-3 right">
              <q-btn type="button" size="sm" icon="delete" color="red" outline unelevated @click="removeList(index)" >Remove</q-btn>
            </div>
          </div>

          <div class="pt-2">
          [input] Model :
          <pre>{{dynamicList}}</pre>
          </div>

        </div>
      </div>

      <!-- dynamic list select -->
      <div class="col-12 pv-1 ph-1 mb-4">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Dynamic List Select</div>

          <q-btn label="Add list" color="primary" unelevated @click="addListSelect" />

          <div v-for="(item, index) in dynamicListSelect"  :key="index + '-list-select'" class="row mt-1 ph-1" style="border-top:1px solid #ccc" >
            <div class="bold col-8" >
              <q-select dense filled square
                @input="(val) => customBindSelect(val, index)"
                :options="select.options"
                v-model="item.id"
                label="Pick"
                option-value="id" option-label="name"
                emit-value map-options use-input
                @filter="(val, update) => filterSelect(val, update, 'options')"
              >
                <template v-slot:selected-item="row">
                  <span class="ellipsis">{{ row.opt.name }}</span>
                </template>
                <template v-slot:no-option>
                  <q-item>
                    <q-item-section class="text-grey"> Not found </q-item-section>
                  </q-item>
                </template>
                <template v-slot:hint></template>
              </q-select>
            </div>
            <div class="col-3 right">
              <q-btn type="button" size="sm" icon="delete" color="red" outline unelevated @click="removeListSelect(index)" >Remove</q-btn>
            </div>
          </div>

          <div class="pt-2">
          [input] Model :
          <pre>{{dynamicListSelect}}</pre>
          </div>

        </div>
      </div>

      <div class="col-12 pv-1 ph-1 mb-4">
       <div class="bg-white radius-1 box-shadow pv-2 ph-2">

        <q-btn label="Open Modal" color="primary" unelevated @click="showForm = true" />
      </div>
      </div>

        <q-dialog v-model="showForm" persistent
          transition-show="jump-up"
          transition-hide="jump-down"
        >
          <q-card :style="'width: '+pageStyle.width+'; height: '+pageStyle.height+';'">
            <q-form >
              <q-toolbar>
                <q-avatar> <q-icon name="ion-cube" class="text-primary" /> </q-avatar>
                <q-toolbar-title><span class="text-weight-bold text-primary text-h6">Judul</span></q-toolbar-title>
                <q-btn flat round dense icon="close" color="red" @click="showForm = false" />
              </q-toolbar>

              <q-scroll-area :style="'height: '+pageStyle.contentHeight+';'"  class=" pr-2 pl-1 items-start q-gutter-sm  bg-soft"
                :thumb-style="thumbStyle"
                :bar-style="barStyle"
              >
                <q-card-section class="row ph pv">

                  <!-- PAGE AREA -->
                  <div class="col-12 col-md-12 pv ph">
                    <q-input v-model="input" label="Input Default" dense filled square
                      lazy-rules :rules="[
                          val => val !== null && val !== '' || 'Harus di isi boss!',
                        ]"
                      />
                  </div>
                  <div class="col-12 col-md-12 pv ph">
                    <q-input v-model="input" label="Input Default" dense filled square
                      lazy-rules :rules="[
                          val => val !== null && val !== '' || 'Harus di isi boss!',
                        ]"
                      />
                  </div>
                  <div class="col-12 col-md-12 pv ph">
                    <q-input v-model="input" label="Input Default" dense filled square
                      lazy-rules :rules="[
                          val => val !== null && val !== '' || 'Harus di isi boss!',
                        ]"
                      />
                  </div>
                  <!-- END PAGE AREA -->

                </q-card-section>

              </q-scroll-area>

              <q-card-actions align="right" class=" bg-soft">
                <q-btn class="capital bold" unelevated flat color="red" label="Batal" icon="cancel" @click="showForm = false" />
                <q-btn class="capital bold" unelevated color="green" label="Simpan" type="submit" icon="check_circle"/>
              </q-card-actions>
            </q-form>
          </q-card>
        </q-dialog>

    </div>
  </div>
</template>

<script>
export default {

  name: 'Example',
  data () {
    return {
      API: this.$Api,
      Meta: {
        name: 'Example Component',
        icon: 'stop_circle',
        module: 'users',
        topBarMenu: []
      },
      // select
      select: {
        options: [],
        optionsTmp: [], // need for base when use filter search
        model: null
      },
      // input
      input: null,
      // currency & number only
      moneyFormat: this.$Config.currencyConfig(),
      numberOnly: this.$Config.numberOnly(),
      currency: 0,
      // toggle
      toggle: false,
      date: null,
      time: null,
      dateTime: null,
      dynamicList: [], // default {id: null, name: null}
      dynamicListSelect: [], // default {id: null, name: null}
      // uploadFile: null,
      uploadImage: { // wajib seperti ini formatnya
        show: true,
        file: null,
        callback: this.callbackImgUploader, // callback function harus di include jg di script
        subFix: ''
      },
      uploadRes: null,
      // config dialog
      showForm: false,
      pageStyle: {
        width: '90vw',
        height: '80vh',
        contentHeight: '65vh' // -15 dari height untuk konten presisi
      }
    }
  },

  created () {
    // init select
    this.select.options = [
    ]
    this.select.optionsTmp = this.select.options

    //
  },

  mounted () {

  },

  methods: {

    async filterSelect (val, update, target) {
      this.select = await this.$Helper.filterSelect(val, update, target, this.select)
    },

    emitModel (target, val) {
      this[target] = val
    },

    test () {

    },

    // dynamic list
    addList () {
      var data = {
        id: this.$Helper.createUID(),
        name: 'List ..'
      }
      this.dynamicList.push(data)
    },

    removeList (index) {
      this.dynamicList.splice(index, 1)
    },

    addListSelect () {
      var data = {
        id: null,
        name: null
      }
      this.dynamicListSelect.push(data)
    },

    removeListSelect (index) {
      this.dynamicListSelect.splice(index, 1)
    },

    customBindSelect (val, index) {
      var selectPicked = this.$Helper.findObjectByKey(this.select.options, 'id', val) // ambil data sesuai id yg di pick
      this.dynamicListSelect[index].name = selectPicked.name
    },

    callbackImgUploader (data) {
      console.log('callbackImgUploader', data)
      this.uploadImage.file = data
      // custom assign file bellow
    },

    formatModel (data) {
      const dataModel = new FormData()
      for (const key in data) {
        // console.log('key', key)
        dataModel.append(key, data[key])
      }
      return dataModel
    },

    // test upload file
    upload () {
      this.$Helper.loadingOverlay(true, 'Uploading..')
      var data = {
        photo: this.uploadImage.file // pastikan atrribute seesuai dengan receiver di API
      }
      var dataModel = this.formatModel(data)
      this.API.isMultipartForm = true
      this.API.post('upload-image', dataModel, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          this.$Helper.showAlert('Upload Image', message)
          this.uploadRes = data
          // set thumb
          this.uploadRes.url = this.$Config.getApiRoot() + data.url
        }
      })
    }

  }
}
</script>

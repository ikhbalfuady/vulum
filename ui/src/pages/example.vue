<template >

  <div class="root">

    <!-- drawer di init di: boot/extend-component.js -->
    <drawer v-bind:topBarInfo="Meta"  v-bind:topBarMenu="Meta.topBarMenu"  />

    <div class="row ph-1 pv-1">
      <div class="col-12 text-center pt-5 pb-3">
        <span class="text-h4 bold">Example Component.</span> <br>
        <span class="text-grey-8">this is a sample of ready component of this framework!</span>
      </div>

      <div class="col-12 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Default Components</div>
          <div class="pb-2">Support Slot : prepend, append, hint <small>(define to attribute bottomSlots) </small> <br>
          Only element : <b>input, number, select</b></div>
          <q-form class="row" @submit="eventCustom(JSON.stringify(defaultComponent), 'submit')" >

            <vl-input col="4" label="Input"
              v-model="defaultComponent.input"
              @input="val => eventCustom(val, 'input')"
            />

            <vl-number col="4" label="Number"
              v-model="defaultComponent.number"
              @input="val => eventCustom(val, 'number')"
            />

            <vl-number col="4" label="Currency"
              v-model="defaultComponent.currency" currency
              @input="val => eventCustom(val, 'currency')"
            />

            <vl-select col="3" label="Select"
              v-model="defaultComponent.select"
              :options="select.options"
              @input="val => eventCustom(val, 'select')"
            />

            <vl-select-serverside
              col="3"
              url="users"
              label="Select Serverside"
              option-label="name"
              v-model="select.model"
              :default="select.model"
            />

            <vl-select col="3" label="Select Searchable"
              v-model="defaultComponent.select"
              :options="select.options" searchable
              @input="val => eventCustom(val, 'select Searchable')"
            />

            <vl-select col="3" label="Select Multiple"
              v-model="defaultComponent.selectMultiple"
              :options="select.options" searchable multiple
              @input="val => eventCustom(val, 'select multiple')"
            />

            <vl-textarea col="12" label="TextArea"
              v-model="defaultComponent.textarea"
              @input="val => eventCustom(val, 'TextArea')"
            />

            <vl-datepicker col="4" label="DateTime Picker"
              v-model="defaultComponent.dateTime"
              @input="val => eventCustom(val, 'DateTime')"
            />

            <vl-datepicker col="4" label="Date Picker"
              v-model="defaultComponent.date" dateonly
              @input="val => eventCustom(val, 'Date')"
            />

            <vl-datepicker col="4" label="Time Picker"
              v-model="defaultComponent.time" timeonly
              @input="val => eventCustom(val, 'Time')"
            />

            <vl-toggle col="2" label="Toogle"
              v-model="defaultComponent.toggle"
              @input="val => eventCustom(val, 'Toggle')"
            />

            <vl-toggle col="2" label="Toogle Block"
              v-model="defaultComponent.toggle" block
              @input="val => eventCustom(val, 'Toggle')"
            />

            <div class="col-12 mb-2"><q-separator /></div>

            <div class="col-12 bold text-h5 pb-1">With Validation</div>

            <!-- With Validation -->
            <vl-input col="3" label="Input"
              v-model="defaultComponent.inputRequired"
              @input="val => eventCustom(val, 'inputRequired')"
              :rules="[
                val => val !== null && val !== '' || 'Field is required!',
              ]"
            />

            <vl-select col="3" label="Select"
              v-model="defaultComponent.selectRequired"
              :options="select.options"
              @input="val => eventCustom(val, 'selectRequired')"
              :rules="[
                val => val !== null && val !== '' || 'Field is required!',
              ]"
            />

            <vl-datepicker col="3" label="DateTime Picker"
              v-model="defaultComponent.dateTimeRequired"
              @input="val => eventCustom(val, 'DateTimeRequired')"
              :rules="[
                val => val !== null && val !== '' || 'Field is required!',
              ]"
            />

            <vl-datepicker col="3" label="Date Picker"
              v-model="defaultComponent.dateRequired" dateonly
              @input="val => eventCustom(val, 'DateRequired')"
              :rules="[
                val => val !== null && val !== '' || 'Field is required!',
              ]"
            />

            <vl-datepicker col="3" label="Time Picker"
              v-model="defaultComponent.timeRequired" timeonly
              @input="val => eventCustom(val, 'TimeRequired')"
              :rules="[
                val => val !== null && val !== '' || 'Field is required!',
              ]"
            />

            <div class="col-12">
              <q-btn type="submit" label="submit" color="primary" unelveated />
            </div>

            <div class="col-12 col-sm-6">
              <div class="bold text-h5 pb-1 pt-2">Event Handler</div>
              <div><span class="bold text-red-9">Events(@input) :</span> <br>{{eventRes}}</div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="bold text-h5 pb-1 pt-2">Model Result</div>
              <echo :data="defaultComponent" />
            </div>

          </q-form>

       </div>
      </div>

      <!-- From Iterator -->
      <div class="col-12 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">Default Components in Iterators</div>
            <q-form class="row" @submit="eventCustom(JSON.stringify(defaultComponent), 'submit')" >

              <div class="col-12 row"
                v-for="(item, i) in testDl" :key="i+'dl'"
              >

                <vl-input col="3" label="Name" v-model="item.name" />

                <vl-number col="3" label="Salary" v-model="item.salary" currency />

                <vl-select col="3" label="People"  v-model="item.people" :options="select.options" />

                <vl-datepicker col="3" label="Periode" v-model="item.periode" />

            </div>

            <div class="col-12">
              <div class="bold text-h5 pb-1 pt-2">Model Result</div>
              <echo :data="testDl" />
            </div>

          </q-form>
        </div>
      </div>

      <!-- From Fetch API -->
      <div class="col-12 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">
          <div class="bold text-h5 pb-1">
          Default Components in Fetching API
          <q-btn @click="getData" dense label="Fetch" unelevated flat class="ml-1 capital bold bg-blue-1 pv-1" color="blue-9" />
          </div>
            <q-form class="row">

              <vl-input col="3" label="model string" v-model="modelComponent.string" />
              <vl-number col="3" label="model number" v-model="modelComponent.number" />
              <vl-number col="3" label="model currency" v-model="modelComponent.currency" currency />
              <vl-datepicker col="3" label="model datetime" v-model="modelComponent.datetime" />
              <vl-datepicker col="3" label="model date" v-model="modelComponent.date" dateonly/>
              <vl-datepicker col="3" label="model time" v-model="modelComponent.time" timeonly/>

              <vl-select col="3" label="model select" v-model="modelComponent.select" :options="modelComponent.select_source" searchable />
              <vl-select col="3" label="model select multiple" v-model="modelComponent.select_multiple" :options="modelComponent.select_source" multiple searchable />

              <vl-toggle col="3" label="model toggle" v-model="modelComponent.toggle" />

            <div class="col-12">
              <div class="bold text-h5 pb-1 pt-2">Model Result</div>
              <echo :data="modelComponent" />
            </div>

          </q-form>
        </div>
      </div>

      <!-- Select -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">

          <div class="bold text-h5 pb-1">Select</div>
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

      <!-- Autocomplete -->
      <div class="col-12 col-md-4 pv-1 ph-1">
        <div class="bg-white radius-1 box-shadow pv-2 ph-2">

          <div class="bold text-h5 pb-1">Autocomplete</div>
          <q-select ref="users" label="Autocomplete" dense filled square class=""
            :options="select.users"
            v-model="autocomplete"
            option-value="id" option-label="name"
            emit-value map-options use-input
            @filter="(val, update) => filterSelect(val, update, 'users')"
            @input-value="searchAutocomplete"
            :hint="$Config.noOptionLabel()"
            lazy-rules :rules="[
              val => val !== null && val !== '' || 'Data must be choose!',
            ]"
          >
            <template v-slot:no-option>
              <q-item> <q-item-section class="text-grey">{{noOptionLabel}}</q-item-section> </q-item>
            </template>
          </q-select>

          <div class="mt-2">
            <small class="text-red-9">
            Untuk menggunakan autocomplete pastikan hal berikut: <br>
            1. tambah properties : noOptionLabel // untuk kebutuhan no option label<br>
            2. berikan ref="namaRef" // usahakan disamakan dengan nama select options <br>
            3. import debounce plugin utils <br>
            4. define hanlder @input-value="searchAutocomplete" dan gunakan method nya <br>
            5. define hanlder Helper.handlerFocusAutocomplete di method getList <br>
            6. untuk set default value nya saat edit, pastikan foregin / relation data terpanggil di API, dan di push ke select option target saat setelah fetch data <br>

            </small>
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
    </div>
  </div>
</template>

<script>
import { debounce } from 'quasar'

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
      autocomplete: null,
      noOptionLabel: this.$Config.noOptionLabel('user'),
      select: {
        options: [],
        optionsTmp: [], // need for base when use filter search
        users: [],
        usersTmp: [], // need for base when use filter search
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
      defaultComponent: {
        input: null,
        currency: 123,
        number: 321,
        select: null,
        selectMultiple: [],
        textarea: null,
        dateTime: null,
        date: null,
        time: null,
        toggle: false,
        // with validation
        inputRequired: null,
        currencyRequired: null,
        numberRequired: null,
        selectRequired: null,
        dateTimeRequired: null,
        dateRequired: null,
        timeRequired: null
      },
      modelComponent: {
        string: '',
        number: 0,
        currency: 0,
        toggle: true,
        datetime: '',
        date: '',
        time: '',
        select: null,
        select_multiple: [],
        select_source: []
      },
      eventRes: '',
      testDl: [
        {
          id: 1,
          name: 'Manager',
          people: 1,
          salary: 2412.2,
          periode: '2021-01-01 10:15:51'
        },
        {
          id: 2,
          name: 'Head',
          people: 2,
          salary: 12123,
          periode: '2021-02-06 13:35:51'
        }
      ]
    }
  },

  created () {
    // init select
    this.select.options = [
      {
        id: 1,
        name: 'John Pantau Maringgas'
      },
      {
        id: 2,
        name: 'Doe'
      }
    ]
    this.select.optionsTmp = this.select.options
    this.searchAutocomplete = debounce(this.searchAutocomplete, 500)

    //
  },

  mounted () {
    this.getData()
  },

  methods: {

    async filterSelect (val, update, target) {
      this.select = await this.$Helper.filterSelect(val, update, target, this.select)
    },

    emitModel (target, val) {
      this[target] = val
    },

    test (val) {
      console.log('triggered', val)
    },

    eventCustom (val, from = '-') {
      this.eventRes = `triggered from (${from}) with val = ${val}`
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
      console.log(this.uploadImage.file)
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
    },

    getListSelect (endpoint, selectSource = null) {
      this.$Helper.loadingOverlay(true)
      // var endpoint = 'menus'
      this.API.get(endpoint, (status, data, message, response, full) => {
        this.$Helper.loadingOverlay(false)
        if (status === 200) {
          var targetSource = endpoint
          if (selectSource) targetSource = selectSource
          var tmpName = targetSource + 'Tmp'

          this.select[targetSource] = data
          this.select[tmpName] = data

          // dibutuhkan untuk handle autocomplete
          if (data.length === 0) this.noOptionLabel = 'Data not found'
          this.$Helper.handlerFocusAutocomplete(this, selectSource, data) // handler autofocus autocomplete
        }
      })
    },

    getData () {
      this.API.get('test-component', (status, data, message, response, full) => {
        if (status === 200) {
          this.modelComponent = data
          console.log('api', data.currency)
          console.log('model', this.modelComponent.currency)
        }
      })
    },

    searchAutocomplete (val) {
      console.log('select', val)
      if (val.length > 1) this.getListSelect(`users/?limit=0&search=name:${val}`, 'users')
    }

  }
}
</script>

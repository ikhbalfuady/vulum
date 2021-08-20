<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}}
  </div>
  <q-input
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    :label="(label && toplabel !== '') ? label : ''"
    dense filled square
    v-bind:value="value"
    v-on:input="emiters($event)"
    @focus="() => handlerClick(refDate, refTime)"
    :rules="(rules) ? rules : []"
    :readonly="(readonly==='') ? true : false"
  >
    <template v-if="$slots.prepend" v-slot:prepend>
      <slot name="prepend"></slot>
    </template>

    <template v-if="$slots.append" v-slot:append>
      <slot name="append"></slot>
    </template>

    <template v-if="$slots.hint" v-slot:hint>
      <slot name="hint"></slot>
    </template>

   <!-- Tanggal -->
    <div>
      <q-popup-proxy :ref="refDate" transition-show="jump-up" transition-hide="jump-down">
        <q-date :mask="mask"
          v-bind:value="value"
          v-on:input="emiters($event)"
          @input="() => handlerInput(refDate, refTime)"
        ></q-date>
      </q-popup-proxy>
    </div>
    <!-- Jam -->
    <div>
      <q-popup-proxy persistent :ref="refTime" transition-show="jump-up" transition-hide="jump-down">
        <q-time
          v-bind:value="value"
          v-on:input="emiters($event)"
          :mask="mask" format24h
        >
          <div class="row items-center justify-end"><q-btn v-close-popup label="Oke" color="primary" flat /></div>
        </q-time>
      </q-popup-proxy>
    </div>
  </q-input>
</div>
</template>

<style>

</style>

<script>
/* v.1.0.1
? Components Attributes
  --------------------------------------------------
  * className <attributeWithValue:string>
  > define class inner element input
  USAGE    : < className="classInput" >

  --------------------------------------------------
  * styleEl <attributeWithValue:string>
  > define style inner element input
  USAGE    : < styleEl="color:red" >

  --------------------------------------------------
  * label <attributeWithValue:any>
  > define label text
  USAGE    : < label="label input" >

  --------------------------------------------------
  * toplabel <attribute>
  > use top labels or default
  USAGE    : < toplabel >

  --------------------------------------------------
  * rules <array:QuasarDefaultRule>
  > rules like default common validation
  USAGE    : < :rules="[ val => val !== null && val !== '' || 'Field is required!']" >

  --------------------------------------------------
  * col <attributeWithValue:number>
  > define column of this element on medium breakpoint with defaul col-12 in mobile
  > value follow a breakpoint number, 1 - 12
  USAGE    : < col="3" >

  --------------------------------------------------
  * dateonly <attribute>
  > define picker as date picker only
  USAGE    : < dateonly >

  --------------------------------------------------
  * readonly <attribute>
  > define readonly element
  USAGE    : < readonly >

  --------------------------------------------------
  * bottomSlots <attribute>
  > to enable slot hint
  USAGE    : < bottomSlots >

  --------------------------------------------------
  * timeonly <attribute>
  > define picker as time picker only
  USAGE    : < timeonly >

  ? USAGE Element :
  <vl-datepicker label="DateTime Picker" v-model="modelName" />

*/
export default {
  name: 'VlDatePicker',
  props: [
    'className',
    'styleEl',
    'label',
    'value',
    'toplabel',
    'rules',
    'col',
    'dateonly',
    'readonly',
    'bottomSlots',
    'timeonly'
  ],
  data () {
    return {
      //
    }
  },

  created () {
    // console.log('col', this.col)
  },

  computed: {
    columnSize () {
      var col = 'pr-1 pb-1'
      if (this.col !== undefined) col = 'pr-1 pb-1 col-12 col-md-' + this.col
      return col
    },

    refDate () {
      var res = 'DP_'
      if (this.label !== undefined) res = this.label
      res = this.$Helper.createUID(false) + '_' + res.toLowerCase()
      res = this.$Helper.makeRef(res)
      return res
    },

    refTime () {
      var res = 'TP_'
      if (this.label !== undefined) res = this.label
      res = this.$Helper.createUID(false) + '_' + res.toLowerCase()
      res = this.$Helper.makeRef(res)
      return res
    },

    mask () {
      var res = 'YYYY-MM-DD HH:mm'
      if (this.dateonly === '') res = 'YYYY-MM-DD'
      if (this.timeonly === '') res = 'HH:mm'
      return res
    }

  },

  methods: {
    onRefresh () {
      // console.log('activate')
    },

    emiters (e) {
      // console.log('emiters input', e)
      this.$emit('input', e)
    },

    handlerClick (refDate, refTime) {
      if (this.timeonly === '') {
        this.$refs[refTime].show()
      } else {
        this.$refs[refDate].show()
      }
    },

    handlerInput (refDate, refTime) {
      if (this.dateonly === '') {
        this.$refs[refDate].hide()
      } else if (this.timeonly === '') {
        this.$refs[refTime].hide()
      } else {
        this.$refs[refDate].hide()
        this.$refs[refTime].show()
      }
    }
  }

}

</script>

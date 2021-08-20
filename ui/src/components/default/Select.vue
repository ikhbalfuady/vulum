<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}}
  </div>
  <q-select dense filled square
    :label="(label && toplabel !== '') ? label : ''"
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    :options="select.options"
    :option-value="(optionValue) ? optionValue : 'id'"
    :option-label="(optionLabel) ? optionLabel : searchField"
    :emit-value="(raw === '') ? false : true"
    map-options
    :use-input="(searchable === '' && multiple === undefined) ? true : false"
    :clearable="(searchable === '' && multiple === undefined) ? true : false"
    v-bind:value="value"
    v-on:input="emiters($event)"
    :rules="(rules) ? rules : []"
    :multiple="(multiple === '') ? true : false"
    :use-chips="(multiple === '') ? true : false"
    @filter="(val, update) => filterSelect(val, update)"
    :readonly="(readonly==='') ? true : false"
    :bottom-slots="(bottomSlots==='') ? true : false"
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
    <template v-if="multiple === undefined" v-slot:selected-item="row"> <span class="ellipsis">{{ row.opt ? row.opt[searchField] : '' }}</span> </template>
    <template v-slot:no-option>
      <q-item> <q-item-section class="text-grey"> Not found </q-item-section> </q-item>
    </template>
  </q-select>
</div>
</template>

<style>

</style>

<script>
/* v.1.0.2
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
  * options <attributeWithValue:arrayObject>
  > format must be have id & name , ex : { id:1, name: 'john'}
  USAGE    : < :options="optionList" >

  --------------------------------------------------
  * optionValue <attributeWithValue:string>
  > define selector to get binding value from option
  USAGE    : < optionValue="id" >

  --------------------------------------------------
  * optionLabel <attributeWithValue:string>
  > define selector to set label display from option
  USAGE    : < optionLabel="name" >

  --------------------------------------------------
  * multiple <attribute>
  > define type multiple or single select
  USAGE    : < toplabel >

  --------------------------------------------------
  * readonly <attribute>
  > define readonly element
  USAGE    : < readonly >

  --------------------------------------------------
  * bottomSlots <attribute>
  > to enable slot hint
  USAGE    : < bottomSlots >

  --------------------------------------------------
  * raw <attribute>
  > set false emit-value, return as raw selected data
  USAGE    : < raw >

  --------------------------------------------------
  * searchable <attribute>
  > if define make avail searching and clear input (recommended usage if source form API)
  USAGE    : < toplabel >

  ? USAGE Element :
  <vl-select label="Select" v-model="modelName" :options="select.options" />

*/
export default {
  name: 'VlSelect',
  props: [
    'className',
    'styleEl',
    'label',
    'value',
    'toplabel',
    'rules',
    'col',
    'options',
    'optionValue',
    'optionLabel',
    'multiple',
    'readonly',
    'bottomSlots',
    'raw',
    'searchable'
  ],
  data () {
    return {
      select: {
        options: [],
        optionsTmp: [] // need for base when use filter search
      }
    }
  },

  created () {
    // console.log('options:' + this.optionLabel, this.options)
    if (this.options !== undefined) {
      this.select.options = this.options
      this.select.optionsTmp = this.options
    }
  },

  watch: {
    value: function (val) {
      this.select.options = this.selectListTmp
    }
  },

  computed: {
    columnSize () {
      var col = 'pr-1 pb-1'
      if (this.col !== undefined) col = 'pr-1 pb-1 col-12 col-md-' + this.col
      return col
    },

    searchField () {
      var col = 'name'
      if (this.optionLabel !== undefined) col = this.optionLabel
      return col
    },

    selectList: { // untuk tampung options agar reactive
      // getter
      get: function () {
        var res = []
        if (this.options !== undefined) res = this.options
        return res
      },
      // setter
      set: function (newValue) {
        // console.log('value', this.value)
        this.select.options = newValue
        this.emiters()
      }
    },

    selectListTmp () {
      var res = []
      if (this.options !== undefined) res = this.options
      return res
    }
  },

  methods: {
    onRefresh () {
      // console.log('activate')
    },

    emiters (e) {
      if (e === undefined) e = this.value
      // console.log('emiters input', e)
      this.$emit('input', e)
      this.valueData = e
      this.select.options = this.selectListTmp
    },

    async filterSelect (val, update) {
      const select = {
        select: this.selectList,
        selectTmp: this.selectListTmp
      }
      var res = await this.$Helper.filterSelect(val, update, 'select', select, this.searchField)
      this.selectList = res.select
    }
  }

}

</script>

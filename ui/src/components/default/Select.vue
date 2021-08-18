<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}} <small v-if="toplabel === '' || optional === ''" class="optional">(Optional)</small>
  </div>
  <q-select dense filled
    :label="(label && toplabel !== '') ? label : ''"
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    :options="select.options"
    :option-value="(optionValue) ? optionValue : 'id'"
    :option-label="(optionLabel) ? optionLabel : 'name'"
    emit-value map-options
    :use-input="(searchable === '' && multiple === undefined) ? true : false"
    :clearable="(searchable === '' && multiple === undefined) ? true : false"
    v-bind:value="value"
    v-on:input="emiters($event)"
    :rules="(rules) ? rules : []"
    :multiple="(multiple === '') ? true : false"
    :use-chips="(multiple === '') ? true : false"
    @filter="(val, update) => filterSelect(val, update, 'options')"
  >
    <template v-if="multiple === undefined" v-slot:selected-item="row"> <span class="ellipsis">{{ row.opt.name }}</span> </template>
    <template v-slot:no-option>
      <q-item> <q-item-section class="text-grey"> Not found </q-item-section> </q-item>
    </template>
  </q-select>
</div>
</template>

<style>

</style>

<script>
/*
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
* optional <attribute>
> use top labels or default
USAGE    : < optional >

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
    'optional',
    'rules',
    'col',
    'options',
    'optionValue',
    'optionLabel',
    'multiple',
    'searchable'
  ],
  data () {
    return {
      mod: null,
      select: {
        options: [],
        optionsTmp: [] // need for base when use filter search
      }
    }
  },

  created () {
    // console.log('searchable', this.searchable)
    if (this.options !== undefined) {
      this.select.options = this.options
      this.select.optionsTmp = this.options
    }
  },

  computed: {
    columnSize () {
      var col = 'pr-1 pb-1'
      if (this.col !== undefined) col = 'pr-1 pb-1 col-12 col-md-' + this.col
      return col
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

    async filterSelect (val, update, target) {
      this.select = await this.$Helper.filterSelect(val, update, target, this.select)
    }
  }

}

</script>

<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}} <small v-if="toplabel === '' || optional === ''" class="optional">(Optional)</small>
  </div>
  <q-field
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    :label="(label && toplabel !== '') ? label : ''"
    dense filled
    v-model="valueData"
    v-bind:value="value"
    v-on:input="emiters($event)"
    >
    <template v-slot:control="{ id, floatingLabel, value }">
      <money :id="id" class="q-field__input text-right"
        :value="value" @input="val => emitModel('valueData', val)"
        v-bind="type"
        v-show="floatingLabel"
        v-on:input="emiters($event)"
      />
    </template>
  </q-field>
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
* currency <attribute>
> define mode currency
USAGE    : < currency >

? USAGE Element :
<vl-number label="Number" v-model="modelName" />

*/
export default {
  name: 'VlNumber',
  props: [
    'className',
    'styleEl',
    'label',
    'value',
    'toplabel',
    'optional',
    'rules',
    'col',
    'currency'
  ],
  data () {
    return {
      valueData: 0
    }
  },

  created () {
    // console.log('col', this.col)
    if (this.value !== undefined) this.valueData = this.value
  },

  computed: {
    columnSize () {
      var col = 'pr-1 pb-1'
      if (this.col !== undefined) col = 'pr-1 pb-1 col-12 col-md-' + this.col
      return col
    },

    type () {
      var type = this.$Config.numberOnly()
      if (this.currency !== undefined) type = this.$Config.currencyConfig()
      return type
    }
  },

  methods: {
    onRefresh () {
      // console.log('activate')
    },

    emitModel (target, val) {
      this[target] = val
    },

    emiters (e) {
      this.$emit('input', e)
    }
  }

}

</script>

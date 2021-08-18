<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}} <small v-if="toplabel === '' || optional === ''" class="optional">(Optional)</small>
  </div>
  <q-input
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    :label="(label && toplabel !== '') ? label : ''"
    dense filled
    v-bind:value="value"
    v-on:input="emiters($event)"
    :rules="(rules) ? rules : []"
  >
  </q-input >
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

? USAGE Element :
<vl-input label="Input" v-model="modelName" />

*/
export default {
  name: 'VlInput',
  props: [
    'className',
    'styleEl',
    'label',
    'value',
    'toplabel',
    'optional',
    'rules',
    'col'
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
      var col = 'pr-1 pb-1 col-12 col-md-6'
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
    }
  }

}

</script>

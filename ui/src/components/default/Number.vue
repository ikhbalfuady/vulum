<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}}
  </div>
  <q-field
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    :label="(label && toplabel !== '') ? label : ''"
    dense filled square
    v-bind:value="value"
    v-on:input="emiters($event)"
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
    <template v-slot:control="{ id, floatingLabel, value }">
      <money :id="id" class="q-field__input text-right"
        :value="value"
        v-bind="type"
        v-show="floatingLabel"
        v-on:input="emiters($event)"
        :readonly="(readonly==='') ? true : false"
      />
    </template>
  </q-field>
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
  * readonly <attribute>
  > define readonly element
  USAGE    : < readonly >

  --------------------------------------------------
  * bottomSlots <attribute>
  > to enable slot hint
  USAGE    : < bottomSlots >

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
    'rules',
    'col',
    'readonly',
    'bottomSlots',
    'currency'
  ],
  data () {
    return {
      //
    }
  },

  created () {
    // console.log('col', this.col)
  },

  updated () {
    // console.info('updated number', this.value)
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
      // console.warn('emiters ' + this.label, e)
      // if (this.value) e = this.value
      this.$emit('input', e)
    },

    binding (val) {
      // console.error('binding ' + this.label, val)
      return val
    }
  }

}

</script>

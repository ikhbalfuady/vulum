<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}}
  </div>
  <q-input v-if="toplabel === ''"
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    dense filled square
    v-bind:value="value"
    v-on:input="emiters($event)"
    :rules="(rules) ? rules : []"
    :readonly="(readonly==='') ? true : false"
    :placeholder="(placeholder) ? placeholder : ''"
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
  </q-input>

  <q-input v-if="toplabel === undefined"
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    :label="(label && toplabel !== '') ? label : null"
    dense filled square
    v-bind:value="value"
    v-on:input="emiters($event)"
    :rules="(rules) ? rules : []"
    :readonly="(readonly==='') ? true : false"
    :placeholder="(placeholder) ? placeholder : ''"
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
  * bottomSlots <attribute>
  > to enable slot hint
  USAGE    : < bottomSlots >

  --------------------------------------------------
  * readonly <attribute>
  > define readonly element
  USAGE    : < readonly >

  ? USAGE Element :
  <vl-input label="Input" v-model="modelName" />

  !Tips
  if want to use hint slot, you must be define "bottomSlots" attribute

*/
export default {
  name: 'VlInput',
  props: [
    'className',
    'styleEl',
    'label',
    'value',
    'toplabel',
    'rules',
    'col',
    'placeholder',
    'bottomSlots',
    'readonly'
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

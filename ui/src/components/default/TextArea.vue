<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}}
  </div>
  <q-input
    type="textarea"
    :class="(className) ? className : ''"
    :style="(styleEl) ? styleEl : ''"
    :label="(label && toplabel !== '') ? label : ''"
    dense filled square
    v-bind:value="value"
    v-on:input="emiters($event)"
    :rules="(rules) ? rules : []"
    :rows="rows ? rows : 6"
  >
  </q-input >
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

  ? USAGE Element :
  <vl-textarea label="TextArea" v-model="modelName" />

  ! Tips
  if want to set height of content textarea, use style for sizing ex : height:10px
*/
export default {
  name: 'VlTextArea',
  props: [
    'className',
    'styleEl',
    'label',
    'value',
    'toplabel',
    'rules',
    'col',
    'rows'
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

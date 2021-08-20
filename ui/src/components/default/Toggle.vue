<template>
<div :class="columnSize" >
  <div v-if="toplabel === ''" class="bold capital text-primary mh-1" >
    {{(label) ? label : ''}}
  </div>
  <q-toggle floating dense
    :size="(size) ? size : 'md'"
    :icon="(icon) ? icon : ''"
    :class="(block === '') ? 'bold toggle-block text-caption' : 'bold toggle-vertical text-caption'"
    :style="(styleEl) ? styleEl : ''"
    :label="(label && toplabel !== '') ? label : ''"
    emit-value
    v-bind:value="value"
    v-on:input="emiters($event)"
  />
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
  * icon <attributeWithValue:string>
  > define icon on toggle
  USAGE    : < icon="power" >

  --------------------------------------------------
  * size <attributeWithValue:string>
  > define size on toggle, default set is 'md', avail : xs, sm, md, lg
  USAGE    : < size="md" >

  --------------------------------------------------
  * block <attribute>
  > set display block toogle
  USAGE    : < block >

  ? USAGE Element :
  <vl-toggle label="Toggle" v-model="modelName" />
*/
export default {
  name: 'VlToggle',
  props: [
    'className',
    'styleEl',
    'label',
    'value',
    'toplabel',
    'rules',
    'icon',
    'size',
    'block',
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

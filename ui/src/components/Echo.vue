<template>
  <div :class="(className) ? className : 'bg-dark text-orange'" :style="(styleEl) ? styleEl : 'height:200px; overflow-x:scroll'" >
      <pre v-if="data" style="font-size:11px; font-family:'Consolas'">{{data}}</pre>
  </div>
</template>

<style>
.text-footer {
  font-size: 10px;
  opacity: 0.9;
  padding-top: 5px;
  padding-left: 10px;
}

.txtbold {
  font-weight: 600;
}
</style>

<script>
export default {
  name: 'Echo',
  props: ['className', 'styleEl', 'data'],
  data () {
    return {
      dataModel: null
    }
  },

  created () {
    //
  },

  mounted () {
    //
  },

  methods: {
    onRefresh () {
      console.log('activate')
    }
  }

}

</script>

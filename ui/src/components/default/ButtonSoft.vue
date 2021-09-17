<template>
  <div>
    <q-btn
      flat dense
      :class="classEl"
      :color="color"
      @click="clicked"
    >
      <q-icon v-if="icon" :name="icon" style="font-size:1em !important; padding-right:5px;"/>
      <span v-if="label">{{label}}</span>
    </q-btn>
  </div>
</template>

<script>
export default {
  name: 'ButtonSoft',
  props: [
    'className',
    'styleEl',
    'color',
    'icon',
    'label',
    'background',
    'click',
    'data'
  ],
  data () {
    return {
      dataModel: null
    }
  },

  computed: {
    bgColor () {
      var color = 'blue'
      if (this.color) color = this.color
      if (this.background) color = this.background
      return color
    },

    textColor () {
      var color = 'blue'
      color = (this.color) ? this.color : color
      return color
    },

    classEl () {
      var cls = 'animated zoomIn mr-1 capital pv-1 fix-icon-btn '
      cls = `${cls} bg-${this.bgColor}-1 text-${this.textColor}-9 `
      cls = (this.className) ? cls + this.className : cls
      return cls
    }
  },

  methods: {
    onRefresh () {
      console.log('activate')
    },

    clicked () {
      this.$emit('click')
    }
  }

}

</script>

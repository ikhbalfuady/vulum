<template>
  <div>
    <q-btn
      flat dense
      :class="classEl"
      color="green"
    >
      <q-icon v-if="icon" :name="icon" style="font-size:1em !important; padding-right:5px;"/>
      <span v-if="label">{{label}}</span>
    </q-btn>
  </div>
</template>

<script>
export default {
  name: 'Echo',
  props: [
    'className',
    'styleEl',
    'color',
    'icon',
    'label',
    'background',
    'data'
  ],
  data () {
    return {
      dataModel: null
    }
  },

  computed: {
    bgColor () {
      return (this.background) ? this.background : 'blue'
    },

    textColor () {
      return (this.color) ? this.color : 'blue'
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
    }
  }

}

</script>

<template>
  <div>
    <q-dialog v-model="show" @show="onShow()"
      :persistent="persistent" maximized flat class="card-modal" transition-show="jump-up" transition-hide="jump-down" >
        <q-card :style="'width: '+optimizeWidth()+'; height: '+height+'; margin: 40px auto;'" >
          <q-card-section class="row items-center bg-primary">
            <div class="text-h6 text-light">{{title}}</div>
            <q-space />
            <q-btn icon="close" flat round dense v-close-popup class="text-light" />
          </q-card-section>

          <q-scroll-area ref="modalArea" :style="'height: '+contentHeight+'; '" >
            <slot></slot>
          </q-scroll-area>
          <q-resize-observer @resize="onResize" />
        </q-card>
      </q-dialog>
  </div>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    config: Object
  },
  data () {
    return {
      currentBreakPoint: ''
    }
  },

  created () {
    //
  },

  mounted () {
    //
  },

  computed: {
    show: {
      get: function () {
        return (this.config.show) ?? true
      },
      set: function (newValue) {
        this.config.show = newValue
      }
    },
    title () {
      return (this.config.title) ?? 'Modal'
    },
    persistent () {
      return (this.config.persistent) ?? false
    },
    height () {
      return (this.config.height) ?? '85vh'
    },
    width () {
      return (this.config.width) ?? '60vw'
    },
    contentHeight () {
      return (this.config.contentHeight) ?? '75vh'
    },
    closeBtn () {
      return (this.config.close_btn) ?? true
    },
    toolbar () {
      return (this.config.toolbar) ?? true
    }
  },

  methods: {
    onShow () {
      // console.log('modal loaded', this.config)
      this.$refs.modalArea.$el.click() // handler to enable scroll
    },

    onResize (size) {
      this.currentBreakPoint = this.$q.screen.name
    },

    optimizeWidth () {
      var res = this.$Helper.optimizeWidthDialog(this.width, this.currentBreakPoint)
      return res
    }
  }

}

</script>

<template>
  <div class="row pl-2 pt-2 bg-light" v-if="!inModal">
    <div class="col-12 col-sm-5 col-md-6 pb-1 pv info-page">
      <div class="title">
        <span class="text-caption text-grey-7">Master {{(meta) ? meta.name : 'Page'}}</span><br>
        <q-btn v-if="backEvt" @click="backEventHandle" rounded icon="arrow_back" flat dense class="mr-1" color="grey-9"/>
        <span class="text-h5 bold text-dark capital">{{overideTitle}}</span>
      </div>
    </div>

    <div v-if="!isHalf && $slots.config" class="col-2 col-sm-2 col-md-2 pb-1 pr-1">
      <div class="btn-filter" >
        <q-btn unelevated color="primary" class="capital" :icon="(cofigIcon) ? cofigIcon : 'filter_list'">
          <q-popup-proxy ref="popupFilter" transition-show="jump-up" transition-hide="jump-down">
            <q-banner dense>
              <div class="row pt-1 pl-1 pb-1" style="min-width:320px">
                <div class="col-12 text-grey-7 bold pb-1 pt">{{(cofigLabel) ? cofigLabel : 'Filter'}}</div>
                <slot name="config"></slot>
                <div class="col-12 text-grey-7 bold pb-1 pt pr-1 right">
                  <q-btn dense label="apply" unelevated flat class="capital bg-blue-1 text-blue-9 pv-1" color="blue" @click="$refs.popupFilter.hide()"/>
                </div>
              </div>
            </q-banner>
          </q-popup-proxy>
          <span class="gt-xs pl-1">{{(cofigLabel) ? cofigLabel : 'Filter'}}</span>
        </q-btn>
      </div>
    </div>

    <div v-if="!isHalf && $slots.right" class="col-10 col-sm-5 col-md-4 pb-1 pr-1-5">
      <slot name="right"></slot>
    </div>

    <div v-if="$slots.half" class="col-6 pb-1 pr-1-5 right">
      <slot name="half"></slot>
    </div>

    <div v-if="$slots.bottom" class="col-12 pb-1 pr-1-5">
      <slot name="bottom"></slot>
    </div>

  </div>
</template>

<script>
/*
SLOT Avail :
- config : on popup
- right : right side
- half : right side with half area
- bottom : new line in bottom
*/

export default {
  name: 'HeaderTitle',
  props: ['meta', 'cofigLabel', 'cofigIcon', 'isModal', 'backToRoot', 'halfSlot', 'title', 'formMode'],
  data () {
    return {
      dataModel: null,
      backEvent: null
    }
  },

  created () {
    //
  },

  mounted () {
    this.backEvent = this.backEvt
  },

  computed: {
    //
    inModal () {
      return this.isModal ?? false
    },

    backEvt () {
      return this.backToRoot ?? false
    },

    isHalf () {
      return this.halfSlot ?? false
    },

    overideTitle () {
      var res = this.title ? this.title : this.meta.name
      console.log(this.formMode)
      if (this.formMode === '') {
        var id = this.$Handler.getParamId(this)
        res = (id) ? 'Update ' + this.meta.name : 'Create ' + this.meta.name
      }
      return res
    }
  },

  methods: {

    backEventHandle (event) {
      if (event) event()
    },

    onRefresh () {
      console.log('activate')
    }
  }

}

</script>

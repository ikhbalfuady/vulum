<template>
  <div>

    <!-- <q-file v-if="uploadFile === null" class="hidden" @input="FP_getThumb(subFix)" ref="fuSrc" v-model="uploadFile" /> -->
    <q-file auto-upload max-file-size="2097152" accept=".jpg, .png, .jpeg" @rejected="onRejected" v-if="uploadFile === null" class="hidden" @input="FP_getThumb(subFix)" ref="fuSrc" v-model="uploadFile" />
    <q-btn v-if="uploadFile !== null" @click="FP_remove(subFix)" color="red" icon="close" size="xs" round unelevated style="position: absolute;" />
    <img ref="fuThumb" @click="FP_select(subFix)" src="assets/noimg.png" style="width:100%" class="img-uploader link"/>
    <div v-if="uploadFile === null" class="text-center">
      <small class=" text-grey-6">Click image to change</small>
    </div>

  </div>
</template>
<style>
</style>

<script>
export default {
  name: 'ImageUploader',
  props: ['data'],
  data () {
    return {
      uploadFile: null,
      subFix: ''
    }
  },

  mounted () {
    // this.uploadFile = this.data
    if (this.data.subFix !== undefined) this.subFix = this.data.subFix
    console.log('imgUpload', this.data)
    if (this.data) {
      this.uploadFile = this.data.file
      this.subFix = this.data.subFix
    }
    if (typeof this.data.file === 'string') this.FP_setThumb(this.subFix, this.data.file) // set file image jika file berupa url
  },

  methods: {

    onRejected () {
      this.$q.notify({
        type: 'negative',
        message: 'Only .jpg, .png and .jpeg file with max 2MB file size allowed'
      })
    },

    updateData () {
      this.FP_getThumb()
      this.$emit('q-file', this.uploadFile)
    },

    FP_select (subfix = '') {
      var refName = 'fuSrc' + subfix
      var ref = this.$refs[refName]
      // console.log('ref', refName, ref)
      ref.$el.childNodes[0].click()
    },

    FP_getThumb (subfix = '') {
      // getting source
      var srcThumb = 'fuSrc' + subfix
      var file = this.$refs[srcThumb]
      file = file.$refs.input.files[0]
      // console.log('fuSrc', srcThumb, file)
      var url = URL.createObjectURL(file)
      this.FP_setThumb(subfix, url)

      if (this.data.callback !== undefined) this.data.callback(this.uploadFile)
    },

    FP_setThumb (subfix, url, reset = false) {
      // binding source
      var imgRef = 'fuThumb' + subfix
      var img = this.$refs[imgRef]
      // console.log(imgRef, reset, url, this.$refs, img)
      if (img !== undefined) {
        if (reset === true) img.src = 'assets/noimg.png'
        else if (url) img.src = url
      } else img.src = 'assets/noimg.png'
    },

    FP_remove (subfix = '') {
      this.uploadFile = null
      this.FP_setThumb(subfix, null, true)
    }

  }
}
</script>

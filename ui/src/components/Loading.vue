<template>
  <div>
    <div class="text-center mt-5 pt-5 mb-5 pb-5">
      <q-spinner-facebook class="animated fadeInDown"
          color="primary"
          size="7em"
      /> <br>
      <div class="text-grey-7 animated fadeInUp" >Loading...</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Loading',
  props: ['data']
}
</script>

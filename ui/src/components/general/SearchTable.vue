<template>
<div>
  <q-input debounce="300" :placeholder="`Search by ${table.searchBySelected.name} ...`" outlined dense class="fix-after bg-grey-3"
    v-bind:value="value"
    v-on:input="emiters($event)"
    >
    <template v-slot:append>
      <q-icon v-if="table.search !== ''" name="close" @click="table.search = ''" class="cursor-pointer" />
      <q-icon name="search" />
    </template>
  </q-input>
</div>
</template>

<style>

</style>

<script>

export default {
  name: 'SearchTable',
  props: [
    'value',
    'table'
  ],
  data () {
    return {
      //
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

<template>
  <span>

    <span v-if="data.created_by_user">
      <q-badge color="primary">C</q-badge>
      {{ data.created_by_user ? `${data.created_by_user.name} -` : '' }} {{ $Helper.logInfo(data.created_at) }}
    </span><br>

    <span v-if="data.updated_by_user">
      <q-badge color="green">U</q-badge>
      {{ data.updated_by_user ? `${data.updated_by_user.name} -` : '' }} {{ $Helper.logInfo(data.updated_at) }}
    </span><br>

     <span v-if="data.deleted_by_user">
      <q-badge color="red">D</q-badge>
      {{ data.deleted_by_user ? `${data.deleted_by_user.name} -` : '' }} {{ $Helper.logInfo(data.deleted_at) }}
    </span>

  </span>
</template>

<script>
export default {
  name: 'LogInfo',
  props: ['data']
}
</script>

<template>
  <div class="fixed-center text-center">
    <p>
      <q-icon name="o_lock" size="15em" class="text-primary" />
    </p>
    <h5 class="bold">Unauthorized <strong>(401)</strong></h5>
    <p class="text-faded">
      Opps!, You don't have to accesed this page!
    </p>
    <q-btn
      icon="arrow_left"
      color="primary"
      style="width:200px;"
      to="/"
      label="Go Back"
    />
  </div>
</template>

<script>
export default {
  name: 'Error404'
}
</script>

<template>
  <q-layout view="lhh lpR lFr">

    <q-header reveal class="bg-primary text-white">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="left = !left" />

        <q-toolbar-title>
          Title
        </q-toolbar-title>

        <q-btn dense flat round icon="menu" @click="right = !right" />
      </q-toolbar>
    </q-header>

    <q-drawer show-if-above v-model="left" side="left" behavior="desktop">
      <!-- drawer content -->
    </q-drawer>

    <q-drawer v-model="right" side="right" behavior="desktop">
      <!-- drawer content -->
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
export default {
  data () {
    return {
      left: false,
      right: false
    }
  }
}
</script>

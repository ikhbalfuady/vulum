<template>
  <div>
    <q-drawer v-model="notifArea" side="right" overlay class="right-menu" >
      <!-- drawer content -->
      <div class="bg-secondary">
        <div class=" ph-2 pv-2">
          <q-btn @click="notifArea = false" icon="close" flat color="light" size="lg" dense />
          <q-btn @click="getNotif()" flat size="lg" dense color="light" label="Notifications" class="capital">
            <q-badge v-if="notifList.length !== 0" color="red" floating transparent>{{notifList.length}}</q-badge>
          </q-btn>
        </div>
      </div>
      <div class="pt" style="max-width: 350px">

        <div v-if="notifSkeleton">
          <q-item v-for="item in 4" :key="item.id">
            <q-item-section avatar> <q-skeleton type="QAvatar" animation="fade" /> </q-item-section>
            <q-item-section>
              <q-item-label> <q-skeleton type="rect" animation="fade" /> </q-item-label>
              <q-item-label caption> <q-skeleton type="text" animation="fade" /> </q-item-label>
            </q-item-section>
          </q-item>
        </div>

        <q-item v-if="notifList.length === 0 && !notifSkeleton" class="animated fadeIn">
          <q-item-section>
            <q-item-label caption>There are no recent notifications for you </q-item-label>
          </q-item-section>
        </q-item>

        <q-list v-for="(notif, index) in notifList" :key="index+'notif'" class="animated fadeIn">

          <q-item clickable v-ripple @click="readNotif(notif.id)" class="animated fadeIn">
            <q-item-section avatar top>
              <q-avatar :icon="notif.icon" color="light-blue" text-color="white" />
            </q-item-section>

            <q-item-section>
              <q-item-label>{{notif.title}}</q-item-label>
              <q-item-label caption lines="4" v-html="notif.description"></q-item-label>
              <q-item-label caption lines="4">
                <q-btn color="light-blue-1" unelevated size="xs" @click="openLinkNotif(notif.link_path, notif.link_params)" >
                <span class="text-light-blue-10 capital">View Detail</span>
                </q-btn>
              </q-item-label>
              <q-item-label class="text-orange-10 italic" caption>{{notif.time}}</q-item-label>
            </q-item-section>

          </q-item>

          <q-separator inset="item" />

        </q-list>

      </div>
    </q-drawer>

  </div>
</template>

<script>
export default {
  name: 'Notifications',
  props: ['data'],
  data () {
    return {
      API: this.$Api,
      notifSkeleton: false,
      notifList: []
    }
  },

  created () {
    //
  },

  mounted () {
  },

  computed: {
    notifArea: {
      set: function (val) {
        this.$store.dispatch('GlobalState/notificationsAction', val)
      },
      get: function () {
        this.getNotif()
        return this.$store.state.GlobalState.notifications
      }
    }
  },

  methods: {
    onRefresh () {
      var cre = this.$Config.credentials()
      this.user = (cre.user) ? cre.user : cre
    },

    openLinkNotif (path, params) {
      this.$router.push({ name: path, params: params })
    },

    readNotif (id) {
      // this.$Helper.loading()
      this.API.get(`/user-notifications/${id}/read`, (status, data, message, response, full) => {
        this.$Helper.loading(false)
      })
    },

    getNotif () {
      this.notifList = []
      this.notifSkeleton = true
      // this.$Helper.loading()
      this.API.get('me/notifications', (status, data, message, response, full) => {
        this.notifSkeleton = false
        this.$Helper.loading(false)
        if (status === 200) {
          this.notifList = data
          this.$Handler.notifications(data)
          // console.log('notif', data)
        }
      })
    }
  }

}

</script>

<style>
.right-menu aside {
  box-shadow: 0 0 28px 0 rgba(82,63,105, 0.3);
}
</style>

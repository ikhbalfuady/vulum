import Vue from 'vue'
import Drawer from '../components/Drawer'
import UserMenu from '../components/UserMenu'
import ProfilePopup from '../components/ProfilePopup'

// we globally register our component
Vue.component('drawer', Drawer)
Vue.component('user-menu', UserMenu)
Vue.component('profile-popup', ProfilePopup)

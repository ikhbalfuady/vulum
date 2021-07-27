import Vue from 'vue'
import Drawer from '../components/Drawer'
import UserMenu from '../components/UserMenu'
import ProfilePopup from '../components/ProfilePopup'
import LogInfo from '../components/LogInfo'
import ImageUploader from '../components/ImageUploader'
import Echo from '../components/Echo'

// we globally register our component
Vue.component('drawer', Drawer)
Vue.component('user-menu', UserMenu)
Vue.component('profile-popup', ProfilePopup)
Vue.component('log-info', LogInfo)
Vue.component('img-uploader', ImageUploader)
Vue.component('echo', Echo)

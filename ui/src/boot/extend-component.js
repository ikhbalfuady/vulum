import Vue from 'vue'
import Drawer from '../components/Drawer'
import UserMenu from '../components/UserMenu'
import ProfilePopup from '../components/general/ProfilePopup'
import LogInfo from '../components/LogInfo'
import ImageUploader from '../components/ImageUploader'
import Echo from '../components/Echo'

// general
import Loading from '../components/general/Loading'
import SideMenu from '../components/general/SideMenu'
import TopMenu from '../components/general/TopMenu'
import HeaderTitle from '../components/general/HeaderTitle'
import Notifications from '../components/general/Notifications'
import SearchTable from '../components/general/SearchTable'
import Modal from '../components/general/Modal'

Vue.component('loading', Loading)
Vue.component('side-menu', SideMenu)
Vue.component('top-menu', TopMenu)
Vue.component('header-title', HeaderTitle)
Vue.component('search-table', SearchTable)
Vue.component('notifications', Notifications)
Vue.component('modal', Modal)

// components default
import _Input from '../components/default/Input'
import _Number from '../components/default/Number'
import _Select from '../components/default/Select'
import _TextArea from '../components/default/TextArea'
import _Toggle from '../components/default/Toggle'
import _DatePicker from '../components/default/DatePicker'
import _SelectServerSide from '../components/default/SelectServerSide'

// we globally register our component
Vue.component('drawer', Drawer)
Vue.component('user-menu', UserMenu)
Vue.component('profile-popup', ProfilePopup)
Vue.component('log-info', LogInfo)
Vue.component('img-uploader', ImageUploader)
Vue.component('echo', Echo)

Vue.component('vl-input', _Input)
Vue.component('vl-number', _Number)
Vue.component('vl-select', _Select)
Vue.component('vl-textarea', _TextArea)
Vue.component('vl-toggle', _Toggle)
Vue.component('vl-datepicker', _DatePicker)
Vue.component('vl-select-serverside', _SelectServerSide)

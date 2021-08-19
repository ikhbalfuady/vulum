import Vue from 'vue'
import Drawer from '../components/Drawer'
import UserMenu from '../components/UserMenu'
import ProfilePopup from '../components/ProfilePopup'
import LogInfo from '../components/LogInfo'
import ImageUploader from '../components/ImageUploader'
import Echo from '../components/Echo'
import Loading from '../components/Loading'
import SideMenu from '../components/SideMenu'
import TopMenu from '../components/TopMenu'
import HeaderTitle from '../components/HeaderTitle'

// components default
import _Input from '../components/default/Input'
import _Number from '../components/default/Number'
import _Select from '../components/default/Select'
import _TextArea from '../components/default/TextArea'
import _Toggle from '../components/default/Toggle'
import _DatePicker from '../components/default/DatePicker'
import _SelectServerSide from '../components/default/SelectServerSide'
import _SearchTable from '../components/default/SearchTable'

// we globally register our component
Vue.component('drawer', Drawer)
Vue.component('user-menu', UserMenu)
Vue.component('profile-popup', ProfilePopup)
Vue.component('log-info', LogInfo)
Vue.component('img-uploader', ImageUploader)
Vue.component('echo', Echo)
Vue.component('loading', Loading)
Vue.component('loading', Loading)
Vue.component('side-menu', SideMenu)
Vue.component('top-menu', TopMenu)
Vue.component('header-title', HeaderTitle)
Vue.component('search-table', _SearchTable)

Vue.component('vl-input', _Input)
Vue.component('vl-number', _Number)
Vue.component('vl-select', _Select)
Vue.component('vl-textarea', _TextArea)
Vue.component('vl-toggle', _Toggle)
Vue.component('vl-datepicker', _DatePicker)
Vue.component('vl-select-serverside', _SelectServerSide)

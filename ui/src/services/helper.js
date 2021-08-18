/* eslint-disable quote-props */
/* eslint-disable no-prototype-builtins */
import {
  LocalStorage,
  Dialog,
  Notify,
  uid,
  format,
  Loading,
  LoadingBar,
  QSpinnerFacebook,
  date,
  exportFile,
  openURL,
  Platform,
  scroll
} from 'quasar'

const { getScrollTarget, setScrollPosition } = scroll

export const Helper = {
  test () {
    console.log('Halo Helper berhasil di panggil')
  },

  scrollTo (el, customOffset = null) {
    let offset = el.offsetTop
    if (customOffset !== null) offset = customOffset
    const target = getScrollTarget(el)
    const duration = 300
    setScrollPosition(target, offset, duration)
  },

  getPlatform () {
    var pf = Platform.is
    console.log('Platform : ', pf)
    return pf
  },

  openLink (url) {
    openURL(url)
  },

  findString (string, keyword) {
    var result = string.search(keyword)
    var res = false
    if (result < 0) res = false
    else res = true

    // console.log('find: (' + keyword + ') =>' + string, res)
    return res
  },

  exportFile (fileName, data) {
    const status = exportFile(fileName, data)

    if (status === true) {
      // browser allowed it
      console.log(status)
    } else {
      Helper.showToast('Error: ' + status)
      // browser denied it
      console.log('Error: ' + status)
    }
  },

  console (str, bg = '#6a12a5', color = '#fff') {
    console.info('%c ' + str + ' ', 'background:' + bg + ';color:' + color + '')
  },

  replace (target, replace, str) {
    var string = '' + str
    var regex = new RegExp(target, 'g')

    return string.replace(regex, replace)
  },

  loadingOverlay (show = true, msg = 'Loading...') {
    /* This is for Codepen (using UMD) to work */
    const spinner = typeof QSpinnerFacebook !== 'undefined'
      ? QSpinnerFacebook // Non-UMD, imported above
      : Quasar.components.QSpinnerFacebook // eslint-disable-line
    /* End of Codepen workaround */

    Loading.show({
      spinner,
      spinnerColor: 'white',
      spinnerSize: 140,
      backgroundColor: 'primary',
      message: msg,
      messageColor: 'white'
    })

    if (show === false) {
      setTimeout(() => {
        Loading.hide()
      }, 300)
    }
  },

  loading (show = true) {
    LoadingBar.setDefaults({
      color: 'orange',
      size: '5px',
      position: 'top'
    })

    if (show === false) {
      setTimeout(() => {
        LoadingBar.stop()
      }, 300)
    } else LoadingBar.start()
  },

  createUID () {
    var serial = uid()
    var d = new Date()
    serial = serial + btoa(d)
    return serial
  },

  getFileSize (size) {
    return format.humanStorageSize(size)
  },

  capitalize (string) {
    return format.capitalize(string)
  },

  beautySize (size) {
    return format.humanStorageSize(size)
  },

  autoNumber (number = 1, size = 4) {
    return format.pad(number, size)
  },

  getKeySession () {
    var d = this.createYMD(this.getDateNow(), '')
    d = 'usersessionlogin' + d
    return d
  },

  checkLogin (params) {
    console.log('checkLDB', this.checkLdb(this.getKeySession()))
    if (this.checkLdb(this.getKeySession()) === false) {
      this.showAlert('Opps!', 'Anda tidak memiliki akses kesini!')
      params.push({
        name: 'login'
      })
    }
  },

  logout (params) {
    this.deleteLdb(this.getKeySession())
    params.push({
      name: 'index'
    })
  },

  saveLdb (key, ret) {
    // var data = JSON.stringify(ret)
    return LocalStorage.set(key, ret)
    // return LocalStorage.set(key, btoa(data))
  },

  checkLdb (key) {
    var data = LocalStorage.has(key)
    if (data !== true) return false
    else return true
  },

  getLdb (key) {
    var data = LocalStorage.getItem(key)
    if (!this.checkLdb(key)) console.info('LDB [' + key + '] Not found!')
    return data
    // return JSON.parse(atob(data))
  },

  deleteLdb (key) {
    return LocalStorage.remove(key)
  },

  getFromLdb (ldbName, defaultData = null) {
    if (this.checkLdb(ldbName)) {
      if (this.getLdb(ldbName) !== null) defaultData = this.getLdb(ldbName)
    }
    return defaultData
  },

  geolocate () {
    navigator.geolocation.getCurrentPosition(position => {
      var marker = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      }

      return marker
    })
  },

  setFormData (_params) {
    var params = new FormData()
    var arr = [_params]

    for (var i = 0, l = arr.length; i < l; i++) {
      var keys = Object.keys(arr[i])
      for (var j = 0, k = keys.length; j < k; j++) {
        params.append(keys[j], arr[i][keys[j]])
        // console.log('Key:' + keys[j] + '  Value:' + arr[i][keys[j]])
      }
    }
    return params
  },

  successAlertBody (title, msg = null, className = 'text-center') {
    var body = '<div class="success-checkmark"> <div class="check-icon "> <span class="icon-line line-tip "></span> <span class="icon-line line-long"></span> <div class="icon-circle"></div> <div class="icon-fix"></div> </div> </div>'
    var judul = '<div class="text-center q-dialog__title text-primary capital">' + title + '</div>'
    var pesan = '<div class="' + className + ' animated fadeIn">' + msg + '</div>'
    return body + judul + pesan
  },

  showSuccess (title, msg = null, className = 'text-center') {
    Dialog.create({
      transitionShow: 'jump-up',
      transitionHide: 'jump-down',
      title: '',
      message: this.successAlertBody(title, msg, className),
      html: true
    }).onOk(() => {
      // console.log('OK')
    }).onCancel(() => {
      // console.log('Cancel')
    }).onDismiss(() => {
      // console.log('I am triggered on both OK and Cancel')
    })
  },

  showAlert (title, msg = null, persistent = false) {
    var judul = title
    var pesan = msg
    if (title === 'try') {
      judul = 'Opps!'
      pesan = 'Terjadi kesalahan saat menghubungkan ke server, harap coba lagi atau tekan Refresh!'
    }

    Dialog.create({
      transitionShow: 'jump-up',
      transitionHide: 'jump-down',
      title: judul,
      message: pesan,
      html: true,
      persistent: persistent
    }).onOk(() => {
      // console.log('OK')
    }).onCancel(() => {
      // console.log('Cancel')
    }).onDismiss(() => {
      // console.log('I am triggered on both OK and Cancel')
    })
  },

  showNotif (title, msg, color = 'dark') {
    Notify.create({
      title: title,
      message: msg,
      position: 'top-right',
      icon: 'info',
      color: color,
      timeout: 5000,
      actions: [{ label: 'x', color: 'yellow', handler: () => { /* console.log('wooow') */ } }]
    })
  },

  showToast (msg, timeout = 5000, position = 'top-right', caption = '', color = 'dark') {
    Notify.create({
      progress: true,
      message: msg,
      position: position,
      icon: 'info',
      color: color,
      timeout: timeout,
      caption: caption,
      actions: [{ label: 'x', color: 'yellow', handler: () => { /* console.log('wooow') */ } }]
    })
  },

  showInfo (mode) {
    if (mode === 'save') this.showToast('Data berhasil disimpan!', 'green')
    else if (mode === 'update') this.showToast('Data berhasil diubah!', 'green')
    else if (mode === 'delete') this.showToast('Data berhasil dihapus!', 'red')
    else this.showToast('Opps!', 'yellow')
  },

  showError (e, json) {
    var err = e
    if (json) err = JSON.stringify(e)
    this.showAlert('ERROR', err)
  },

  showFailProcess (e, json) {
    var err = e
    if (json) err = JSON.stringify(e)
    this.showAlert('Failed Process', err)
  },

  checkParams (params) {
    if (Object.keys(params).length === 0) return false
    else return true
  },

  checkUrlParams (params) {
    if (params === undefined || params === null) return false
    else return true
  },

  // chart method helper
  getDataByLabel (data, label) {
    var res = []
    for (let index = 0; index < label.length; index++) {
      var lbl = label[index]
      res.push(data[lbl])
    }
    return res
  },

  groupDataBy (data, target) {
    var result = {}
    for (var key in data) {
      var row = data[key][target]
      if (!result[row]) result[row] = []
      result[row].push(data[key])
    }
    return result
  },

  getListKey (data, target) {
    var grouped = this.groupDataBy(data, target)
    // getting list name of target
    var res = []
    for (var i in grouped) {
      res.push(i)
    }
    return res
  },

  createLabelFromKey (data) {
    return Object.keys(data)
  },

  convertTypeDate (d) {
    var tzoffset = new Date(d).getTimezoneOffset() * 60000 // offset in milliseconds
    var localISOTime = new Date(d - tzoffset).toISOString().slice(0, -1)
    var res = localISOTime.split('T')
    return res[0]
  },

  setCurrency2 (number, format = 'id-ID') {
    // console.log(new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(number));
    // var a = new Intl.NumberFormat(format, { style: 'currency', currency: 'IDR' }).format(number)
    var a = new Intl.NumberFormat(format).format(number)
    return a
  },

  setCurrency (number, lang) {
    // if string use plus in front  = +val
    // var isMinus = false
    // if (number < 0) isMinus = true

    var language = 'US'
    if (lang !== undefined) language = lang

    if (number === null || number === undefined) number = 0
    var Currency = number
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    Currency = Currency.toLocaleString(language)
    // if (isMinus) return '-' + Currency
    // else return Currency
    return Currency
  },

  base64 (str, type) {
    if (type === 'enc') return btoa(str)
    else return atob(str)
  },

  getFirstChar (str) {
    return str.charAt(0)
  },

  fixNumber (_val) {
    // console.log(_val.length);
    var val = _val
    if (_val === '') val = '0'
    else if (_val === null) val = '0'
    else if (_val === undefined) val = '0'
    // eslint-disable-next-line use-isnan
    else if (_val === NaN) val = '0'
    else if (_val.length < 3) return +_val
    var value = this.toString(val)
    value = value.replace('.', '')
    value = value.replace('.', '')
    value = value.replace('.', '')
    value = value.replace('.', '')
    value = value.replace(',', '.')
    // console.log("clean :",value);
    return +value
  },

  toNumber (_val) {
    // var value = this.toString(_val)
    var val = _val
    if (_val === '') val = '0'
    else if (_val === null) val = '0'
    else if (_val === undefined) val = '0'
    else if (_val.length < 3) return +_val
    var value = '.' + val
    value = value.replace('.', '')
    value = value.replace('.', '')
    value = value.replace('.', '')
    value = value.replace('.', '')
    value = value.replace('.', '')
    value = value.replace('.', '')
    value = value.replace(',', '.')
    return +value
  },

  sumFromArray (prop, array) {
    var total = 0
    for (var i of array) {
      total += +i[prop]
    }
    return total
  },

  beautyDate (date, csep, day, sortmode) {
    // (datevalue , content separator,with day or not == true/false) params date , with day, separator
    var tanggal = new Date(date),
      hari = tanggal.getDay(),
      tgl = tanggal.getDate(),
      bln = tanggal.getMonth(),
      thn = tanggal.getFullYear(),
      jam = tanggal.getHours(),
      menit = tanggal.getMinutes()
    // var sbulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
    var sbulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    var shari = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']

    var sep = ''
    if (csep === null || csep === '') sep = ' '
    else sep = csep

    var beauty = ''
    if (day === null || day === '') beauty = '' + tgl + '' + sep + '' + sbulan[bln] + '' + sep + '' + thn + ''
    else if (day === 'full') beauty = '' + shari[hari] + ', ' + tgl + '' + sep + '' + sbulan[bln] + '' + sep + '' + thn + ' ' + jam + ':' + menit + ''
    else beauty = '' + shari[hari] + ', ' + tgl + '' + sep + '' + sbulan[bln] + '' + sep + '' + thn + ''

    if (sortmode) beauty = '' + sbulan[bln] + ' ' + tgl + ', ' + thn + ''
    return beauty
  },

  toDate (tanggal, format = 'YYYY-MM-DD') { // full date format sql : YYYY-MM-DD HH:MM / beauty DD MMM YYYY
    return date.formatDate(tanggal, format)
  },

  createYMD (_date, time) {
    // create ymd system
    var tanggal = new Date(_date),
      tgl = tanggal.getDate(),
      bln = tanggal.getMonth() + 1,
      thn = tanggal.getFullYear(),
      jam = tanggal.getHours(),
      menit = tanggal.getMinutes()

    if (bln < 10) bln = '0' + bln
    if (tgl < 10) tgl = '0' + tgl
    if (jam < 10) jam = '0' + jam
    if (menit < 10) menit = '0' + menit

    var date = ''
    if (time === undefined || time === '') date = thn + '-' + bln + '-' + tgl + ''
    else date = thn + '-' + bln + '-' + tgl + ' ' + jam + ':' + menit + ':00'
    return date
  },

  getDateNow (days = 0) {
    var tzoffset = new Date().getTimezoneOffset() * 60000 // offset in milliseconds
    var localISOTime = new Date(Date.now() - tzoffset)
      .toISOString()
      .slice(0, -1)
    var res = localISOTime.split('T')
    var time = res[1]
    time = time.split(':')
    var hasil = res[0] + ' ' + time[0] + ':' + time[1]

    if (days !== 0) {
      var addDate = new Date(hasil)
      addDate.setDate(addDate.getDate() + days)
      hasil = this.createYMD(addDate, true)
    }
    return hasil
  },

  getTimeNow () {
    var tanggal = new Date(this.getDateNow()),
      jam = tanggal.getHours(),
      menit = tanggal.getMinutes()
    return jam + ':' + menit
  },

  today () {
    return Helper.createYMD(this.getDateNow(), true)
  },

  findObjectByKey (array, key, value, getIndex = false) {
    // console.log('findObjectByKey', array)
    if (array !== undefined) {
      for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
          if (getIndex) return i
          else return array[i]
        }
      }
      return null
    } else return null
  },

  findObjectByKeyV2 (arr, key, value) {
    return arr.filter(r => {
      let check = false
      value.map(val => {
        if (val === r[key] && !check) check = true
      })
      return check
    })
  },

  getKeyObject (obj, index) {
    var keyList = Object.keys(obj)
    console.log('getKeyObject', index, obj, keyList)
    return keyList[index]
    // return 'asd'
  },

  setArray (array, target) {
    var l = []
    for (var r of array) {
      l.push(r[target])
    }
    return l
  },

  checkDuplicate (propertyName, inputArray) {
    var seenDuplicate = false,
      testObject = {}

    inputArray.map(function (item) {
      var itemPropertyName = item[propertyName]
      if (itemPropertyName in testObject) {
        testObject[itemPropertyName].duplicate = true
        item.duplicate = true
        seenDuplicate = true
      } else {
        testObject[itemPropertyName] = item
        delete item.duplicate
      }
    })

    return seenDuplicate
  },

  setSelectList (array, targetValue, targetLabel) {
    var l = []
    for (var r of array) {
      var d = {
        label: r[targetValue],
        value: r[targetLabel]
      }
      l.push(d)
    }
    return l
  },

  shareOn (target, data, url) {
    var namaAgenda = ''
    if (data.agenda !== null) namaAgenda = '"' + data.agenda.nama + '"'

    var namaShare = ''
    if (data.nama !== null) namaShare = data.nama

    var urlPost = ''
    // if (data.nama !== null) urlPost = data.url

    var keterangan = ''
    if (data.keterangan !== null) keterangan = data.keterangan

    var linkEnc = 'http://sinode.keuskupanbogor.org/%23/' + url
    // var linkWa = 'http%3A%2F%2Fsinode.keuskupanbogor.org%2F%23%2Ffoto%3Fview%3D' + data.id
    var textNormal = urlPost + ' \n ' + namaAgenda + ' \n ' + namaShare + ' \n - ' + keterangan
    var hashtagEnc = '%23sinode%20%23sinode2019%20%23sinodebogor%20%23sinodekeuskupan%20%23sinodekeuskupan2019%20%23sinodekeuslupanbogor%20%23sinodekeuskupanbogor2019'
    var hastagString = 'sinode, sinode2019, sinodebogor, sinodekeuskupan, sinodekeuskupan2019, sinodekeuslupanbogor, sinodekeuskupanbogor2019'
    Helper.showToast('Sedang mempersiapkan..')

    if (target === 'wa') {
      window.open('whatsapp://send?text=' + linkEnc + '%0A%0A' + textNormal + '%0A%0A' + hashtagEnc, '_system')
    } else if (target === 'fb') {
      var linkFB = 'https://www.facebook.com/sharer/sharer.php?u=' + linkEnc + '&quote=' + textNormal + ' %0A' + hashtagEnc
      window.open(linkFB, '_system')
    } else if (target === 'tw') {
      var linkTW = 'http://twitter.com/share?text=' + textNormal + '&hashtags=' + hastagString + '&url=' + linkEnc + ''
      window.open(linkTW, '_system')
    }
  },

  filterFunction (target, val, update, list, listTmp) { // Search ,Filter Function for Select
    if (val === '') {
      update(() => {
        this.select.materi = this.select.materiTmp
      })
      return
    }

    update(() => {
      const needle = val.toLowerCase()
      this.select.materi = this.select.materiTmp.filter(v => v[target].toLowerCase().indexOf(needle) > -1)
    })
  },

  setFullscreen (mode = true) {
    var elem = document.getElementById('fullscreen')
    if (mode) { // active fullscreen
      if (elem.requestFullscreen) elem.requestFullscreen()
      else if (elem.mozRequestFullScreen) elem.mozRequestFullScreen() /* Firefox */
      else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen() /* Chrome, Safari and Opera */
      else if (elem.msRequestFullscreen) elem.msRequestFullscreen() /* IE/Edge */
    } else { // exit fullscren
      if (document.exitFullscreen) document.exitFullscreen()
      else if (document.mozCancelFullScreen) document.mozCancelFullScreen() /* Firefox */
      else if (document.webkitExitFullscreen) document.webkitExitFullscreen() /* Chrome, Safari and Opera */
      else if (document.msExitFullscreen) document.msExitFullscreen() /* IE/Edge */
    }
  },

  isNull (data) {
    if (data === null) return true
    else return false
  },

  optimizeWidthDialog (sizeDefault, currentSize) {
    if (currentSize === 'sm') return '95vw'
    else if (currentSize === 'xs') return '95vw'
    else return sizeDefault
  },

  valueFromPercent (percent, total) {
    // var realTotal = 0
    // if (percent !== 0) realTotal = (percent / 100) * total
    // if (percent < 100) return realTotal
    // else return percent
    return this.persentase(percent, total)
  },

  persentase (val, from, up = true) { // up : pembulatan keatas (true), pembulatan kebawah (false)
    var res = (val / 100) * from
    if (up) return Math.ceil(res)
    else return Math.floor(res)
  },

  toChartLineData (data, name = 'name') {
    var header = []
    var lines = []

    for (const row of data) {
      var hari = row.tanggal.split('-')
      hari = hari[2]
      header.push(hari)
      lines.push(row.total)
    }

    var res = {
      header: header,
      name: name,
      data: lines
    }

    return res
  },

  async filterSelect (val, update, target, selectSource, searchField) {
    var targetNameTmp = target + 'Tmp'
    console.log('select.' + targetNameTmp, selectSource[targetNameTmp])
    await update(() => {
      selectSource[target] = selectSource[targetNameTmp]
    })

    await update(() => {
      const needle = val.toLowerCase()
      var tmp = selectSource[targetNameTmp]
      selectSource[target] = tmp.filter(v => v[searchField || 'name'].toLowerCase().indexOf(needle) > -1)
    })
    return selectSource
  },

  unReactive (arr) {
    arr = JSON.stringify(arr)
    return JSON.parse(arr)
  },

  logInfo (date, time = true) {
    const newDate = new Date(date),
      getDate = newDate.getDate(),
      getMonth = newDate.getMonth(),
      getYear = newDate.getFullYear(),
      getHours = newDate.getHours(),
      getMinutes = newDate.getMinutes()
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    // const month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

    const res = `${getDate} ${months[getMonth]} ${getYear} ${time ? `- ${getHours}:${getMinutes}` : ''}`
    return res
  },

  // function splitUppercaseToStrip($string){
  //   $selector = preg_replace('/([a-z0-9])?([A-Z])/','$1-$2',$string);
  //   if($selector[0] == '-') $selector = substr($selector, 1); // hapus underscore di awal text
  //   return $selector;
  // }

  formatCamelCase (text, withS = true) {
    let res = text.replace(/[A-Z]/g, str => `-${str.toLowerCase()}`)
    res = `${res.substring(1)}${withS ? 's' : ''}`
    return res
  },

  numberSay (number) {
    const first = ['', 'one ', 'two ', 'three ', 'four ', 'five ', 'six ', 'seven ', 'eight ', 'nine ', 'ten ', 'eleven ', 'twelve ', 'thirteen ', 'fourteen ', 'fifteen ', 'sixteen ', 'seventeen ', 'eighteen ', 'nineteen ']
    const tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety']
    const mad = ['', 'thousand', 'million', 'billion', 'trillion']
    let word = ''

    for (let i = 0; i < mad.length; i++) {
      let tempNumber = number % (100 * Math.pow(1000, i))
      if (Math.floor(tempNumber / Math.pow(1000, i)) !== 0) {
        if (Math.floor(tempNumber / Math.pow(1000, i)) < 20) {
          word = first[Math.floor(tempNumber / Math.pow(1000, i))] + mad[i] + ' ' + word
        } else {
          word = tens[Math.floor(tempNumber / (10 * Math.pow(1000, i)))] + '-' + first[Math.floor(tempNumber / Math.pow(1000, i)) % 10] + mad[i] + ' ' + word
        }
      }

      tempNumber = number % (Math.pow(1000, i + 1))
      if (Math.floor(tempNumber / (100 * Math.pow(1000, i))) !== 0) word = first[Math.floor(tempNumber / (100 * Math.pow(1000, i)))] + 'hundred ' + word
    }
    return word
  },

  handlerFocusAutocomplete (_this, selectSource, data) {
    setTimeout(() => {
      var el = _this.$refs[selectSource]
      if (el) el.focus()
    }, 300)
  },

  makeRef (res) {
    res = res.replace(' ', '_')
    res = res.replace(' ', '_')
    res = res.replace(' ', '_')
    res = res.replace('-', '_')
    res = res.replace('-', '_')
    res = res.replace('-', '_')
    res = res.replace('-', '_')
    res = res.replace('-', '_')
    res = res.replace('-', '_')
    return res
  }

}

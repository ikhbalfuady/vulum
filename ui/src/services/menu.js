/* eslint-disable indent */
/* eslint-disable no-unused-vars */
import { Helper } from '../services/helper'
import { ModuleConfig } from '../services/ModuleConfig'
import { Config } from '../config'

export const Menu = {

  getMenu () {
    var menuList = [
      {
        prefix: 'view-home',
        icon: 'home',
        label: 'Home',
        page: 'index',
        separator: true
      },
      {
        prefix: 'view-data',
        icon: 'ion-albums',
        label: 'Master Data',
        page: '',
        separator: true,
        sub: [
          {
            prefix: 'view-kategori',
            icon: 'class',
            label: 'Kategori',
            page: 'listMeta',
            params: { type: 'kategori' },
            separator: true
          },
          {
            prefix: 'view-satuan',
            icon: 'class',
            label: 'Satuan',
            page: 'listMeta',
            params: { type: 'satuan' },
            separator: true
          },
          {
            prefix: 'view-principal',
            icon: 'class',
            label: 'Principal',
            page: 'listMeta',
            params: { type: 'principal' },
            separator: true
          },
          {
            prefix: 'view-produksi_penjualan',
            icon: 'class',
            label: 'Grup Produksi Penjualan',
            page: 'listMeta',
            params: { type: 'produksi_penjualan' },
            separator: true
          },
          {
            prefix: 'view-produksi_pembelian',
            icon: 'class',
            label: 'Grup Produksi Pembelian',
            page: 'listMeta',
            params: { type: 'produksi_pembelian' },
            separator: true
          },
          {
            prefix: 'view-gudang',
            icon: 'store',
            label: 'Gudang',
            page: 'listMeta',
            params: { type: 'gudang' },
            separator: true
          },
          {
            prefix: 'view-produk',
            icon: 'ion-cube',
            label: 'Produk',
            page: 'listProduk',
            separator: true
          },
          {
            prefix: 'view-pemasok',
            icon: 'account_box',
            label: 'Pemasok',
            page: 'listPemasok',
            separator: true
          },
          {
            prefix: 'view-pelanggan',
            icon: 'supervisor_account',
            label: 'Pelanggan',
            page: 'listPelanggan',
            separator: true
          },
          {
            prefix: 'view-role',
            icon: 'vpn_key',
            label: 'Role / Hak Akses',
            page: 'listRole',
            separator: true
          },
          {
            prefix: 'view-user',
            icon: 'supervised_user_circle',
            label: 'User',
            page: 'listUser',
            separator: true
          },
          {
            prefix: 'view-department',
            icon: 'business',
            label: 'Department',
            page: 'listDepartment',
            separator: true
          },
          {
            prefix: 'view-bank',
            icon: 'account_balance',
            label: 'Bank',
            page: 'listBank',
            separator: true
          },
          {
            prefix: 'view-promo',
            icon: 'confirmation_number',
            label: 'Promo',
            page: 'listPromo',
            separator: true
          }
        ]
      },
      {
        prefix: 'view-pembelian',
        icon: 'shopping_basket',
        label: 'Pembelian',
        page: '',
        separator: true,
        sub: [
          {
            prefix: 'view-pesanan_pembelian',
            icon: 'assignment',
            label: 'List Pesanan Pembelian',
            page: 'listPesananPembelian',
            separator: true
          },
          {
            prefix: 'add-pesanan_pembelian',
            icon: 'note_add',
            label: 'Pesanan Pembelian',
            page: 'addPesananPembelian',
            separator: true
          },
          {
            prefix: 'view-pembelian',
            icon: 'description',
            label: 'List Pembelian',
            page: 'listPO',
            separator: true
          },
          {
            prefix: 'add-pembelian',
            icon: 'add_box',
            label: 'Pembelian',
            page: 'addPembelian',
            separator: true
          },
          {
            prefix: 'add-hutang',
            icon: 'o_payments',
            label: 'Pembayaran Hutang',
            page: 'addPembayaran',
            params: { type: 'pembelian' },
            separator: true
          },
          {
            prefix: 'view-retur_pembelian',
            icon: 'flip_camera_android',
            label: 'Retur Pembelian',
            page: 'listRetur',
            params: { type: 'pembelian' },
            separator: true
          }

        ]
      },
      {
        prefix: 'module-penjualan',
        icon: 'shopping_cart',
        label: 'Penjualan',
        page: '',
        separator: true,
        sub: [
          {
            prefix: 'view-penawaran_penjualan',
            icon: 'assignment',
            label: 'List Penawaran Penjualan',
            page: 'listPenawaranPenjualan',
            separator: true
          },
          {
            prefix: 'add-penawaran_penjualan',
            icon: 'note_add',
            label: 'Penawaran Penjualan',
            page: 'addPenawaranPenjualan',
            separator: true
          },
          {
            prefix: 'view-penjualan',
            icon: 'description',
            label: 'List Penjualan',
            page: 'listOrder',
            separator: true
          },
          // {
          //   prefix: 'view-penjualan',
          //   icon: 'description',
          //   label: 'List Penjualan',
          //   page: 'listPenjualan',
          //   separator: true
          // },
          {
            prefix: 'add-penjualan',
            icon: 'add_box',
            label: 'Penjualan Invoice',
            page: 'addPenjualanInvoice',
            separator: true
          },
          {
            prefix: 'pos-penjualan',
            icon: 'add_shopping_cart',
            label: 'Penjualan POS',
            page: 'addPenjualan',
            separator: true
          },
          {
            prefix: 'view-piutang',
            icon: 'o_payments',
            label: 'Pembayaran Piutang',
            page: 'addPembayaran',
            params: { type: 'penjualan' },
            separator: true
          },
          {
            prefix: 'view-retur_penjualan',
            icon: 'flip_camera_android',
            label: 'Retur Penjualan',
            page: 'listRetur',
            params: { type: 'penjualan' },
            separator: true
          },
          {
            prefix: 'kitchen-penjualan',
            icon: 'description',
            label: 'Prepare List',
            page: 'listOrderPrepare',
            separator: true
          }

        ]
      },
      {
        prefix: 'module-kas',
        icon: 'money',
        label: 'Kas',
        page: 'listKas',
        separator: true,
        sub: [
          {
            prefix: 'view-kas_masuk',
            icon: 'archive',
            label: 'Kas Masuk',
            page: 'listKas',
            params: { type: 'masuk' },
            separator: true
          },
          {
            prefix: 'view-kas_keluar',
            icon: 'unarchive',
            label: 'Kas Keluar',
            page: 'listKas',
            params: { type: 'keluar' },
            separator: true
          }
        ]
      },
      {
        prefix: 'view-setoran_kasir',
        icon: 'o_point_of_sale',
        label: 'Setoran Kasir',
        page: 'listSetoran',
        separator: true
      },
      {
        prefix: 'module-inventory',
        icon: 'o_calculate',
        label: 'Inventory',
        page: '',
        separator: true,
        sub: [
          {
            prefix: 'view-penyesuaian_stok',
            icon: 'o_flaky',
            label: 'Penyesuaian Stok',
            page: 'listPenyesuaian',
            separator: true
          },
          {
            prefix: 'view-transfer_stok',
            icon: 'compare_arrows',
            label: 'Transfer Stok',
            page: 'listTransfer',
            separator: true
          }
        ]
      },
      // {
      //   prefix: 'view-notification',
      //   icon: 'notification_important',
      //   label: 'Notification',
      //   page: 'notification',
      //   separator: true
      // },
      {
        prefix: 'module-laporan_pembelian',
        icon: 'assignment_returned',
        label: 'Laporan Pembelian',
        page: 'laporan',
        separator: true,
        sub: this.getMenuFromPermission('pembelian')
      },
      {
        prefix: 'module-laporan_penjualan',
        icon: 'o_article',
        label: 'Laporan Penjualan',
        page: 'laporan',
        separator: true,
        sub: this.getMenuFromPermission('penjualan')
      },
      {
        prefix: 'module-laporan_stok',
        icon: 'description',
        label: 'Laporan Stok',
        page: 'laporan',
        separator: true,
        sub: this.getMenuFromPermission('stok')
      },
      {
        prefix: 'module-laporan_kas',
        icon: 'library_books',
        label: 'Laporan Kas',
        page: 'laporan',
        separator: true,
        sub: this.getMenuFromPermission('kas')
      },
      {
        prefix: 'view-pengaturan',
        icon: 'settings',
        label: 'Pengaturan',
        page: 'pengaturan',
        separator: true
      },
      {
        prefix: 'view-logout',
        icon: 'lock',
        label: 'Logout',
        page: 'logout',
        separator: true
      }
    ]
    return menuList
  },

  drawer (params = true) {
    if (Helper.checkLdb('drawerControl') === false) Helper.saveLdb('drawerControl', false)
    else Helper.saveLdb('drawerControl', params)
    return Helper.getLdb('drawerControl')
  },

  getMenuFromPermission (type) {
    var module = 'laporan_' + type
    var list = ModuleConfig.getPermission(module)
    var sendMenu = []
    for (const row in list) {
      // console.log(row)
      var menu = {
        prefix: row + '-' + module,
        icon: 'short_text',
        label: Helper.replace('_', ' ', row),
        page: 'laporan',
        params: { type: row, module: module },
        separator: true
      }
      if (row !== 'module') sendMenu.push(menu)
    }
    // console.log('sendMenu', sendMenu)
    return sendMenu
  },

  ApiRoot () {
    var api = Config.getApiRoot()
    this.$q.dialog({
      title: 'Set API Root',
      message: 'ex: http://localhost/apiroot/',
      prompt: {
        model: api,
        type: 'text' // optional
      },
      cancel: true,
      persistent: true
    }).onOk(data => {
      Config.saveApiRoot(data)
    }).onCancel(() => {
      // console.log('>>>> Cancel')
    }).onDismiss(() => {
      // console.log('I am triggered on both OK and Cancel')
    })
  }
}

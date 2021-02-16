import { Config } from '../config'

export const Model = {

  getDefaultGudang () {
    var gudang = 'UTAMA'
    var credentials = Config.credentials()
    if (credentials !== false) {
      gudang = credentials.gudang
    }
    return gudang
  },

  Users () {
    var model = {
      id: null,
      name: null,
      email: null,
      email_verified_at: null,
      nomor_ktp: null,
      password: null,
      balance: null,
      coin: null,
      status: null,
      gender: null,
      address: null,
      zip_code: null,
      province_id: null,
      regency_id: null,
      country: null,
      mobile: null,
      ref_id: null,
      remember_token: null,
      verified: null,
      active: null,
      cloud_expired: null,
      started_mining_time: null
    }
    return model
  },

  Admins () {
    var model = {
      id: null,
      name: null,
      email: null,
      password: null,
      is_super: null,
      remember_token: null
    }
    return model
  },

  AhliWaris () {
    var model = {
      id: null,
      user_id: null,
      name: null,
      address: null,
      no_telp: null,
      email: null,
      nomor_ktp: null,
      status: null
    }
    return model
  },

  CloudTransaction () {
    var model = {
      id: null,
      user_id: null,
      amount: null,
      total_month: null,
      r_img: null,
      accepted: null
    }
    return model
  },

  CoinMining () {
    var model = {
      id: null,
      user_id: null,
      coin: null,
      earn: null,
      date: null,
      pay: null
    }
    return model
  },

  CoinTransaction () {
    var model = {
      id: null,
      user_id: null,
      coin: null,
      accepted: null,
      type: null,
      description: null
    }
    return model
  },

  Deposits () {
    var model = {
      id: null,
      user_id: null,
      gateway_id: null,
      amount: null,
      charge: null,
      usd_amo: null,
      btc_amo: null,
      btc_wallet: null,
      trx: null,
      status: null,
      try: null
    }
    return model
  },

  DepositRequests () {
    var model = {
      id: null,
      user_id: null,
      gateway_id: null,
      amount: null,
      charge: null,
      usd_amo: null,
      accepted: null,
      r_img: null,
      trx: null,
      sent: null
    }
    return model
  },

  Generals () {
    var model = {
      id: null,
      web_name: null,
      currency: null,
      color_code: null,
      contact_address: null,
      contact_email: null,
      contact_phone: null,
      copyright_text: null,
      banner_header: null,
      banner_body: null,
      banner_footer: null,
      about_head: null,
      about_title: null,
      about_body: null,
      about_video_url: null,
      single_about1_icon: null,
      single_about1_title: null,
      single_about1_description: null,
      single_about2_icon: null,
      single_about2_title: null,
      single_about2_description: null,
      invest_head: null,
      invest_title: null,
      invest_description: null,
      investor_head: null,
      investor_title: null,
      investor_description: null,
      footer_text: null,
      email_template: null,
      sms_api: null,
      created_at: null,
      updated_at: null,
      esender: null,
      comment_script: null,
      bal_trans_fixed_charge: null,
      bal_trans_percentage_charge: null,
      email_verification: null
    }
    return model
  },

  MasterBank () {
    var model = {
      id: null,
      name: null,
      image: null
    }
    return model
  },

  Menus () {
    var model = {
      id: null,
      title: null,
      description: null
    }
    return model
  },

  MiningHistory () {
    var model = {
      id: null,
      total_user: null,
      total_user_gifted: null,
      periode: null
    }
    return model
  },

  News () {
    var model = {
      id: null,
      image: null,
      title: null,
      description: null
    }
    return model
  },

  PasswordResets () {
    var model = {
      id: null,
      email: null,
      status: null,
      token: null
    }
    return model
  },

  PaymentGatways () {
    var model = {
      id: null,
      image: null,
      name: null,
      minimum_deposit_amount: null,
      maximum_deposit_amount: null,
      fixed_charge: null,
      percentage_charge: null,
      rate: null,
      gateway_key_one: null,
      gateway_key_two: null,
      gateway_key_three: null,
      gateway_key_four: null,
      status: null
    }
    return model
  },

  Services () {
    var model = {
      id: null,
      title: null,
      icon: null,
      description: null
    }
    return model
  },

  Transactions () {
    var model = {
      id: null,
      user_id: null,
      trans_id: null,
      description: null,
      amount: null,
      old_bal: null,
      new_bal: null,
      status: null
    }
    return model
  },

  UserBankAccount () {
    var model = {
      id: null,
      user_id: null,
      account_name: null,
      account_number: null,
      bank_id: null,
      admin_id: null
    }
    return model
  },

  UserEvidences () {
    var model = {
      id: null,
      user_id: null,
      status: null,
      type: null,
      admin_id: null
    }
    return model
  },

  WithdrawLogs () {
    var model = {
      id: null,
      withdraw_id: null,
      user_id: null,
      amount: null,
      charge: null,
      method_name: null,
      processing_time: null,
      detail: null,
      status: null,
      method_cur: null,
      method_rate: null,
      admin_id: null
    }
    return model
  },

  WithdrawMethods () {
    var model = {
      id: null,
      name: null,
      image: null,
      min_amo: null,
      max_amo: null,
      chargefx: null,
      chargepc: null,
      rate: null,
      processing_day: null,
      status: null,
      bank_id: null,
      currency: null
    }
    return model
  },

  userInfo () {
    var model = {
      id: null,
      role_id: '',
      role: '',
      email: '',
      name: ''
    }
    return model
  }

}

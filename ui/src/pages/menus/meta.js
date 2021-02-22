const Meta = {
  name: 'Menus',
  icon: 'stop_circle',
  module: 'menus',
  topBarMenu: [],
  permission: {
    create: false,
    update: false,
    delete: true,
    restore: true
  },
  model: {
    id: null,
    parent_id: null,
    menu_item_id: null,
    master_menu_id: null

  }
}

export default Meta

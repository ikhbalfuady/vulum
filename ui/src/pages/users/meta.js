const Meta = {
  name: 'Users',
  icon: 'stop_circle',
  module: 'users',
  topBarMenu: [],
  permission: {
    create: false,
    update: false,
    delete: true,
    restore: true
  },
  model: {
    id: null,
    name: null,
    username: null,
    password: null,
    email: null,
    picture: null,
    role_id: null,
    menu_id: null,
    active: null

  }
}

export default Meta

const Meta = {
  parent: 'Master Data',
  name: 'Users',
  icon: 'stop_circle',
  module: 'users',
  topBarMenu: [],
  permission: {
    browse: true,
    create: true,
    update: true,
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

const Meta = {
  name: 'User Sessions',
  icon: 'stop_circle',
  module: 'user-sessions',
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
    user_id: null,
    token: null,
    ip: null,
    agent: null,
    platform: null

  }
}

export default Meta

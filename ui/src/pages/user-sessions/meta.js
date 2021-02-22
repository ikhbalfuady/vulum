const Meta = {
  name: 'UserSessions',
  icon: 'stop_circle',
  module: 'user-sessions',
  topBarMenu: [],
  permission: {
    create: false,
    update: false,
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

const Meta = {
  name: 'User Notifications',
  icon: 'stop_circle',
  module: 'user-notifications',
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
    is_read: null,
    title: null,
    description: null,
    type: null

  }
}

export default Meta

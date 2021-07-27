const Meta = {
  name: 'Role Permissions',
  icon: 'stop_circle',
  module: 'role-permissions',
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
    permission_id: null,
    role_id: null

  }
}

export default Meta

const Meta = {
  name: 'RolePermissions',
  icon: 'stop_circle',
  module: 'role-permissions',
  topBarMenu: [],
  permission: {
    create: false,
    update: false,
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

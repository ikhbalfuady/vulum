const Meta = {
  name: 'Permissions',
  icon: 'stop_circle',
  module: 'permissions',
  topBarMenu: [],
  permission: {
    browse: true,
    create: true,
    read: true,
    update: true,
    delete: true,
    restore: true
  },
  model: {
    id: null,
    name: null,
    slug: null,
    created_by: null,
    updated_by: null,
    deleted_by: null

  }
}

export default Meta

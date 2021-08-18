const Meta = {
  name: 'Department',
  icon: 'stop_circle',
  module: 'permissions',
  topBarMenu: [],
  permission: {
    browse: true,
    read: true,
    create: true,
    update: false,
    delete: true,
    restore: true
  },
  model: {
    id: null,
    name: null,
    parent: null,
    rate: null,
    created_by: null,
    updated_by: null,
    deleted_by: null

  }
}

export default Meta

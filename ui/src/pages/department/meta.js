const Meta = {
  name: 'Department',
  icon: 'stop_circle',
  module: 'department',
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
    parent: null,
    rate: null,
    created_by: null,
    updated_by: null,
    deleted_by: null

  }
}

export default Meta

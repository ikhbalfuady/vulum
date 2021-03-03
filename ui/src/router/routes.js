
const routes = [
  {
    name: 'login',
    path: '/login',
    component: () => import('layouts/Login.vue')
  },
  {
    path: '/401',
    component: () => import('layouts/NoHeader.vue'),
    children: [
      { name: '401', path: '/401', component: () => import('pages/401.vue') }
    ]
  },
  {
    path: '/403',
    component: () => import('layouts/NoHeader.vue'),
    children: [
      { name: '403', path: '/403', component: () => import('pages/403.vue') }
    ]
  },
  {
    path: '/',
    component: () => import('layouts/Main.vue'),
    children: [
      { name: 'dashboard', path: '/', component: () => import('pages/index.vue') },
      { name: 'users-list', path: '/users', component: () => import('pages/users/index.vue') },
      { name: 'view-users', path: '/users/view/:id', component: () => import('pages/users/detail.vue') },
      { name: 'add-users', path: '/users/form', component: () => import('pages/users/form.vue') },
      { name: 'edit-users', path: '/users/form/:id', component: () => import('pages/users/form.vue') },

      { name: 'user-sessions-list', path: '/user-sessions', component: () => import('pages/user-sessions/index.vue') },
      { name: 'view-user-sessions', path: '/user-sessions/view/:id', component: () => import('pages/user-sessions/detail.vue') },
      { name: 'add-user-sessions', path: '/user-sessions/form', component: () => import('pages/user-sessions/form.vue') },
      { name: 'edit-user-sessions', path: '/user-sessions/form/:id', component: () => import('pages/user-sessions/form.vue') },

      { name: 'permissions-list', path: '/permissions', component: () => import('pages/permissions/index.vue') },
      { name: 'view-permissions', path: '/permissions/view/:id', component: () => import('pages/permissions/detail.vue') },
      { name: 'add-permissions', path: '/permissions/form', component: () => import('pages/permissions/form.vue') },
      { name: 'edit-permissions', path: '/permissions/form/:id', component: () => import('pages/permissions/form.vue') },

      { name: 'role-permissions-list', path: '/role-permissions', component: () => import('pages/role-permissions/index.vue') },
      { name: 'view-role-permissions', path: '/role-permissions/view/:id', component: () => import('pages/role-permissions/detail.vue') },
      { name: 'add-role-permissions', path: '/role-permissions/form', component: () => import('pages/role-permissions/form.vue') },
      { name: 'edit-role-permissions', path: '/role-permissions/form/:id', component: () => import('pages/role-permissions/form.vue') },

      { name: 'roles-list', path: '/roles', component: () => import('pages/roles/index.vue') },
      { name: 'view-roles', path: '/roles/view/:id', component: () => import('pages/roles/detail.vue') },
      { name: 'add-roles', path: '/roles/form', component: () => import('pages/roles/form.vue') },
      { name: 'edit-roles', path: '/roles/form/:id', component: () => import('pages/roles/form.vue') },

      { name: 'menu-items-list', path: '/menu-items', component: () => import('pages/menu-items/index.vue') },
      { name: 'view-menu-items', path: '/menu-items/view/:id', component: () => import('pages/menu-items/detail.vue') },
      { name: 'add-menu-items', path: '/menu-items/form', component: () => import('pages/menu-items/form.vue') },
      { name: 'edit-menu-items', path: '/menu-items/form/:id', component: () => import('pages/menu-items/form.vue') },

      { name: 'menus-list', path: '/menus', component: () => import('pages/menus/index.vue') },
      { name: 'view-menus', path: '/menus/view/:id', component: () => import('pages/menus/detail.vue') },
      { name: 'add-menus', path: '/menus/form', component: () => import('pages/menus/form.vue') },
      { name: 'edit-menus', path: '/menus/form/:id', component: () => import('pages/menus/form.vue') },

      { name: 'master-menus-list', path: '/master-menus', component: () => import('pages/master-menus/index.vue') },
      { name: 'view-master-menus', path: '/master-menus/view/:id', component: () => import('pages/master-menus/detail.vue') },
      { name: 'add-master-menus', path: '/master-menus/form', component: () => import('pages/master-menus/form.vue') },
      { name: 'edit-master-menus', path: '/master-menus/form/:id', component: () => import('pages/master-menus/form.vue') }
      //
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes

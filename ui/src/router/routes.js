
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
      { name: 'users', path: '/users', component: () => import('pages/users/index.vue') },
      { name: 'users-detail', path: '/users/view/:id', component: () => import('pages/users/detail.vue') },
      { name: 'users-create', path: '/users/form', component: () => import('pages/users/form.vue') },
      { name: 'users-update', path: '/users/form/:id', component: () => import('pages/users/form.vue') },

      { name: 'user-sessions', path: '/user-sessions', component: () => import('pages/user-sessions/index.vue') },
      { name: 'user-sessions-detail', path: '/user-sessions/view/:id', component: () => import('pages/user-sessions/detail.vue') },
      { name: 'user-sessions-create', path: '/user-sessions/form', component: () => import('pages/user-sessions/form.vue') },
      { name: 'user-sessions-update', path: '/user-sessions/form/:id', component: () => import('pages/user-sessions/form.vue') },

      { name: 'permissions', path: '/permissions', component: () => import('pages/permissions/index.vue') },
      { name: 'permissions-detail', path: '/permissions/view/:id', component: () => import('pages/permissions/detail.vue') },
      { name: 'permissions-create', path: '/permissions/form', component: () => import('pages/permissions/form.vue') },
      { name: 'permissions-update', path: '/permissions/form/:id', component: () => import('pages/permissions/form.vue') },

      { name: 'role-permissions', path: '/role-permissions', component: () => import('pages/role-permissions/index.vue') },
      { name: 'role-permissions-detail', path: '/role-permissions/view/:id', component: () => import('pages/role-permissions/detail.vue') },
      { name: 'role-permissions-create', path: '/role-permissions/form', component: () => import('pages/role-permissions/form.vue') },
      { name: 'role-permissions-update', path: '/role-permissions/form/:id', component: () => import('pages/role-permissions/form.vue') },

      { name: 'roles', path: '/roles', component: () => import('pages/roles/index.vue') },
      { name: 'roles-detail', path: '/roles/view/:id', component: () => import('pages/roles/detail.vue') },
      { name: 'roles-create', path: '/roles/form', component: () => import('pages/roles/form.vue') },
      { name: 'roles-update', path: '/roles/form/:id', component: () => import('pages/roles/form.vue') },

      { name: 'menu-items', path: '/menu-items', component: () => import('pages/menu-items/index.vue') },
      { name: 'menu-items-detail', path: '/menu-items/view/:id', component: () => import('pages/menu-items/detail.vue') },
      { name: 'menu-items-create', path: '/menu-items/form', component: () => import('pages/menu-items/form.vue') },
      { name: 'menu-items-update', path: '/menu-items/form/:id', component: () => import('pages/menu-items/form.vue') },

      { name: 'menus', path: '/menus', component: () => import('pages/menus/index.vue') },
      { name: 'menus-detail', path: '/menus/view/:id', component: () => import('pages/menus/detail.vue') },
      { name: 'menus-create', path: '/menus/form', component: () => import('pages/menus/form.vue') },
      { name: 'menus-update', path: '/menus/form/:id', component: () => import('pages/menus/form.vue') },

      { name: 'master-menus', path: '/master-menus', component: () => import('pages/master-menus/index.vue') },
      { name: 'master-menus-detail', path: '/master-menus/view/:id', component: () => import('pages/master-menus/detail.vue') },
      { name: 'master-menus-create', path: '/master-menus/form', component: () => import('pages/master-menus/form.vue') },
      { name: 'master-menus-update', path: '/master-menus/form/:id', component: () => import('pages/master-menus/form.vue') }
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

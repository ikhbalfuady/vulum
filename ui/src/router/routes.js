
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
    path: '/',
    component: () => import('layouts/Main.vue'),
    children: [
      { name: 'index', path: '/', component: () => import('pages/index.vue') },
      { name: 'users', path: '/users', component: () => import('pages/users/index.vue') },
      { name: 'users-detail', path: '/users/view/:id', component: () => import('pages/users/detail.vue') },
      { name: 'users-add', path: '/users/form', component: () => import('pages/users/form.vue') },
      { name: 'users-edit', path: '/users/form/:id', component: () => import('pages/users/form.vue') },

      { name: 'user-sessions', path: '/user-sessions', component: () => import('pages/user-sessions/index.vue') },
      { name: 'user-sessions-detail', path: '/user-sessions/view/:id', component: () => import('pages/user-sessions/detail.vue') },
      { name: 'user-sessions-add', path: '/user-sessions/form', component: () => import('pages/user-sessions/form.vue') },
      { name: 'user-sessions-edit', path: '/user-sessions/form/:id', component: () => import('pages/user-sessions/form.vue') },

      { name: 'permissions', path: '/permissions', component: () => import('pages/permissions/index.vue') },
      { name: 'permissions-detail', path: '/permissions/view/:id', component: () => import('pages/permissions/detail.vue') },
      { name: 'permissions-add', path: '/permissions/form', component: () => import('pages/permissions/form.vue') },
      { name: 'permissions-edit', path: '/permissions/form/:id', component: () => import('pages/permissions/form.vue') },

      { name: 'roles', path: '/roles', component: () => import('pages/roles/index.vue') },
      { name: 'roles-detail', path: '/roles/view/:id', component: () => import('pages/roles/detail.vue') },
      { name: 'roles-add', path: '/roles/form', component: () => import('pages/roles/form.vue') },
      { name: 'roles-edit', path: '/roles/form/:id', component: () => import('pages/roles/form.vue') },

      { name: 'user-roles', path: '/user-roles', component: () => import('pages/user-roles/index.vue') },
      { name: 'user-roles-detail', path: '/user-roles/view/:id', component: () => import('pages/user-roles/detail.vue') },
      { name: 'user-roles-add', path: '/user-roles/form', component: () => import('pages/user-roles/form.vue') },
      { name: 'user-roles-edit', path: '/user-roles/form/:id', component: () => import('pages/user-roles/form.vue') }
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

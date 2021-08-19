/*
export function someAction (context) {
}
*/

export function drawerAction ({ commit }, data) {
  // console.log('drawerAction', data)
  commit('drawerCtrl', data)
}

export function drawerMiniAction ({ commit }, data) {
  // console.log('drawerMiniAction', data)
  commit('drawerMiniCtrl', data)
}

export function notificationsAction ({ commit }, data) {
  // console.log('notificationsAction', data)
  commit('notificationsCtrl', data)
}

export function userInfoAction ({ commit }, data) {
  // console.log('notificationsAction', data)
  commit('userInfoCtrl', data)
}

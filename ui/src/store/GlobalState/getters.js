/*
export function someGetter (state) {
}
*/

export function drawerDefault (state) {
  const res = state.data.drawer
  return res ?? false
}

export function drawerMiniDefault (state) {
  const res = state.data.drawerMini
  return res ?? false
}

export function notificationsDefault (state) {
  const res = state.data.notifications
  return res ?? false
}

export function userInfoDefault (state) {
  const res = state.data.userInfo
  return res ?? false
}

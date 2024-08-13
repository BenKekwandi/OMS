import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from "../stores/auth"
import routes from './routes'


const router = createRouter({
  routes,
  history: createWebHistory(),
})

// router.beforeEach(async (to, from) => {
//   const store = useAuthStore()
//   await store.fetchUser()
//   if (to.meta.auth && !store.isLoggedIn) {
//     return {
//       name: "login",
//       query: {
//         redirect: to.fullPath
//       }
//     }
//   } else if ((to.meta.guest && store.isLoggedIn)) {
//     return { name: "admin" }
//   }

router.beforeEach(async (to, from) => {
  const store = useAuthStore()
  await store.fetchUser()
  const userRole = store.role
  if (to.meta.auth && !store.isLoggedIn) {
    return {
      name: "login",
      query: {
        redirect: to.fullPath
      }
    }
  } else {
    if (to.meta.role && !to.meta.role.includes(userRole)) {
      return {
        name: "login",
        query: {
          redirect: to.fullPath
        }
      }
    }
  }

  // else if ((to.meta.guest && store.isLoggedIn)) {
  //   if (to.meta.roles.includes(userRole)) {
  //     return { name: "admin" }
  //   }
  //   else {
  //     return { name: "user" }
  //   }
  // }
})

export default router
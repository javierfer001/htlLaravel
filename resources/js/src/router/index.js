import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/home',
      name: 'home',
      component: () => import('@/views/Home.vue'),
      meta: {
        pageTitle: 'Home',
        breadcrumb: [
          {
            text: 'Home',
            active: true,
          },
        ],
      },
    },
    {
      path: '/keys',
      name: 'keys',
      component: () => import('@/views/key/List.vue'),
      meta: {
        pageTitle: 'Key',
        breadcrumb: [
          {
            text: 'Key',
            active: true,
          },
        ],
      },
    },
    {
      path: '/keys/:id',
      name: 'keys-add',
      component: () => import('@/views/key/Add.vue'),
      meta: {
        pageTitle: 'Key',
        breadcrumb: [
          {
            text: 'Key data',
            active: true,
          },
        ],
      },
    },
    {
      path: '/technicians',
      name: 'technicians',
      component: () => import('@/views/technician/List.vue'),
      meta: {
        pageTitle: 'Technician',
        breadcrumb: [
          {
            text: 'technician',
            active: true,
          },
        ],
      },
    },
    {
      path: '/vehicles',
      name: 'vehicles',
      component: () => import('@/views/vehicle/List.vue'),
      meta: {
        pageTitle: 'Vehicle',
        breadcrumb: [
          {
            text: 'vehicle',
            active: true,
          },
        ],
      },
    },
    {
      path: '/second-page',
      name: 'second-page',
      component: () => import('@/views/SecondPage.vue'),
      meta: {
        pageTitle: 'Second Page',
        breadcrumb: [
          {
            text: 'Second Page',
            active: true,
          },
        ],
      },
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/Login.vue'),
      meta: {
        layout: 'full',
      },
    },
    {
      path: '/error-404',
      name: 'error-404',
      component: () => import('@/views/error/Error404.vue'),
      meta: {
        layout: 'full',
      },
    },
    {
      path: '*',
      redirect: '/home',
    },
  ],
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router

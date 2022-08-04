import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import secondPage from './components/pages/secondVuePage'
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'
// Admin project pages
import home from './components/pages/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from './admin/pages/adminusers'
import usecom from './vuex/usecom'
import login from './admin/pages/login'
import role from './admin/pages/role'
import assignRole from './admin/pages/assignRole'
import createBlog from './admin/pages/createBlog'
import editblog from './admin/pages/editBlog'
import blog from './admin/pages/blog'
import notfound from './admin/pages/notfound'
import store from './store'

const routes = [{
        path: '/dashboard',
        component: home,
        meta: { requiresAuth: true },
        name: 'dashboard',
        //beforeEnter: checkAuth
    },
    // {
    //     path: '/testvuex',
    //     component: usecom,
    //     meta: { requiresAuth: true },
    //     name: 'testvuex'
    // },
    {
        path: '/tags',
        component: tags,
        meta: { requiresAuth: true },
        name: 'tags',
        //beforeEnter: checkAuth
    },
    {
        path: '/category',
        component: category,
        meta: { requiresAuth: true },
        name: 'category',
        //beforeEnter: checkAuth
    },
    {
        path: '/blogs',
        component: blog,
        meta: { requiresAuth: true },
        name: 'blogs',
        //beforeEnter: checkAuth
    },
    {
        path: '/createBlog',
        component: createBlog,
        meta: { requiresAuth: true },
        name: 'createBlog',
        //beforeEnter: checkAuth
    },
    {
        path: '/editblog/:id',
        component: editblog,
        meta: { requiresAuth: true },
        name: 'editblog',
        //beforeEnter: checkAuth
    },
    {
        path: '/adminusers',
        component: adminusers,
        meta: { requiresAuth: true },
        name: 'adminusers',
        //beforeEnter: checkAuth
    },
    {
        path: '/login',
        component: login,
        name: 'login',
        meta: { requiresAuth: false },
        //beforeEnter: checkAuth

    },
    {
        path: '/role',
        component: role,
        meta: { requiresAuth: true },
        name: 'role',
        //beforeEnter: checkAuth
    },
    {
        path: '/assign_role',
        component: assignRole,
        meta: { requiresAuth: true },
        name: 'assign_role',
        //beforeEnter: checkAuth
    },
    {
        path: '*',
        component: notfound,
        meta: { requiresAuth: true },
        name: 'notfound',
        //beforeEnter: checkAuth
    },
    // {
    //     path: '/second-vue-route',
    //     component: secondPage,
    //     meta: { requiresAuth: true }
    // },
    // {
    //     path: '/hooks',
    //     component: hooks,
    //     meta: { requiresAuth: true }
    // },
    // {
    //     path: '/methods',
    //     component: methods,
    //     meta: { requiresAuth: true }
    // },
]

const router = new Router({
    mode: 'history',
    routes
})
router.beforeEach((to, from, next) => {
        // if (window.localStorage.getItem('storedData') !== null && to.path == '/login') {
        //     next(from.path)
        //     return
        // }

        (function() {
            window.onpageshow = function(event) {
                if (event.persisted) {
                    if (window.localStorage.getItem('storedData') && to.path == '/login') {

                        console.log("Ezhiga")
                        store.state.user = true
                        router.push('/dashboard')
                        return window.location.reload()

                    } else {

                        console.log("Ezhigaaa")
                        store.state.user = false
                        next('/login')
                        return window.location.reload()
                    }
                    // window.location.reload();
                    //window.location.pathname('login')
                }
            };
        })();

        if (window.localStorage.getItem('storedData') === null && to.path != '/login') {
            //console.log("Ezhiga")
            console.log("Ezhigaaa 3rd")
            console.log("The user", store.state.user)

            // next('/login')
            // window.location.reload()
            // return

            location.replace('/login')
            return
        }

        next()
    })
    // router.beforeEach((to, from, next) => {
    //         // Internally this gets the token from localStorage, and sets user.authenticated to 
    //         if (to.path !== '/' && !store.state.user) { // Not logged in? You get booted to /login
    //             router.push('/login')
    //         } else if (to.path === '/login' && store.state.user) { // Let's not allow a login transition 
    //             //if we're already logged in
    //             router.push('/')
    //         }
    //         next()
    //     })
    // router.beforeEach((to, from, next) => {
    //     if (
    //         'requiresAuth' in to.meta &&
    //         to.meta.requiresAuth &&
    //         !store.state.user
    //     ) {
    //         next('/login');
    //     } else if (
    //         'requiresAuth' in to.meta &&
    //         !to.meta.requiresAuth &&
    //         store.state.user
    //     ) {
    //         next('/');
    //     } else {
    //         next();
    //     }
    // });
    // router.beforeEach((to, from, next) => {
    //     console.log('From', from)
    //     console.log('To', to)
    //     if (to.path === "/" && !store.state.user) {
    //         next("/login")
    //     } else {
    //         next()
    //     }

// });
// function checkAuth(to, from, next) {
//     if (store.state.user) next();
//     else next("/login");
// }
// window.popStateDetected = false
// window.addEventListener('popstate', () => {
//     window.popStateDetected = true
// })
// router.beforeEnter((to, from, next) => {
//         const IsItABackButton = window.popStateDetected
//         window.popStateDetected = true
//         const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
//         let storage = localStorage.getItem("storedData")
//         if (!store.state.user && !storage) {

//             // if (store.state.user) {
//             next('/login')
//                 // window.history.forward();
//                 // next('/login');
//                 // return;
//             console.log("1st")
//                 // } else if (!storage) {
//                 //     next(window.location.href = "/login")
//                 //     return;
//                 //     console.log("2nd")
//                 // } else {
//                 //     return
//                 // }
//         } else {
//             console.log('4th')
//             next()
//         }
//     })
// router.beforeEach((to, from, next) => {
//     const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
//     const loggedIn = localStorage.getItem('storedData')
//     if (!loggedIn && !requiresAuth) {
//         console.log("Ohhhhhhhhhh")
//             // window.history.forward()
//         return next('/login')

//     } else if (to.path == '/login' && loggedIn) {
//         next('/');
//     } else {
//         next();
//     }


// })
export default router
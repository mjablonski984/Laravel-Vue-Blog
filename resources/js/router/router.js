import Router from 'vue-router';
import Vue from 'vue';
import Home from '../pages/blog/Home.vue';
import Login from '../pages/Login.vue';
import PostSingle from '../pages/blog/PostSingle.vue';
import PostsByCategory from '../pages/blog/PostsByCategory.vue';
import PostsByTag from '../pages/blog/PostsByTag.vue';
import PostsByUser from '../pages/blog/PostsByUser.vue';
import SearchResults from '../pages/blog/SearchResults.vue';
import AdminHome from '../pages/admin/AdminHome.vue';
import AdminUsers from '../pages/admin/AdminUsers.vue';
import AssignPermissions from '../pages/admin/AssignPermissions.vue';
import Posts from '../pages/admin/Posts.vue';
import CreatePost from '../pages/admin/CreatePost.vue';
import EditPost from '../pages/admin/EditPost.vue';
import Categories from '../pages/admin/Categories.vue';
import Roles from '../pages/admin/Roles.vue';
import Tags from '../pages/admin/Tags.vue';
import NotFound from '../pages/NotFound.vue';

Vue.use(Router);

const routes = [
    {
        path: '/',
        component: Home,
        name: 'Home'
    },
    {
        path: '/post/:slug',
        component: PostSingle,
        name: 'PostSingle'
    },
    {
        path: '/category/:categoryName/:id',
        component: PostsByCategory,
        name: 'PostsByCategory'
    },
    {
        path: '/tag/:tagName/:id',
        component: PostsByTag,
        name: 'PostsByTag'
    },
    {
        path: '/user/:userName/:id',
        component: PostsByUser,
        name: 'PostsByUser'
    },
    {
        path: '/search',
        component: SearchResults,
        name: 'SearchResults'
    },
    {
        path: '/login',
        component: Login,
        name: 'Login'
    },
    {   path: '/admin-redirect',
        redirect: '/app' },
    {
        path: '/app',
        component: AdminHome,
        name: 'AdminHome',
        meta: { 
            requiresAuth: true,
            permission: false
        },
        children: [
            // Nested Routes - This components will be displaed inside nested router-view in AdminHome component
            {
                path: '/app/users',
                component: AdminUsers,
                name: 'Users',
                meta: { 
                    requiresAuth: true,
                    permission: false
                },
            },
            {
                path: '/app/roles',
                component: Roles,
                name: 'Roles',
                meta: { 
                    requiresAuth: true,
                    permission: false
                },
            },
            {
                path: '/app/permissions',
                component: AssignPermissions,
                name: 'Permissions',
                meta: { 
                    requiresAuth: true,
                    permission: false
                },
            },
            {
                path: '/app/posts',
                component: Posts,
                name: 'Posts',
                meta: { 
                    requiresAuth: true,
                    permission: false
                },
            },
            {
                path: '/app/createpost',
                component: CreatePost,
                name: 'CreatePost',
                meta: { 
                    requiresAuth: true,
                    permission: false
                },
            },
            {
                path: '/app/editpost/:id',
                component: EditPost,
                name: 'EditPost',
                
            },
            {
                path: '/app/categories',
                component: Categories,
                name: 'Categories',
                meta: { 
                    requiresAuth: true,
                    permission: false
                },
            },
            {
                path: '/app/tags',
                component: Tags,
                name: 'Tags',
                meta: { 
                    requiresAuth: true,
                    permission: false
                },
            },
            {
                path: '/app/notfound',
                component: NotFound,
                name: 'Notfound',
                meta: { 
                    requiresAuth: true,
                    permission: false
                },
            },
        ]
    },
    {
        path: '*',
        component: NotFound,
        name: 'NotFound'
    },
];


const router = new Router({
    mode: 'history',
    routes
});

import store from '../store/store';

router.beforeEach((to, from, next) => {
    // Get logged in user
    const user = store.getters['getUser'];

    //Admin Routes Guard- ( routes with meta.requiresAuth) redirect unautorized(!user or !user.role.isAdmin) users to Home route
    if (to.matched.some(record => record.meta.requiresAuth)){      
        // console.log('Path: ',to.path)
        if(!user) return next({ name: 'Home' });
        if (user.role.isAdmin){
            if (!store.getters['getUserPermission']){
                next({ name: 'Home' });
            }
            // Permission settings below are optional and can be used if you don't use permission check methods from app.js (included in a mixin file)
            // Exclude edit route (it doesn't have a permission obj. and can be accesed only from other protected rotes 
            if (to.name == 'EditPost') return next() 
            if(store.getters['getUserPermission']){
                const permission = store.getters['getUserPermission'].filter(p => p.name == to.path)  ;            
                to.meta.permission = permission[0]; // Set permission for the rout
                permission[0].read ? next() : next({ name: 'Home' });
            }           
            next();
       }else {
        next();
        }             
    } 
    if (to.path == '/login'){
        // Login Guard - if user is authenticated redirect to home route
        (user) ? next({ name: 'Home' }) : next()          
    } else {
        next();
    }
    
});

export default router;
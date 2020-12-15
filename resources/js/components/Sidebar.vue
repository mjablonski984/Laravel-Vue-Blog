<template>
    <div
        class="w-1/2 md:w-1/3 lg:w-64 fixed md:top-0 h-screen lg:block bg-gray-100 border-r z-30"
        :class="sideBarOpen ? '' : 'hidden'"
        id="main-nav"
    >
        <div class="w-full h-20 border-b flex px-4 items-center mb-8">
            <router-link to='/'>
                <p class="font-semibold text-lg sm:text-2xl text-blue-500 pl-4">&lt;&nbsp;DevBlog&nbsp;&gt;</p>
            </router-link>
        </div>

        <div class="mb-4 px-4">
            <p class="pl-4 text-sm font-semibold mb-1">MAIN</p>
           
            <div v-for="(permission, i) in getUserPermission" :key="i" v-if="getUserPermission.length && permission.read"
                class="w-full flex items-center text-blue-500 h-10 pl-4 rounded-lg cursor-pointer mb-2"
                :class="[($route.path == permission.name) ? 'bg-gray-200' : '' ]"> 
                <Icon :type="(icon.resource == permission.resourceName) ? icon.type : null" size="24" v-for="(icon) in icons " :key="icon.resource"/>
                <router-link :to="permission.name" class="text-gray-700 text-base pl-4 w-full" >{{permission.resourceName}}</router-link>
            </div>

        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'Sidebar',
    data() {
        return {
            icons : [
                {resource : 'Home', type : 'ios-home-outline'},
                {resource : 'Users', type : 'ios-people-outline'},
                {resource : 'Roles', type : 'ios-briefcase-outline'},
                {resource : 'Permissions', type : 'ios-unlock-outline'},
                {resource : 'Posts', type : 'ios-list-box-outline'},
                {resource : 'Add Post', type : 'ios-paper-outline'},
                {resource : 'Categories', type : 'ios-filing-outline'},
                {resource : 'Tags', type : 'ios-pricetags-outline'},
            ]
        }
    },
    computed: {
        ...mapGetters(['sideBarOpen','getUser','getUserPermission']),
    }
};
</script>
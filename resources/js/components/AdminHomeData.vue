<template>
    <div id="home" >
        <!-- breadcrumb -->
            <route-breadcrumb></route-breadcrumb>
        <!-- breadcrumb end -->

        <div class="lg:flex justify-between items-center mb-4">
            <p class="text-2xl font-semibold mb-4 lg:mb-0">
                Hello, {{getUser.name}}
            </p>
            <Tag type="border" size="large">{{getUser.role ? getUser.role.roleName : 'Unknown'}}</Tag>
        </div>

        <div class="flex flex-wrap -mx-3">

            <div class="w-full xl:w-1/2 px-3 mb-3 lg:mb-0">
                <p class="text-xl font-semibold mb-4">Latest Posts</p>
                <div class="w-full bg-white border rounded-lg p-4">
                    <div v-for="(post,i) in getAllPosts.slice(0,5)" :key="i" 
                         class="w-full bg-gray-100 border rounded-lg flex justify-between items-center px-4 py-2 mb-4">
                        <div>
                            <p class="font-semibold text-xl">{{post.title}}</p>
                            <p>By: <span v-for="(user,i) in getUsers" :key="i">{{(post.user_id == user.id) ? user.name : null}}</span></p>
                        </div>
                        <span class="text-green-500 font-semibold text-lg">
                            <Button @click="$router.push(`/app/editpost/${post.id}`)" v-if="isUpdatePermitted">
								<Icon type="md-create" />&nbsp;&nbsp;Edit&nbsp;&nbsp;
							</Button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-full xl:w-1/2 px-3">
                <p class="text-xl font-semibold mb-4">Stats</p>

            <div class="w-full">
                <div class="w-full bg-white border text-blue-500 rounded-lg flex items-center p-6 mb-2 xl:mb-10">
                    <Icon type="ios-list-box-outline" size="50" color="#63b3ed"/>
                    <div class="text-gray-700 pl-4">
                        <p class="font-semibold text-3xl">{{getAllPosts.length}}</p>
                        <p>Posts Total</p>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="w-full bg-white border text-blue-500 rounded-lg flex items-center p-6 mb-2 xl:mb-10">
                    <Icon type="ios-paper-outline" size="50" color="#63b3ed"/>
                    <div class="text-gray-700 pl-4">
                        <p class="font-semibold text-3xl">{{getUsersPosts().length}}</p>
                        <p>Your posts</p>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="w-full bg-white border text-blue-500 rounded-lg flex items-center p-6 mb-2 xl:mb-10">
                    <Icon type="ios-people-outline" size="50" color="#63b3ed"/>
                    <div class="text-gray-700 pl-4">
                        <p class="font-semibold text-3xl">{{getUsers.length}}</p>
                        <p>Users Total</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import RouteBreadcrumb from './RouteBreadcrumb.vue';
export default {
    methods: {
        readPermitted(resourceName){
            const permission = this.getUserPermission.filter(
                p => p.resourceName == resourceName
            );
            return permission[0].read; // bool
        },
        getUsersPosts(){
           return this.getAllPosts.filter( post => post.user_id == this.getUser.id);
        },
    },
    components: {
        RouteBreadcrumb
    },
    computed : {
        ...mapGetters(['getUser', 'getUserPermission', 'getUsers', 'getPosts', 'getAllPosts']),
    },
    async created(){
        if(this.getUsers.length == 0){
            if(this.readPermitted('Users')){
                const res = await this.callApi('get', '/app/get_users');
                if(res.status==200){
                    this.$store.commit('setUsers',res.data);
                }else{
                    this.swr();
                }
            }
        }
        if(this.getAllPosts.length == 0){
            if(this.readPermitted('Posts')){
                const res = await this.callApi('get', '/app/get_posts');
                if(res.status==200){
                    this.$store.commit('setAllPosts',res.data); // get paginated data
                }else{
                    this.swr();
                }
            }
        }
	}, 
};
</script>
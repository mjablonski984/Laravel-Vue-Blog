import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: '',
        // Users
        users: [],
        user: false, // auth. user 
        userPermission: false,
        // Posts
        allPosts: [],
        posts: [],  // paginated
        total: 6, // pagination - posts per page 
        // Roles, Tags, Categories
        roles: [],
        categories: [],
        tags: [],
        
        // UI
        sideBarOpen: false,
        // Delete modal data
        deleteModalObj : {
            showDeleteModal: false, 
            deleteUrl : '', 
            data : null, 
            deletingIndex: -1, 
            isDeleted : false,
        },
        showDeleteModal: false,
        // Avatar
        avatarModal: false
    },
    getters: {
        // Users
        getUser(state){
            return state.user
        },
        getUsers(state){
            return state.users
        },
        getUserPermission(state){
            return state.userPermission
        },
        // Posts
        getPosts(state){
            return state.posts
        },
        getAllPosts(state){
            return state.allPosts
        },
        // Roles
        getRoles(state){
            return state.roles
        },
        // Categories
        getCategories(state){
            return state.categories
        },
        // Tags
        getTags(state){
            return state.tags
        },
        // UI
        sideBarOpen: state => {
            return state.sideBarOpen
        },
        getDeleteModalObj(state){
            return state.deleteModalObj
        },        
    },
    // Actions - call mutations
    actions: {
        // Users
        setUser({commit},data) {
            commit('setUser',data);
        },
        setUsers({commit},data) {
            commit('setUsers',data);
        },
        setUserPermission({commit},data) {
            commit('setUserPermission',data);
        },
        // UI
        toggleSidebar(context) {
            context.commit('toggleSidebar');
        },
    },
    // Mutations - directly change state
    mutations: {
        // Users
        setUser(state, data){
            state.user = data
        },
        setUsers(state, data){
            state.users = data
        },
        setUserPermission(state, data){
            state.userPermission = data
        },
        setUserAvatar(state, data){
            state.user.avatar = data
        },
        // Posts
        setPosts(state, data){
            state.posts = data
        },
        setAllPosts(state, data){
            state.allPosts = data
        },
        // Roles
        setRoles(state, data){
            state.roles = data
        },
        // Categories
        setCategories(state, data){
            state.categories = data
        },
        // Tags
        setTags(state, data){
            state.tags = data
        },
        // UI
        toggleSidebar (state) {
            state.sideBarOpen = !state.sideBarOpen
        },
        setDeleteModal(state, data){
            const deleteModalObj = {
                showDeleteModal: false, 
                deleteUrl : '', 
                data : null, 
                deletingIndex: -1, 
                isDeleted : data,
            }
            state.deleteModalObj = deleteModalObj
        },
        setDeletingModalObj(state, data){
            state.deleteModalObj = data
        },
    },

    modules: {
        
    }
})

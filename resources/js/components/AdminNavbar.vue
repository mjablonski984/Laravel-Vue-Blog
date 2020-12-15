<template>
  <div class="sticky top-0 z-40">
    <div class="w-full h-20 px-6 bg-gray-100 border-b flex items-center justify-between">
      <!-- left navbar -->
      <div class="flex">
        
        <!-- mobile hamburger -->
        <div class="inline-block lg:hidden flex items-center mr-4">
          <button class="hover:text-blue-500 hover:border-white focus:outline-none navbar-burger" @click="toggleSidebar()">
            <svg class="h-5 w-5" v-bind:style="{ fill: 'black' }" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <title>Menu</title>
              <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
            </svg>
          </button>
          <router-link to='/'>
              <p class="font-semibold text-lg sm:text-2xl text-blue-500 pl-4">&lt;&nbsp;DevBlog&nbsp;&gt;</p>
          </router-link>
        </div> 

      </div>

      <!-- right navbar -->
      <div class="flex items-center relative cursor-pointer">
        <!-- Search Bar Icon -->
        <search></search>
        <!-- Set avatar -->
        <img v-if="authUser() && authUser().avatar"  :src="avatarUrl()" class="w-8 h-8 md:w-10 md:h-10 rounded-full shadow-lg" @click="dropDownOpen = !dropDownOpen">
        <img v-if="authUser() && !authUser().avatar" :src="avatarReplacement()" class="w-8 h-8 md:w-10 md:h-10 rounded-full shadow-lg" @click="dropDownOpen = !dropDownOpen">
        <!--  If user isn't logged in-->
        <svg v-if="!authUser()" @click="dropDownOpen = !dropDownOpen" class="h-5 w-5" v-bind:style="{ fill: 'black' }" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <title>Menu</title>
              <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
        </svg>
      </div>
    </div>

    <!-- dropdown menu -->
    <div class="absolute bg-gray-100 border border-t-0 shadow-xl text-gray-700 rounded-b-lg w-48 bottom-10 right-0 mr-6" :class="dropDownOpen ? '' : 'hidden'">
      <router-link to="/" class="block px-4 py-2 hover:bg-gray-200" >Home</router-link>
      <!-- Login -->
      <router-link to='/login' class="block px-4 py-2 hover:bg-gray-200" v-if="!authUser()">Login</router-link>           
      <!--  If user is admin-->
      <router-link to="/admin-redirect" class="block px-4 py-2 hover:bg-gray-200" v-if="authUser() && authUser().role.isAdmin">Admin</router-link>
      <!-- Avatar -->
      <a href="#" @click="$store.state.avatarModal =!$store.state.avatarModal" class="block px-4 py-2 hover:bg-gray-200" v-if="getUser">Avatar</a>
      <!--  If user is logged in-->
      <a href="/" @click="logout()" class="block px-4 py-2 hover:bg-gray-200" v-if="getUser">Logout</a>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Search from '../components/Search.vue';
export default {
  name: 'AdminNavbar',
  computed: {
      ...mapGetters(['sideBarOpen','getUser'])
  },
  data() {
    return {
        dropDownOpen: false
    }
  },
  methods: {
    toggleSidebar() {
        this.$store.dispatch('toggleSidebar');
    },
    async logout(){                 
      let res = await this.callApi('post','/logout');
      if (res.status == 200) {
        this.$store.commit('setUser', false);
        this.$store.commit('setUserPermission', false);
      }else{
        this.swr();
      }
      this.$router.push('/'); 
    },
    authUser (){
      return this.getUser;
    },
    avatarUrl (){
      return this.getUser.avatar;
    },
    avatarReplacement () {
      let name = this.getUser.name;
      return `https://eu.ui-avatars.com/api/?background=0D8ABC&color=fff&name=${name}`;
    }
  },
  components: {
    Search
  }
}
</script>
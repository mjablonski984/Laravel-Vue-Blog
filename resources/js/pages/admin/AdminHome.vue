<template>
  <div class="leading-normal tracking-normal" id="main-body">
    <div class="flex flex-wrap">

      <Sidebar />

      <div class="w-full bg-gray-100 pl-0 lg:pl-64 min-h-screen relative" :class="sideBarOpen ? 'overlay' : ''" id="main-content" >

        <admin-navbar />

        <div class="p-6 bg-gray-100 mb-24">
          <admin-home-data v-if="$route.name === 'AdminHome'" />

          <router-view v-else/>

          <avatar-modal v-if="$store.state.avatarModal"></avatar-modal>
        </div>

        <br>
          <div class="w-full border-t-2 px-8 py-6 md:flex justify-between items-center absolute bottom-0 right-0">
          <p class="mb-2 lg:mb-0 ml-0 sm:ml-0 md:ml-0 lg:ml-64">© Copyright 2020</p>
          <div class="flex">
              <a href="#" class="mr-6 hover:text-gray-900">Terms of Service</a>
              <a href="#" class="mr-6 hover:text-gray-900">Privacy Policy</a>
              <a href="#" class="hover:text-gray-900">About Us</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import AdminHomeData from'../../components/AdminHomeData';
import Sidebar from '../../components/Sidebar';
import AdminNavbar from '../../components/AdminNavbar';
import Footer from '../../components/Footer';
import AvatarModal from '../../components/AvatarModal';


export default {
  name: 'AdminHome',
  computed: {
    ...mapGetters(['sideBarOpen','getUser'])
  },
  components: {
    Sidebar,
    AdminNavbar,
    Footer,
    AdminHomeData,
    AvatarModal
  },
  async created(){
    const res = await this.callApi('post', '/auth');
    if (!res.data.role.isAdmin) {
      return this.$router.push('/');
    }
  }, 
}
</script>
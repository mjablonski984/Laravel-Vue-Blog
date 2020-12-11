<template>
    <div class="xl:container mx-auto">
        <!-- VUE ROUTER -->
        <router-view :key="$route.fullPath" />
    </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  methods: {
    ...mapActions(["setUser", "setUserPermission"])
  },

  async created() {
    this.$store.state.token = window.Laravel.csrfToken;

    const res = await this.callApi("post", "/auth");
    if (res.status === 200) {
      // If user object is returned (user is authorized), update state for user & user's permissions
      if (res.data.user) {
          this.setUser(res.data.user);
          if (res.data.role) {
            let permission = JSON.parse(res.data.role.permission);
            this.setUserPermission(permission);
            // console.log('AUTH USER & PERMISSIONS SET',res.data)
          }
      } else {
        this.setUser(false);
        this.setUserPermission(false);
      }
    } else {
        this.swr();
    }
  }
};
</script>

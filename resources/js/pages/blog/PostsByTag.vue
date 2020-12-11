<template>
<div class="min-h-screen flex flex-col">
    <navbar></navbar>
    <!-- Avatar Modal -->
    <avatar-modal v-if="$store.state.avatarModal"></avatar-modal>

    <!-- Text Header -->
    <blog-header :subtitle="`Tag : ${tagName}`"></blog-header>

    <!-- Categories Nav -->
    <categories-nav></categories-nav>

    <!-- Main container -->
    <div class="container mx-auto flex flex-wrap py-6">
        <!-- Posts Section -->
        <section class="w-full md:w-2/3 px-3">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
				<post-card v-for="post in sortedByTag" :key="post.id" :postData="post"></post-card>
			</div>
			
            <!-- Pagination -->
            <div class="py-8 w-full">
				<Page 
				class-name="text-center"
				:total="pageInfo.total"  
				:current="pageInfo.current_page" 
				:page-size="parseInt(pageInfo.per_page)"
				@on-change="getPostData" 
				v-if="pageInfo" />
            </div>
        </section>

        <!-- Sidebar Section -->
        <blog-sidebar></blog-sidebar>
    </div>
	<Footer></Footer>    
</div>
</template>

<script>
import { mapGetters } from 'vuex';
import Navbar from '../../components/Navbar.vue';
import AvatarModal from '../../components/AvatarModal.vue';
import Footer from '../../components/Footer.vue';
import PostCard from '../../components/PostCard.vue';
import CategoriesNav from '../../components/CategoriesNav.vue';
import BlogSidebar from '../../components/BlogSidebar.vue';
import BlogHeader from '../../components/BlogHeader.vue';

export default {
    data(){
        return {
            isLoggedIn : false,
            total: this.$store.state.total, // pagination - posts per page
            pageInfo: {},
            sortedByTag: [],
            tagName: ''
        }
	},
	methods: {
		async getPostData(page=1) {
			const res = await this.callApi('get', `/tags/${this.$route.params.tagName}/${this.$route.params.id}?page=${page}&total=${this.total}`);
			if(res.status==200){
				this.sortedByTag = res.data.posts.data; // get paginated data
                this.pageInfo = res.data.posts; // get page num /links to prev/next
                this.tagName = res.data.tagName;	
			}else{
				this.swr();
			}
		}
	},
    components: {
        Navbar,
		AvatarModal,
		Footer,
        PostCard,
        CategoriesNav,
        BlogSidebar,
        BlogHeader
	},
	async created () {
        this.getPostData();
    }
}
</script>
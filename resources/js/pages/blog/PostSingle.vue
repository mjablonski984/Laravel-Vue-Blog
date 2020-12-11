<template>
<div class="min-h-screen flex flex-col">
    <navbar></navbar>
    <!-- Avatar Modal -->
    <avatar-modal v-if="$store.state.avatarModal"></avatar-modal>
    <!-- Blog header -->
    <blog-header></blog-header>

    <!-- Categories Nav -->
    <categories-nav></categories-nav>

    <div class="container mx-auto flex flex-wrap py-6">

        <!-- Post Section -->
        <section class="w-full md:w-2/3 flex flex-col items-center px-3">

            <article class="flex flex-col w-full shadow my-4">
                <!-- Article Image -->
                <span>
                    <img v-if="post.featuredImage" :src="post.featuredImage" class="object-cover h-56 w-full" >
                    <div v-else class="w-full h-56 bg-blue-700 flex"><span class="font-semibold text-xl text-white m-auto">&lt;&nbsp;DevBlog&nbsp;&gt;</span></div>
                </span>
                <div class="bg-white flex flex-col justify-start p-6">
                    <span>
                        <router-link :to="`/category/${c.categoryName}/${c.id}`" class="text-blue-700 text-sm font-bold uppercase pb-4" v-for="(c,i) in post.cat" :key="i">
                            {{c.categoryName}}&nbsp;&nbsp;
                        </router-link>
                    </span>
                    <h1 class="text-3xl font-bold py-4">{{post.title}}</h1>
                    <p class="text-sm pb-8">
                        By <router-link :to="`/user/${post.user.name}/${post.user.id}`" class="font-semibold hover:text-blue-400" v-if="post.user">{{post.user.name}}</router-link>
                        , Published on {{createdAt}}
                    </p>

                    <!-- Post body (Editor.js object) will be rendered here -->
                    <div v-html="post.post"></div>
                </div>
            </article>

            <div class="w-full flex flex-col text-center md:text-left md:flex-row shadow bg-white mt-10 mb-10 p-6">
                <div class="flex justify-center mx-4 mt-4" v-if="post.user">
                    <img class="rounded-full shadow h-12 w-12" 
                        :src="post.user.avatar ? post.user.avatar : `https://eu.ui-avatars.com/api/?background=0D8ABC&color=fff&name=${post.user.name}`">
                </div>
                <div class="flex-1 flex flex-col justify-center md:justify-start">
                    <p class="font-semibold text-2xl" v-if="post.user">{{post.user.name}}</p>
                    <p class="pt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel neque non libero suscipit suscipit eu eu urna.</p>
                    <div class="flex items-center justify-center md:justify-start text-2xl no-underline text-blue-800 pt-4">
                        <a class="" href="#">
                            <Icon type="logo-facebook" />
                        </a>
                        <a class="pl-4" href="#">
                            <Icon type="logo-instagram" />
                        </a>
                        <a class="pl-4" href="#">
                            <Icon type="logo-twitter" />
                        </a>
                        <a class="pl-4" href="#">
                            <Icon type="logo-linkedin" />
                        </a>
                    </div>
                </div>
            </div>

        </section>

        <!-- Sidebar Section -->
        <aside class="w-full md:w-1/3 flex flex-col items-center px-3">

            <div class="w-full bg-white shadow flex flex-col my-4 p-6">
                <p class="text-xl font-semibold pb-5 border-b border-grey-300 uppercase">RELATED ARTICLES</p>
                <div class="flex mb-3 p-3 border-b border-gray-300"  v-for="(post,i) in relatedPosts" :key="i" >
                    <router-link :to="`/post/${post.slug}`" class="mx-2 hover:opacity-75">
                        <img v-if="post.featuredImage" :src="post.featuredImage" class="shadow h-12 w-12" >
                        <div v-else class="shadow h-12 w-12 bg-blue-700 flex"></div>
                    </router-link>
                    <div>
                        <router-link :to="`/post/${post.slug}`">
                            <p class="font-semibold text-lg" >{{post.title}}</p>
                        </router-link>
                        <p class="pt-2 text-gray-600 text-md" v-if="post.user">{{post.user.name}}</p>
                    </div> 
                </div>

            </div>
            <div class="w-full bg-white shadow flex flex-col my-4 p-6">
                <p class="text-xl font-semibold pb-5 border-b border-grey-300 uppercase mb-4">Tags</p>
                <div v-if="post.tag && post.tag.length">                    
                    <Tag type="border" size="large" v-for="(t, i) in post.tag" :key="i" class="mx-2">
                        <router-link :to="`/tag/${t.tagName}/${t.id}`" >{{t.tagName}}</router-link>
                    </Tag>                 
                </div>
            </div>
            
            <div class="w-full bg-white shadow flex flex-col my-4 p-6">
                <p class="text-xl font-semibold pb-5 border-b border-grey-300 uppercase mb-4">Share this post</p>
                <div class="grid grid-cols-3 gap-3">
                    <Icon class="hover:opacity-75 cursor-pointer" color="#4267B2" size="40" type="logo-facebook" />
                    <Icon class="hover:opacity-75 cursor-pointer" color="#1DA1F2" size="40" type="logo-twitter" />
                    <Icon class="hover:opacity-75 cursor-pointer" color="#F56040" size="40" type="logo-instagram" />
                    <Icon class="hover:opacity-75 cursor-pointer" color="#00AFF0" size="40" type="logo-skype" />
                    <Icon class="hover:opacity-75 cursor-pointer" color="#DB4437" size="40" type="logo-googleplus" />   
                    <Icon class="hover:opacity-75 cursor-pointer" color="#FF0000" size="40" type="logo-youtube" />
                </div>
            </div>

        </aside>
    </div>
	<Footer></Footer>    
</div>
</template>

<script>
import { mapGetters } from 'vuex';
import Navbar from '../../components/Navbar.vue';
import AvatarModal from '../../components/AvatarModal.vue';
import Footer from '../../components/Footer.vue';
import CategoriesNav from '../../components/CategoriesNav.vue';
import BlogHeader from '../../components/BlogHeader.vue';

export default {
    data(){
        return {
            post: false,
            relatedPosts: false,
        }
	},
    components: {
        Navbar,
		AvatarModal,
		Footer,
        CategoriesNav,
        BlogHeader,
    },
    computed : {
        createdAt (){
            return new Date(this.post.created_at).toLocaleDateString();
        }
	},
    async created(){
        const res = await this.callApi('post', `/post/${this.$route.params.slug}`);
        if(res.status==200){
            this.post = res.data.post;
            this.relatedPosts = res.data.relatedPosts;
        }else{
            this.swr();
        }
    }
}
</script>

<template>
<div class="w-full">
    <div class="bg-white rounded-lg overflow-hidden shadow relative">
        <router-link :to="`/post/${post.slug}`">
            <img v-if="post.featuredImage" :src="post.featuredImage" class="h-48 w-full object-cover object-center cursor-pointer" >
            <div v-else class="w-full h-48 bg-blue-700 flex">
                <span class="font-semibold text-lg text-white m-auto cursor-pointer">&lt;&nbsp;DevBlog&nbsp;&gt;</span>
            </div>
        </router-link>
        <div class="p-4 mt-2">
            <router-link :to="`/category/${c.categoryName}/${c.id}`" class="inline border border-gray-300 py-1 px-2 text-xs capitalize text-gray-700 mr-2" v-for="(c,i) in post.cat" :key="i">
                {{c.categoryName}}
            </router-link>
        </div>
        <div class="p-4 h-64 md:h-48 border-t border-gray-300">
            <router-link :to="`/post/${post.slug}`" class="block text-blue-500 hover:text-blue-600 font-semibold mb-3 text-lg md:text-base lg:text-lg">
                {{post.title}}
            </router-link>
            <div class="text-gray-600 text-sm leading-relaxed block md:text-xs lg:text-sm">{{post.postExcerpt}}</div>
        </div>
        <div class="p-4 flex items-center border-t border-gray-300" >
            <div class="user-logo">
                <img class="w-10 h-10 object-cover rounded-full mr-4 shadow" alt="avatar"  
                    :src="post.user.avatar ? post.user.avatar : `https://eu.ui-avatars.com/api/?background=0D8ABC&color=fff&name=${post.user.name}`"
                >
            </div>
            <h2 class="text-sm tracking-tighter text-gray-900">
                <router-link :to="`/user/${post.user.name}/${post.user.id}`">By {{post.user.name}}</router-link> 
                <p class="text-gray-600 text-sm">{{createdAt}}</p>
            </h2>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: ['postData'],
    data() {
        return {
            post: this.postData
        }
    },
    computed : {
        createdAt (){
            return new Date(this.post.created_at).toLocaleDateString();
        }
    }
}
</script>
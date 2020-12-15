<template>
<nav class="w-full py-4 border-t border-b">
    <div class="block sm:hidden">
        <a href="#" @click="open = !open" class="block md:hidden text-base font-bold uppercase text-center flex justify-center items-center">
            Categories 
            <Icon v-if="open" type="ios-arrow-up" />
            <Icon v-else type="ios-arrow-down" />
        </a>
    </div>
    <div :class="open ? 'block': 'hidden'" class="w-full flex-grow sm:flex sm:items-center sm:w-auto overflow-x-auto">
        <div class="w-full container mx-auto flex flex-col sm:flex-row items-center justify-center text-sm font-bold uppercase mt-0 px-4">
            <router-link :to="`/category/${c.categoryName}/${c.id}`" class="flex text-blue-700 text-sm font-bold uppercase px-4 mx-2 py-2 sm:pb-0" v-for="(c, i) in getCategories" :key="i"> 
                <img class="w-6 h-6 object-cover rounded-full mx-1 shadow" :src="c.iconImage" alt="">
                <span class="inline-block pt-1">{{c.categoryName}}</span>
            </router-link>
        </div>
    </div>
</nav>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    data(){
        return {
            open: false,		
        }
    },
    computed : {
		...mapGetters(['getCategories'])
    },
    async created () {
        const res = await this.callApi('get', `/getCategories`);
        if(res.status==200){
            this.$store.commit('setCategories',res.data);
        }else{
			this.swr();
		}
	}
}
</script>
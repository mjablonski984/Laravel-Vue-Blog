<template>
 <div class="pr-4 cursor-pointer" @click="showSearchBar">
    <Icon type="ios-search" size="22" color="#63b3ed" v-show="!showSearch"/>
    <!-- use @click.stop to stop propagation from parent elem-->
    <div class="relative text-gray-600 " @click.stop v-show="showSearch">
        <input type="text" v-model="str"  
            v-on:keyup.enter="searchBlog" 
            ref="search"            
            @blur="showSearch=false" 
            placeholder="Search ..." 
            class="bg-white h-8 w-40 sm:w-64 px-5 rounded-lg border text-sm focus:outline-none absolute right-0 top-8 ">
        <button type="submit" class="absolute right-0 top-0 p-1 pr-2"  @click="searchBlog">
            <Icon type="ios-search" size="22" color="#D1D5DB" class="hover:font-semibold"/>
        </button>
    </div>
</div>
</template>

<script>
export default {
    data(){
        return {
            showSearch: false,
            str: ''
        }
    },
    methods: {
        showSearchBar(){
            this.showSearch = !this.showSearch;
            // Focus on input element
            this.$nextTick(() => this.$refs.search.focus());
        },        
        searchBlog(){
			this.$router.push(`/search?str=${this.str}`);
        }
    }
}
</script>
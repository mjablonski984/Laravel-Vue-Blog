<template>
    <div>
		<!-- breadcrumb -->
		<route-breadcrumb></route-breadcrumb>	
		
		<div class="lg:flex justify-between items-center mb-6">
            <p class="text-2xl font-semibold mb-2 lg:mb-0">Posts</p>
            
			<Button size="large" @click="$router.push('/app/createpost')" v-if="isWritePermitted"><Icon type="md-add" /> Add Post</Button>
        </div>

		<!-- Posts -->
		<section class="text-gray-700 w-full bg-white border rounded-lg p-4">
			<table class="min-w-full table-auto border rounded-lg">
				<thead class="text-left">
					<tr class="bg-gray-700">
						<th class="px-3 py-2 hidden md:table-cell"><span class="text-gray-300">Id&nbsp;&nbsp;</span></th>
						<th class="px-3 py-2"><span class="text-gray-300">Title</span></th>
						<th class="px-3 py-2"><span class="text-gray-300">Categories</span></th>
						<th class="px-3 py-2 hidden md:table-cell"><span class="text-gray-300">Tags</span></th>
						<th class="px-3 py-2 hidden lg:table-cell"><span class="text-gray-300">Views</span></th>
						<th class="px-3 py-2 hidden lg:table-cell"><span class="text-gray-300">Created at</span></th>					
						<th class="px-2 py-2 w-5"><span class="text-gray-300">Actions</span></th>
					</tr>
				</thead>
				<tbody class="bg-gray-200">
					<tr class="bg-white border-4 border-gray-200"  v-for="(post, i) in getPosts" :key="i" v-if="getPosts">
						<td class="px-3 break-all py-2 hidden md:table-cell">
							<span>{{post.id}}</span>
						</td>
						<td class="px-3 break-all py-2">
							<span class="text-center font-semibold">{{post.title}}</span>
						</td>
						<td class="px-3 break-all py-2">
							<span v-for="(c, j) in post.cat" v-if="post.cat.length"><Tag type="border">{{c.categoryName}}</Tag></span>
						</td>
						<td class="px-3 break-all py-2 hidden md:table-cell">
							<span v-for="(t, j) in post.tag" v-if="post.tag.length"><Tag type="border">{{t.tagName}}</Tag></span>
						</td>
						<td class="px-3 break-all py-2 hidden lg:table-cell">
							<span>{{post.views}}</span>
						</td>
						<td class="px-3 break-all py-2 hidden lg:table-cell">
							<span>{{new Date(post.created_at).toLocaleDateString()}}</span>
						</td>
						<td class="px-2 break-all py-2 w-5">
							<Button class="w-full" size="small" @click="$router.push(`/post/${post.slug}`)" ><Icon type="md-eye" /> &nbsp; View &nbsp;</Button>
							<Button class="w-full" size="small" @click="$router.push(`/app/editpost/${post.id}`)" v-if="isUpdatePermitted">
								<Icon type="md-create" />&nbsp;&nbsp;Edit&nbsp;&nbsp;
							</Button>					
							<Button class="w-full" size="small" @click="showDeletingModal(post, i)" :loading="post.isDeleting" v-if="isDeletePermitted">
								<Icon type="md-trash" /> Delete
							</Button>
						</td>
					</tr>
				</tbody>
			</table>
		</section>
		<br><br>
		<!-- PAGINATION  -->
		<!-- @on-change - ViewUi syntax -->
		<Page 
		:total="pageInfo.total"  
		:current="pageInfo.current_page" 
		:page-size="parseInt(pageInfo.per_page)" 
		@on-change="getPostData" 
		v-if="pageInfo" />		
		
		<delete-modal></delete-modal>
    </div>
</template>


<script>
import { mapGetters } from 'vuex'
import DeleteModal from '../../components/DeleteModal.vue';
import RouteBreadcrumb from '../../components/RouteBreadcrumb.vue';
export default {
  components : {
		DeleteModal,
		RouteBreadcrumb
	},
	data(){
		return {
			isAdding : false,
      		index : -1,
			showDeleteModal: false,
      		deletingIndex: -1,
			total: this.$store.state.total, // pagination - posts per page
			pageInfo: {}
		}
	},
	methods : {
		showDeletingModal(post, i){
			this.deletingIndex = i
			const deleteModalObj  =  {
				showDeleteModal: true,
				deleteUrl : '/app/delete_post',
				data : {id: post.id},
				deletingIndex: i,
				isDeleted : false,
				msg : 'Are you sure you want to delete this post?',
				successMsg: 'Post has been deleted successfully!'
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj);
		},
		// PAGINATION
		async getPostData(page=1) {
			const res = await this.callApi('get', `/app/get_posts?page=${page}&total=${this.total}`);
			if(res.status==200){
				this.$store.commit('setPosts',res.data.data); // get paginated data
				this.pageInfo = res.data; // get page num /links to prev/next
			}else{
				if(res.status==403){
					this.e(res.data.msg);
					this.$router.push('/');
					return;
				}
				this.swr();
			}
		}
	},
	async created(){
		this.getPostData();
	},
	computed : {
		...mapGetters(['getDeleteModalObj','getPosts'])
	},
	watch : {
		getDeleteModalObj(obj){
			if(obj.isDeleted){
                this.$store.state.posts.splice(this.deletingIndex,1);
			}
		}
	}
}
</script>
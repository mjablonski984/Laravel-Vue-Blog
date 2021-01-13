<template>
<div>
	<!-- breadcrumb -->
	<route-breadcrumb></route-breadcrumb>	

	<div class="lg:flex justify-between items-center mb-6">
		<p class="text-2xl font-semibold mb-2 lg:mb-0">Tags</p>
		<!-- v-if="isWritePermitted" Check if user is premitted to add new tag -->
		<Button size="large" @click="addModal=true" v-if="isWritePermitted" ><Icon type="md-add" /> Add Tag</Button>
	</div>

	<!-- Table - Categories -->
	<section class="text-gray-700 w-full bg-white border rounded-lg p-4">
		<table class="min-w-full table-auto border rounded-lg">
			<thead class="text-left">
			<tr class="bg-gray-700">
				<th class="px-3 hidden sm:block py-2"><span class="text-gray-300"></span></th>
				<th class="px-3 py-2"><span class="text-gray-300">Id</span></th>
				<th class="px-3 py-2"><span class="text-gray-300">Name</span></th>
				<th class="px-3 py-2 hidden md:table-cell"><span class="text-gray-300">Created at</span></th>					
				<th class="px-2 py-2 w-5" v-show="isUpdatePermitted || isDeletePermitted"><span class="text-gray-300">Actions</span></th>
			</tr>
			</thead>
			<tbody class="bg-gray-200">
				<tr class="bg-white border-4 border-gray-200"  v-for="(tag, i) in getTags" :key="i" v-if="getTags">
					<td class="px-3 break-all py-2 flex flex-row items-center hidden sm:block">
						<svg class="h-10 w-10 fill-current " viewBox="0 0 20 20">
							<path d="M17.35,2.219h-5.934c-0.115,0-0.225,0.045-0.307,0.128l-8.762,8.762c-0.171,0.168-0.171,0.443,0,0.611l5.933,5.934c0.167,0.171,0.443,0.169,0.612,0l8.762-8.763c0.083-0.083,0.128-0.192,0.128-0.307V2.651C17.781,2.414,17.587,2.219,17.35,2.219M16.916,8.405l-8.332,8.332l-5.321-5.321l8.333-8.332h5.32V8.405z M13.891,4.367c-0.957,0-1.729,0.772-1.729,1.729c0,0.957,0.771,1.729,1.729,1.729s1.729-0.772,1.729-1.729C15.619,5.14,14.848,4.367,13.891,4.367 M14.502,6.708c-0.326,0.326-0.896,0.326-1.223,0c-0.338-0.342-0.338-0.882,0-1.224c0.342-0.337,0.881-0.337,1.223,0C14.84,5.826,14.84,6.366,14.502,6.708"></path>
						</svg>
					</td>
					<td class="px-3 break-all py-2">
						<span>{{tag.id}}</span>
					</td>
					<td class="px-3 break-all py-2">
						<span class="text-center font-semibold">{{tag.tagName}}</span>
					</td>
					<td class="px-3 break-all py-2 hidden md:table-cell">
						<span>{{new Date(tag.created_at).toLocaleDateString()}}</span>
					</td>
					<td class="px-2 break-all py-2 w-5" v-show="isUpdatePermitted || isDeletePermitted">
						<Button class="w-full" size="small" @click="showEditModal(tag, i)"  v-if="isUpdatePermitted">
							<Icon type="md-create" />&nbsp;&nbsp;Edit&nbsp;&nbsp;
						</Button>					
						<Button class="w-full" size="small" @click="showDeletingModal(tag, i)"  :loading="tag.isDeleting" v-if="isDeletePermitted">
							<Icon type="md-trash" /> Delete
						</Button>
					</td>
				</tr>
			</tbody>
		</table>
	</section>

    <!-- Add tag modal -->
    <Modal
		v-model="addModal"
		title="Add tag"
		:mask-closable="false"
		:closable="false"
		:mask="true"
		>
		<Input v-model="data.tagName" placeholder="Add tag name"  />

		<div slot="footer">
			<Button type="default" @click="addModal=false">Close</Button>
			<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add tag'}}</Button>
		</div>
    </Modal>

        <!-- Edit tag modal -->
    <Modal
		v-model="editModal"
		title="Edit tag"
		:mask-closable="false"
		:closable="false"
		>
		<Input v-model="editData.tagName" placeholder="Edit tag name"  />

		<div slot="footer">
			<Button type="default" @click="editModal=false">Close</Button>
			<Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit tag'}}</Button>
		</div>
    </Modal>

    <!-- delete alert modal -->
    <delete-modal></delete-modal>

</div>
</template>

<script>
import { mapGetters } from 'vuex';
import DeleteModal from '../../components/DeleteModal';
import RouteBreadcrumb from '../../components/RouteBreadcrumb.vue';
export default {
  components : {
		DeleteModal,
		RouteBreadcrumb
	}, 
	data(){
    return {
		data : {
			tagName: ''
		},
		editData : {
			tagName: ''
		},
		addModal : false,
		editModal : false,
		isAdding : false,
		index : -1,
		isDeleing : false,
		deletingIndex: -1
		}
	},
	methods : {
		async addTag(){
			// this.e(desc) -our method e() from mixins (common.js) , show error notification
			if(this.data.tagName.trim()=='') return this.e('Tag name is required');
			const res = await this.callApi('post', '/app/create_tag', this.data);
			if(res.status===201){
				this.$store.state.tags.unshift(res.data);
				this.s('Tag has been added successfully!');
				this.addModal = false;
				this.data.tagName = '';
			}else{
				// laravel validation error
				if(res.status==422){
					if(res.data.errors.tagName){
						this.e(res.data.errors.tagName[0]);
					}					
				}else{
					this.swr();
				}				
			}
		},

		async editTag(){
			if(this.editData.tagName.trim()=='') return this.e('Tag name is required');
			const res = await this.callApi('post', '/app/update_tag', this.editData);
			if(res.status===200){
				// get index of currently updated item and display tag name from edited data obj
				this.$store.state.tags[this.index].tagName = this.editData.tagName;
				this.s('Tag has been edited successfully!');
				this.editModal = false;
				
			}else{
				if(res.status==422){
					if(res.data.errors.tagName){
						this.e(res.data.errors.tagName[0]);
					}				
				}else{
					this.swr();
				}				
			}
		},

		showEditModal(tag, index){
			let obj = {
				id : tag.id, 
				tagName : tag.tagName
			}
			this.editData = obj
			this.editModal = true
			this.index = index 
		},
		showDeletingModal(tag, i){ 
			this.deletingIndex = i
			const deleteModalObj  =  {
				showDeleteModal: true,
				deleteUrl : '/app/delete_tag',
				data : {id: tag.id},
				deletingIndex: i,
				isDeleted : false,
				msg : 'Are you sure you want to delete this tag?',
				successMsg: 'Tag has been deleted successfully!'
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj);
		}
	},

	async created() {
		if(this.getTags.length == 0){
			const res = await this.callApi('get', '/app/get_tags');
			if(res.status==200){
				this.$store.commit('setTags',res.data);
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

	computed : {
		...mapGetters(['getDeleteModalObj','getTags'])
	},
	watch : {
		getDeleteModalObj(obj){
			if(obj.isDeleted){
				this.$store.state.tags.splice(this.deletingIndex,1); // remove last deleted item from ui
			}
		}
	}
}
</script>
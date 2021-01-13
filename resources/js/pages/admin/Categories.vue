<template>
    <div>
		<!-- breadcrumb -->
		<route-breadcrumb></route-breadcrumb>	

		<div class="lg:flex justify-between items-center mb-6">
            <p class="text-2xl font-semibold mb-2 lg:mb-0">Categories</p>
            <!-- Use v-if to check if user is premitted to add new category -->
			<Button size="large" @click="addModal=true" v-if="isWritePermitted"><Icon type="md-add" /> Add Category</Button>
        </div>
		
		<!-- Table - Categories -->
		<section class="text-gray-700 w-full bg-white border rounded-lg p-4">
			<table class="min-w-full table-auto border rounded-lg">
				<thead class="text-left">
				<tr class="bg-gray-700">
					<th class="px-3 hidden sm:block py-2"><span class="text-gray-300">Icon</span></th>
					<th class="px-3 py-2"><span class="text-gray-300">Id</span></th>
					<th class="px-3 py-2"><span class="text-gray-300">Name</span></th>
					<th class="px-3 py-2 hidden md:table-cell"><span class="text-gray-300">Created at</span></th>					
					<th class="px-2 py-2 w-5" v-show="isUpdatePermitted || isDeletePermitted"><span class="text-gray-300">Actions</span></th>
				</tr>
				</thead>
				<tbody class="bg-gray-200">
					<tr class="bg-white border-4 border-gray-200" v-for="(category, i) in getCategories" :key="i" v-if="getCategories.length">
						<td class="px-3 break-all py-2 flex flex-row items-center hidden sm:block">
							<img class="h-10 w-10 rounded-full object-cover " :src="category.iconImage" alt=""/>
						</td>
						<td class="px-3 break-all py-2">
							<span>{{category.id}}</span>
						</td>
						<td class="px-3 break-all py-2">
							<span class="text-center font-semibold">{{category.categoryName}}</span>
						</td>
						<td class="px-3 break-all py-2 hidden md:table-cell">
							<span>{{new Date(category.created_at).toLocaleDateString()}}</span>
						</td>
						<td class="px-2 break-all py-2 w-5" v-show="isUpdatePermitted || isDeletePermitted" >
							<Button class="w-full" size="small" @click="showEditModal(category, i)"  v-if="isUpdatePermitted">
								<Icon type="md-create" />&nbsp;&nbsp;Edit&nbsp;&nbsp;
							</Button>					
							<Button class="w-full" size="small" @click="showDeletingModal(category, i)"  :loading="category.isDeleting" v-if="isDeletePermitted">
								<Icon type="md-trash" /> Delete
							</Button>
						</td>
					</tr>
				</tbody>
			</table>
		</section>

		<!-- Add category modal-->
		<Modal
			v-model="addModal"
			title="Add category"
			:mask-closable="false"
			:closable="false"
			>
			
			<Input v-model="data.categoryName" placeholder="Add category name"  />
			<Upload
				ref="uploads"
				type="drag"
				:headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
				:on-success="handleSuccess"
				:on-error="handleError"
				:format="['jpg','jpeg','png']"
				:max-size="1024"
				:on-format-error="handleFormatError"
				:on-exceeded-size="handleMaxSize"
				action="/app/upload">
				<div class="py-5">
					<Icon type="ios-cloud-upload" size="52" class="text-blue-500"></Icon>
					<p>Click or drag files here to upload</p>
				</div>
			</Upload>
			<div class="img-upload" v-if="data.iconImage">
				<img :src="data.iconImage">
				<div class="img-upload-cover">
					<Icon type="ios-trash-outline" @click="deleteImage"></Icon>
				</div>					
			</div>
			<div slot="footer">
				<Button type="default" @click="addModal=false">Close</Button>
				<Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add Category'}}</Button>
			</div>
		</Modal>
		<!-- Edit category modal-->
		<Modal
			v-model="editModal"
			title="Edit category"
			:mask-closable="false"
			:closable="false"
		>
			<Input v-model="editData.categoryName" placeholder="Add category name"  />
			<Upload v-show="isIconImageNew"
				ref="editDataUploads"
				type="drag"
				:headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
				:on-success="handleSuccess"
				:on-error="handleError"
				:format="['jpg','jpeg','png']"
				:max-size="2048"
				:on-format-error="handleFormatError"
				:on-exceeded-size="handleMaxSize"
				action="/app/upload">

				<div class="py-5">
					<Icon type="ios-cloud-upload" size="52" class="text-blue-500"></Icon>
					<p>Click or drag files here to upload</p>
				</div>
			</Upload>
			<div class="img-upload" v-if="editData.iconImage">
				<img :src="`${editData.iconImage}`">
				<div class="img-upload-cover">
					<Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
				</div>	
			</div>					
				<div slot="footer">
					<Button type="default" @click="closeEditModal">Close</Button>
					<Button type="primary" @click="editCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit Category'}}</Button>
				</div>
		</Modal>

		<!-- delete alert modal -->
		<delete-modal></delete-modal>
    </div>
</template>


<script>
import DeleteModal from '../../components/DeleteModal.vue';
import RouteBreadcrumb from '../../components/RouteBreadcrumb.vue';
import {mapGetters} from 'vuex';
export default {
	data(){
		return {
			data : {	// new data
				iconImage: '', 
				categoryName: ''
			}, 
			addModal : false, // is add modal open
			editModal : false, // is edit modal open
			isAdding : false,  // is data being send to the server
			editData : {
				iconImage: '', 
				categoryName: ''
			}, 
			index : -1, // get index of clicked category (to get it's id) 
     		deletingIndex: -1,  
			token: '', //csrf token
			isIconImageNew: false, // used to make sure old icon is deleted before uploading  new img
			isEditingItem: false, //check if item is being deleted
		}
	},
	methods : {
		async addCategory(){
			if(this.data.categoryName.trim()=='') return this.e('Category name is required');
			if(this.data.iconImage.trim()=='') return this.e('Icon image is required');
            
			const res = await this.callApi('post', '/app/create_category', this.data);
			if(res.status===201){
				this.$store.state.categories.unshift(res.data);
				this.s('Category has been added successfully!');
				this.addModal = false;
				this.data.categoryName = '';
				this.data.iconImage = '';
			}else{
				if(res.status==422){
					if(res.data.errors.categoryName){
						this.e(res.data.errors.categoryName[0]);
					}
					if(res.data.errors.iconImage){
						this.e(res.data.errors.iconImage[0]);
					}					
				}else{
					this.swr();
				}				
			}
		},
		async editCategory(){
			if(this.editData.categoryName.trim()=='') return this.e('Category name is required');
			if(this.editData.iconImage.trim()=='') return this.e('Icon image is required');
			const res = await this.callApi('post', '/app/update_category', this.editData);
			if(res.status===200){
				this.$store.state.categories[this.index].categoryName = this.editData.categoryName; // update name in ui with name from responce
				this.s('Category has been updated successfully!');
				this.editModal = false;
				
			}else{
				if(res.status==422){
					if(res.data.errors.categoryName){
						this.e(res.data.errors.categoryName[0]);
					}
					if(res.data.errors.iconImage){
						this.e(res.data.errors.iconImage[0]);
					}					
				}else{
					this.swr();
				}			
			}
		},
		showEditModal(category, index){
			this.editData = category
			this.editModal = true
			this.index = index
            this.isEditingItem = true
            this.isIconImageNew = false
		}, 
		showDeletingModal(category, i){
			this.deletingIndex = i
		 	const deleteModalObj  =  {
				showDeleteModal: true, 
				deleteUrl : '/app/delete_category', 
				data : category, 
				deletingIndex: i, 
				isDeleted : false,
				msg : 'Are you sure you want to delete this category?',
        		successMsg: 'Category has been deleted successfully!'
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj);
		}, 
		handleSuccess (res, file) {
			res = `/uploads/${res}` 
			if(this.isEditingItem){
				// set iconImage of edited item to a image coming from the response
				return this.editData.iconImage = res;
			}
			// set iconImage of new category to img from response
			this.data.iconImage = res ;
		},
		async deleteImage(isAdd=true){
			let image;
			// isAdd is used to check if we try delete image in edit or add modal
			if(!isAdd){ // for editing.... 
				this.isIconImageNew = true;
				image = this.editData.iconImage;
				this.editData.iconImage = '';
				this.$refs.editDataUploads.clearFiles(); // ViewUI method used with Upload component
			}else{
				image = this.data.iconImage;
				this.data.iconImage = '';
				this.$refs.uploads.clearFiles();
			}
			
			const res = await this.callApi('post', '/app/delete_image', {imageName: image});
			if(res.status!=200){
				this.data.iconImage = image;
				this.swr();
			}
		}, 
		closeEditModal(){
			this.isEditingItem = false;
			this.editModal = false;
		}
	}, 
	async created(){
		this.token = window.Laravel.csrfToken
		if(this.getCategories.length == 0){
			const res = await this.callApi('get', '/app/get_categories');
			if(res.status==200){
				this.$store.commit('setCategories',res.data);
			}else{
			if(res.status==403){
				this.e(res.data.msg);
				this.$router.push('/');
				return
			}
			this.swr();
			}
		}
	},
	components : {
		DeleteModal,
		RouteBreadcrumb
	},
	computed : {
		...mapGetters(['getDeleteModalObj','getCategories'])
	},
	watch : {
		getDeleteModalObj(obj){
			if(obj.isDeleted){
				this.$store.state.categories.splice(this.deletingIndex,1);
			}
		}
	}
}
</script>
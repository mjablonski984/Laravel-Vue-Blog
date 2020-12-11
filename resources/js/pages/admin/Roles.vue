<template>
    <div>
		<!-- breadcrumb -->
		<route-breadcrumb></route-breadcrumb>	

		<div class="lg:flex justify-between items-center mb-6">
            <p class="text-2xl font-semibold mb-2 lg:mb-0">Role Manangement</p>
            
			<Button size="large" @click="addModal=true" v-if="isWritePermitted"><Icon type="md-add" /> Add a new role</Button>
        </div>
		<!-- Table - Roles -->
		<section class="text-gray-700 w-full bg-white border rounded-lg p-4">
			<table class="min-w-full table-auto border rounded-lg">
				<thead class="text-left">
					<tr class="bg-gray-700">
						<th class="px-3 hidden sm:block py-2"><span class="text-gray-300"></span></th>
						<th class="px-3 py-2"><span class="text-gray-300">Id</span></th>
						<th class="px-3 py-2"><span class="text-gray-300">Role type</span></th>
						<th class="px-3 py-2 hidden md:table-cell"><span class="text-gray-300">Created at</span></th>
						<th class="px-2 py-2 w-5"><span class="text-gray-300">Actions</span></th>
					</tr>
				</thead>
				<tbody class="bg-gray-200">
				<tr class="bg-white border-4 border-gray-200" v-for="(role, i) in getRoles" :key="i" v-if="getRoles.length">
					<td class="px-2 break-all py-2 flex flex-row items-center hidden sm:block">
						<svg class="h-10 w-10 fill-current" viewBox="0 0 20 20">
							<path d="M17.283,5.549h-5.26V4.335c0-0.222-0.183-0.404-0.404-0.404H8.381c-0.222,0-0.404,0.182-0.404,0.404v1.214h-5.26c-0.223,0-0.405,0.182-0.405,0.405v9.71c0,0.223,0.182,0.405,0.405,0.405h14.566c0.223,0,0.404-0.183,0.404-0.405v-9.71C17.688,5.731,17.506,5.549,17.283,5.549 M8.786,4.74h2.428v0.809H8.786V4.74z M16.879,15.26H3.122v-4.046h5.665v1.201c0,0.223,0.182,0.404,0.405,0.404h1.618c0.222,0,0.405-0.182,0.405-0.404v-1.201h5.665V15.26z M9.595,9.583h0.81v2.428h-0.81V9.583zM16.879,10.405h-5.665V9.19c0-0.222-0.183-0.405-0.405-0.405H9.191c-0.223,0-0.405,0.183-0.405,0.405v1.215H3.122V6.358h13.757V10.405z"></path>
						</svg>
					</td>
					<td class="px-3 break-all py-2">
						<span>{{role.id}}</span>
					</td>
					<td class="px-3 break-all py-2">
						<span class="text-center font-semibold">{{role.roleName}}</span>
					</td>
					<td class="px-3 break-all py-2 hidden md:table-cell">
						<span>{{new Date(role.created_at).toLocaleDateString()}}</span>
					</td>
					<td class="px-2 break-all py-2 w-5">
						<Button class="w-full" size="small" @click="showEditModal(role, i)" v-if="isUpdatePermitted">
							<Icon type="md-create" />&nbsp;&nbsp;Edit&nbsp;&nbsp;
						</Button>					
						<Button class="w-full" size="small" @click="showDeletingModal(role, i)" :loading="role.isDeleting" v-if="isDeletePermitted">
							<Icon type="md-trash" /> Delete
						</Button>
					</td>
				</tr>
				</tbody>
			</table>
		</section>

		<!-- Add modal -->
		<Modal
			v-model="addModal"
			title="Add role"
			:mask-closable="false"
			:closable="false"
			>
			<Input v-model="data.roleName" placeholder="Role name" />

			<div slot="footer">
				<Button type="default" @click="addModal=false">Close</Button>
				<Button type="primary" @click="add" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add Role'}}</Button>
			</div>
		</Modal>
		<!-- Edit modal -->
		<Modal
			v-model="editModal"
			title="Edit role"
			:mask-closable="false"
			:closable="false"
			>
			<Input v-model="editData.roleName" placeholder="Edit role" />

			<div slot="footer">
				<Button type="default" @click="editModal=false">Close</Button>
				<Button type="primary" @click="edit" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing..' : 'Edit Role'}}</Button>
			</div>

		</Modal>
		<!-- Delete modal -->
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
				roleName: ''
			},
			editData : {
				roleName: ''
			},
			addModal : false, 
			editModal : false, 
			isAdding : false,  
			index : -1,  
			deletingIndex: -1, 
		}
	},
	methods : {
		async add(){
			if(this.data.roleName.trim()=='') return this.e('Role name is required');
			const res = await this.callApi('post', '/app/create_role', this.data);
			if(res.status===201){
				this.$store.state.roles.unshift(res.data);
				this.s('Role has been added successfully!');
				this.addModal = false;
				this.data.roleName = '';
			}else{
				if(res.status==422){
					if(res.data.errors.roleName){
						this.e(res.data.errors.roleName[0]);
					}					
				}else{
					this.swr();
				}	
			}
		},
		async edit(){
			if(this.editData.roleName.trim()=='') return this.e('Tag name is required');
			const res = await this.callApi('post', '/app/update_role', this.editData);
			if(res.status===200){
				this.$store.state.roles[this.index].roleName = this.editData.roleName;
				this.s('Role name has been updated successfully!');
				this.editModal = false;
			}else{
				if(res.status==422){
					if(res.data.errors.roleName){
						this.e(res.data.errors.roleName[0]);
					}				
				}else{
					this.swr();
				}				
			}
		},
		showEditModal(role, index){
			let obj = {
				id : role.id, 
				roleName : role.roleName
			}
			this.editData = obj
			this.editModal = true
			this.index = index
		}, 

	  	showDeletingModal(data, i){
			this.deletingIndex = i
			const deleteModalObj  =  {
				showDeleteModal: true, 
				deleteUrl : '/app/delete_role',
				data : data,
				deletingIndex: i,
				isDeleted : false,
				msg : 'Are you sure you want to delete this role?',
				successMsg: 'Role has been deleted successfully!'
			}
			this.$store.commit('setDeletingModalObj', deleteModalObj);
	    }
	}, 
	async created(){
		if(this.getRoles.length == 0){
			const res = await this.callApi('get', '/app/get_roles');
			if(res.status==200){
			this.$store.commit('setRoles',res.data);
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

	computed : {
		...mapGetters(['getDeleteModalObj', 'getRoles'])
	},

	watch : {
		getDeleteModalObj(obj){
			if(obj.isDeleted){
				this.$store.state.roles.splice(this.deletingIndex,1);
			}
		}
	}	
}
</script>
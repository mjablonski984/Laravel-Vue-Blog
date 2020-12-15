<template>
    <div class="z-100">
    <Modal
		v-model="$store.state.avatarModal"
		title="Upload avatar"
		:mask-closable="true"
		:closable="false"
		>
        <Upload
            v-show="!avatar"
            ref="uploads"
            type="drag"
            :headers="{'x-csrf-token' : $store.state.token, 'X-Requested-With' : 'XMLHttpRequest'}"
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
        <div class="img-upload" v-if="avatar">
            <img :src="avatar">
            <div class="img-upload-cover">
                <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
            </div>					
        </div>
        <span class="inline-block text-gray-700 align-top pt-5" v-if="avatar">Change avatar image</span>

        <div slot="footer">
            <Button type="default" @click="$store.state.avatarModal=false">Close</Button>
            <Button type="primary" @click="upload" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Change avatar'}}</Button>
        </div>

	</Modal>
</div>
</template>

<script>
export default {
    data(){
        return{
            avatar: '',
            isAdding: false,
            avatarModal: false,
        }
    },
    methods: {
        async upload(){     
            if(this.avatar.trim()=='') return this.e('No Image Selected');
			const res = await this.callApi('post', '/app/update_avatar', {'avatar':this.avatar});
			if(res.status===200){
				this.s('Avatar has been updated!');
                this.$store.state.avatarModal = false;
                this.avatar = '';
                		
			}else{
				if(res.status==422){
					if(res.data.errors.avatar){
						this.e(res.data.errors.avatar[0]);
					}	
				}else{
					this.swr();
				}			
			}
        },
        handleSuccess (res, file) {
			res = `/uploads/${res}`;
            this.avatar = res;
            this.$store.commit('setUserAvatar',this.avatar);
        },
        async deleteImage(isAdd=true){
            let image;
            
			image = this.avatar;
			this.avatar = '';
            this.$refs.uploads.clearFiles(); // ViewUI mehtod - clears all data from the upload input field
                
			const res = await this.callApi('post', '/app/delete_image', {imageName: image});
			if(res.status!=200){
				this.avatar = image;
				this.swr();
            }
            this.$store.commit('setUserAvatar',null);
		}, 
    }
}
</script>
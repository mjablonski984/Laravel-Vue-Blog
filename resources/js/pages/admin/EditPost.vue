<template>
    <div>
		<!-- breadcrumb -->
		<route-breadcrumb></route-breadcrumb>

		<div class="lg:flex justify-between items-center mb-6">
            <p class="text-2xl font-semibold mb-2 lg:mb-0">Update a post</p>
        </div>
		
		<div class="input-field">
			<Input type="text" v-model="data.title" placeholder="Title" />
		</div>
		<!-- ViewUI file upload component - display only if featuredImage is null to prevent upload of multiple files -->
		<Upload 
			v-show="!data.featuredImage"
			class="input-field"
			v-model="data.featuredImage"
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
			<div class="py-3">
				<Icon type="ios-cloud-upload" size="22" class="text-blue-500"></Icon>
				<p  class="text-gray-400">Upload cover image</p>
			</div>
		</Upload>
		<div class="input-field"  v-if="data.featuredImage">
			<div class="img-upload">
				<img :src="data.featuredImage">
				<div class="img-upload-cover">
					<Icon type="ios-trash-outline" @click="deleteImage"></Icon>
				</div>								
			</div>
			<span class="inline-block text-gray-700 align-top pt-5">Change cover image</span>
		</div>

		<div class="overflow-auto post-editor input-field">
			<editor v-if="initData"
				ref="editor"
				autofocus
				holder-id="codex-editor"
				save-button-id="save-button"
				:init-data="initData"
				@save="onSave"
				:config="config"
			/>
		</div>
		<div class="input-field">
				<Input  type="textarea" v-model="data.postExcerpt" :rows="4" placeholder="Post excerpt " />
			</div>
		<div class="input-field">
			<Select  filterable multiple placeholder="Select category" v-model="data.category_id">
				<Option v-for="(c, i) in getCategories" :value="c.id" :key="i">{{ c.categoryName }}</Option>
			</Select>
		</div>
		<div class="input-field">
			<Select  filterable multiple placeholder="Select tag" v-model="data.tag_id">
				<Option v-for="(t, i) in getTags" :value="t.id" :key="i">{{ t.tagName }}</Option>
			</Select>
		</div>
		<div class="input-field">
			<Input  type="textarea" v-model="data.metaDescription" :rows="4" placeholder="Meta description" />
		</div>
		<div class="input-field">
			<Button size="large" @click="save" :loading="isCreating" :disabled="isCreating"><Icon type="md-create" /> {{isCreating ? 'Please wait...' : 'Update post'}}</Button>
		</div>
		
	</div>
</template>

<script>
import { mapGetters } from 'vuex';
import RouteBreadcrumb from '../../components/RouteBreadcrumb.vue';
export default {
	data(){
		return {
			config: {},
			initData: null,
			data: {
				title : '',
				post : '',
				postExcerpt : '',
				metaDescription : '',
				featuredImage : '',
				category_id : [], 
				tag_id : [], 
				jsonData: null				
			}, 
			articleHTML: '',  
			isCreating: false,
		}
	},
	methods : {
		async onSave(response){
			let data = response;
			await this.outputHtml(data.blocks);
			this.data.post = this.articleHTML;
			this.data.jsonData = JSON.stringify(data);
			// console.log(data.blocks)
			if(this.data.title.trim()=='') return this.e('Post title is required');
			if(this.data.post.trim()=='') return this.e('Post body is required');
			if(this.data.postExcerpt.trim()=='') return this.e('Post exerpt is required');
			if(this.data.metaDescription.trim()=='') return this.e('Meta description is required');
			if(!this.data.tag_id.length) return this.e('Tag is required');
			if(!this.data.category_id.length) return this.e('Category is required');

			this.isCreating = true;
			const res = await this.callApi('post', `/app/update_post/${this.$route.params.id}`, this.data);
			if(res.status===200){
				this.s('Post has been updated successfully!');
				this.$router.push('/app/posts');
			}else{
				if(res.status==422){
					for(let i in res.data.errors){
						this.e(res.data.errors[i][0]);
					}
				}else{
					this.swr();
				}
			}
			this.isCreating = false;
		},
		async save(){
			this.$refs.editor.save();
		},
		outputHtml(articleObj){
			articleObj.map(obj => {
				switch (obj.type) {
				case 'paragraph':
					this.articleHTML += this.makeParagraph(obj);
					break;
				case 'image':
					this.articleHTML += this.makeImage(obj);
					break;
				case 'header':
					this.articleHTML += this.makeHeader(obj);
					break;
				case 'raw':
					this.articleHTML += this.makeRawHtml(obj);
					break;
				case 'code':
					this.articleHTML += this.makeCode(obj);
					break;
				case 'list':
					this.articleHTML += this.makeList(obj);
					break;
				case 'link':
					this.articleHTML += this.makeLink(obj);
					break;
				case "quote":
					this.articleHTML += this.makeQuote(obj);
					break;
				case "table":
					this.articleHTML += this.makeTable(obj);
					break;
				case "warning":
					this.articleHTML += this.makeWarning(obj);
					break;
				case "checklist":
					this.articleHTML += this.makeChecklist(obj);
					break;
				case 'delimeter':
					this.articleHTML += this.makeDelimeter(obj);
					break;
				default:
					return '';
				}
			});
		},
		handleSuccess (res, file) {
			res = `/uploads/${res}`;
			this.data.featuredImage = res;
		},
		async deleteImage(){
			let image;
			image = this.data.featuredImage;
			this.data.featuredImage = '';
			this.$refs.uploads.clearFiles(); // ViewUI mehtod - clears all data from the upload input field
				
			const res = await this.callApi('post', '/app/delete_image', {imageName: image});
			if(res.status!=200){
				this.data.featuredImage = image;
				this.swr();
			}
			this.data.featuredImage = '';
		},		
	},
	computed: {
		...mapGetters(['getTags','getCategories'])
	},
	components : {
		RouteBreadcrumb
	},
	async created(){
        const id = parseInt(this.$route.params.id);
        
        if(!id){
            return this.$router.push('/notfound');
        }
		const [post, cat, tag] = await Promise.all([
			this.callApi('get', `/app/show_post/${id}`),
			this.callApi('get', '/app/get_categories'),
			this.callApi('get', '/app/get_tags'),
		])
			
		if(post.status==200){
            if(!post.data) return this.$router.push('/notfound');
            this.initData = JSON.parse(post.data.jsonData); // parsed jsonData with editorjs object
            // console.log(this.initData.blocks)
			this.category = this.$store.state.category;
            this.tag = this.$store.state.tag;
            for(let t of post.data.tag){
                this.data.tag_id.push(t.id);
            }
            for(let c of post.data.cat){
                this.data.category_id.push(c.id);
            }
            this.data.title = post.data.title;
            this.data.jsonData = post.data.jsonData;
            this.data.metaDescription = post.data.metaDescription;
			this.data.postExcerpt = post.data.postExcerpt;
			this.data.featuredImage = post.data.featuredImage;
		}else{
			this.swr();
		}
		if(cat.status==200){
			this.$store.commit('setCategories',cat.data); 
		}else{
			this.swr();
        }
		if(tag.status==200){
			this.$store.commit('setTags',tag.data);  
		}else{
			this.swr();
        }
	}
}
</script>
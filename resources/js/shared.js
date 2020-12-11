/* This file is used as mixin in app.js and contains methods shared by vue components.
    Mehods should be called in the same way as internal component methods (this.methodName)*/
import { mapGetters } from 'vuex';
export default {
    methods: {
        // Primary method for sending request to the server
        async callApi(method, url, dataObj ){
            try {
              return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                return e.response;
            }
        },
 
        // ViewUI methods used to display notifications,
        i(desc, title="Hey") {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        s(desc, title="Great!") {
            this.$Notice.success({
                title: title,
                desc: desc
            });
        },
        w(desc, title="Warning!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        e(desc, title="Error!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        }, 
        swr(desc='Somethingn went wrong! Please try again.', title="Oops") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        // File upload notifications 
		handleError (res, file) {
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: `${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
			});
		},
		handleFormatError (file) {
			this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
			});
		},
		handleMaxSize (file) {
			this.$Notice.warning({
				title: 'Exceeding file size limit',
				desc: 'File  ' + file.name + ' is too large, no more than 1MB.'
			});
		},

        // Check if user has permission to create/read/update/delete  
        checkUserPermission(key){
            // If no permissions object allow all / else find permision name matchnig current route name , if key(c,r,u,d) is true break and return true
            if(!this.userPermission) return true;
            let isPermitted = false;
            for(let d of this.userPermission){
                if(this.$route.path==d.name){
                    if(d[key]){
                        isPermitted = true;
                        break;
                    }else{
                        break;
                    }
                }    
            }
            return isPermitted;
        }
    },

    computed: {
        ...mapGetters({
            'userPermission' : 'getUserPermission'
        }),
        isReadPermitted(){
           return this.checkUserPermission('read');
        },
        isWritePermitted(){
            return this.checkUserPermission('write');
        },
        isUpdatePermitted(){
            return this.checkUserPermission('update');
        },
        isDeletePermitted(){
            return this.checkUserPermission('delete');
        },
    }, 
}
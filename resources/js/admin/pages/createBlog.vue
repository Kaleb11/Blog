<template>
<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">Create blog</p>
          <div class="_input_field">
            <Input type="text" v-model="data.title" placeholder="title"></Input>
          </div>
              
					<div class="_overflow _table_div blog_editor">
						 <Editor
                            autofocus
                            ref="editor"
                            holder-id="codex-editor"
                            save-button-id="save-button"
                            :config="config"
                            :init-data="initData"
                            @save="onSave()"
                           
                    />
					</div>
          <div class="_input_field">
            <Input type="textarea" :rows="4" v-model="data.post_excerpt" placeholder="Post excerpt"></Input>
          </div>
          <div class="_input_field">
          <Select filterable multiple placeholder="Select category" v-model="data.category_id">
                <Option v-for="(c,i) in category" :value="c.id" :key="i">{{ c.categoryName }}</Option>
            </Select>
          </div>
           <div class="_input_field">
          <Select filterable multiple placeholder="Select tag" v-model="data.tag_id">
                <Option v-for="(t,i) in tags" :value="t.id" :key="i">{{ t.tagName }}</Option>
            </Select>
          </div>
          <div class="_input_field">
            <Input type="textarea" :rows="4" placeholder="Meta description" v-model="data.metaDescription"></Input>
          </div>
           <div class="_input_field">
           <p><b>*Upload blog feature image</b></p>
           <Upload
			  
                        ref="uploads" 
                        type="drag"
                        :headers="{'x-csrf-token' :token,'X-Requested-With':'XMLHttpRequest'}"
                        :on-success="handleSuccess"
                        :format="['jpg','jpeg','png']"
                        :on-format-error="handleFormatError"
                        :max-size="2048"
                        :on-error="handleError"
                        :on-exceeded-size="handleMaxSize"
                        action="/app/upload/blog/feature/photo">
                      <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                          <p>Click or drag files here to upload</p>
                      </div>
                      </Upload>
                <div class="demo-upload-list" v-if="data.featuredImage">
                      
                          <img :src= "`${data.featuredImage}`">
                      <div class="demo-upload-list-cover">
                          <Icon type="ios-trash-outline" @click="deleteImage()"></Icon>
                      </div>
                        
                      </div>

         
          </div>
          <div class="_input_field">
                    <Button @click="save" :loading="isCreating" :disabled="isCreating">{{isCreating ? 'Please wait...' : 'Create blog'}}</Button></div>
				</div>
       
			</div>
		</div>
	</div>
</template>

<script>
import List from '@editorjs/list';
import CodeTool from '@editorjs/code'
import Paragraph from '@editorjs/paragraph'
import Embed from '@editorjs/embed'
import Table from '@editorjs/table'
import Checklist from '@editorjs/checklist'
import Marker from '@editorjs/marker'
import Warning from '@editorjs/warning'
import RawTool from '@editorjs/raw'
import Quote from '@editorjs/quote'
import InlineCode from '@editorjs/inline-code'
import Delimiter from '@editorjs/delimiter'
import SimpleImage from '@editorjs/simple-image'
import Image from '@editorjs/image'
import Underline from '@editorjs/underline'
export default{
     data(){
		 return{
            data :{
              ress:'',
              title:'',
              post:'',
              post_excerpt:'',
              metaDescription:'',
              category_id:[],
              tag_id:[],
              jsonData: null,
              featuredImage:'',
              roleName:'',
			        isAdmin:false
              // user_id:'',
            },
            // data:'',
            img:'',
            token: '',
            articleHTML:'',
            category:[],
            tags:[],
            isCreating:false,
            initData:null,
            isOnEditing:false,
            isEditing:false,
            isIconImageNew:false,
            config : {
           
            tools: {
                                header: require('@editorjs/header'),
                                list: {
            class: List,
            inlineToolbar: true,
          },
          code: {
            class: CodeTool
          },
          paragraph: {
            class: Paragraph,
          },
          embed: {
            class: Embed,
            config: {
              services: {
                youtube: true,
                coub: true,
                imgur: true
              }
            }
          },
          table: {
            class: Table,
            inlineToolbar: true,
            config: {
              rows: 2,
              cols: 3,
            },
          },
          checklist: {
            class: Checklist,
          },
          Marker: {
            class: Marker,
            shortcut: 'CMD+SHIFT+M',
          },
          warning: {
            class: Warning,
            inlineToolbar: true,
            shortcut: 'CMD+SHIFT+W',
            config: {
              titlePlaceholder: 'Title',
              messagePlaceholder: 'Message',
            },
          },
          raw: RawTool,
          quote: {
            class: Quote,
            inlineToolbar: true,
            shortcut: 'CMD+SHIFT+O',
            config: {
              quotePlaceholder: 'Enter a quote',
              captionPlaceholder: 'Quote\'s author',
            },
          },
          inlineCode: {
            class: InlineCode,
            shortcut: 'CMD+SHIFT+M',
          },
          image: {
           
            class: Image,
            shortcut: 'CMD+SHIFT+z',
            config: {
                endpoints: {
                    byFile: '/createBlog'
                }
            }
            
          },
          underline: {
            class: Underline,
            shortcut: 'CTRL+SHIFT+U',
          },
          delimiter: Delimiter,
          imagee: SimpleImage,
                               },
               image :{
                   field: 'image',
                   types: 'image/*',
               }
            },
            initData : null,
			// data : {
				
			// },
		 }
	 },
	 methods : {
    async onSave(response) {
				console.log('Response on save',response)
        var data = response
        await this.outputHtml(data.blocks)
        console.log("The html",this.articleHTML)
			},
        async save() {
          this.$refs.editor._data.state.editor.save()
					.then(async(data) => {
						// Do what you want with the data 
          this.isCreating=true
          this.outputHtml(data.blocks)
          this.data.post = this.articleHTML
					if(this.data.title == '') return this.error('Title is required')
          if(this.data.post== '') return this.error('Post is required')
          if(this.data.post_excerpt== '') return this.error('Post excerpt is required')
          if(this.data.metaDescription == '') return this.error('Meta description is required')
          if(this.data.featuredImage=='') return this.error('Feature image is required')
           
            this.data.jsonData = JSON.stringify(data)
            
            const res = await this.callApi('post','app/create-blog',this.data)
            console.log("The status",res.status)
            if(res.status === 200){
              this.success("Blog has been created successfully")
              this.$router.push('/blogs')
            }else{
			
			     this.isCreating=false
	         if(res.status==422){
			
			   
		  
                console.log(res.data.errors)
                for(let i in res.data.errors){
                    console.log(res.data.errors[i])
                    this.error(res.data.errors[i])
                }

              }else{
                      this.swr()
                    }
            }
            this.isCreating = false
                  }

                  )
					.catch(err => { console.log(err) })
			},
      handleSuccess (res, file) {
			if(this.isOnEditing){
				this.editData.featuredImage= `/uploads/BlogFeaturedImage/${res}`
				console.log(this.editData.featuredImage)
			}else{
        
				 
				 console.log(this.data.featuredImage)
			   console.log("Here")
				//  this.$store.commit('setPhoto',res)
				//  console.log("Success photo", this.$store.state.featuredImage)
				 
			     this.data.featuredImage = `/uploads/BlogFeatureImage/${res}`
           console.log('The featured image',this.data.featuredImage)
				 }   
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
                    desc: 'File  ' + file.name + ' is too large, no more than 2M.'
                });
            },
		   handleError(res,file){
         this.$Notice.warning({
                   title: 'The file format is incorrect',
                    desc: `${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!!'}`
                });
		},
    async deleteImage(isAdd=true){
			if(!isAdd){ // for editing.....
			    // this.editData.iconImage=''
			    // this.$refs.clearFiles()
				
        this.isIconImageNew=true
				this.img = this.editData.featuredImage
			  this.editData.featuredImage=''
			  this.$refs.editDataUploads.clearFiles()
				this.isAdd=false
			}else{        
			this.$refs.uploads.clearFiles() 
			this.img = this.data.featuredImage
			this.data.featuredImage=''
			
			}
			const res = await this.callApi('post','app/delete_image',{featuredImage:this.img})
			// this.isIconImageNew=true
			if (res.status!=200){
         this.data.featuredImage = this.img
			   this.swr()
			}
		},
		onClosingImage(isAdd=true){
		   //this.isIconImageNew=false
		   if(!isAdd){
			 this.isOnEditing=false
			 this.isIconImageNew=false
			 this.editModal=false
			 this.$refs.editDataUploads.clearFiles()
			 this.isEditing=false
		   }else{
		   this.deleteImage()
		   
		   this.data.fullName=''
		   this.data.email=''
		   this.data.password=''
		   this.data.role_id=1
		   this.addModal=false
		   
		  
		   }
		},
    outputHtml(articleObj){
       console.log("Switch started"),
       articleObj.map(obj =>{
         console.log("Switch started")
         switch (obj.type){
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
           this.articleHTML += `<div class ="ce-block">
           <div class = "ce-block__content">
           <div class = "ce-code"
              <code>${obj.data.html}</code>
              </div>
              </div>
              </div>\n
           `;
           break;
         case 'code':
            this.articleHTML += this.makeCode(obj);
            break;
         case 'list':
            this.articleHTML += this.makeList(obj)
            break;
         case 'quote':
            this.articleHTML += this.makeQuote(obj)
            break;   
         case "warning":
            this.articleHTML += this.makeQuote(obj)
            break;
         case "embed":
            this.articleHTML += this.makeEmbed(obj)
            break;
         case "delimeter":
            this.articleHTML += this.makeDelimeter(obj);
            break;
         default:
            return '';
         }
       });
    },
		async add(){
          if(this.data.roleName.trim()=='' || this.data.roleName == '') return this.error('Role name is required')
		  const res = await this.callApi('post','/app/create_role',this.data)
	      if(res.status===201){
		      this.roles.unshift(res.data)
              this.success('Role has been added successfully!')
			  this.addModal = false
			  this.data.roleName = ''
		  }else{
	        if(res.status==422){
				if(res.data.error.roleName){
					this.error(res.data.error.roleName[0])
				}
                 console.log(res.data.error.roleName)
			}else{
				
				this.swr()
			}
			  
		  }
		 },
 
        
	 },
async created() {
    this.token=window.Laravel.csrfToken
    const [res,ress]= await Promise.all([
        this.callApi('get','/app/get_category'),
			  this.callApi('get','/app/get_tags')
		])
    if(res.status === 200){
      this.category = res.data
    }else{
      this.swr()
    }
    
    if(ress.status === 200){
      this.tags = ress.data
    }else{
      this.swr()
    }
  },
    //  async created(){
	// 	this.token=window.Laravel.csrfToken}
}
</script>
<style>
.blog_editor {
  width:797px;
  margin-left:117px;
  padding:4px 7px;
  font-size:14px;
  
  border:1px solid #dcdee2;
  border-radius:4px;
  color:black;
  background-color: #fff;
  background-image: none;
  z-index:-1;
}
.blog_editor:hover {
  border:1px solid #57a3f3;
}
._input_field{
  margin: 20px 0 20px 117px;
  width:797px;
}
</style>
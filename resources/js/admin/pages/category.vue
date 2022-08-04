<template>
<div>
		<div class="content">
			<div class="container-fluid">
				<div class="demo-spin-article">
        
        <Spin size="large" fix v-if="spinShow"></Spin>
    </div>
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Category <Button v-if="isWritePermitted" @click="AddModal()"><Icon type="md-add" />Add category</Button></p>
                    
					<div class="_overflow _table_div">
						
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Icon image</th>
								<th>Category name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(category, i) in categories" :key="i" v-if="categories.length">
								<td>{{category.id}}</td>
								<td class="table_image">
									<img :src="category.iconImage"/> </td>
								<td class="_table_name">{{category.categoryName}}</td>
								
								<td>{{new Date(category.created_at).toLocaleDateString("en-US")}}</td>
								<td>
									<Button v-if="isReadPermitted" type="info" @click="viewInfo(category)" size="small">View</Button>
									<Button v-if="isUpdatePermitted" size="small" @click="showEditModal(category,i)">Edit</Button>
									<Button v-if="isDeletePermitted" type="error" size="small" @click="showDeletingmodal(category,i)" :loading="category.isDeleting">Delete</Button>			
								</td>
							</tr>
								<!-- ITEMS -->

								<!-- ITEMS -->
							
								<!-- ITEMS -->


						</table>
						<div class="pagspace">
						   
							<Page :total="pageInfo.total" :current="pageInfo.current_page" :page-size="parseInt(pageInfo.per_page)" :on-next="pageInfo.next_page_url" @on-change="getCategoryData" v-if="pageInfo" show-total />
							
						
						</div>
					</div>
				</div>
        <!-- Category adding  modal	 -->
        <Modal
			v-model="imageModal"
			title="Add category"
			:mask-closable="false"
			:closable="false">
            
			<Input v-model="data.categoryName" placeholder="Add category name" />
            <div class="space" style="padding: 8px;"></div>
			
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
              action="/app/upload">
            <div style="padding: 20px 0">
               <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                <p>Click or drag files here to upload</p>
            </div>
            </Upload>
			<div class="demo-upload-list" v-if="data.iconImage">
            
                <img :src= "`/uploads/${data.iconImage}`">
            <div class="demo-upload-list-cover">
                <Icon type="ios-trash-outline" @click="deleteImage()"></Icon>
            </div>
               
            </div>
			<!-- <div class="image_thumb" v-if="data.iconImage">
				 <img :src = "`/uploads/${data.iconImage}`" style="width: 26%;" />
			</div> -->
			<div slot="footer">
				<Button type="default" @click="onClosingImage()">Close</Button>
				<Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding...': 'Add Category'}}</Button>
			</div>
		</Modal>
		<!-- Edit modal -->
		 <Modal
			v-model="editModal"
			title="Edit category"
			:mask-closable="false"
			:closable="false">
			<Input v-model="editData.categoryName" placeholder="Add category name" />
            <div class="space" style="padding: 8px;"></div>
			
            <Upload
			  v-show="isIconImageNew"
			  ref="editDataUploads" 
              type="drag"
			  :headers="{'x-csrf-token' :token,'X-Requested-With':'XMLHttpRequest'}"
			  :on-success="handleSuccess"
              :format="['jpg','jpeg','png']"
			  :on-format-error="handleFormatError"
              :max-size="2048"
              :on-error="handleError"
              :on-exceeded-size="handleMaxSize"
              action="/app/upload">
            <div style="padding: 20px 0" >
               <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                <p>Click or drag files here to upload</p>
            </div>
            </Upload>
			<div class="demo-upload-list" v-if="editData.iconImage">
               
                <img :src= "`${editData.iconImage}`">
            <div class="demo-upload-list-cover">
                <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
            </div>
               
            </div>
			<div slot="footer">
				<Button type="default" @click="onClosingImage(false)">Close</Button>
				<Button type="primary" @click="editCategory" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Editing...': 'Edit category'}}</Button>
			</div>
		</Modal>
            
    <!-- Full screen modal -->
		<Modal v-model="viewInfoModal" fullscreen title="Category Info">
               <p>Category Name: {{viewData.categoryName}}</p>
			   <p>Icon Image</p>
			   <div class="demo-infoview-list" v-if="viewData.iconImage">
               
                <img :src= "`${viewData.iconImage}`">
			   </div>
			   <p>Created at: {{viewData.created_at}}</p>
			   <div slot="footer">
				<Button type="default" @click="viewInfoModal=false">Close</Button>
			</div>
        </Modal>
		<deleteModal></deleteModal>
		<!-- <Modal
			v-model="showDelete"
			width="360">
		<p slot="header" style="color:#f60;text-align:center">
			<Icon type="ios-information-circle"></Icon>
			<span>Delete confirmation</span>
		</p>
		<div style="text-align:center">
			<p>Are you sure you want to delete tag?</p>
			
		</div>
		<div slot="footer">
			<Button type="error" size="large" long : loading="showDeleteModal" :disabled="showDeleteModal"  @click="deleteTag">Delete</Button>
		</div>
		</Modal> -->
			</div>
		</div>
	</div>
</template>

<script>
import deleteModal from '../components/deleteModal.vue'
import { mapGetters } from 'vuex'
export default{
     data(){
		 return{
			data : {
				iconImage:'',
				categoryName:''
			},
			editModal: false,
			addModal : false,
			isAdding : false,
			isEditing : false,
			categories : [],
			editData: {
				iconImage:'',
				categoryName:''
			},
			viewData:{},
			index : -1,
			showDeleteModal: false,
			isDeleting:false,
			deleteItem:{},
			// deletingIndex:-1,
			viewInfoModal:false,
			token: '',
			imageModal:false,
			res:'',
			spinShow: true,
			isIconImageNew:false,
			isEditingg:false,
			img:'',
			isOnEditing:false,
			total: 5,
			pageInfo:null
		 }
	 },
	 
	 methods : {
		async addCategory(){
          if(this.data.categoryName.trim()=='') return this.error('Category name is required')
		  if(this.data.iconImage=='') return this.error('Icon image is required')
		  this.isAdding=true
		  this.data.iconImage = `/uploads/${this.data.iconImage}`
		  const res = await this.callApi('post','/app/create_category',this.data)
	      if(res.status===201){
		      this.categories.unshift(res.data)
			 
              this.success('Category has been added successfully!')
			  this.imageModal = false
			  this.data.categoryName = ''
			  this.data.iconImage = ''
			  this.$refs.uploads.clearFiles()
			  this.isAdding=false
		  }else{
	        if(res.status===422){
				if(res.data.errors.categoryName){
					
					this.error(res.data.errors.categoryName[0])
					
				}
				
                 console.log(res.data.errors.categoryName)
				 console.log(res.data.errors.iconImage)

			}else{
				console.log(this.data.iconImage)
				this.swr()
			}
			  
		  }
		 },

        async editCategory(){
		  
          if(this.editData.categoryName.trim()=='' || this.editData.categoryName == '') return this.error('Tag name is required')
		  if(this.editData.iconImage=='') return this.error('Image icon is required')
		  this.editData.iconImage = this.editData.iconImage
		  const res = await this.callApi('put','/app/update_category',this.editData)
	      if(res.status===200){
			  
		      this.categories[this.index].categoryName = this.editData.categoryName
			  this.categories[this.index].iconImage= this.editData.iconImage
              this.success('Category has been edited successfully!')
			  this.editModal = false
			  this.isIconImageNew=false
			  //this.data.tagName = ''
		  }else{
	        if(res.status==422){
				if(res.data.errors.categoryName){
					console.log("First error")
					this.error(res.editData.errorsName[0])
				}
				console.log("Second error")
                console.log(res.data.errors.categoryName)
			}else{
				
				this.swr()
			}
			  
		  }
		 },
		showEditModal(category,index){
			this.isOnEditing=true
			let obj = {
				id:category.id,
				categoryName:category.categoryName,
			    iconImage:category.iconImage
			}
			
		    this.editData = obj
			this.editModal=true
			this.index = index
			this.isEditingg=false
	     },
		
		showDeletingmodal(category,i){
		const deleteModalObj = {
            showDeleteModal: true,
            deleteUrl: 'app/delete_category',
            data: category,
            deletingIndex: i,
            isDeleted: false,
			msg: 'Are you sure delete this category?',
			successmsg:'Category has been deleted successfully!!!'
        }
		this.$store.commit('setDeletingModalObj',deleteModalObj)
			// this.deleteItem = category
			// this.deletingIndex=i
			// this.showDeleteModal = true
            // if(!confirm('')) return
            // //tag.isDeleting = true
			// this.$set(tag,'isDeleting',true)
		},
		viewInfo(category){
			this.viewInfoModal =true
			let obj = {
				id:category.id,
				categoryName:category.categoryName,
				iconImage:category.iconImage,
				created_at:category.created_at
			}
		    this.viewData = obj

		},
		handleSuccess (res, file) {  
			if(this.isOnEditing){
				this.editData.iconImage = `/uploads/${res}`
				console.log(this.editData.iconImage)
			}else{
				
				 console.log(this.data.iconImage)
			     console.log("Here")
			     this.data.iconImage = res}   
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
		AddModal(isAdd=true){
			this.imageModal=true
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
		   
		   this.data.categoryName=''
		   this.imageModal=false
		   
		  
		   }
		},
		async deleteImage(isAdd=true){
			if(!isAdd){ // for editing.....
			    // this.editData.iconImage=''
			    // this.$refs.clearFiles()
				
                this.isIconImageNew=true
				this.img = this.editData.iconImage
			    this.editData.iconImage=''
			    this.$refs.editDataUploads.clearFiles()
				this.isAdd=false
			}else{
			this.$refs.uploads.clearFiles() 
			this.img = this.data.iconImage
			this.data.iconImage=''
			
			}
			const res = await this.callApi('post','app/delete_image',{imageName:this.img})
			// this.isIconImageNew=true
			if (res.status!=200){
               this.data.iconImage = this.img
			   this.swr()
			}
		},
		async getCategoryData(page = 1){
		
			this.token=window.Laravel.csrfToken
			const res = await this.callApi('get',`/app/get_category?page=${page}&total=${this.total}`);
		    console.log(res.data)
			if(res.status===200){
				let techStack = localStorage.getItem("storedData");
                console.log(techStack);
				this.categories = res.data.data
		        this.pageInfo = res.data
				this.spinShow= false
			}else{
				this.swr()
			}
		},
	 },
	 
	 async created(){
	        this.getCategoryData()
		},
	components:{
        deleteModal,
	 },
    computed:{
       ...mapGetters(['getDeleteModalObj'])
	 },
	 watch : {
       getDeleteModalObj(obj){
	      console.log(obj)
		  console.log(obj.isDeleted)
		  if(obj.isDeleted){
			  
			  this.categories.splice(obj.deletingIndex,1)
			  console.log("Hey")
		  }
	   }
	 }
//    async created(){
// 	   const res = await this.callApi('post','/app/create_tag',{tagName:'testertag'});
	  
// 	   if(res.status==200){
// 		  // console.log(res)
// 	   }else{
// 		   console.log(res)
// 		   console.log('running')
// 	   }
//    }
}
</script>
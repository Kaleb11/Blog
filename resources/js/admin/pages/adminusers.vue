<template>
<div>
		<div class="content">
			<div class="container-fluid">
				<div class="demo-spin-article">
        
                <Spin size="large" fix v-if="spinShow"></Spin>
            </div>	
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				
					
                    
					
						<!-- <input class="input" type="text" v-model="search" placeholder="Search..." @input="resetPagination()" style="width: 250px;" >
									<div class="control">
									<div class="select">
									<select v-model="length" @change="resetPagination()">
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									</select></div></div> -->
						<!-- <table class="_table"> -->
								<!-- TABLE TITLE -->
								
							<!-- <tr>
								<th>ID</th>
								<th>Name</th>
                                <th>Email</th>
								<th>Photo</th>
                                <th>User type</th>
								<th>Created at</th>
								<th>Action</th>
							</tr> -->
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<!-- <tr v-for="(user, i) in users" :key="i" v-if="users.length">
								<td>{{user.id}}</td>
								<td>{{user.fullName}}</td>
                                <td>{{user.email}}</td>
								<td class="table_image">
									<img :src="user.photo"/> </td>
								
                                 <td v-for="(rle,i) in roles" :key="i" v-if="user.role_id == rle.id">{{rle.roleName}}</td>
								<td>{{new Date(user.created_at).toLocaleDateString("en-US")}}</td>
								<td>
									<Button v-if="isReadPermitted" type="info" @click="viewInfo(user)" size="small">View</Button>
									<Button v-if="isUpdatePermitted" size="small" @click="showEditModal(user,i)">Edit</Button>
									<Button v-if="isDeletePermitted" type="error" size="small" @click="showDeletingmodal(user,i)" :loading="user.isDeleting">Delete</Button>			
								</td>
							</tr> -->
								<!-- ITEMS -->

								<!-- ITEMS -->
							
								<!-- ITEMS -->


						<!-- </table> -->
						 <!-- <v-simple-table  
						item-key="name"
						class="elevation-1"
						:loading="loadvar"
						loading-text="Loading... Please wait">
								<template v-slot:default >
								<thead>
									<tr>
									<th class="text-left">
										Id
									</th>
									<th class="text-left">
										Name
									</th>
									<th class="text-left">
										Email
									</th>
									<th class="text-left">
										Photo
									</th>
									<th class="text-left">
										User type
									</th>
									<th class="text-left">
										Date
									</th>
                                     <th class="text-left">
										Action
									</th>
									</tr>
								</thead>
								<tbody>
									<tr
									v-for="(user, i) in users" :key="i" v-if="users.length"
									>
									        <td>{{user.id}}</td>
											<td>{{user.fullName}}</td>
											<td>{{user.email}}</td>
											<td class="table_image">
												<img :src="user.photo"/> </td>
											
											<td v-for="(rle,i) in roles" :key="i" v-if="user.role_id == rle.id">{{rle.roleName}}</td>
											<td>{{new Date(user.created_at).toLocaleDateString("en-US")}}</td>
											<td>
												<v-icon class="mx-1" v-if="isReadPermitted" type="info" @click="viewInfo(user)" size="large" color="green"> mdi-eye</v-icon>
												<v-icon class="mx-1" v-if="isUpdatePermitted" size="large" @click="showEditModal(user,i)">edit</v-icon>
												<v-icon class="mx-1" v-if="isDeletePermitted" type="error" size="large" @click="showDeletingmodal(user,i)" :loading="user.isDeleting" color="red">mdi-delete</v-icon>			
											</td>
									</tr>
								</tbody>
								</template>
                        </v-simple-table> -->
						    <!-- :items-per-page="parseInt(pageInfo.per_page)"
						    :options.sync="options"
                            :server-items-length="parseInt(pageInfo.total)"
							:page="pageInfo.current_page"
      						:pageCount="parseInt(pageInfo.per_page)"
							
							@update:pagination="updatePagination" -->
							<v-card>
								<!-- <p class="_title0">Users <Button v-if="isWritePermitted" @click="addModal=true"><Icon type="md-add" /> Add admin</Button></p> -->
							<v-list-item-content>
        
        <v-list-item-title class="text-h5 mb-1" style="margin-left:12px;">
         <b> Users</b> 
		 <!-- <Button v-if="isWritePermitted" @click="addModal=true"><v-icon > PersonAddAlt</v-icon></Button></v-icon> -->
		 <v-icon class="mx-1" v-if="isWritePermitted"  @click="addModal=true" font-size="medium" color="green">person_add</v-icon> 
        </v-list-item-title></v-list-item-content>
    <v-col
          cols="12"
          sm="6"
          md="3"
        >
          <v-text-field
		    type="text"
		    v-model="str"
			@input = "searchUserData"
			v-on:keyup.enter="searchUserData"
            label = "Search"
            outlined			
            
          ></v-text-field>
		
  
        </v-col>

        
   
						<v-data-table
						    
						    :loading="loadvar"
							loading-text="Loading... Please wait"
							class="elevation-3 user-table"
							:headers="headers"
							:items="users"
							
							:hide-default-footer="true"
							
							v-if="pageInfo"  
							
							item-key="name"
							style="width: 97%;margin-left: 16px;/* border-left: 23px; */"
							
							
							>

							
							
							<template v-slot:item="{ item,index }" >
								<tr >
								<td class="text-left"> {{ item.id }} </td>
								<td class="text-left"> {{ item.fullName }} </td>
								<td class="text-left"> {{ item.email }} </td>
								<td class="table_image text-left">
								<img :src="item.photo"/> </td>
								<td v-for="(rle,i) in roles" :key="i" v-if="item.role_id == rle.id">{{rle.roleName}}</td>
											<td class="text-left">{{new Date(item.created_at).toLocaleDateString("en-US")}}</td>
											<td class="text-left">
												<v-icon class="mx-1" v-if="isReadPermitted" type="info" @click="viewInfo(item)" size="large" color="green"> mdi-eye</v-icon>
												<v-icon class="mx-1" v-if="isUpdatePermitted" size="large" @click="showEditModal(item,index)">edit</v-icon>
												<v-icon class="mx-1" v-if="isDeletePermitted" type="error" size="large" @click="showDeletingmodal(item,index)" :loading="item.isDeleting" color="red">mdi-delete</v-icon>			
											</td>
								</tr>
							</template>
							
							</v-data-table>
						<div class="pagspace">
						   
							<Page 
							:total="parseInt(pageInfo.total)" 
							:current="pageInfo.current_page" 
							:page-size="parseInt(pageInfo.per_page)" 
							:on-next="pageInfo.next_page_url"
							:page-size-opts="[5,10, 20, 50, 100, 500]" 
							@on-change="getUserData" 
							v-if="pageInfo" 
							show-total 
							show-sizer 
							@on-page-size-change="changePageSize" 
							/>
							
						
						
					
				</div>	
</v-card>
						
			<Modal
			v-model="addModal"
			title="Add admin"
			:mask-closable="false"
			:closable="false">
            
			<div class="space">
            <Input type="text" v-model="data.fullName" placeholder="Full name" />
            </div>
			 <div class="space">
            <Input type="email" v-model="data.email" placeholder="Email" />
            </div>
             <div class="space">
            <Input type="password" v-model="data.password" placeholder="Password" />
            </div>
            <div class="space">
                <Select v-model="data.role_id"  placeholder="Select Admin Type">
                   <Option :value="r.id" v-for="(r, i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                </Select>
            </div>
			
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
              action="/app/upload/user/photo">
            <div style="padding: 20px 0">
               <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                <p>Click or drag files here to upload</p>
            </div>
            </Upload>
			<div class="demo-upload-list" v-if="data.photo">
            
                <img :src= "`${data.photo}`">
            <div class="demo-upload-list-cover">
                <Icon type="ios-trash-outline" @click="deleteImage()"></Icon>
            </div>
               
            </div>
			<!-- <div slot="footer">
				<Button type="default" @click="addModal=false">Close</Button>
				<Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding...': 'Add user'}}</Button>
			</div> -->
			<div slot="footer">
				<Button type="default" @click="onClosingImage()">Close</Button>
				<Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding...': 'Add Admin'}}</Button>
			</div>
		</Modal>	
        <!-- Add modal		 -->
        <!-- <Modal
			v-model="addModal"
			title="Add admin"
			:mask-closable="false"
			:closable="false">
            <div class="space">
            <Input type="text" v-model="data.fullName" placeholder="Full name" />
            </div>
			 <div class="space">
            <Input type="email" v-model="data.email" placeholder="Email" />
            </div>
             <div class="space">
            <Input type="password" v-model="data.password" placeholder="Password" />
            </div>
            <div class="space">
                <Select v-model="data.role_id"  placeholder="Select Admin Type">
                   <Option :value="r.id" v-for="(r, i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                </Select>
            </div>
            
			<div slot="footer">
				<Button type="default" @click="addModal=false">Close</Button>
				<Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding...': 'Add user'}}</Button>
			</div>
		</Modal> -->
		<!-- Edit modal -->
		 <Modal
			v-model="editModal"
			title="Edit admin"
			:mask-closable="false"
			:closable="false">
			<div class="space">
            <Input type="text" v-model="editData.fullName" />
            </div>
			 <div class="space">
            <Input type="email" v-model="editData.email" />
            </div>
            <div class="space">
            <Input type="password" v-model="editData.password" placeholder="password   (optional)" />
            </div>
            <div class="space">
               <Select v-model="editData.role_id"  placeholder="Select Admin Type">
                   <Option :value="r.id" v-for="(r, i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                </Select>
            </div>
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
              action="/app/upload/user/photo">
            <div style="padding: 20px 0" >
               <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                <p>Click or drag files here to upload</p>
            </div>
            </Upload>
			<div class="demo-upload-list" v-if="editData.photo">
               
                <img :src= "`${editData.photo}`">
            <div class="demo-upload-list-cover">
                <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
            </div>
               
            </div>
			<div slot="footer">
				<Button type="default" @click="editModal=false">Close</Button>
				<Button type="primary" @click="editAdmin" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Editing...': 'Edit admin'}}</Button>
			</div>
		</Modal>
              <!-- Delete alert modal -->
		<!-- <modal v-model="showDeleteModal" width="360">
        <p slot="header" style="color:#f60;text-align:center">
            <icon type="ios-information-circle"></icon>
            <span>Delete confirmation</span>
        </p>
        <div style="text-align:center">
            <p>Are you sure you want to delete tag?</p>
        </div>
        <div slot="footer">
            <i-button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteTag">Delete</i-button>
        </div>
        </modal> -->
		<deleteModal></deleteModal>
    <!-- Full screen modal -->
		<Modal v-model="viewInfoModal" fullscreen title="Tag Info">
               <p>Full Name: {{viewData.fullName}}</p>
               <p>Email: {{viewData.email}}</p>
               <p>Password: {{viewData.password}}</p>
               <p>User Type: {{viewData.userType}}</p>
			   <p>Created at: {{viewData.created_at}}</p>
               
			   <div slot="footer">
				<Button type="default" @click="viewInfoModal=false">Close</Button>
			</div>
        </Modal>
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
import { mapGetters } from 'vuex'
import deleteModal from '../components/deleteModal.vue'
export default{
props: ['user','permission'],
     data(){
		 return{
			
			 headers: [
					{
						text: 'id',
						align: 'start',
						filterable: false,
						value: 'id',
					},
					{ text: 'Name', value: 'fullName' },
					{ text: 'Email', value: 'email' },
					{ text: 'Photo', value:'photo' },
					{ text: 'User type', value: 'fullName' },
					{ text: 'Date', value: 'created_at' },
					{ text: 'Action', value: 'fullName' },
        ],
			data : {
				fullName:'',
                email:'',
                password:'',
                role_id:1,
				photo:''
			},
			editModal: false,
			addModal : false,
			isAdding : false,
			users : [],
			roles : [],
			editData: {
				fullName: '',
				photo:''
			},
			viewData:{},
			index : -1,
			showDeleteModal: false,
			isDeleting:false,
			deleteItem:{},
			deletingIndex:-1,
			viewInfoModal:false,
			spinShow: false,
			img:'',
			repeatedPhoto:'',
			isIconImageNew:false,
			token: '',
			imageModal:false,
			res:'',
			isEditing : false,
			isEditingg:false,
			isOnEditing:false,
			total:5,
			pageInfo:null,
			loadvar:true,
			str:''
		 }
	 },
	 methods : {
		 updatePagination (pagination) {
			console.log('update:pagination', pagination)
		},
		async addAdmin(){
          if(this.data.fullName.trim()=='' || this.data.fullName == '') return this.error('User name is required')
          if(this.data.email.trim()=='' || this.data.email== '') return this.error('Email is required')
          if(this.data.password.trim()=='' || this.data.password == '') return this.error('Password is required')
		  if(this.data.photo=='') return this.error('The user photo is required')
          if(!this.data.role_id) return this.error('Role is required')
		  this.isAdding=true
		
          
		    const res = await this.callApi('post','/app/create_user',this.data)
		    this.res = res
	      if(this.res.status===201){
              if(this.data.role_id != 4){
		      this.users.unshift(res.data)}
              this.success('Admin user has been added successfully!')
			  this.addModal = false
			  this.data.fullName = ''
              this.data.email = ''
              this.data.password = ''
              this.data.role_id = 1,
			  this.data.photo=''
		  }else{
			
			this.isAdding=false
	        if(this.res.status==422){
			
			   
		  
                console.log(this.res.data.errors)
                for(let i in this.res.data.errors){
                    console.log(this.res.data.errors[i])
                    this.error(this.res.data.errors[i])
                }
				// if(res.data.errors.tagName){
				// 	this.error(res.data.errors.tagName[0])
				// }
                //  console.log(res.data.errors.tagName)
			}else{
				
				this.swr()
			}
			  
		  }
		 },
removeDuplicateCharacters(string) {
  return string
    .split('')
    .filter(function(item, pos, self) {
      return self.indexOf(item) == pos;
    })
    .join('');
},
        async editAdmin(){
          if(this.editData.fullName.trim()=='' || this.editData.fullName == '') return this.error('Full name is required')
          if(this.editData.email.trim()=='' || this.editData.email == '') return this.error('Email is required')
          if(!this.editData.role_id) return this.error('Role is required')
		  if(this.editData.photo=='') return this.error('User Photo is required')
		//   this.editData.photo = this.editData.photo
		  const res = await this.callApi('put','/app/update_user',this.editData)
	      if(res.status===200){
		      this.users[this.index].fullName = this.editData.fullName
              this.users[this.index].email = this.editData.email
			  this.users[this.index].photo = this.editData.photo
              this.users[this.index].role_id = this.editData.role_id
              this.success('User has been edited successfully!')
			  this.editModal = false
			  this.isIconImageNew=false
			  if(this.$store.state.user.id == this.editData.id){
				  location.reload()
			  }
			  //this.data.tagName = ''
		  }else{
	        if(res.status==422){
                for(let i in res.data.errors){
                    console.log(res.data.errors[i])
                    this.error(res.data.errors[i])
                }
				// if(res.data.errors.tagName){
				// 	this.error(res.data.errors.tagName[0])
				// }
                //  console.log(res.data.errors.tagName)
			}else{
				
				this.swr()
			}
			  
		  }
		 },
		showEditModal(user,index){
			this.isOnEditing=true
			let obj = {
				id:user.id,
				fullName:user.fullName,
                email:user.email,
				photo:user.photo,
                password:user.password,
                role_id:user.role_id,
			}
		    this.editData = obj
			this.editModal=true
			this.index = index
			this.isEditingg=false
	     },
		// async deleteTag(){
        //     this.isDeleting =true
		// 	const res = await this.callApi('delete','app/delete_tag',this.deleteItem)
		// 	if(res.status==200){
		// 	    this.tags.splice(this.deletingIndex,1)
		// 		this.success('Tag has been deleted successfully!!!')
		// 	}else{
		// 		this.swr()
		// 	}
		// 	this.isDeleting = false
		// 	this.showDeleteModal = false
		// },
		showDeletingmodal(user,i){
			const deleteModalObj = {
            showDeleteModal: true,
            deleteUrl: 'app/delete_user',
            data: user,
            deletingIndex: i,
            isDeleted: false,
			msg: 'Are you sure delete this user?',
			successmsg:'User has been deleted successfully!!!'
        }
		this.$store.commit('setDeletingModalObj',deleteModalObj)
		console.log('User is on deleting')
			// this.deleteItem = tag
			// this.deletingIndex=i
			// this.showDeleteModal = true
            // if(!confirm('')) return
            // //tag.isDeleting = true
			// this.$set(tag,'isDeleting',true)
		},
		viewInfo(user){
			this.viewInfoModal =true
			let obj = {
				id:user.id,
				fullName:user.fullName,
                email:user.email,
                password:user.password,
                userType:user.userType,
				created_at:user.created_at
			}
		    this.viewData = obj


		},
		async deleteImage(isAdd=true){
			if(!isAdd){ // for editing.....
			    // this.editData.iconImage=''
			    // this.$refs.clearFiles()
				
                this.isIconImageNew=true
				this.img = this.editData.photo
			    this.editData.photo=''
			    this.$refs.editDataUploads.clearFiles()
				this.isAdd=false
			}else{
			this.$refs.uploads.clearFiles() 
			this.img = this.data.photo
			this.data.photo=''
			
			}
			const res = await this.callApi('post','app/delete_image',{photo:this.img})
			// this.isIconImageNew=true
			if (res.status!=200){
               this.data.photo = this.img
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
		handleSuccess (res, file) {
			if(this.isOnEditing){
				this.editData.photo = `/uploads/User photos/${res}`
				console.log(this.editData.photo)
			}else{
				
				 console.log(this.data.photo)
			     console.log("Here")
				 this.$store.commit('setPhoto',res)
				 console.log("Success photo", this.$store.state.photo)
				 
			     this.data.photo = `/uploads/User photos/${res}`
                 console.log(this.data.photo)
				//  if(this.data.photo != res){
				// 	 this.data.photo = res
				//  }
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
		async getUserData(page = 1){
				this.token=window.Laravel.csrfToken
				console.log("User permissions",this.permission)
					const [res,resRole]= await Promise.all([
					this.callApi('get',`/app/get_users?page=${page}&total=${this.total}`),
					this.callApi('get','/app/get_roles')
				])
					
					console.log(res.data)
					if(res.status===200){
						this.users = res.data.data
						this.pageInfo = res.data
						this.spinShow= false
						this.loadvar=false
					}else{
						this.swr()
					}
					if(resRole.status===200){
						this.roles = resRole.data.data
						console.log("This is the role",this.roles)
						
					}else{
						this.swr()
					}
			},
			async searchUserData(){
					const res = await this.callApi('get',`/search_users?str=${this.str}`);
					console.log(res.data)
					if(res.status===200){
						this.users = res.data.data;
						this.pageInfo = res.data;
						console.log("Hehe", this.roles)
								
							}else{
								this.swr()
							}
					},
					changePageSize(page){
						//s page size 
						this.pageInfo.index = 1;
						this.total = page;
						this.getUserData();
					},
	 },
	 
	 async created(){
		this.getUserData()
        this.searchUserData()
		},
	components:{
        deleteModal,
	 },
	 computed:{
	...mapGetters([
      'getDeleteModalObj',
      'getPhoto',
      // ...
    ])
    //    ...mapGetters(['getDeleteModalObj'])
	 },
	 watch : {
	  async searchUser(){
		   this.searchUserData()
	   },
       getDeleteModalObj(obj){
	      console.log(obj)
		  console.log(obj.isDeleted)
		  if(obj.isDeleted){
			  this.users.splice(obj.deletingIndex,1)
		  }
	   }
	 },
	// created(){
      
	//    console.log("Store photo", this.$store.state.photo)
	// }
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
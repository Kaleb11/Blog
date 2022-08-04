<template>
<div>
		<div class="content">
			<div class="container-fluid">
				<div class="demo-spin-article">
        
                <Spin size="large" fix v-if="spinShow"></Spin>
    </div>	
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management <Button v-if="isWritePermitted" @click="addModal=true" style="
    margin-left: 729px;
"><Icon type="md-add" />Add a new role</Button></p>
                    
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Role name</th>
								<th>Is Admin</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(role, i) in roles" :key="i" v-if="roles.length">
								<td>{{role.id}}</td>
								<td class="_table_name">{{role.roleName}}</td>
								<td><Checkbox v-model="role.isAdmin" onclick="return false;" /></td>
								<td>{{new Date(role.created_at).toLocaleDateString("en-US")}}</td>
								<td>
									<Button v-if="isReadPermitted" type="info" @click="viewInfo(role)" size="small">View</Button>
									<Button v-if="isUpdatePermitted" size="small" @click="showEditModal(role,i)">Edit</Button>
									<Button v-if="isDeletePermitted" type="error" size="small" @click="showDeletingmodal(role,i)" :loading="role.isDeleting">Delete</Button>			
								</td>
							</tr>
								<!-- ITEMS -->

								<!-- ITEMS -->
							
								<!-- ITEMS -->


						</table>
						<div class="pagspace">
						   
							<Page :total="pageInfo.total" :current="pageInfo.current_page" :page-size="parseInt(pageInfo.per_page)" :on-next="pageInfo.next_page_url" @on-change="getRoleData" v-if="pageInfo" show-total />
							
						
						</div>
					</div>
				</div>
        <!-- Add modal		 -->
        <Modal
			v-model="addModal"
			title="Add role"
			:mask-closable="false"
			:closable="false">
			<Input v-model="data.roleName" placeholder="Add role name" />
			<Checkbox v-model="data.isAdmin">Is Admin?</Checkbox>
			<div slot="footer">
				<Button type="default" @click="addModal=false">Close</Button>
				<Button type="primary" @click="add" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding...': 'Add role'}}</Button>
			</div>
		</Modal>
		<!-- Edit modal -->
		 <Modal
			v-model="editModal"
			title="Edit role"
			:mask-closable="false"
			:closable="false">
			<Input v-model="editData.roleName" />
			<Checkbox v-model="editData.isAdmin">Is Admin?</Checkbox>
			<div slot="footer">
				<Button type="default" @click="editModal=false">Close</Button>
				<Button type="primary" @click="edit" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing...': 'Edit role'}}</Button>
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
		<Modal v-model="viewInfoModal" fullscreen title="Role Info">
               <p>Role Name: {{viewData.roleName}}</p>
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
     data(){
		 return{
			data : {
				roleName:'',
				isAdmin:false
			},
			editModal: false,
			addModal : false,
			isAdding : false,
			roles : [],
			permissions : [],
			editData: {
				roleName: ''
			},
			viewData:{},
			index : -1,
			showDeleteModal: false,
			isDeleting:false,
			deleteItem:{},
			deletingIndex:-1,
			viewInfoModal:false,
			spinShow: true,
			total:5,
			pageInfo:null
		 }
	 },
	 methods : {
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

        async edit(){
          if(this.editData.roleName.trim()=='' || this.editData.roleName == '') return this.error('Role name is required')
		  const res = await this.callApi('put','/app/update_role',this.editData)
	      if(res.status===200){
		      this.roles[this.index].roleName = this.editData.roleName
			  this.roles[this.index].isAdmin = this.editData.isAdmin
              this.success('Role has been edited successfully!')
			  this.editModal = false
			  //this.data.roleName = ''
		  }else{
	        if(res.status==422){
				if(res.data.errors.roleName){
					this.error(res.data.errors.roleName[0])
				}
                 console.log(res.data.errors.roleName)
			}else{
				
				this.swr()
			}
			  
		  }
		 },
		showEditModal(role,index){
			let obj = {
				id:role.id,
				roleName:role.roleName,
				isAdmin:role.isAdmin
			}
		    this.editData = obj
			this.editModal=true
			this.index = index
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
		showDeletingmodal(role,i){
			const deleteModalObj = {
            showDeleteModal: true,
            deleteUrl: 'app/delete_role',
            data: role,
            deletingIndex: i,
            isDeleted: false,
			msg: 'Are you sure delete this role?',
			successmsg:'Role has been deleted successfully!!!'
        }
		this.$store.commit('setDeletingModalObj',deleteModalObj)
		console.log('Role is on deleting')
			// this.deleteItem = tag
			// this.deletingIndex=i
			// this.showDeleteModal = true
            // if(!confirm('')) return
            // //tag.isDeleting = true
			// this.$set(tag,'isDeleting',true)
		},
		viewInfo(role){
			this.viewInfoModal =true
			let obj = {
				id:role.id,
				roleName:role.roleName,
				created_at:role.created_at
			}
		    this.viewData = obj


		},
		async getRoleData(page = 1){
			this.permissions = this.$store.state.permissions
			const res = await this.callApi('get',`/app/get_roles?page=${page}&total=${this.total}`);
		    console.log(res.data)
			if(res.status===200){
				this.roles = res.data.data
				this.spinShow= false
				this.pageInfo=res.data
			}else{
				this.swr()
			}
		},
	 },
	 
	 async created(){
		  this.getRoleData()
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
			  this.roles.splice(obj.deletingIndex,1)
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
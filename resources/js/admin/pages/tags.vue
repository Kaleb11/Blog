<template>
<div>
		<div class="content">
			<div class="container-fluid">
				<div class="demo-spin-article">
        
                <Spin size="large" fix v-if="spinShow"></Spin>
    </div>
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button @click="addModal=true" v-if="isWritePermitted"><Icon type="md-add" /> Add tag</Button></p>
                    
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag name</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(tag, i) in tags" :key="i" v-if="tags.length">
								<td>{{tag.id}}</td>
								<td class="_table_name">{{tag.tagName}}</td>
								<td>{{new Date(tag.created_at).toLocaleDateString("en-US")}}</td>
								<td>
									<Button type="info" v-if="isReadPermitted" @click="viewInfo(tag)" size="small">View</Button>
									<Button size="small" v-if="isUpdatePermitted" @click="showEditModal(tag,i)">Edit</Button>
									<Button type="error" v-if="isDeletePermitted" size="small" @click="showDeletingmodal(tag,i)" :loading="tag.isDeleting">Delete</Button>			
								</td>
							</tr>
								<!-- ITEMS -->

								<!-- ITEMS -->
							
								<!-- ITEMS -->


						</table>
						<div class="pagspace">
						   
							<Page :total="pageInfo.total" :current="pageInfo.current_page" :page-size="parseInt(pageInfo.per_page)" :on-next="pageInfo.next_page_url" @on-change="getTagData" v-if="pageInfo" show-total />
							
						
						</div>
					</div>
				</div>
        <!-- Add modal		 -->
        <Modal
			v-model="addModal"
			title="Add tag"
			:mask-closable="false"
			:closable="false">
			<Input v-model="data.tagName" placeholder="Add tag name" />
			<div slot="footer">
				<Button type="default" @click="addModal=false">Close</Button>
				<Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding...': 'Add tag'}}</Button>
			</div>
		</Modal>
		<!-- Edit modal -->
		 <Modal
			v-model="editModal"
			title="Edit tag"
			:mask-closable="false"
			:closable="false">
			<Input v-model="editData.tagName" />
			<div slot="footer">
				<Button type="default" @click="editModal=false">Close</Button>
				<Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing...': 'Edit tag'}}</Button>
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
               <p>Tag Name: {{viewData.tagName}}</p>
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
				tagName:''
			},
			editModal: false,
			addModal : false,
			isAdding : false,
			tags : [],
			editData: {
				tagName: ''
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
		async addTag(){
          if(this.data.tagName.trim()=='' || this.data.tagName == '') return this.error('Tag name is required')
		  const res = await this.callApi('post','/app/create_tag',this.data)
	      if(res.status===201){
		      this.tags.unshift(res.data)
              this.success('Tag has been added successfully!')
			  this.addModal = false
			  this.data.tagName = ''
		  }else{
	        if(res.status==422){
				if(res.data.errors.tagName){
					this.error(res.data.errors.tagName[0])
				}
                 console.log(res.data.errors.tagName)
			}else{
				
				this.swr()
			}
			  
		  }
		 },

        async editTag(){
          if(this.editData.tagName.trim()=='' || this.editData.tagName == '') return this.error('Tag name is required')
		  const res = await this.callApi('put','/app/update_tag',this.editData)
	      if(res.status===200){
		      this.tags[this.index].tagName = this.editData.tagName
              this.success('Tag has been edited successfully!')
			  this.editModal = false
			  //this.data.tagName = ''
		  }else{
	        if(res.status==422){
				if(res.data.errors.tagName){
					this.error(res.data.errors.tagName[0])
				}
                 console.log(res.data.errors.tagName)
			}else{
				
				this.swr()
			}
			  
		  }
		 },
		showEditModal(tag,index){
			let obj = {
				id:tag.id,
				tagName:tag.tagName
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
		showDeletingmodal(tag,i){
			const deleteModalObj = {
            showDeleteModal: true,
            deleteUrl: 'app/delete_tag',
            data: tag,
            deletingIndex: i,
            isDeleted: false,
			msg: 'Are you sure delete this tag?',
			successmsg:'Tag has been deleted successfully!!!'
        }
		this.$store.commit('setDeletingModalObj',deleteModalObj)
		console.log('Tag is on deleting')
			// this.deleteItem = tag
			// this.deletingIndex=i
			// this.showDeleteModal = true
            // if(!confirm('')) return
            // //tag.isDeleting = true
			// this.$set(tag,'isDeleting',true)
		},
		viewInfo(tag){
			this.viewInfoModal =true
			let obj = {
				id:tag.id,
				tagName:tag.tagName,
				created_at:tag.created_at
			}
		    this.viewData = obj


		},
		async getTagData(page = 1){
			
		    const res = await this.callApi('get',`/app/get_tags?page=${page}&total=${this.total}`);
		    console.log(res.data)
			if(res.status===200){
				this.tags = res.data.data
				this.pageInfo = res.data
				this.spinShow= false
			}else{
				this.swr()
			}
		},
		
	 },
	 
	 async created(){
			this.getTagData()
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
			  this.tags.splice(obj.deletingIndex,1)
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
<template>
<div>
		<div class="content">
			<div class="container-fluid">
				<div class="demo-spin-article">
        
                <Spin size="large" fix v-if="spinShow"></Spin>
    </div>
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Blogs <Button @click="$router.push('/createBlog')" v-if="isWritePermitted"><Icon type="md-add" />Create blog</Button></p>
                    
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Title</th>
								<th>Categories</th>
								<th>Tags</th>
								<th>Views</th>
								<!-- <th>Created at</th> -->
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
								<td>{{blog.id}}</td>
								<td style="width:13%">{{blog.title}}</td>
								<td style="width:18%"><span v-for="(c,j) in blog.cat" v-if="blog.cat.length"><Tag type="border">{{c.categoryName}}</Tag></span></td>
								<td style="width:27%"><span v-for="(t,j) in blog.tag" v-if="blog.tag.length"><Tag type="border">{{t.tagName}}</Tag></span></td>
								<td>{{blog.views}}</td>
								<!-- <td>{{new Date(blog.created_at).toLocaleDateString("en-US")}}</td> -->

								<td style="width:20%">
									<Button type="info" v-if="isReadPermitted" @click="viewInfo(blog)" size="small">View</Button>
									<Button size="small" v-if="isUpdatePermitted" @click="$router.push(`/editblog/${blog.id}`)">Edit</Button>
									<Button type="error" v-if="isDeletePermitted" size="small" @click="showDeletingmodal(blog,i)" :loading="blog.isDeleting">Delete</Button>			
								</td>
							</tr>
								<!-- ITEMS -->

								<!-- ITEMS -->
							
								<!-- ITEMS -->
                        
                        
						</table>
						<div class="pagspace">
						   
							<Page :total="pageInfo.total" :current="pageInfo.current_page" :page-size="parseInt(pageInfo.per_page)" :on-next="pageInfo.next_page_url" @on-change="getBlogData" v-if="pageInfo" show-total />
							
						
						</div>
					</div>
				</div>
		<deleteModal></deleteModal>
    <!-- Full screen modal -->
		<Modal v-model="viewInfoModal" fullscreen title="Tag Info">
               <p>Tag Name: {{viewData.tagName}}</p>
			   <p>Created at: {{viewData.created_at}}</p>
			   <div slot="footer">
				<Button type="default" @click="viewInfoModal=false">Close</Button>
			</div>
        </Modal>
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
			isAdding : false,
			tags:[],
			blogs : [],
			viewData:{},
			index : -1,
			showDeleteModal: false,
			isDeleting:false,
			deleteItem:{},
			deletingIndex:-1,
			viewInfoModal:false,
			spinShow: true,
			total: 8,
			pageInfo:null
		 }
	 },
	 methods : {
		showDeletingmodal(blog,i){
			console.log('The index is',i)
			const deleteModalObj = {
            showDeleteModal: true,
            deleteUrl: 'app/delete_blog',
            data: blog,
            deletingIndex: i,
            isDeleted: false,
			msg: 'Are you sure delete this blog?',
			successmsg:'Blog has been deleted successfully!!!'
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
	async getBlogData(page = 1){
       const res = await this.callApi('get',`/app/blogdata?page=${page}&total=${this.total}`);
	  
	   if(res.status==200){
		  console.log(res.data)
		  this.blogs = res.data.data
		  this.pageInfo = res.data
		  this.spinShow = false
	   }else{
		   console.log(res)
		   console.log('running')
	   }
		},
		// async getTags(){
        //     const res = await this.callApi('get','/app/get_tags');
		//     console.log(res.data)
		// 	if(res.status===200){
		// 		this.tags = res.data
		// 		this.spinShow= false
		// 	}else{
		// 		this.swr()
		// 	}
		// }
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
			  this.blogs.splice(obj.deletingIndex,1)
		  }
	   }
	 },
   async created(){
	  this.getBlogData()
   }
}
</script>
<template>
<div>
		<div class="content">

			<div class="container-fluid">
				<div class="demo-spin-article">
        
                <Spin size="large" fix v-if="spinShow"></Spin>
            </div>
				
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management
						<Select v-model="data.id"  @on-change="changeAdmin" placeholder="Select Admin Type" style="width:300px;margin-left: 570px;">
                   <Option :value="r.id" v-for="(r, i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
                </Select>
			</p>
                    
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>Resource name</th>
								<th>Read</th>
								<th>Write</th>
								<th>Update</th>
								<th>Delete</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(r,i) in resources" :key="i">
								<td>{{r.resourceName}}</td>
								<td><Checkbox v-model="r.read"></Checkbox></td>
								<td><Checkbox v-model="r.write"></Checkbox></td>
								<td><Checkbox v-model="r.update"></Checkbox></td>
								<td><Checkbox v-model="r.delete"></Checkbox></td>
							</tr>
								<!-- ITEMS -->
                            <div class="center_button">
                                 <Button v-if="isWritePermitted" type="primary" :loading="isSending" :disabled="isSending" @click="assignRoles">Assign</Button>
							</div>
								<!-- ITEMS -->
							
								<!-- ITEMS -->


						</table>
					</div>
				</div>
       
		
			</div>
		</div>
	</div>
</template>

<script>
export default{
	 props: ['user','permission'],
     data(){
		 return{
			data : {
				roleName:'',
				id:null
			},
			isSending : false,
			roles: [],
			permissons: [],
			//readApi: 'app/get_tags',writeApi: 'app/create_tag', updateApi: 'app/update_tag', deleteApi: 'app/delete_tag'
			resources : [
				{resourceName: 'Dashboard', read: false, write: false, update: false, delete: false, name:'dashboard'},
				{resourceName: 'Tags', read: false, write: false, update: false, delete: false, name:'tags'},
				{resourceName: 'Category', read: false, write: false, update: false, delete: false, name:'category'},
				{resourceName: 'Blogs', read: false, write: false, update: false, delete: false, name:'blogs'},
				{resourceName: 'Create blogs', read: false, write: false, update: false, delete: false, name:'createBlog'},
				{resourceName: 'Adminusers', read: false, write: false, update: false, delete: false, name:'adminusers'},
				{resourceName: 'Role', read: false, write: false, update: false, delete: false, name:'role'},
				{resourceName: 'Assign Role', read: false, write: false, update: false, delete: false, name:'assign_role'},
			],
			defaultResourcesPermission : [
				{resourceName: 'Dashboard', read: false, write: false, update: false, delete: false, name:'dashboard'},
				{resourceName: 'Tags', read: false, write: false, update: false, delete: false, name:'tags'},
				{resourceName: 'Category', read: false, write: false, update: false, delete: false, name:'category'},
				{resourceName: 'Blogs', read: false, write: false, update: false, delete: false, name:'blogs'},
				{resourceName: 'Create blogs', read: false, write: false, update: false, delete: false, name:'createBlog'},
				{resourceName: 'Adminusers', read: false, write: false, update: false, delete: false, name:'adminusers'},
				{resourceName: 'Role', read: false, write: false, update: false, delete: false, name:'role'},
				{resourceName: 'Assign Role', read: false, write: false, update: false, delete: false, name:'assign_role'},
			],
			spinShow: true
		 }
	 },
	 methods : {
        async assignRoles(){
			let data = JSON.stringify(this.resources)
			const res = await this.callApi('post','app/assign_roles', {'permission':data, id: this.data.id})
            if(res.status==200){
				this.success('Role has been assigned successfully!')
				let index = this.roles.findIndex(role => role.id == this.data.id)
				this.roles[index].permission = data
				let user = JSON.parse(localStorage.getItem('storedData'));
			    if(user.role_id==this.data.id){
				   location.reload();
				}
			
			}else{
				this.swr()
			}
		},
		changeAdmin(){
			console.log("The id is",this.data.id)
			let index = this.roles.findIndex(role => role.id == this.data.id)
            let permission = this.roles[index].permission
			console.log(index)
			if(!permission){
				this.resources = this.defaultResourcesPermission
			}else{
                this.resources = JSON.parse(permission)
			}
			
					//this.resources = JSON.parse(res.data.id[id].permission)
				

		},
		checkPermm(){
			var pathArray = window.location.pathname.split('/');
			console.log("The path",pathArray[1])
			// if(pathArray[1]==)
		}
		
	 },
	 
	 async created(){
		    let user = JSON.parse(localStorage.getItem('storedData'));
			console.log("Store permission", this.$store.state.permission)
			console.log("The user",user.role_id)
		    console.log(this.$route)
			var pathArray = window.location.pathname.split('/');
			console.log("The path",pathArray[1])
			this.permissions = this.$store.state.permission
			const res = await this.callApi('get','/app/get_roles');
		    console.log(res.data)
			if(res.status===200){
				this.roles = res.data.data;
				console.log("Hehe", this.roles)
			  if(res.data.data.length){
				this.data.id = res.data.data[3].id
				console.log("Hi",this.data.id)
				if(res.data.data[3].permission){
					this.resources = JSON.parse(res.data.data[3].permission)
				}
			  }
			this.spinShow= false
			}else{
				this.swr()
			}
		},
	
}
</script>
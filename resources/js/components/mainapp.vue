<template>
 <v-app>
 
    <v-card class="overflow-hidden" v-if="$store.state.user" style="
    height: 55px;
            ">
    <!-- color="#459c98" -->
    <v-app-bar
      height="63px"
      color="#0099ff"
      dense
      dark
      fixed
    >
      <v-app-bar-nav-icon></v-app-bar-nav-icon>

      <v-toolbar-title>Administration Office</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-heart</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-menu
        left
        bottom
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            icon
            v-bind="attrs"
            v-on="on"
          >
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-list class="list-title">
           <!-- <v-list-item 
            v-for="n in 5"
            :key="n"
            @click="() => {}"
          >
            <v-list-item-title>Option {{ n }}</v-list-item-title>
          </v-list-item> -->
          <v-list-item >
            <v-list-item-title><v-icon class="mx-2">mdi-account</v-icon>Profile</v-list-item-title>
          </v-list-item>
          <v-list-item href="/logout" @click="logout">
            <v-list-item-title><v-icon class="mx-2">logout</v-icon>Logout</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
  
    <v-navigation-drawer
    app
    style="
    height:  1191%;
    "
   
      v-model="drawer"
      :mini-variant.sync="mini"
      permanent
    >
      <v-list-item class="px-2" style="height: 63px; background-color:aliceblue">
        <!-- <v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/men/85.jpg"></v-img>
        </v-list-item-avatar> -->

        <v-list-item-title class="mx-2" v-for="(rle,i) in roles" :key="i" v-if="user.role_id == rle.id"><b>Adminstration office</b></v-list-item-title>
        <v-btn
          icon
          @click.stop="mini = !mini"
        >
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
       
      </v-list-item>

      <v-divider></v-divider>

      <v-list dense>
        <v-list-item class="px-2">
        <v-list-item-avatar  style="margin-left: 76px;height: 80px;min-width: 80px;width: 80px;"  >
          <v-img :src="`${user.photo}`" data-holder-rendered="true"></v-img>
        </v-list-item-avatar>

        

        
      </v-list-item>
      <v-list-item-title style="text-align: center;" class="mx-2
        " v-for="(rle,i) in roles" :key="i" v-if="user.role_id == rle.id"><b>{{user.fullName}}({{rle.roleName}})</b></v-list-item-title>
        <div style="padding: 13px;"></div>
        <v-divider></v-divider>
        <div style="padding: 5px;"></div>
        <v-list-item two-line v-for="menuItem in permission" :key="menuItem.resourceName" v-if="permission.length && menuItem.read" link :to="'/'+menuItem.name">
              
               
                  <v-list-item-icon style="margin-left:-13px"><v-icon class="mx-1" v-for="iconItem in icons" :key="iconItem.iconName" v-if="iconItem.routerName == menuItem.name"  >{{iconItem.iconName}}</v-icon> </v-list-item-icon>

               
                  <v-list-item-content style="margin-top: -8%; margin-left: -21px;"><v-list-item-title>{{menuItem.resourceName}}</v-list-item-title>
          </v-list-item-content>
         
        </v-list-item>

          <v-list-item two-line href="/logout" @click="logout">
            
            <v-list-item-icon style="margin-left:-13px">
              
              <v-icon class="mx-1">logout</v-icon>
            </v-list-item-icon>
    
            <v-list-item-content style="margin-top: -8%; margin-left:-21px;"
            ><v-list-item-title>Logout</v-list-item-title>
             
          </v-list-item-content>    
           

          </v-list-item>
      </v-list>
     </v-navigation-drawer>
     <v-sheet
      id="scrolling-techniques-7"
      class="overflow-y-auto"
      max-height="600"
    >
      <v-container style="height: 1500px;">
      </v-container>
    </v-sheet>
     </v-card>
  
     
 
  
  
  
       <v-main>
      
        <router-view></router-view>
      
    </v-main>
</v-app>
    <!-- <div v-if="$store.state.user">
       -->
      <!--========== ADMIN SIDE MENU one ========-->
    
      <!--========== ADMIN SIDE MENU ========-->

      <!--========= HEADER ==========-->
      <!-- <div class="header">
        <div class="_2menu _box_shadow"> -->
          <!-- <div class="_2menu_logo"> -->
            <!-- <ul class="open_button">
              <li>
                <Icon type="ios-list" />
              </li>
              
            </ul> -->
            <!--<li><Icon type="ios-albums" /></li>-->
          <!-- </div> -->
        <!-- </div>
      </div> -->
      <!--========= HEADER ==========-->
    <!-- </div>
    	<router-view />
  </div> -->
 
</template>


<script>

export default{
  props: ['user','permission'],
  data(){
    return{
      isLoggedIn : false,
      roles:[],
      icons : [
				{iconName: 'dashboard', routerName:"dashboard"},
				{iconName: 'reorder', routerName:"category"},
				{iconName: 'tag', routerName:"tags"},
				{iconName: 'people', routerName:"adminusers"},
				{iconName: 'settings_applications', routerName:"role"},
				{iconName: 'admin_panel_settings', routerName:"assign_role"},
        {iconName: 'rate_review', routerName:"createBlog"},
        {iconName: 'label', routerName:"blogs"},
			],
      drawer: true,
        items: [
          { title: ' Home', icon: 'mdi-home-city' },
          { title: ' My Account', icon: 'mdi-account' },
          { title: ' Users', icon: 'mdi-account-group-outline' },
        ],
        mini: true,

    }
  },
  methods : {
     logout(){
       let item = localStorage.removeItem('storedData')
       console.log("Storage removed",item)
       
     },

  },
  async created(){
    this.$store.commit('setUpdateUser',this.user)
    console.log(this.user)
    console.log("User permissions",this.permission)
    this.$store.commit('setUserPermission',this.permission)
    if(this.user){
    const res = await this.callApi('get','/app/get_roles');
    if(res.status===200){
				this.roles = res.data.data
				console.log("This is the role",this.roles)
				//this.spinShow= false
			}else{
				this.swr()
			}
    }
   
  }
}
</script>
// https://silvawebdesigns.com/bootstrap-4-mobile-nav-bar-slide-from-left-right/
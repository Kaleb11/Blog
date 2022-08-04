<template>
    <div>
          <!-- Delete alert modal -->
		<modal 
        :value="deleteModal.showDeleteModal"
        :mask-closable="false" 
        :closable="false"
        width="360"> ,
        <p slot="header" style="color:#f60;text-align:center">
            <icon type="ios-information-circle"></icon>
            <span>Delete confirmation</span>
        </p>
        <div style="text-align:center">
            <p>{{deleteModal.msg}}</p>
        </div>
        <div slot="footer">
            <Button type="default" size="large" @click="close">Close</Button>
            <Button type="error" size="large" :loading="isDeleting" :disabled="isDeleting" @click="deleteTag">{{isDeleting ? 'Deleting...': 'Delete'}}</Button>
        </div>
        </modal>
    </div>
</template>
<script>
import {mapGetters} from 'vuex'
export default{
    data() {
        return{
             isDeleting: false,
        }
       
    },
    methods : {
      close(){
        // this.deleteModal.showDeleteModal = false
        this.$store.commit('setDeleteModal',false)
      },
      async deleteTag(){
            this.isDeleting=true
            const res = await this.callApi('delete', this.deleteModal.deleteUrl, this.deleteModal.data)
            if (res.status == 200) {
                // this.categories.splice(this.deletingIndex,1)
                this.success(this.deleteModal.successmsg)
                this.$store.commit('setDeleteModal',true)
                this.isDeleting=false
            } else {
                this.swr()
                this.$store.commit('setDeleteModal',false)
            }
            // this.isDeleting = false
            // getDeleteModalObj.showDeleteModal = false
           
           
		},
    },
    computed : {
       ...mapGetters({
           'deleteModal':'getDeleteModalObj'})
   },
}
</script>

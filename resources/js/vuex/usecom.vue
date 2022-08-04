<template>
<div>
<div class="content">
			<div class="container-fluid">
    
    <h1>User component</h1>
    <h2>Current state of counter is: {{counter}}</h2>
    <div>
        <comA></comA>
    </div>
    <div>
        <comB></comB>
    </div>
    <div>
        <comC></comC>
    </div>
    <Button type="info" @click="changeCounter">Change the state of the counter</Button>
            </div>
</div>
</div>
</template>

<script>
import comA from './comA.vue'
import comB from './comB.vue'
import comC from './comC.vue'
import {mapGetters,mapActions} from 'vuex'
export default { 
   data() {
       return {
           localVar :'Local var'
       }
   },
   methods : {
     ...mapActions([
         'changeCounterAction'
     ])
   },
   computed : {
       ...mapGetters({
           'counter':'getCounter'})
   },
   methods :{
       changeCounter(){
           this.$store.dispatch('changeCounterAction',1)
           //this.$store.commit('changeTheCounter',1)
       },
       runSomethingCounterChange(){
          console.log('Counter is changing on')
       }
   },
   components : {
       comA,
       comB,
       comC,
   },
   watch : {
     counter(value){
         console.log('Counter is changing',value)
         this.runSomethingCounterChange()
         console.log(this.localVar)
     }
   }
}
</script>

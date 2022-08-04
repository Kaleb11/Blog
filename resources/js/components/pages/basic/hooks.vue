<template>
<div>
    <h1 ref="myref">hooks</h1>
    <p>My name is {{name}} </p>
</div>
</template>
<script>
export default {
    data(){
        return{
            name: 'Kaleb'
        }
    },
    beforeCreate(){
      console.log(this.name)
    },
    created(){
        console.log("Hook", this.name)
    },
    mounted(){
      console.log(this.$refs.myref)
    }
}
</script>
<template>
<div>
    <h1>The number is : {{counter}}</h1>
    <Button @click = "updateCounter(counter)" >Increase</Button>
    <Button @click = "decreaseCounter()" >Decrease</Button>
    <br>
    <br><br>
    <div v-for="(blog,i) in blogs" :key="i">
        <h1>{{blog.title}}</h1>
        <p>{{blog.post}}</p>
    </div>
    <br>
    <br>
    <br>
    <div>
        <h1 v-if="showItem"> show this item if "showItem" is true</h1>
        <p v-else>show this item if "showItem" is false</p>
    </div>
    <button @click="showItem=!showItem">Change showItem</button>
</div>
</template>
<script>
export default {
    data(){
        return{
            counter: 0,
            actualCounter :0,
            blogs:[],
            showItem : false
        }
    },
    methods :{
         updateCounter(number){
             this.counter += number
        
        
    },
     decreaseCounter(){
             this.counter -= this.actualCounter
        if (this.counter == 0) {
             return this.counter = 0;
        }
        
    },
    changeShowItem(){
        this.showItem = !this.showItem
    }
    },
    created(){
        this.counter =100
        this.actualCounter=100
        let posts = [{title:"This is blog title 1",'post':"This is the blog post 1", id :1},
                     {title:"This is blog title 2",'post':"This is the blog post 2", id :2},
                     {title:"This is blog title 3",'post':"This is the blog post 3", id :3},
                     {title:"This is blog title 4",'post':"This is the blog post 4", id :4},
        ]
        this.blogs = posts
    }
}
</script>
<template>
    <!-- this is search results manage component -->
    <div class="card-body">
        <div v-if="!editing" class="card-text">
            <p @click.prevent="enableEditing">{{ value }}</p> 
        </div>
        <div v-if="editing" class="card-text">
            <input v-on:dblclick="disableEditing" type="text" v-model="tempValue" class="form-control mb-3" v-on:keyup.enter.prevent="saveEdit" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>
        <div class="row justify-content-around">
            <button v-on:click="addPhoto" type="button" class="btn btn-outline-success col-5 btn-sm">Save to MyMedia</button>
            <button v-if="!editing" v-on:click="enableEditing" type="button" class="btn btn-outline-info col-5 btn-sm">Edit description</button>
            <button v-if="editing" v-on:click="saveEdit" type="button" class="btn btn-outline-info col-5 btn-sm">Save description</button> 
        </div>
    </div>
    
</template>

<script>
export default {
    data() {
        return {
            value: this.description !== "" ? this.description:"Click to ",
            tempValue: null,
            editing: false,
            photoData:{}
        }
    },
    props:{
        description:{
            type:String
        },
        photoId:{
            type:String
        },
        title:{
            type:String
        },
        photoUrl:{
            type:String
        }
    },
    methods: {
        enableEditing: function(){
        this.tempValue = this.value;
        this.editing = true;
        },
        disableEditing: function(){
        this.tempValue = null;
        this.editing = false;
        },
        saveEdit: function(){
        this.value = this.tempValue;

        //call to save to DB
        this.disableEditing();
        },
        addPhoto: function(){
            if(this.editing === false){
                this.photoData = {
                    title: this.title,
                    //incase empty string
                    boby: this.value !== null? this.value: "Click to edit",
                    picture_id:this.photoId,
                    picture_url:this.photoUrl
                }
                //Add picture to DB
                fetch('api/article',{
                    method:'post',
                    body:JSON.stringify(this.photoData),
                    headers: {
                        'content-type':'application/json'
                    }
                })
                .then(res=>res.json())
                .then(data=>{
                    this.photoData.title ='';
                    this.photoData.boby = '';
                    this.photoData.picture_id = '';
                    this.photoData.picture_url = '';
                    alert('Picture Saved');
                })
            }
        }
    },
    directives: {
        'click-outside': {
        bind: function(el, binding, vNode) {
            // Provided expression must evaluate to a function.
            if (typeof binding.value !== 'function') {
                const compName = vNode.context.name
            let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`
            if (compName) { warn += `Found in component '${compName}'` }
            
            console.warn(warn)
            }
            // Define Handler and cache it on the element
            const bubble = binding.modifiers.bubble
            const handler = (e) => {
            if (bubble || (!el.contains(e.target) && el !== e.target)) {
                binding.value(e)
            }
            }
            el.__vueClickOutside__ = handler

            // add Event Listeners
            document.addEventListener('click', handler)
                },
        
        unbind: function(el, binding) {
            // Remove Event Listeners
            document.removeEventListener('click', el.__vueClickOutside__)
            el.__vueClickOutside__ = null

        }
        }
    }
}
</script>

<style scoped>
.textarea{
    width: 100%;
}
</style>
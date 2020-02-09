<template>
    <!-- this is media library edit component -->
    <div class="card-body">
        <div v-if="!editing" class="card-text">
            <p @click.prevent="enableEditing">{{ value }}</p> 
        </div>
        <div v-if="editing" class="card-text">
            <input v-if="!titleEdit" v-on:dblclick="disableEditing" type="text" v-model="tempValue" class="form-control mb-3" v-on:keyup.enter.prevent="saveEdit" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            <input v-if="titleEdit" v-on:dblclick="disableEditing" type="text" v-model="tempTitle" class="form-control mb-3" v-on:keyup.enter.prevent="saveEdit" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
        </div>
        
        <div class="row justify-content-around">
            <button type="button" class="btn btn-outline-success col-5 btn-sm">Edit title</button>
            <!-- <button v-if ="titleEditing" v-on:click="saveEdit" type="button" class="btn btn-outline-success col-5 btn-sm">Save title</button> -->
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
            allTitles:{},
            updateData:{},
            titleEdit:false
        }
    },
    props:{
        description:{
            type:String
        },
        picture_id:{
            type:String
        },
        title:{
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
            //check description whether update
            if(this.value !== this.tempValue){
                this.value = this.tempValue;
                this.disableEditing();
                
                //save to databse
                this.updateInfor();
            }else{
                this.disableEditing();
            }
        },
        updateInfor(){
            this.updateData = {
                    title: this.title,
                    boby:this.value,
                    picture_id:this.picture_id
                }
            console.log(this.updateData);
            fetch('api/updatePicture',{
                    method:'put',
                    body:JSON.stringify(this.updateData),
                    headers: {
                        'content-type':'application/json'
                    }
            })
            .then((data)=>{
                alert("successfully updated");
                location.reload();
                this.updateData.title = "";
                this.updateData.boby = "";
            })
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
<template>
    <div class="container">
        <!-- class tages display -->
        <div class="container mt-3">
            <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
                <!-- ALL buttons -->
                <div class="btn-group mr-2" role="group" aria-label="First group">
                    <button type="button" v-if="pageLoad" class="btn btn-outline-dark btn-lg" v-on:click="fetchAll">  &nbsp; All &nbsp; </button>
                </div>
                <!-- classes results from DB to render -->
                <div class="btn-group mr-2 " role="group" aria-label="Second group">
                    <button type="button" class="btn btn-outline-secondary btn-lg" v-for="title in titles" :key="title" v-on:click="fetchClassPhotos(title)">{{title}}</button>
                </div>
            </div>
        </div>

        <!-- class results display -->
        <div class="container mt-4">
            <div class="card-columns">
                <div class="card border-light" v-for="picture in pictures" :key="picture.picture_id">
                    <!-- picture hover css class -->
                    <div class="box14">
                        <!-- each results display -->
                        <expandable-image
                            class="image card-img-top"
                            :src="picture.picture_url"
                            alt="picture.boby"
                        />
                        <div class="box-content">
                            <h3 class="title">{{picture.title}}: {{picture.picture_id}}</h3>
                            <!-- <span class="post">Best img ever</span> -->
                            <ul class="icon">
                                <li><a @click.self= "deletePictureById(picture.picture_id)"><i class="far fa-trash-alt"></i></a></li>
                                <li><a @click.self = "downloadBig" :data-id="picture.picture_id"><i class="fas fa-download"></i></a></li>
                            </ul>
                        </div>
                </div>
                
                <!-- library picture infor component -->
                <mediaCardBody :description = "picture.boby" :picture_id = "picture.picture_id" :title = "picture.title"/>
            
            </div>
        </div>
    </div>
    
    </div>
</template>

<script>
import expandableImage from './commonuse/expandableImage';
import mediaCardBody from './mediaCardBody';


export default {
    data() {
        return {
            pageLoad:false,
            searchValue:'car',
            pictures:[],
            picture:{
                id:'',
                title:'',
                picLink:'',
                altInfo:'',
            },
            picture_id:'',
            pagination:{},
            total_pages:0,
            page:1,
            reutrnedTitles:{},
            titles:[],
            titleData:{},
            deleteData:{}
        }
    },
    created() {
            this.getAllTitles();
            this.fetchAll();
    },

    components: {
        expandableImage,mediaCardBody
    },

    methods: {
        fetchAll(){
            fetch('api/articles')
            .then((data)=>{
                return data.json()
            })
            .then((data)=>{
                this.pictures = data.data;
                this.pageLoad = true;
            })

        },
        fetchClassPhotos(photoClass){
            this.titleData = {
                    title: photoClass
            }
            fetch('api/titleByClass',{
                    method:'post',
                    body:JSON.stringify(this.titleData),
                    headers: {
                        'content-type':'application/json'
                    }
            })
            .then((data)=>{
                return data.json();
            })
            .then((data)=>{
                this.pictures = data.data;
                this.pageLoad = true;
                this.titleData.title = '';
            })
        },
        deletePictureById(deletePictureId){
           if(confirm("Are you sure you want to delete this picture from library?")){
            this.deleteData = {
                        picture_id: deletePictureId
                };
                fetch('api/delete',{
                        method:'delete',
                        body:JSON.stringify(this.deleteData),
                        headers: {
                            'content-type':'application/json'
                        }
                })
                .then(()=>{
                    this.pageLoad = true;
                    this.deleteData.picture_id = '';
                    location.reload();
                })
           }
        },
        downloadBig(e) {
            if(e){
                let temp = e.target.attributes; 
                let myphoto_id = temp["data-id"].value;
                let client_id = '8f2610cce8b7e60e887e6c6024cae7be704ffabd1aea6d7caa9dbefe4e70ab46';
                let url = `https://api.unsplash.com/photos/${myphoto_id}/download?client_id=${client_id}`;
                this.downloadWithVueResource(url,myphoto_id);
                // console.log(url);
            }else{
                alert('Not found!')
                return 
            }
        },
        forceFileDownload(url,myphoto_id){
            const link = document.createElement('a');
            link.href = url;
            link.style.display = "none";
            document.body.appendChild(link);
            link.setAttribute('target','_blank');
            link.setAttribute('download',  `${myphoto_id}.jpg`) //or any other extension

            link.click();

            window.URL.revokeObjectURL(link.href);
            document.body.removeChild(link);

            //to be fixed, child node can't be remove by click but after refresh
        },
        downloadWithVueResource(url,myphoto_id) {
            fetch(url)
                .then(data=>{
                    return data.json();
                })
                .then(response => {
                    this.forceFileDownload(response.url,myphoto_id)  
                })
                .catch(() => console.log('error occured'))
        
        },
        getAllTitles: function(){
            fetch('api/title')
            .then((data)=>{
                return data.json();
            })
            .then((data)=>{
                this.reutrnedTitles = data.data;
                let titlesTemp = [];
                for(let title in this.reutrnedTitles){
                    titlesTemp.push(this.reutrnedTitles[title].title);
                }
                this.titles = [...new Set(titlesTemp)];
            })
        }
    }
}
</script>

<style scoped>
@import '../../css/pictureHover.css';
</style>
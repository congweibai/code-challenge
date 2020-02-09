<template>
    <div class="container flag-max">
        <!-- search container -->
        <div class="container">
            <div v-if="noSearch" class="logo mt-3">
                <img src="https://vertogroup.com.au/wp-content/uploads/sites/2/2019/10/verto_group_document_logo_transparent-300x214.png" alt="">
            </div>
            <div v-bind:class="[noSearch ? nosearchClass : '', searchClass]">
            <input class="form-control form-control-lg" type="text" placeholder="Search Photos" v-model="searchValue" v-on:keyup.enter="fetchPictures">
            <span class="input-group-btn">
                    <button type="button" class="btn btn-default btn-lg" v-on:click = "fetchPictures" >
                        <i class="fas fa-search"></i>
                    </button>
                </span> 
            </div>

            <!-- reminder no results can be found -->
            <div v-if="noResults" class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Sorry,</strong> we couldn't find any results matching "{{tempSearchValue}}"
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    
    <!-- search results display -->
    <div class="container">
        <div class="card-columns">
            <div class="card border-light" v-for="picture in pictures" :key="picture.id">
                <!-- picture hover css class -->
                <div class="box14">
                    <!-- each picture display with zoom function -->
                    <expandable-image
                        class="image card-img-top"
                        :src="picture.urls.regular"
                        alt="picture.description"
                    />
                    <div class="box-content">
                        <h3 class="title">{{searchValue}}: {{picture.id}}</h3>
                        <ul class="icon">
                            <li><a ><i class="far fa-heart"></i></a></li>
                            <li><a @click.self = "downloadBig" :data-id="picture.id"><i class="fas fa-download"></i></a></li>
                        </ul>
                    </div>
                </div>

                <!-- each picture infor component -->
                <search-cardBody :description = "picture.description" :title = "searchValue" :photoId = "picture.id" :photoUrl = "picture.urls.regular"/>

            </div>
        </div>
        <!-- lazy loading pictutes observer componet -->
        <observer v-on:intersect="intersected" />
    </div>
    </div>
</template>


<script>
import observer from './commonuse/observer';
import expandableImage from './commonuse/expandableImage';
import searchCardBody from './searchCardBody';

export default {
    data() {
        return {
            searchValue:'',
            tempSearchValue:'',
            pictures:[],
            picture:{
                id:'',
                title:'',
                picLink:'',
                altInfo:'',
            },
            pagination:{},
            total_pages:0,
            page:1,
            noSearch: true,
            noResults:false,
            nosearchClass: "input-group mt-5 mb-4 vertical-center",
            searchClass:"input-group mt-5 mb-4"
        }
    },
    components: {
        observer,expandableImage,searchCardBody
    },


    methods: {
        fetchPictures() {
            let clientId = '8f2610cce8b7e60e887e6c6024cae7be704ffabd1aea6d7caa9dbefe4e70ab46';
            let searchValue = this.searchValue;
            this.page = 1;
            let url = `https://api.unsplash.com/search/photos?client_id=${clientId}&page=${this.page}&query=${searchValue}`;
            
            //make a request to upslah api
            fetch(url)
                .then((data)=>{
                    return data.json();
                })
                .then((data)=>{
                    let vm = this;
                    this.pictures = data.results;
                    let total_pages = data.total_pages;
                    if(total_pages === 0){
                        this.tempSearchValue = this.searchValue;
                        this.noResults=true;
                        return
                    }
                    this.noResults = false;
                    this.noSearch = false;
                })
        },
        downloadBig(e) {
            if(e){
                let temp = e.target.attributes; 
                let myphoto_id = temp["data-id"].value;
                let client_id = '8f2610cce8b7e60e887e6c6024cae7be704ffabd1aea6d7caa9dbefe4e70ab46';
                let url = `https://api.unsplash.com/photos/${myphoto_id}/download?client_id=${client_id}`;
                this.downloadWithVueResource(url,myphoto_id);
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
            link.setAttribute('download',  `${myphoto_id}.jpg`);

            //this function not allow to download directly but get new _blank
            //could be fixed in future
            link.click();

            window.URL.revokeObjectURL(link.href);
            document.body.removeChild(link);

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

        intersected() {
            let clientId = '8f2610cce8b7e60e887e6c6024cae7be704ffabd1aea6d7caa9dbefe4e70ab46';
            let searchValue = this.searchValue;

            //load more pictures from server
            ++this.page;
            let url = `https://api.unsplash.com/search/photos?client_id=${clientId}&page=${this.page}&query=${searchValue}`;

            fetch(url)
                .then((data)=>{
                    return data.json();
                })
                .then((data)=>{
                    const new_photoes = data.results;
                    this.pictures = [...this.pictures,...new_photoes];
                })
        }
        
    }
}

</script>

<style scoped>
.logo {
    text-align: center;
}
.vertical-center {
  min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
  min-height: 8vh; /* These two lines are counted as one :-)       */

  display: flex;
  align-items: center;
}

@import '../../css/pictureHover.css';
</style>

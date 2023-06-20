<template>
    <div class="flex mx-auto items-center justify-center max-w-5xl py-8">
        <form class="w-full" action="javascript:void(0)" method="post" enctype="multipart/form-data">
            <div :class="`flex p-4 mb-4 text-sm rounded-lg ${(validationErrors.type === 'errors') ? 'text-red-800 bg-red-50' : 'text-green-800 bg-green-50'}`" role="alert" v-if="Object.keys(validationErrors).length > 0">
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <div>
                    <span class="font-medium">{{ validationErrors.message }}</span>
                    <ul class="mt-1.5 ml-4 list-disc list-inside" v-if="validationErrors.data">
                        <li v-for="(value, key) in validationErrors.data" :key="key">{{ value[0] }}</li>
                    </ul>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="titre" class="block mb-2 text-sm font-medium text-gray-900">Titre *</label>
                <input type="text" v-model="titre" id="titre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>

            <div class="mb-6">
                <label for="tags" class="block mb-2 text-sm font-medium text-gray-900">Tags</label>
                <input type="text" v-model="tags" id="tags" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            <div class="mb-6">
                <label for="content" class="block mb-2 text-sm font-medium text-gray-900">Contenue</label>
                <mavon-editor v-model="content" language="fr" @htmlCode="$event => console.log($event)"/>
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="image">Image</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="image" ref="fileInput" @change="handleFileInputChange" type="file">
            </div>

            <button @click="handleSubmitForm" type="submit"
                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 hover:bg-blue-800">
                Modifier l'article
            </button>
        </form>
    </div>
</template>

<script>
import { useRoute } from "vue-router";
import axios from 'axios';
import MarkdownIt from 'markdown-it';
import TurndownService from 'turndown';
import { mapGetters } from 'vuex'
import router from "../../router";

export default {
    name: 'write-post',
    data(){
        return {
            titre: null,
            tags: null,
            content: null,
            image: null,
            isFetching: false,
            route: useRoute(),
            validationErrors:{},
        }
    },
    methods: {
        async handleSubmitForm() {
            // Convertit le markdown du text editor en HTMl
            const md = new MarkdownIt();
            const htmlContent = md.render(this.content);

            const formData = new FormData();
            formData.append('_method', 'put');

            formData.append('titre', this.titre);
            if(this.tags) formData.append('tags', this.tags);
            formData.append('content', htmlContent);
            if(this.image) formData.append('image', this.image);
            formData.append('user_id', this.currentUser.id);
            
            await axios.post('/api/posts/' + this.route.params.id, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((response) => {
                if(response.data.success){
                    router.push({ name: 'home'})
                }else{
                    this.validationErrors = {
                            type: 'errors',
                            message: response.data.message,
                            data: response.data.data,
                        }
                }
            }).catch(err => console.log(err));
        },

        handleFileInputChange(event) {
            this.image = event.target.files[0];
        },
    },
    computed: {
        ...mapGetters(['isAuthenticated', 'currentUser'])
    },
        // Get post informations from api
        async mounted() {
            this.isFetching = true;
            axios
                .get("/api/posts/" + this.route.params.id)
                .then((response) => {
                    const turndownService = new TurndownService({headingStyle: 'atx'});
                    const post = response.data.post;
                    
                    this.titre = post.titre;
                    this.tags = (post.tags && post.tags != 'null') ? post.tags : null;
                    this.content = turndownService.turndown(post.content);

                    this.isFetching = false;
                })
        },
}
</script>

<style>
/* Text editor reset style */
.v-note-wrapper .v-note-panel .v-note-edit.divarea-wrapper .content-input-wrapper{
    padding: 8px 8px 15px 8px!important
}

.auto-textarea-wrapper .auto-textarea-input.no-border {
    outline: 0 none;
    border: none !important;
    box-shadow: none!important;
}

.v-note-wrapper.shadow{
    box-shadow: none!important;
    border: 1px solid rgb(209 213 219)!important;
}
</style>
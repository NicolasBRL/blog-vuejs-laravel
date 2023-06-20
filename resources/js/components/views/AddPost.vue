<template>
    <div class="flex mx-auto items-center justify-center max-w-5xl py-8">
        <form class="w-full" action="javascript:void(0)" method="post" enctype="multipart/form-data">
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
                Poster l'article
            </button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import MarkdownIt from 'markdown-it';

export default {
    name: 'write-post',
    data(){
        return {
            titre: null,
            tags: null,
            content: null,
            image: null,
        }
    },
    methods: {
        async handleSubmitForm() {
            // Convertit le markdown du text editor en HTMl
            const md = new MarkdownIt();
            const htmlContent = md.render(this.content);

            const formData = new FormData();
            formData.append('titre', this.titre);
            formData.append('tags', this.tags);
            formData.append('content', htmlContent);
            formData.append('image', this.image);
            formData.append('user_id', this.$store.state.user.id);

            await axios.post('/api/posts', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((response) => {
                router.push({name: 'home'})
            }).catch(err => console.log(err));
        },

        handleFileInputChange(event) {
            this.image = event.target.files[0];
        }
    }
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
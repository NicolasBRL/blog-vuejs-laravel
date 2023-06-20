<template>
    <div class="flex mx-auto items-center justify-center max-w-5xl py-8">
        <form class="w-full" action="javascript:void(0)" method="post" enctype="multipart/form-data">
            <div class="mb-6">
                <label for="pseudo" class="block mb-2 text-sm font-medium text-gray-900">Pseudo</label>
                <input type="text" v-model="pseudo" id="pseudo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>

            <div class="mb-6">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                <input type="email" v-model="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            <div class="mb-6">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Mot de passe</label>
                <input type="password" v-model="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900" for="image">Image</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="image" ref="fileInput" @change="handleFileInputChange" type="file">
            </div>

            <button @click="handleSubmitForm" type="submit"
                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 hover:bg-blue-800">
                Modifier le profil
            </button>
        </form>
    </div>
</template>

<script>
import { useRoute } from "vue-router";
import axios from 'axios';
import { mapGetters } from 'vuex'

export default {
    name: 'edit-profile',
    data(){
        return {
            pseudo: null,
            email: null,
            image: null,
            password: null,
            isFetching: false,
            route: useRoute()
        }
    },
    methods: {
        async handleSubmitForm() {

            const formData = new FormData();

            formData.append('pseudo', this.pseudo);
            formData.append('email', this.email);
            if(this.password) formData.append('password', this.password);
            if(this.image) formData.append('image', this.image);
            formData.append('id', this.currentUser.id);


            await axios.post('/api/profil', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((response) => {
                console.log(response)
                if(response.data.success) this.$store.dispatch('setUser', {user: response.data.user});
            }).catch(err => console.log(err));
        },

        handleFileInputChange(event) {
            this.image = event.target.files[0];
        },
    },
    computed: {
        ...mapGetters(['isAuthenticated', 'currentUser']),
    },
    mounted() {
        this.pseudo = this.currentUser.pseudo
        this.email = this.currentUser.email
    }
}
</script>
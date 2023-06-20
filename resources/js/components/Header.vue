<template>
    <nav class="bg-white border-gray-200 border-b border-gray-200">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl py-4">
            
            <router-link :to="{name: 'home'}" class="self-center text-black font-semibold whitespace-nowrap">Blog</router-link>
            <div class="flex items-center">

                <router-link :to="{name: 'write-post'}" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-2.5 py-1.5 text-center mr-2" v-if="isAuthenticated">Écrire un article</router-link>

                <div class="flex items-center md:order-2" v-if="isAuthenticated">
                    <button
                        type="button"
                        class="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300"
                        id="user-menu-button"
                        aria-expanded="false"
                        data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom"
                    >
                        <img
                            class="w-8 h-8 rounded-full"
                            :src="'http://127.0.0.1:8000/storage/' + currentUser.image"
                            alt="user photo"
                        />
                    </button>
                    <!-- Dropdown menu -->
                    <div
                        class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow"
                        id="user-dropdown"
                    >
                        <div class="px-4 py-3">
                            <span class="block text-sm text-gray-900">{{ currentUser.pseudo }}</span>
                            <span class="block text-sm text-gray-500 truncate">{{ currentUser.email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <router-link :to="{name: 'profil'}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</router-link>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" @click="logout" >Se déconnecter</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <router-link v-else :to="{ name: 'login' }" class="text-sm text-blue-600 hover:underline" >Se connecter</router-link>
            </div>
        </div>
    </nav>
</template>

<script>
import { initFlowbite } from 'flowbite'
import { mapGetters } from 'vuex'

export default {
    name: 'header',
    computed: {
        ...mapGetters(['isAuthenticated', 'currentUser'])
    },
    updated() {
        initFlowbite();
    },
    methods: {
        async logout() {
            await axios.post("/api/logout")
                .then((response) => {
                    this.$store.dispatch('logout')
                });
        },
    }
}
</script>
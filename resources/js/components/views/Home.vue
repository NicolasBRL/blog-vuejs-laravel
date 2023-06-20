<template>
    <div class="max-w-screen-xl mx-auto py-4">
        <div class="grid grid-cols-3 gap-4">
            <div v-for="post in posts.data" :key="post.id" class="card flex flex-col">
                <div class="thumbnail relative">
                    <div class="absolute top-2 right-2 z-100 hidden" v-if="isAuthenticated && (currentUser.id === post.user.id || currentUser.isAdmin)">
                        <div class="flex gap-2">
                            <router-link :to="{ name: 'edit-post', params: { id: post.id } }" class="rounded-full bg-white bg-opacity-50 p-2 hover:bg-opacity-100 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                </svg>
                            </router-link>

                            <div class="rounded-full bg-white bg-opacity-50 p-2 hover:bg-opacity-100 cursor-pointer" @click="deletePost(post.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <router-link :to="{ name: 'post', params: { id: post.id } }">
                        <img :src="'http://127.0.0.1:8000/storage/' + post.image" />
                    </router-link>
                </div>
                <div class="card-body flex flex-col flex-1">
                    <div class="post-meta">
                        <span class="pseudo">{{ post.user.pseudo }}</span>
                        -
                        <span class="date">{{
                            formatDate(post.created_at)
                        }}</span>
                    </div>

                    <router-link :to="{ name: 'post', params: { id: post.id } }">
                        <h2 class="titre uppercase text-lg mb-2">{{ post.titre }}</h2>
                    </router-link>
                    <p class="content flex-1">
                        {{ truncate(post.content, 150, "...") }}
                    </p>
                    
                    <span class="mt-2"
                        >{{ post.comments.length }} Commentaire{{
                            post.comments.length > 1 ? "s" : ""
                        }}</span
                    >
                </div>
            </div>
        </div>

        <!-- Boutons de pagination -->
        <div class="buttons-container mt-4">
            <nav>
                <ul class="inline-flex -space-x-px">
                    <li>
                        <a class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 cursor-pointer" @click="previousPage">Précédent</a>
                    </li>
                    <li>
                        <a class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 cursor-pointer" @click="nextPage">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters } from 'vuex'

export default {
    name: "Home",
    data() {
        return {
            posts: [],
            currentPage: 1,
        }
    },
    computed: {
        ...mapGetters(['isAuthenticated', 'currentUser'])
    },
    methods: {
        async getAllPosts() {
            await axios.get('/api/posts', {
                params: {
                    page: this.currentPage,
                    per_page: 6
                }
            }).then((response) => {
                this.posts = response.data.posts;
            })
        },

        // Truncate text
        truncate: (text, length, clamp) => {
            clamp = clamp || "...";
            var node = document.createElement("div");
            node.innerHTML = text;
            var content = node.textContent;
            return content.length > length
                ? content.slice(0, length) + clamp
                : content;
        },

        // Format date
        formatDate(dateString) {
            const date = new Date(dateString);
            // Then specify how you want your dates to be formatted
            return new Intl.DateTimeFormat("default", {
                dateStyle: "long",
            }).format(date);
        },

        // Méthode pour passer à la page précédente
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.getAllPosts();
            }
        },
        // Méthode pour passer à la page suivante
        nextPage() {
            if (this.currentPage < this.posts.last_page) {
                this.currentPage++;
                this.getAllPosts();
            }
        },

        async deletePost(postId) {
            await axios.delete(`/api/posts/${postId}`)
            .then((response) => {
                if(response.data.success){
                     this.getAllPosts();}
            })
        }
    },
    created: function () {
        this.getAllPosts(this);
    },
}
</script>

<style scoped>

.card .thumbnail {
    margin-bottom: 12px;
}
.card .thumbnail:hover div:first-child{
    display: flex;
}

.card .thumbnail img {
    border-radius: 7px;
    max-width: 100%;
    height: 100%;
    height: 250px;
    object-fit: cover;
}

.card .titre {
    color: #222;
    font-weight: 600;
}

.card .post-meta {
    margin-bottom: 0.25rem !important;
}

.card .post-meta .pseudo {
    color: #222;
    font-weight: 600;
}
.card .post-meta .date {
    color: #999;
}
.card .content {
    color: #888;
    margin: 0 0 0.25rem 0;
}
</style>
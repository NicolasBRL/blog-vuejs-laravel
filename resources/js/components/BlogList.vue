<template>
    <div class="post-container py-4">
        <router-link to="/" class="back-btn mb-4">« Retour</router-link>
        <a class="dropdown-item" href="javascript:void(0)" @click="logout">Se déconnecter</a>
        
        <p class="mb-0" v-if="user.id">You are logged in as <b>{{user.email}}</b></p>
        <p class="mb-0" v-else>You are not logged</p>
    
        <div class="grid grid-cols-3 gap-4">
            <div v-for="post in posts.data" :key="post.id" class="card flex flex-col">
                <div class="thumbnail">
                    <router-link
                        :to="{ name: 'post', params: { id: post.id } }"
                    >
                        <img :src="'http://127.0.0.1:8000/storage/' + post.image" />
                    </router-link>
                </div>
                <div class="card-body flex flex-col flex-1">
                    <div class="post-meta">
                        <span class="tags">{{ post.tags }}</span>
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
                            post.comments.length ? "s" : ""
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
import { mapActions } from "vuex";
import apiClient from "../api";

export default {
    name: "BlogList",
    data() {
        return {
            posts: [],
            currentPage: 1,
            user: this.$store.state.auth.user
        };
    },
    methods: {
        // Get all posts from api
        getAllPosts: function () { 
            console.log(this.user)
            apiClient.get('/articles', {
                params: {
                    page: this.currentPage, // Récupérer la page actuelle
                    per_page: 6 // Définir le nombre d'articles par page
                }
            })
            .then(response => {
                this.posts = response.data.data; // Stocker les articles dans la variable du composant
            })
            .catch(error => {
                console.error(error);
            });
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

        // Méthode pour déconnecter l'utilisateur
        ...mapActions({
            signOut:"auth/logout"
        }),
        
        async logout(){
            await apiClient.post('/logout').then(({data})=>{
                this.signOut()
                this.$router.push({name:"login"})
            })
        }
    },
    created: function () {
        this.getAllPosts(this);
    },
};
</script>

<style scoped>
.post-container {
    max-width: 1320px;
    margin: 0 auto;
}


.card .thumbnail {
    margin-bottom: 12px;
}

.card .thumbnail img {
    border-radius: 7px;
    max-width: 100%;
    height: auto;
}

.card .titre {
    color: #222;
    font-weight: 600;
}

.card .post-meta {
    margin-bottom: 0.25rem !important;
}

.card .post-meta .tags {
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

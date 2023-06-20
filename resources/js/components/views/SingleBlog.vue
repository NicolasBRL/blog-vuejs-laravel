<template>
    <div v-if="!isFetching" class="container py-4">
        <router-link :to="{name: 'home'}" class="back-btn mb-4">« Retour</router-link>
        <div class="post-meta">
            <span class="pseudo">{{ post.user.pseudo }}</span>
            -
            <span class="date">{{ formatDate(post.created_at) }}</span>
        </div>
        <h1 class="uppercase text-xl mb-2 text-black font-bold">{{ post.titre }}</h1>
        <div class="thumbnail">
            <img :src="'http://127.0.0.1:8000/storage/' + post.image" />
        </div>
        <p class="post-content my-8" v-html="post.content"></p>

        <hr class="hr" />

        <div class="comment-container">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">Commentaires ({{ post.comments.length }})</h2>
            </div>

            <Comment v-if="isAuthenticated" :postId="route?.params.id" />


            <div v-for="comment in post.comments" :key="comment.id">
                <article class="p-6 text-base bg-white rounded-lg dark:bg-gray-900">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                                <img 
                                    class="mr-2 w-6 h-6 rounded-full" 
                                    :src="'http://127.0.0.1:8000/storage/' + comment.user.image" 
                                    :alt="comment.user.pseudo">
                                    {{comment.user.pseudo}}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <span>{{ formatDate(comment.created_at) }}</span>
                            </p>
                        </div>
                        <div class="flex gap-2" v-if="isAuthenticated && (comment.user.id === currentUser.id || currentUser.isAdmin)">
                            <div class="rounded-full cursor-pointer" @click="deleteComment(comment.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p>{{ comment.content }}</p>
                </article>
            </div>
        </div>

        
    </div>
</template>

<script>
import { useRoute } from "vue-router";
import Comment from './Comment.vue';
import { mapGetters } from 'vuex'

export default {
    name: "SingleBlog",
    computed: {
        ...mapGetters(['isAuthenticated', 'currentUser'])
    },
    components: {
        Comment
    },
    data() {
        return {
            post: [],
            isFetching: false,
            route: useRoute()
        };
    },
    methods: {
        // Get post informations from api
        getPost: function (components) {
            this.isFetching = true;
            axios
                .get("/api/posts/" + this.route.params.id)
                .then((response) => {
                    this.post = response.data.post;
                    this.isFetching = false;
                })
                .catch((components.error = true));
        },

        // Format date
        formatDate(dateString) {
            const date = new Date(dateString);
            // Then specify how you want your dates to be formatted
            return new Intl.DateTimeFormat("default", {
                dateStyle: "long",
            }).format(date);
        },

        async deleteComment(commentId) {
            await axios.delete(`/api/comments/${commentId}`)
            .then((response) => {
                if(response.data.success){
                     this.getPost(this);
                }
            })
        }
    },
    created: function () {
        this.getPost(this);
    },
};
</script>

<style>
.back-btn {
    color: #888;
}
.container {
    width: 60%;
    margin: 0 auto;
}
.post-meta {
    margin-bottom: 0.5rem;
}
.post-meta .pseudo,
.comment-meta .pseudo {
    color: #222;
    font-weight: 600;
}
.thumbnail img {
    width: 100%;
    height: auto;
}

.post-content *{
    text-align: justify;
    color: #222;
}

.post-content p{
    margin-bottom: 5px;
}

.comment-container {
    margin-top: 2rem;
}
.comment-content {
    text-align: justify;
}

.hr{width: 50%; margin: 0 auto;}
</style>

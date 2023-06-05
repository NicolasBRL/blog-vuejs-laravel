<template>
    <router-link to="/" class="back-btn">« Retour</router-link>
    <div class="container">
        <div class="post-meta">
            <span class="tags">{{ post.tags }}</span>
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
            <div v-for="comment in post.comments" :key="comment.id">
                <div class="comment-meta">
                    <span class="pseudo">{{ comment.user.pseudo }}</span>
                    -
                    <span class="date">{{
                        formatDate(comment.created_at)
                    }}</span>
                </div>
                <p class="comment-content">{{ comment.content }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { useRoute } from "vue-router";
export default {
    name: "SingleBlog",
    data() {
        return {
            post: [],
        };
    },
    methods: {
        // Get post informations from api
        getPost: function (components) {
            const route = useRoute();
            axios
                .get("http://127.0.0.1:8000/api/posts/" + route.params.id)
                .then((resp) => {
                    console.log(resp.data.data);
                    components.post = resp.data.data;
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
.post-meta .tags,
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

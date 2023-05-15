<template>
    <div class="post-container">
        <div class="card-container">
            <div v-for="post in posts" :key="post.id" class="card">
                <div class="thumbnail">
                    <router-link :to="{ name: 'post', params: { id: post.id }}">
                        <img src="https://placehold.co/600x400" />
                    </router-link>
                </div>
                <div class="card-body">
                    <div class="post-meta">
                        <span class="tags">{{ post.tags }}</span>
                        -
                        <span class="date">{{ formatDate(post.created_at) }}</span>
                    </div>
                    <p class="content">{{ truncate(post.content, 70, "...") }}</p>
                    <span>{{ post.comments.length }} Commentaire{{ (post.comments.length) ? 's' : ''}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "BlogList",
    data() {
        return {
            posts: [],
        };
    },
    methods: {
        // Get all posts from api
        getAllPosts: function (components) {
            axios
                .get("http://127.0.0.1:8000/api/posts")
                .then((resp) => {
                    console.log(resp);
                    components.posts = resp.data.data;
                })
                .catch((components.error = true));
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
            return new Intl.DateTimeFormat('default', {dateStyle: 'long'}).format(date);
        }
    },
    created: function () {
        this.getAllPosts(this);
    },
};
</script>

<style scoped>
.post-container{
    max-width: 1320px;
    margin: 0 auto;
}

.card-container{
    display: flex;
    flex-wrap: wrap;
    margin-top: calc(3rem * -1);
    margin-right: calc(3rem / -2);
    margin-left: calc(3rem / -2);
}

.card-container > * {
    flex-shrink: 0;
    width: 100%;
    max-width: 100%;
    padding-right: calc(3rem / 2);
    padding-left: calc(3rem / 2);
    margin-top: 3rem;
}

.card{
    flex: 0 0 auto;
    width: 33.33333%;
    position: relative;
}

.card .thumbnail{
    flex: 0 0 100% !important;
    margin-bottom: 24px;
}

.card .thumbnail img{
    border-radius: 7px;
    max-width: 100%;
    height: auto;
}

.card .post-meta{
    margin-bottom: 0.25rem !important;
}

.card .post-meta .tags{color: #222; font-weight: 600;}
.card .post-meta .date{color: #999}
.card .content{color: #888; margin: 0 0 .25rem 0;}
</style>
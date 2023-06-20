<template>
    <div class="write-comment-container" v-if="!success">
        <form class="mb-6" action="javascript:void(0)" method="post">
            <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200">
                <label for="comment" class="sr-only">Votre commentaire</label>
                <textarea id="comment" rows="6"
                    class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none"
                    placeholder="Écrire un commentaire..." v-model="content" required></textarea>
            </div>
            <button @click="postComment" type="submit"
                class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 hover:bg-blue-800">
                Poster le commentaire
            </button>
        </form>
    </div>

    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" v-else>
        {{ successText }}
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['postId'],
    name: "write-comment",
    data() {
        return {
            content: null,
            success: false,
            successText: ''
        }
    },
    methods: {
        async postComment() {
            console.log(this.postId)
            await axios.post('/api/comments', 
                {
                    content: this.content, 
                    user_id: this.$store.state.user.id, 
                    post_id: this.postId
                }).then((response) => {
                    console.log(response)
                    this.success = true
                    this.successText = response.data.message
                }).catch((error) => console.log(error))
        }
    }
}
</script>
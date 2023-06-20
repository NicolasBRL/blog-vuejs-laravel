<template>
    <header>
        <Header />
    </header>
    <main>
        <router-view></router-view>
    </main>
</template>

<script>
import Header from "../components/Header.vue";

export default {
    name: "app",
    components: {
        Header,
    },
};
</script>

<template>
    <div class="app">
        <Menu></Menu>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<style lang="scss">
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-weight: normal;
        font-size: 1rem;
        outline: none;
    }

    .btn {
        background: #00bcd4;
        color: #fff;
        border: none;
        padding: 0.62rem 1.25rem;
        border-radius: 0.3rem;
        font-size: 1.3rem;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;

        &:hover {
            background: #00acc1;
        }
    }
</style>

<script>
import api from "@/services/api";
import Menu from "./components/Menu.vue";

export default {
    components: {
        Menu,
    },
    methods: {
        getPeople() {
            api.get("/people")
                .then(response => {
                    if (response.data.success) {
                        this.$store.commit("setPeople", response.data.data);
                    }
            });
        },
    },
    mounted() {
        this.getPeople();
    },
};
</script>

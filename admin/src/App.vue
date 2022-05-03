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
        background: #575757;
        color: #fff;
        border: none;
        padding: 0.62rem 1.25rem;
        border-radius: 0.3rem;
        font-size: 1.3rem;
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;

        &:hover {
            background: #1b1e21;
        }

        @media screen and (max-width: 570px) {
            font-size: 1.15rem;
        }
    }
</style>

<script>
import Menu from "./components/Menu.vue";

export default {
    components: {
        Menu,
    },
};
</script>

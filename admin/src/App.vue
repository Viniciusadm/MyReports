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
import Menu from "./components/Menu.vue";

export default {
    components: {
        Menu,
    },
};
</script>

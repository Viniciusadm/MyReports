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
</style>

<script>
import Menu from './components/Menu.vue'

export default {
    components: {
        Menu
    }
}
</script>
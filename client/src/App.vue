<template>
    <div class="app">
        <Menu></Menu>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    
</style>

<script>
import Menu from './components/Menu.vue'

export default {
    components: {
        Menu
    }
}
</script>
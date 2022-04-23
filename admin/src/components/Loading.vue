<template>
    <div class="c-loader"></div>
</template>

<style scoped lang="scss">
    .c-loader {
        animation: is-rotating 1s infinite;
        border-radius: 50%;
        border: 6px solid #e5e5e5;
        border-top-color: #00bcd4;
        height: 8rem;
        width: 8rem;
    }

    @keyframes is-rotating {
        to {
            transform: rotate(1turn);
        }
    }
</style>

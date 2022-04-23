<template>
    <div class="loading">
        <div class="c-loader"></div>
    </div>
</template>

<style scoped lang="scss">
    .loading {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        position: fixed;
        top: 0;
        left: 0;

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
    }
</style>

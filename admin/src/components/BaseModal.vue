<template>
    <div class="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.modal {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;

    .modal-dialog {
        max-width: 700px;
        width: 90%;
        background-color: #fff;
        border: 1px solid #ccc;
        margin: 1rem 0;

        &.scroll {
            overflow-y: scroll;
        }
    }
}
</style>

<script>
export default {
    name: 'BaseModal',
    props: {
        scroll: {
            type: Boolean,
            default: false
        }
    },
    mounted() {
        if (this.scroll) {
            const modal = this.$el.querySelector('.modal-dialog');
            modal.classList.add('scroll');
        }
    }
}
</script>

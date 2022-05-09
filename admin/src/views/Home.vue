<template>
    <div class="home">
        <participants class="graphic"></participants>
    </div>
</template>

<script>
import participants from "@/components/Dashboard/ParticipantsGraphic";

export default {
    components: {
        participants
    },
}

</script>

<style lang="scss" scoped>
    .home {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 70vh;

        .graphic {
            max-height: 40rem;
        }
    }
</style>

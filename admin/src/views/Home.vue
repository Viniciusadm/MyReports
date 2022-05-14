<template>
    <div class="home">
        <participants class="graphic"></participants>
        <minutes class="graphic"></minutes>
    </div>
</template>

<script>
import participants from "@/components/Dashboard/ParticipantsGraphic";
import minutes from "@/components/Dashboard/MinutesGraphic";

export default {
    components: {
        participants,
        minutes,
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

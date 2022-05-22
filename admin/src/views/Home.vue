<template>
    <div class="home">
        <div class="graphics">
            <participants class="graphic"></participants>
            <assis class="graphic"></assis>
            <minutes class="graphic"></minutes>
        </div>
    </div>
</template>

<script>
import participants from "@/components/Dashboard/Participants";
import minutes from "@/components/Dashboard/MinutesGraphic";
import assis from "@/components/Dashboard/Assis";

export default {
    components: {
        participants,
        minutes,
        assis,
    },
}

</script>

<style lang="scss" scoped>
    .home {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        .graphics {
            width: 90%;
            height: 70vh;

            .graphic {
                max-height: 40rem;
            }
        }
    }
</style>

<template>
    <div class="home">
        <div class="lists">
            <warnings class="list" />
        </div>
        <div class="lists">
            <assis class="list"></assis>
            <participants class="list"></participants>
        </div>
        <div class="graphics">
            <points class="graphic"></points>
            <minutes class="graphic"></minutes>
        </div>
    </div>
</template>

<script>
import participants from "@/components/Dashboard/Participants";
import minutes from "@/components/Dashboard/MinutesGraphic";
import assis from "@/components/Dashboard/Assis";
import points from "@/components/Dashboard/PointsGraphic";
import warnings from "@/components/Dashboard/Warnings";

export default {
    components: {
        points,
        participants,
        minutes,
        assis,
        warnings,
    },
}

</script>

<style lang="scss" scoped>
    .home {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        .lists {
            margin-top: 2rem;
            width: 90%;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;

            .list {
                margin-right: 2rem;
                margin-bottom: 1.5rem;

                &:last-child {
                    margin-right: 0;
                }
            }
        }

        .graphics {
            width: 90%;
            height: 70vh;

            .graphic {
                max-height: 40rem;
            }
        }
    }
</style>

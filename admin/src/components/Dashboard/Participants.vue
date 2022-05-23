<template>
    <div class="participants">
        <h2>Top 10 participantes</h2>
        <p v-for="participant in participants" :key="participant.nickname">
            {{ participant.nickname }}: {{ participant.participations }} {{ participant.participations === 1 ? 'relato' : 'relatos' }}
        </p>
    </div>
</template>

<script>
import api from '@/services/api';

export default {
    data() {
        return {
            participants: [],
        }
    },
    methods: {
        getData() {
            api.get('/dashboard/participants')
                .then(response => {
                    if (response.data.success) {
                        this.participants = response.data.data;
                    }
                })
        },
    },
    mounted() {
        this.getData();
    },
}
</script>

<style lang="scss" scoped>
    .participants {
        h2 {
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        p {
            margin-bottom: 0.4rem;
            font-size: 1.5rem;
        }
    }
</style>

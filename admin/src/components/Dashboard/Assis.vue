<template>
    <div class="participants">
        <h2>Top 10 coleções</h2>
        <p v-for="collection in collections" :key="collection.name">
            {{ collection.name }}: {{ collection.count }}
        </p>
    </div>
</template>

<script>
import api from '@/services/api';

export default {
    data() {
        return {
            collections: [{
                name: '',
                count: 0,
            }],
        }
    },
    methods: {
        getData() {
            api.get('/dashboard/episodes')
                .then(response => {
                    if (response.data.success) {
                        this.collections = response.data.data;
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
    margin: 2rem 0;
    width: 100%;

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

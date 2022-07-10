<template>
    <div class="warnings">
        <h2>Avisos</h2>
        <template v-for="(warning, index) in warnings" :key="index">
            <p v-html="warning.message"></p>
        </template>
    </div>
</template>

<script>
import api from '@/services/api';

export default {
    data() {
        return {
            warnings: [],
        }
    },
    methods: {
        getData() {
            api.get('/dashboard/warnings')
                .then(response => {
                    if (response.data.success) {
                        this.warnings = response.data.data.sort((a, b) => {
                            return b.priority - a.priority;
                        });
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
.warnings {
    h2 {
        margin-bottom: 0.5rem;
        font-size: 2rem;
    }

    p {
        margin-bottom: 0.4rem;
        font-size: 1.5rem;

        a {
            color: #000;
            text-decoration: none;
            font-weight: bold;

            &:hover {
                text-decoration: underline;
            }
        }
    }
}
</style>

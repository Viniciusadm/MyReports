<template>
    <div class="assis_container">
        <div class="header">
            <h1>Assis</h1>
            <router-link to="/reports/new" class="btn">Nova coleção</router-link>
        </div>
        <div class="assis_list">
            <assis  v-for="assi in assis" :key="assi.id" :assis="assi" />
        </div>
    </div>
</template>

<script>
import api from "@/services/api";
import assis from "@/components/Assis/AssisComponent";

export default {
    data() {
        return {
            assis: [{
                id: 0,
                collection_id: 0,
                name: "",
                total: 0,
                status: 0,
                created_at: "",
                type: "",
                image: "",
                episodes_count: 0,
                collection: {
                    id: 0,
                    name: "",
                    image: "",
                }
            }],
        }
    },
    components: {
        assis,
    },
    methods: {
        getAssis() {
            api.get('assis/status/assistindo')
                .then(response => {
                    if (response.data.success) {
                        this.assis = response.data.data;
                    }
                })
        },
    },
    mounted() {
        this.getAssis();
    },
}
</script>

<style scoped lang="scss">
    .assis_container {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;

        .header {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            width: 90%;
            margin-bottom: 1.25rem;

            .btn {
                @media screen and (max-width: 570px) {
                    font-size: 1rem;
                }
            }

            h1 {
                font-size: 2em;
                font-weight: bold;
                color: #333;
            }
        }

        .assis_list {
            display: flex;
            flex-direction: column;
            width: 90%;
        }
    }
</style>

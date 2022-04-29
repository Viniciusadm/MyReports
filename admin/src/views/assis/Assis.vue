<template>
    <div class="assis_container">
        <div class="header">
            <h1>Assis</h1>
            <router-link to="/assis/new-collection" class="btn">Nova coleção</router-link>
        </div>
        <div class="filters">
            <select @change="getAssis" v-model="filters.status">
                <option value="">Todos</option>
                <option value="assistindo">Assistindo</option>
                <option value="para_assistir">Para Assistir</option>
                <option value="desistido">Desistido</option>
                <option value="completo">Completo</option>
                <option value="pausado">Pausado</option>
            </select>
        </div>
        <div v-if="!carregando" class="assis_list">
            <template v-if="assis.length > 0">
                <assis v-for="assi in assis" :key="assi.id" :assis="assi" />
            </template>
            <template v-else>
                <div class="no_results">Nenhum resultado encontrado</div>
            </template>
        </div>
        <loading v-if="carregando" />
    </div>
</template>

<script>
import api from "@/services/api";
import assis from "@/components/Assis/AssisComponent";
import loading from "@/components/Loading";

export default {
    data() {
        return {
            assis: [],
            carregando: false,
            filters: {
                status: "",
            },
        }
    },
    components: {
        assis,
        loading,
    },
    methods: {
        getAssis() {
            this.carregando = true;
            api.get(`/assis?status=${this.filters.status}`)
                .then(response => {
                    if (response.data.success) {
                        this.assis = response.data.data;
                    }
                })
                .finally(() => {
                    this.carregando = false;
                });
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

        .filters {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            width: 90%;
            margin-bottom: 1.25rem;

            select {
                width: 100%;
                height: 2.5rem;
                border-radius: 0.25rem;
                font-size: 1.25rem;
                color: #333;
                background: transparent;
                padding: 0 1rem;
                outline: none;
                border: 1px solid #ccc;
            }
        }

        .assis_list {
            display: flex;
            flex-direction: column;
            width: 90%;

            .no_results {
                font-size: 1.8rem;
                color: #333;
                text-align: center;
                margin-top: 1.25rem;
            }
        }
    }
</style>

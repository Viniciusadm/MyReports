<template>
    <div class="assis_container">
        <div class="header">
            <h1>Assis</h1>
            <router-link to="/assis/new-collection" class="btn">Nova coleção</router-link>
        </div>
        <div class="filters">
            <select @change="changeStatus" v-model="filters.status">
                <option value="">Todos</option>
                <option value="assistindo">Assistindo</option>
                <option value="para_assistir">Para Assistir</option>
                <option value="desistido">Desistido</option>
                <option value="completo">Completo</option>
                <option value="pausado">Pausado</option>
            </select>
            <select @change="changeType" v-model="filters.type">
                <option value="">Todos</option>
                <option v-for="type in Object.keys(types)" :value="type" :key="type">{{ types[type] }}</option>
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
                status: localStorage.getItem("assis_status") || "",
                type: localStorage.getItem("assis_type") || ""
            },
        }
    },
    components: {
        assis,
        loading,
    },
    computed: {
        types() {
            return this.$store.state.types;
        },
    },
    methods: {
        changeStatus() {
            localStorage.setItem("assis_status", this.filters.status);
            this.getAssis();
        },
        changeType() {
            localStorage.setItem("assis_type", this.filters.type);
            this.getAssis();
        },
        getAssis() {
            this.carregando = true;
            api.get(`/assis?status=${this.filters.status}&type=${this.filters.type}`)
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 0.5rem;
            width: 90%;
            margin-bottom: 1.25rem;

            @media screen and (max-width: 570px) {
                grid-template-columns: 1fr;
            }

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

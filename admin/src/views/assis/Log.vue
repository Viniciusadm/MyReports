<script>
import api from "@/services/api";
import moment from "moment";
import loading from "@/components/Loading";

export default {
    data() {
        return {
            date: moment().format("YYYY-MM-DD"),
            logs: [{
                id: 0,
                episode: "",
                created_at: "",
                assis: {
                    id: 0,
                    name: "",
                    hidden_collection: false,
                    collection: {
                        id: 0,
                        name: ""
                    }
                },
            }],
            carregando: true
        }
    },
    methods: {
        getLogs() {
            api.get(`/assis/episode/date/${this.date}`)
                .then(response => {
                    if (response.data.success) {
                        this.logs = response.data.data;
                        this.carregando = false;
                    }
                });
        },
        name(assis) {
            if (assis.hidden_collection) {
                return assis.name;
            }

            if (assis.name) {
                return `${assis.collection.name} - ${assis.name}`;
            } else {
                return assis.collection.name;
            }
        },
        dateFormat(date) {
            return moment(date).format("DD/MM/YYYY");
        }
    },
    components: {
        loading
    },
    mounted() {
        this.getLogs();
    }
}
</script>

<template>
    <div v-if="!carregando" class="log">
        <div class="header">
            <h1>Log</h1>
        </div>
        <div class="logs">
            <input class="date" type="date" v-model="date" @change="getLogs()">
            <template v-if="logs.length > 0">
                <div class="log-item" v-for="log in logs" :key="log.id">
                    <p>Episódio {{ log.episode }} de {{ name(log.assis) }} confirmado.</p>
                </div>
            </template>
            <template v-else>
                <div class="log-item">
                    <p>Nenhum log encontrado</p>
                </div>
            </template>
        </div>
    </div>
    <loading v-else-if="carregando" />
</template>

<style scoped lang="scss">
    .log {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;

        button {
            font-size: 1.25em;
            font-weight: bold;
            color: #333;
            background-color: #fff;
            border: none;
            cursor: pointer;
            user-select: none;
        }

        .header {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            width: 90%;
            margin-bottom: 1.20rem;

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

        .logs {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 90%;
            margin-bottom: 1.20rem;

            .date {
                margin-bottom: 1.20rem;
                height: 2.5rem;
                padding: 0.5rem;
            }

            .log-item {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                margin-bottom: 1rem;

                p {
                    font-size: 1.3rem;

                    @media screen and (max-width: 570px) {
                        font-size: 1.2rem;
                    }
                }
            }
        }
    }
</style>

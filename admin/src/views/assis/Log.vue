<template>
    <div v-if="!carregando" class="log">
        <div class="header">
            <h1>Log</h1>
        </div>
        <div class="logs">
            <input class="date" type="date" v-model="date" @change="getLogs()">
            <template v-if="logs.length > 0">
                <p class="quantity">{{ logs.length }} {{ logs.length > 1 ? 'episódios assistidos' : 'episódio assistido' }}</p>
                <p class="time">{{ time }}</p>
            </template>
            <div v-if="logs.length > 0" class="items">
                <p class="log-item" v-for="log in logs" :key="log.id">
                    <template v-if="log.assis.type === 'movie'">
                        Filme {{ name(log.assis) }} confirmado.
                    </template>
                    <template v-else-if="log.assis.type === 'special'">
                        Especial de {{ name(log.assis) }} de {{ log.assis.year }} confirmado.
                    </template>
                    <template v-else>
                        Episódio {{ log.episode }} de {{ name(log.assis) }} confirmado.
                    </template>
                </p>
            </div>
            <div v-else class="items">
                <p class="log-item">Nenhum episódio assistido.</p>
            </div>
        </div>
    </div>
    <loading v-else-if="carregando" />
</template>

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
                    type: "",
                    year: "",
                    collection: {
                        id: 0,
                        name: ""
                    }
                },
            }],
            carregando: true
        }
    },
    computed: {
        time() {
            let time = 0;
            this.logs.forEach(log => {
                time += log.assis.average_time;
            });

            if (time > 60) {
                const hours = Math.floor(time / 60);
                const hours_text = hours > 1 ? `${hours} horas` : `${hours} hora`;
                const minutes = time - (hours * 60);
                const minutes_text = minutes > 0 ? ' e ' + minutes + ' minutos' : '';
                return hours_text + minutes_text;
            } else {
                return time + " minutos";
            }
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

            .quantity {
                font-size: 1.3rem;
                font-weight: bold;
                color: #333;
            }

            .time {
                font-size: 1.3rem;
                font-weight: bold;
                color: #333;
                margin-bottom: 1rem;
            }

            .items {
                .log-item {
                    font-size: 1.3rem;


                    @media screen and (max-width: 570px) {
                        font-size: 1.2rem;
                    }
                }
            }
        }
    }
</style>

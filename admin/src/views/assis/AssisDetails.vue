<template>
    <div v-if="!carregando" class="assis-details">
        <div class="header">
            <h1>Assis</h1>
            <router-link to="/people/new" class="btn">Novo assis</router-link>
        </div>
        <div class="info">
            <p>Nome da coleção: {{ assis.collection.name }}</p>
            <p v-if="assis.name">Nome do assis: {{ assis.name }}</p>
            <p v-if="assis.sinopse">Sinopse: {{ assis.sinopse }}</p>
            <p v-if="assis.year">Ano: {{ assis.year }}</p>
            <p v-if="assis.average_time">Tempo médio: {{ assis.average_time }}</p>
            <p>Tipo: {{ type }}</p>
            <p>Status: {{ assis.status }}</p>
            <p>Episódios: {{ assis.total }}</p>
            <p>Último episódio: {{ lastEpisode }}</p>
        </div>
        <div class="addFast" v-if="lastEpisode < assis.total">
            <button @click="addEpisode(lastEpisode + 1)">Adicionar episódio {{ lastEpisode + 1 }}</button>
        </div>
        <div class="addSpecific" v-if="lastEpisode.length !== assis.total">
            <input type="number" v-model="specificEpisode" />
            <button @click="addEpisode(specificEpisode)">Adicionar</button>
        </div>
        <div class="episodes">
            <h2>Episódios</h2>
            <div class="episode" v-for="episode in assis.total" :key="episode">
                <template v-if="hasEpisode(episode)">
                    <p>Episódio {{ episode }} - {{ dataFormatada(hasEpisode(episode)['created_at']) }}</p>
                    <p>Comentário: {{ hasEpisode(episode).comment ? hasEpisode(episode).comment : "Não há comentário" }}</p>
                </template>
                <template v-else>
                    <p>Episódio {{ episode }}</p>
                    <button @click="addEpisode(episode)">Adicionar episódio</button>
                </template>
            </div>
        </div>
    </div>
    <loading v-else-if="carregando" />
</template>

<script>
import api from "@/services/api";
import loading from "@/components/Loading";

export default {
    data() {
        return {
            carregando: true,
            assis: {
                id: 0,
                collection_id: 0,
                name: "",
                total: 0,
                type: "",
                status: "",
                order: 0,
                image: null,
                average_time: 0,
                year: 0,
                sinopse: null,
                created_at: "",
                updated_at: "",
                hidden_collection: false,
                collection: {
                    id: 0,
                    name: "",
                },
                episodes: {
                    id: 0,
                    assis_id: 0,
                    episode: 0,
                    comment: "",
                },
            },
            specificEpisode: 1,
        };
    },
    components: {
        loading,
    },
    computed: {
        id() {
            return this.$route.params.id;
        },
        lastEpisode() {
            if (this.assis.episodes.length > 0) {
                return this.assis.episodes.reduce((a, b) => a.episode > b.episode ? a : b).episode;
            } else {
                return 0;
            }
        },
        type() {
            const types = {
                anime: "Anime",
                dorama: "Dorama",
                cartoon: "Desenho",
                movie: "Filme",
                serie: "Série",
                other: "Outro"
            }

            return types[this.assis.type];
        },
    },
    methods: {
        getAssis() {
            api.get(`/assis/${this.id}`)
                .then(response => {
                    if (response.data.success) {
                        this.assis = response.data.data;
                    }
                })
                .finally(() => {
                    this.carregando = false;
                });
        },
        hasEpisode(episode) {
            return this.assis.episodes.find(e => e.episode === episode);
        },
        addEpisode(episode) {
            if (this.hasEpisode(episode) || episode <= 0) {
                return;
            }

            this.carregando = true;
            api.post(`/assis/episode/${this.id}/add`, { episode })
                .then(response => {
                    if (response.data.success) {
                        this.assis.episodes.push(response.data.data);
                    }
                })
                .finally(() => {
                    this.carregando = false;
                });
        },
        dataFormatada(data) {
            return data.split('T')[0].split('-').reverse().join('/');
        }
    },
    mounted() {
        this.getAssis();
    }
}
</script>

<style scoped lang="scss">
    .assis-details {
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

        .info {
            width: 90%;
            display: flex;
            flex-direction: column;

            p {
                font-size: 1.25rem;
                margin-bottom: 0.3rem;
                color: #333;
            }
        }

        .addFast {
            width: 90%;
            margin-top: 1.20rem;
        }

        .addSpecific {
            width: 90%;
            margin-top: 1.20rem;

            input {
                font-size: 1.25rem;
                padding: 0.2rem 0.5rem;
                margin-right: 0.5rem;
                border: 1px solid #333;
                border-radius: 0.25rem;
                margin-bottom: 0.5rem;
            }
        }

        .episodes {
            width: 90%;
            display: flex;
            flex-direction: column;
            margin: 1.20rem 0;

            h2 {
                font-size: 1.5em;
                font-weight: bold;
                color: #333;
            }

            .episode {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                margin-top: 1.20rem;
                padding-bottom: 0.8rem;
                border-bottom: 1px solid #333;

                p {
                    font-size: 1.25em;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 0.2rem;
                }
            }
        }
    }
</style>

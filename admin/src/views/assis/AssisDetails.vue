<template>
    <div v-if="!carregando" class="assis-details">
        <div class="header">
            <h1>Assis</h1>
            <router-link to="/people/new" class="btn">Novo assis</router-link>
        </div>
        <div class="info">
            <p>Nome da coleção: {{ assis.collection.name }}</p>
            <p>Nome do assis: {{ assis.name }}</p>
            <p>Sinopse: {{ assis.sinopse }}</p>
            <p>Ano: {{ assis.year }}</p>
            <p>Tempo médio: {{ assis.average_time }}</p>
            <p>Tipo: {{ assis.type }}</p>
            <p>Status: {{ assis.status }}</p>
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
                }
            },
        };
    },
    components: {
        loading,
    },
    computed: {
        id() {
            return this.$route.params.id;
        }
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

        .episodes {
            width: 90%;
            display: flex;
            flex-direction: column;
            margin: 1.25rem 0;

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
                margin-top: 1.25rem;
                padding-bottom: 0.8rem;
                border-bottom: 1px solid #333;

                p {
                    font-size: 1.25em;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 0.2rem;
                }

                button {
                    font-size: 1.25em;
                    font-weight: bold;
                    color: #333;
                    background-color: #fff;
                    border: none;
                    cursor: pointer;
                }
            }
        }
    }
</style>

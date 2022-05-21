<template>
    <div v-if="!carregando" class="assis-details">
        <div class="header">
            <h1>Assis</h1>
            <router-link :to="`/assis/new-assis/${assis.collection.id}`" class="btn">Novo assis</router-link>
        </div>
        <div class="info">
            <p>Nome da coleção: {{ assis.collection.name }}</p>
            <p v-if="assis.name">Nome do assis: {{ assis.name }}</p>
            <p v-if="assis.sinopse">Sinopse: {{ assis.sinopse }}</p>
            <p v-if="assis.year">Ano: {{ assis.year }}</p>
            <p v-if="assis.average_time">Tempo médio: {{ assis.average_time }}</p>
            <p>Tipo: {{ type }}</p>
            <p>Status: {{ assis.status.replace('_', ' ') }}</p>
            <p v-if="pausavel">Episódios: {{ assis.total }}</p>
            <p v-if="lastEpisode !== 0 && pausavel">Último episódio: {{ lastEpisode }}</p>
            <p v-if="assis.status === 'completo' && pausavel">Tempo para finalizar: {{ diffDays }}</p>
            <p v-if="!pausavel && lastDateFormat">Assistido em: {{ lastDateFormat }}</p>
        </div>
        <template v-if="lastEpisode < assis.total && assis.status === 'assistindo'">
            <template v-if="pausavel">
                <div class="item">
                    <button class="btn btn-episode" @click="addEpisode(lastEpisode + 1)">Adicionar episódio {{ lastEpisode + 1 }}</button>
                </div>
                <div class="addSpecific item" v-if="lastEpisode.length !== assis.total">
                    <input type="number" v-model="specificEpisode" />
                    <button @click="addEpisode(specificEpisode)">Adicionar</button>
                </div>
            </template>
            <template v-else>
                <div class="item">
                    <button class="btn btn-episode" @click="addEpisode(1); changeStatus('completo')">Definir como assistido</button>
                </div>
            </template>
        </template>
        <div class="item" v-if="assis.status === 'assistindo' && pausavel">
            <button class="btn" @click="changeStatus('pausado')">Pausar</button>
        </div>
        <div class="item" v-if="assis.status === 'pausado' && pausavel">
            <button class="btn" @click="changeStatus('assistindo')">Continuar</button>
        </div>
        <div class="item" v-if="assis.episodes.length === assis.total && assis.status !== 'completo' && pausavel">
            <button class="btn" @click="changeStatus('completo')">Finalizar</button>
        </div>
        <div class="item" v-if="assis.status === 'para_assistir'">
            <button class="btn" @click="changeStatus('assistindo')">Começar</button>
        </div>
        <div class="episodes" v-if="pausavel">
            <h2>Episódios</h2>
            <div class="episode" v-for="episode in assis.total" :key="episode">
                <template v-if="hasEpisode(episode)">
                    <p>Episódio {{ episode }} - {{ dataFormatada(hasEpisode(episode)['date']) }}</p>
                    <p>Comentário: {{ hasEpisode(episode).comment ? hasEpisode(episode).comment : "Não há comentário" }}</p>
                    <button v-if="assis.status !== 'completo'" @click="removeEpisode(episode)">Remover</button>
                </template>
                <template v-else>
                    <p>Episódio {{ episode }}</p>
                    <button v-if="assis.status === 'assistindo'" @click="addEpisode(episode)">Adicionar episódio</button>
                </template>
            </div>
        </div>
    </div>
    <loading v-else-if="carregando" />
</template>

<script>
import api from "@/services/api";
import loading from "@/components/Loading";
import moment from "moment";

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
                date: "",
                updated_at: "",
                hidden_collection: false,
                collection: {
                    id: 0,
                    name: "",
                },
                episodes: [{
                    id: 0,
                    assis_id: 0,
                    episode: 0,
                    comment: "",
                }],
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
        types() {
            return this.$store.state.types;
        },
        lastEpisode() {
            if (this.assis.episodes.length > 0) {
                return this.assis.episodes.reduce((a, b) => a.episode > b.episode ? a : b).episode;
            } else {
                return 0;
            }
        },
        firstDate() {
            if (this.assis.episodes.length > 0) {
                const stringDate = this.assis.episodes.reduce((a, b) => a.date < b.date ? a : b).date;
                return new Date(stringDate);
            } else {
                return "";
            }
        },
        lastDate() {
            if (this.assis.episodes.length > 0) {
                const stringDate = this.assis.episodes.reduce((a, b) => a.date > b.date ? a : b).date;
                return new Date(stringDate);
            } else {
                return "";
            }
        },
        lastDateFormat() {
            if (this.assis.episodes.length > 0) {
                return moment(this.lastDate).format("DD/MM/YYYY");
            } else {
                return null;
            }
        },
        diffDays() {
            if (this.firstDate && this.lastDate) {
                const days =  moment(this.lastDate).diff(this.firstDate, "days");
                if (days > 30) {
                    return `${Math.floor(days / 30)} meses e ${days % 30} dias`;
                }

                return `${days} dias`;
            } else {
                return '';
            }
        },
        type() {
            return this.types[this.assis.type];
        },
        pausavel() {
            return this.assis.type !== 'movie' && this.assis.type !== 'special';
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
        removeEpisode(episode) {
            this.$swal('Atenção', 'Você tem certeza que deseja remover este episódio?', 'warning', {
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
            }).then((result) => {
                if (result.value) {
                    if (!this.hasEpisode(episode)) {
                        return;
                    }

                    this.carregando = true;
                    api.delete(`/assis/episode/${this.id}/remove/${episode}`)
                        .then(response => {
                            if (response.data.success) {
                                this.assis.episodes = this.assis.episodes.filter(e => e.episode !== episode);
                            }
                        })
                        .finally(() => {
                            this.carregando = false;
                        });
                }
            });
        },
        dataFormatada(data) {
            return data.split('T')[0].split('-').reverse().join('/');
        },
        changeStatus(status) {
            this.carregando = true;
            api.post(`/assis/change-status/${this.id}`, { status })
                .then(response => {
                    if (response.data.success) {
                        this.assis.status = status;
                    }
                })
                .finally(() => {
                    this.carregando = false;
                });
        },
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

        .btn {
            font-weight: bold;
            width: 10rem;
            text-align: center;
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
                    font-size: 1.15rem;
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

                &:last-child {
                    margin-bottom: 0;
                }

                @media screen and (max-width: 570px) {
                    font-size: 1.15rem;
                }
            }
        }

        .item {
            width: 90%;
            margin-top: 1.20rem;

            .btn-episode {
                width: fit-content;
            }
        }

        .addSpecific {
            input {
                font-size: 1.25rem;
                padding: 0.2rem 0.5rem;
                margin-right: 0.5rem;
                border: 1px solid #333;
                border-radius: 0.25rem;
                margin-bottom: 0.5rem;
            }

            button {
                background: transparent;
                border: none;
                font-size: 1.25rem;
                font-weight: bold;
                color: #333;
                cursor: pointer;

                @media screen and (max-width: 570px) {
                    font-size: 1.15rem;
                }
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

            button {
                font-size: 1.25rem;
                font-weight: bold;
                color: #333;
                background-color: #fff;
                border: none;
                cursor: pointer;
                user-select: none;

                @media screen and (max-width: 570px) {
                    font-size: 1.15rem;
                }
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
                    font-size: 1.25rem;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 0.2rem;

                    @media screen and (max-width: 570px) {
                        font-size: 1.15rem;
                    }
                }
            }
        }
    }
</style>

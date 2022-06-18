<template>
    <div v-if="!carregando" class="report">
        <div class="total">
            <div class="menu_report">
                <h3>Relato Nº {{ id }}</h3>
                <div class="buttons">
                    <router-link :to="`/reports/edit/${id}`" class="btn">Editar</router-link>
                    <button @click="deleteReport()" class="btn">Excluir</button>
                </div>
            </div>

            <div class="header">
                <h1 class="title">{{ report.title }}</h1>
                <div class="head">
                    <p class="label">Data do relato: {{ date }}</p>
                    <p class="label">Hora do relato: {{ hour }}</p>
                    <p class="label">Humor: {{ humor }}</p>
                    <p class="label">Tipo: {{ type }}</p>
                    <p class="label">Participantes: {{ participants }}</p>
                </div>
            </div>
            <div class="body">
                <p v-for="(text, index) in reportArray" :key="index">{{ text }}</p>
            </div>
        </div>
    </div>
    <loading v-else-if="carregando" />
</template>

<script>
import api from "@/services/api";
import { useToast } from "vue-toastification";
import loading from "@/components/Loading";

const toast = useToast();

export default {
    data() {
        return {
            id: this.$route.params.id,
            modal: false,
            report: {
                title: '',
                report: '',
                type: '',
                humor: '',
                created_at: '',
                updated_at: '',
                participants: [],
            },
            carregando: true,
        }
    },
    components: {
        loading,
    },
    computed: {
        date() {
            if (this.report.created_at !== '') {
                return this.report.created_at.split('T')[0].split('-').reverse().join('/');
            } else {
                return ''
            }
        },
        hour() {
            if (this.report.created_at !== '') {
                return this.report.created_at.split('T')[1].split('.')[0];
            } else {
                return ''
            }
        },
        humor() {
            return this.$store.state.humors[this.report.humor];
        },
        type() {
            return this.report.type === 'personal' ? 'Pessoal' : 'Diário';
        },
        participants() {
            if (this.report.participants.length > 0) {
                return this.report.participants.map(participant => {
                    if (participant.person.nickname) {
                        if (participant.type === 'main') {
                            return `${participant.person.nickname} (Principal)`;
                        } else {
                            return participant.person.nickname;
                        }
                    } else {
                        if (participant.type === 'main') {
                            return `${participant.person.name} (Principal)`;
                        } else {
                            return participant.person.name;
                        }
                    }
                }).join(', ');
            } else {
                return 'Nenhum participante';
            }
        },
        reportArray() {
            return this.report.report.split('\n');
        },
    },
    methods: {
        getReport() {
            api.get(`/reports/${this.id}`)
                .then(response => {
                    if (response.data.success) {
                        this.report = response.data.data;
                    } else {
                        toast.error(response.data.message);
                    }
                })
                .catch(error => {
                    toast.error(error.response.data.message);
                })
                .finally(() => {
                    this.carregando = false;
                });
        },
        deleteReport() {
            this.$swal('Atenção', 'Você tem certeza que deseja remover este relato?', 'warning', {
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não',
            }).then((result) => {
                    if (result.value) {
                        api.delete(`/reports/${this.id}`)
                            .then(response => {
                                if (response.data.success) {
                                    toast.success('Relato excluído com sucesso!');
                                } else {
                                    toast.error(response.data.message);
                                }
                            })
                            .catch(error => {
                                toast.error(error.response.data.message);
                            })
                            .finally(() => {
                                this.$router.push('/reports');
                            });
                    }
                });
        },
        save() {
            this.modal = false;
            this.getReport();
        },
    },
    mounted() {
        this.getReport();
    },
}
</script>

<style scoped lang="scss">
    .report {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-family: 'Poppins', sans-serif;

        .total {
            width: 65%;

            @media (max-width: 768px) {
                width: 90%;
            }

            .menu_report {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;

                h3 {
                    font-size: 2rem;
                    font-weight: 500;
                    color: #2c3e50;

                    @media (max-width: 768px) {
                        font-size: 1.5rem;
                    }
                }

                .buttons {
                    display: flex;
                    flex-direction: row;

                    @media screen and (max-width: 570px) {
                        flex-direction: column;
                    }

                    .btn {
                        &:first-child {
                            margin-right: 1rem;
                        }

                        &:last-child {
                            background-color: red;
                        }

                        @media screen and (max-width: 570px) {
                            width: 9.5rem;
                            display: flex;
                            font-size: 1rem;
                            flex-direction: column;
                            align-items: center;
                            margin-bottom: 0.3rem;
                            margin-right: 0;
                        }
                    }
                }
            }

            .header {
                margin-bottom: 1rem;

                .title {
                    font-size: 2.2rem;
                    margin-bottom: 0.3rem;

                    @media (max-width: 576px) {
                        font-size: 1.5rem;
                    }
                }

                .head {
                    .label {
                        font-size: 1.5rem;
                        color: #595959;
                        margin-bottom: 0.1rem;

                        @media (max-width: 576px) {
                            font-size: 1rem;
                        }
                    }
                }
            }

            .body {
                padding-bottom: 2rem;

                p {
                    font-size: 1.5rem;
                    margin-bottom: 0.1rem;
                    text-align: justify;
                    text-indent: 2rem;
                    word-break: break-word;

                    @media (max-width: 576px) {
                        font-size: 1.2rem;
                    }
                }
            }
        }
    }
</style>

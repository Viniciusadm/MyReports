<template>
    <div class="report">
        <ReportModal
            v-if="modal"
            @close="modal = false"
            @save="save()"
            :id="id"/>

        <div class="total">
            <div class="menu_report">
                <h3>Relato Nº {{ id }}</h3>
                <button @click="modal = true;" class="btn">Editar relato</button>
            </div>

            <div class="header">
                <h1 class="title">{{ report.title }}</h1>
                <div class="head">
                    <p class="label">Data do relato: {{ date }}</p>
                    <p class="label">Hora do relato: {{ hour }}</p>
                    <p v-if="report.created_at !== report.updated_at" class="label">Última atualização: {{ report.updated_at }}</p>
                    <p class="label">Humor: {{ humor }}</p>
                    <p class="label">Tipo: {{ type }}</p>
                    <p class="label">Participantes: {{ participants }}</p>
                </div>
            </div>
            <div class="body">
                <p class="content">
                    {{ report.report }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import api from "@/services/api";
import ReportModal from "@/components/Reports/ReportModal";

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
        }
    },
    components: {
        ReportModal,
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
                return this.report.participants.map(participant => participant.person.name).join(', ');
            } else {
                return 'Nenhum participante';
            }
        },
    },
    methods: {
        getReport() {
            api.get(`/reports/${this.id}`)
                .then(response => {
                    this.report = response.data.data;
                });
        },
        save() {
            this.modal = false;
            this.getReport();
        }
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

                .btn {
                    @media screen and (max-width: 570px) {
                        font-size: 1rem;
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
                .content {
                    font-size: 1.5rem;
                    margin-bottom: 2rem;
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

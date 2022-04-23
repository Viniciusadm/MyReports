<template>
    <div class="reports">
        <div class="header">
            <h1>Relatos</h1>
            <router-link to="/reports/new" class="btn">Novo relato</router-link>
        </div>
        <div class="filters">
            <div class="filter">
                <label>
                    <span>Procurar</span>
                    <input type="text" @input="setQuery($event)" />
                </label>
            </div>
        </div>
        <template v-if="reports.length > 0 && !carregando">
            <div class="reports-list">
                <report v-for="report in reports" :report="report" :key="report.id" />
            </div>
            <p class="paginas" v-if="total > 12">
                <button @click="backPage()" :disabled="filters.page === 1">
                    Anterior
                </button>
                <span>Página {{ filters.page }} de {{ last_page }}</span>
                <button
                    @click="nextPage()"
                    :disabled="filters.page === last_page">
                    Próxima
                </button>
            </p>
        </template>
        <p v-else-if="reports.length === 0 && !carregando" class="no-reports">Não há relatos com esses filtros</p>
        <div class="loading" v-else-if="carregando">
            <loading />
        </div>
    </div>
</template>

<script>
import api from "@/services/api";
import report from "@/components/Reports/Report";
import loading from "@/components/Loading";
import { useToast } from "vue-toastification";

const toast = useToast();

export default {
    data() {
        return {
            modal: false,
            debounce: null,
            reports: [],
            total: 0,
            last_page: 0,
            filters: {
                page: 1,
            },
            carregando: false,
        };
    },
    components: {
        report,
        loading,
    },
    methods: {
        save() {
            this.modal = false;
            this.getReports();
        },
        getReports() {
            this.carregando = true;
            api.get(this.url)
                .then(response => {
                    if (response.data.success) {
                        this.reports = response.data.data.data;
                        this.last_page = response.data.data.last_page;
                        this.total = response.data.data.total;
                    }
                })
                .catch(error => {
                    toast.error(error.response.data.message);
                })
                .finally(() => {
                    this.carregando = false;
                });
        },
        backPage() {
            clearTimeout(this.debounce);

            if (this.filters.page > 1) {
                this.filters.page--;

                this.debounce = setTimeout(() => {
                    this.getReports();
                }, 500);
            }
        },
        nextPage() {
            clearTimeout(this.debounce);
            if (this.filters.page < this.last_page) {
                this.filters.page++;

                this.debounce = setTimeout(() => {
                    this.getReports();
                }, 500);
            }
        },
        setQuery($event) {
            clearTimeout(this.debounce);

            this.$router.push({
                query: {
                    q: $event.target.value,
                    person: this.person,
                }
            });

            this.debounce = setTimeout(() => {
                this.filters.page = 1;
                this.getReports();
            }, 900);
        }
    },
    computed: {
        url() {
            let url = `reports?page=${this.filters.page}`;

            if (this.q) {
                url += `&q=${this.q}`;
            }

            if (this.person) {
                url += `&person=${this.person}`;
            }

            return url;
        },
        q() {
            return this.$route.query.q;
        },
        person() {
            return this.$route.query.person;
        }
    },
    mounted() {
        this.getReports();
    },
};
</script>

<style lang="scss" scoped>
    .reports {
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
            justify-content: space-between;
            width: 90%;
            margin-bottom: 1.25rem;

            .filter {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                margin-bottom: 1.25rem;

                label {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                    width: 100%;
                    margin-bottom: 1.25rem;

                    span {
                        font-size: 1.25em;
                        font-weight: bold;
                        color: #333;
                    }

                    input {
                        width: 100%;
                        font-size: 1.25em;
                        font-weight: bold;
                        color: #333;
                        border: none;
                        border-bottom: 1px solid #333;
                        outline: none;
                        background: transparent;
                        padding: 0.5rem;
                    }
                }
            }
        }

        .reports-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            grid-gap: 1.25rem;
            margin-bottom: 1.5rem;


            @media screen and (max-width: 1600px) {
                width: 90%;
            }
        }

        .paginas {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            width: 80%;

            span {
                width: 8rem;
                text-align: center;
            }

            button {
                padding: 0.5rem 0.8rem;
                border: 1px solid #ccc;
                border-radius: 0.25rem;
                background: #4e73df;
                color: #fff;
                cursor: pointer;

                &:disabled {
                    background: #ccc;
                    color: #fff;
                    cursor: not-allowed;
                }
            }
        }

        .no-reports {
            font-size: 2rem;

            @media screen and (max-width: 570px) {
                font-size: 1.4rem;
            }
        }
    }

    .loading {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        position: fixed;
        top: 0;
        left: 0;
        color: #fff;
        font-size: 2rem;

        @media screen and (max-width: 570px) {
            font-size: 1.4rem;
        }
    }
</style>

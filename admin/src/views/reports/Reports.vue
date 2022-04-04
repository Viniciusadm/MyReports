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
        <template v-if="reports.length > 0">
            <div class="reports-list">
                <Report v-for="report in reports" :report="report" :key="report.id" />
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
        <p v-else class="no-reports">Não há relatos com esses filtros</p>
    </div>
</template>

<script>
import api from "@/services/api";
import Report from "@/components/Reports/Report";
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
            }
        };
    },
    components: {
        Report,
    },
    methods: {
        save() {
            this.modal = false;
            this.getReports();
        },
        getReports() {
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
                });
        },
        backPage() {
            if (this.filters.page > 1) {
                this.filters.page--;
                this.getReports();
            }
        },
        nextPage() {
            if (this.filters.page < this.last_page) {
                this.filters.page++;
                this.getReports();
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
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-evenly;
            width: 90%;

            @media screen and (max-width: 570px) {
                font-size: 1rem;
                margin-bottom: 1rem;
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
</style>

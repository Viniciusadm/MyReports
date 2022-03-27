<template>
    <div class="reports">
        <ReportModal
            v-if="modal"
            @close="modal = false"
            @save="save()"/>

        <div class="header">
            <h1>Relatos</h1>
            <button @click="modal = true;" class="btn">Novo relato</button>
        </div>

        <div class="reports-list">
            <Report v-for="report in reports" :report="report" :key="report.id" />
        </div>
        <p class="paginas">
            <button @click="backPage()" :disabled="page === 1">
                Anterior
            </button>
            <span>Página {{ page }} de {{ last_page }}</span>
            <button
                @click="nextPage()"
                :disabled="page === last_page">
                Próxima
            </button>
        </p>
    </div>
</template>

<script>
import ReportModal from "@/components/Reports/NewReportModal";
import api from "@/services/api";
import Report from "@/components/Reports/Report";

export default {
    data() {
        return {
            modal: false,
            reports: [],
            page: 1,
            last_page: 1,
            limit: 10,
            total: 0,
            to: 0,
            from: 0,
        };
    },
    components: {
        ReportModal,
        Report,
    },
    methods: {
        save() {
            this.modal = false;
            this.getReports();
        },
        getReports() {
            api.get(`/reports/`)
                .then(response => {
                    if (response.data.success) {
                        this.reports = response.data.data.data;
                        this.last_page = response.data.data.last_page;
                        this.total = response.data.data.total;
                        this.from = response.data.data.from;
                        this.to = response.data.data.to;
                    }
                });
        },
        backPage() {
            if (this.page > 1) {
                this.page--;
                this.getReports();
            }
        },
        nextPage() {
            if (this.page < this.last_page) {
                this.page++;
                this.getReports();
            }
        },
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

        .reports-list {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-evenly;
            width: 90%;
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
    }
</style>

<template>
    <div class="reports">
        <ReportModal
            v-if="modal"
            @fecharModal="modal = false"
            @save="save()"/>

        <Report
            v-if="reportModal"
            :report="report"
            @close="reportModal = false;"/>

        <div class="header">
            <h1 @click="this.$emit('reportModal')">Relatos</h1>
            <button @click="modal = true;" class="btn">Novo relato</button>
        </div>
        <Tabela class="table" :search="search" :table="table" @reportModal="openDetails($event)" />
    </div>
</template>

<script>
import ReportModal from "@/components/Reports/NewReportModal";
import Report from "@/components/Reports/ReportModal";
import Tabela from "@/components/Tabela";

export default {
    data() {
        return {
            modal: false,
            reportModal: false,
            search: "",
            report: {},
            table: {
                name: "Reports",
                column_default: 'created_at',
                order: 'desc',
                columns: [
                    {
                        name: "Título",
                        field: "title",
                        maxLength: Math.ceil(window.innerWidth / 40),
                        type: 'emit',
                        emit: 'reportModal',
                    },
                    {
                        name: "Relato",
                        field: "report",
                        maxLength: Math.ceil(window.innerWidth / 40),
                    },
                    {
                        name: "Humor",
                        field: "humor",
                        format: (humor) => {
                            return this.$store.state.humors[humor];
                        },
                    },
                    {
                        name: 'Tipo',
                        field: 'type',
                        format: (type) => {
                            return type === 'daily' ? 'Diário' : 'Pessoal';
                        },
                    },
                    {
                        name: 'Pessoas',
                        field: 'persons_ids',
                        format: (persons_ids) => {
                            return this.getPeople(persons_ids);
                        },
                        maxLength: Math.ceil(window.innerWidth / 40),
                    },
                    {
                        name: 'Data',
                        field: 'created_at',
                        format: (created_at) => {
                            return created_at.split('T')[0].split('-').reverse().join('/')
                        },
                    }
                ],
            },
        };
    },
    components: {
        Tabela,
        ReportModal,
        Report,
    },
    methods: {
        getPeople(persons_ids) {
            let people = [];
            JSON.parse(persons_ids).forEach(id => {
                people.push(this.$store.state.people[id - 1].name);
            });
            return people.length > 0 ? people.join(', ') : 'Nenhum';
        },
        save() {
            this.modal = false;
            window.location.reload();
        },
        openDetails(report) {
            this.reportModal = true;
            this.report = report;
        },
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

        h1 {
            font-size: 2em;
            font-weight: bold;
            color: #333;
        }
    }

    .table {
        width: 90%;
    }
}
</style>

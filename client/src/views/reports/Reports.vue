<template>
    <div class="reports">
        <div class="header">
            <h1>Relatórios</h1>
            <router-link to="/reports/new" class="btn_new">Novo relatório</router-link>
        </div>
        <Report
        v-for="report in reports"
        :key="report.id"
        :title="report.title"
        :report="report.report"
        :humor="report.humor"
        :date="report.created_at"
        :type="report.type"
        :people="report.people"
        ></Report>
    </div>
</template>

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

            .btn_new {
                background: #00bcd4;
                color: #fff;
                border: none;
                padding: 0.62rem 1.25rem;
                border-radius: 0.3rem;
                font-size: 1.3rem;
                font-weight: bold;
                cursor: pointer;
                text-decoration: none;

                &:hover {
                    background: #00acc1;
                }
            }
        }
    }
</style>

<script>
import Report from '../../components/Report.vue';
import api from '../../services/api';

export default {
    components: {
        Report
    },
    data() {
        return {
            reports: []
        }
    },
    methods: {
        getReports() {
            api.get('/reports')
            .then(response => {
                if (response.status === 200) {
                    this.reports = response.data;
                } else {
                    console.log(response.data.message);
                }
            })
        }
    },
    mounted() {
        this.getReports()
    }
}
</script>
<template>
    <div class="reports">
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
                    this.reports = response.data.data;
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
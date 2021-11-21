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
        :participants="report.participant"
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
import Report from '../components/Report.vue'

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
            fetch('http://localhost:8000/api/reports')
                .then(response => response.json())
                .then(json => {
                    this.reports = json
                })
        }
    },
    mounted() {
        this.getReports()
    }
}
</script>
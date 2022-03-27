<template>
    <div class="report">
        <div class="header">
            <h1 class="title">{{ report.title }}</h1>
        </div>
    </div>
</template>

<script>
import api from "@/services/api";

export default {
    data() {
        return {
            id: this.$route.params.id,
            report: {
                type: Object,
                required: true,
                default: () => ({
                    'title': '',
                    'report': '',
                    'type': '',
                    'humor': '',
                    'created_at': '',
                })
            },
        }
    },
    computed: {
        date() {
            return this.report.created_at.split('T')[0].split('-').reverse().join('/');
        },
        humor() {
            return this.$store.state.humors[this.report.humor];
        },
        type() {
            return this.report.type === 'personal' ? 'Pessoal' : 'Diário';
        },
    },
    methods: {
        getReport() {
            api.get(`/reports/${this.id}`)
                .then(response => {
                    this.report = response.data.data;
                });
        },
    },
    mounted() {
        this.getReport();
    },
}
</script>

<style scoped lang="scss">
    .report {

    }
</style>

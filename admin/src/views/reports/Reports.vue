<template>
    <div class="reports">
        <div class="header">
            <h1>Relatórios</h1>
            <router-link to="/reports/new" class="btn">Novo relatório</router-link>
        </div>
        <Tabela />
    </div>
</template>

<script>
import Tabela from "@/components/Tabela";

export default {
    data() {
        return {
            search: '',
            table: {
                name: 'Reports',
                search: '',
                columns: [{
                    name: 'Título',
                    field: 'title',
                }],
            },
        }
    },
    components: {
        Tabela
    }
}
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
    }
</style>
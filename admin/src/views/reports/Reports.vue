<template>
    <div class="reports">
        <div class="header">
            <h1>Relatórios</h1>
            <router-link to="/reports/new" class="btn">Novo relatório</router-link>
        </div>
        <Tabela class="table" :search="search" :table="table" />
    </div>
</template>

<script>
import Tabela from "@/components/Tabela";

export default {
    data() {
        return {
            search: "",
            table: {
                name: "Reports",
                column_default: 'created_at',
                order: 'desc',
                columns: [
                    {
                        name: "Título",
                        field: "title",
                    },
                    {
                        name: "Relatório",
                        field: "report",
                        maxLength: Math.ceil(window.innerWidth / 70),
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
    },
    methods: {
        getPeople(persons_ids) {
            let people = [];
            JSON.parse(persons_ids).forEach(id => {
                people.push(this.$store.state.people[id - 1].name);
            });
            return people.length > 0 ? people.join(', ') : 'Nenhum';
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

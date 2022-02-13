<template>
    <div id="tabela">
        <label for="limit">
            Mostrar
            <select id="limit" v-model="limit">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="75">75</option>
                <option value="100">100</option>
            </select>
            entradas
        </label>
        <table id="table">
            <thead>
                <tr>
                    <th :class="{ active: column === column_loop.field }"
                        @click="reordernarTabela(`${column_loop.field}`)"
                        v-for="(column_loop, index) in table.columns"
                        :key="index">
                        {{ column_loop.name }}
                    </th>
                </tr>
            </thead>
            <tbody v-if="data.length > 0">
                <tr v-for="value in data" :key="value.id">
                    <td v-for="(item, index) in value" :key="index">
                        <template v-if="getColumn(index).type === 'link'">
                            <a :href="getColumn(index).link(item)"
                                target="_blank">
                                {{ getFormat(index, item) }}</a>
                        </template>
                        <template v-else>
                            {{ getFormat(index, item) }}
                        </template>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td class="no_data" :colspan="table.columns.length">
                        Nenhum
                        {{
                            table.name_singular ??
                            table.name.toLowerCase().slice(0, -1)
                        }}
                        encontrado
                    </td>
                </tr>
            </tbody>
        </table>
        <div id="table_pagination">
            <p class="entradas">
                Mostrando de {{ from }} a {{ to }} de {{ total }} entradas
            </p>
            <p class="paginas">
                <button @click="backPage()" :disabled="current_page === 1">
                    Anterior
                </button>
                <span>Página {{ current_page }} de {{ last_page }}</span>
                <button
                    @click="nextPage()"
                    :disabled="current_page === last_page">
                    Próxima
                </button>
            </p>
        </div>
    </div>
</template>

<script>
import api from "@/services/api";
import { useToast } from "vue-toastification";

const toast = useToast();

export default {
    data() {
        return {
            current_page: 1,
            last_page: 1,
            limit: 10,
            total: 0,
            to: 0,
            from: 0,
            column: this.table.column_default ?? this.table.columns[0].field,
            order: this.table.order ?? 'asc',
            data: [],
        };
    },
    computed: {
        fields() {
            return this.table.columns.map((column) => column.field);
        },
    },
    props: {
        search: {
            type: String,
            default: "",
        },
        date: {
            type: Object,
            default: () => ({
                start: "",
                end: "",
            }),
        },
        table: {
            type: Object,
            default: () => ({
                name: "",
                name_singular: "",
                column_default: "",
                order: "asc",
                columns: [
                    {
                        name: "",
                        field: "",
                        type: "text",
                        link: () => "",
                        format: () => "",
                    },
                ],
            }),
        },
    },
    methods: {
        searchInDatabase() {
            const url = this.montarUrl();
            api.get(url)
                .then((response) => {
                    this.data = response.data.data.data;
                    this.last_page = response.data.data.last_page;
                    this.total = response.data.data.total;
                    this.from = response.data.data.from;
                    this.to = response.data.data.to;
                })
                .catch((error) => {
                    toast.error(error.response.data.message);
                });
        },
        backPage() {
            if (this.current_page > 1) {
                this.current_page--;
                this.searchInDatabase();
            }
        },
        nextPage() {
            if (this.current_page < this.last_page) {
                this.current_page++;
                this.searchInDatabase();
            }
        },
        reordernarTabela(column) {
            if (this.column === column) {
                this.order = this.order === "asc" ? "desc" : "asc";
            } else {
                this.column = column;
                this.order = "asc";
            }
            this.searchInDatabase();
        },
        getColumn(column) {
            return this.table.columns.find((column_loop) => {
                if (column_loop.field.indexOf(".") !== -1) {
                    return column_loop.field.split(".")[1] === column;
                } else {
                    return column_loop.field === column;
                }
            });
        },
        montarUrl() {
            let url = `/${this.table.name.toLowerCase()}/search?fields=${
                this.fields
            }&current_page=${this.current_page}&limit=${this.limit}&column=${
                this.column
            }&order=${this.order}`;

            if (this.search) {
                url += `&q=${this.search}`;
            } else if (this.date.start && this.date.end) {
                url += `&start=${this.date.start}&end=${this.date.end}`;
            }
            return url;
        },
        getFormat(column, value) {
            const column_format = this.getColumn(column);

            if (column_format.maxLength) {
                value = value.length > column_format.maxLength
                    ? value.substring(0, column_format.maxLength) + '...'
                    : value;
            }

            if (column_format.format) {
                value = column_format.format(value);
            }

            return value;
        },
    },
    watch: {
        search() {
            this.current_page = 1;
            this.searchInDatabase();
        },
        limit() {
            this.current_page = 1;
            this.searchInDatabase();
        },
        date: {
            handler() {
                this.current_page = 1;
                this.searchInDatabase();
            },
            deep: true,
        },
    },
    mounted() {
        this.searchInDatabase();
    },
};
</script>

<style scoped lang="scss">
#tabela {
    padding: 0 0.85rem;
    box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.2);

    label {
        padding: 1rem 0.6rem;
        display: block;
        color: #565656;

        select {
            padding: 0.2rem 0.4rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            background: #fff;
            cursor: pointer;
        }
    }

    #table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        border-spacing: 0;

        th,
        td {
            padding: 1rem;
            border: 1px solid #ddd;
            color: #565656;
            width: 100%;
            word-break: break-all;
            cursor: pointer;

            @media screen and (max-width: 1200px) {
                padding: 0.9rem;
                font-size: 0.9rem;
            }

            @media screen and (max-width: 800px) {
                padding: 0.8rem;
                font-size: 0.8rem;
            }

            @media screen and (max-width: 400px) {
                padding: 0.7rem;
                font-size: 0.7rem;
            }
        }

        th {
            text-align: left;
            user-select: none;

            &.active {
                text-decoration: underline;
            }
        }

        td {
            a {
                color: #565656;
                text-decoration: none;

                &:hover {
                    text-decoration: underline;
                }
            }

            &:hover {
                background-color: #f5f5f5;
            }

            &.no_data {
                &:hover {
                    background-color: transparent;
                }
            }
        }
    }

    #table_pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0.15rem;
        font-size: 0.8rem;
        color: #565656;

        .paginas {
            display: flex;
            justify-content: space-between;
            align-items: center;

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
}
</style>

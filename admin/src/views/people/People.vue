<template>
    <div class="people" v-if="!carregando">
        <div class="header">
            <h1>Pessoas</h1>
            <router-link to="/people/new" class="btn">Nova pessoa</router-link>
        </div>
        <template v-if="people.length > 0">
            <div class="people-list">
                <router-link v-for="person in people" :key="person.id" :to="`/person/${person.id}`" class="title">{{ person.name }}</router-link>
            </div>
        </template>
        <p v-else class="no-people">Não há pessoas cadastradas.</p>
    </div>
    <loading v-else-if="carregando" />
</template>

<script>
import api from "@/services/api";
import { useToast} from "vue-toastification";
import loading from "@/components/Loading";

const toast = useToast();

export default {
    data() {
        return {
            modal: false,
            people: [],
            carregando: true,
        };
    },
    components: {
        loading,
    },
    methods: {
        getPeople() {
            api.get("/people")
                .then(response => {
                    if (response.data.success) {
                        this.people = response.data.data;
                    } else {
                        toast.error(response.data.message);
                    }
                })
                .catch(error => {
                    toast.error(error.response.data.message);
                })
                .finally(() => {
                    this.carregando = false;
                });
        },
        save() {
            this.modal = false;
            this.getPeople();
        },
    },
    mounted() {
        this.getPeople();
    }
}

</script>

<style lang="scss" scoped>
    .people {
        display: flex;
        flex-direction: column;
        align-items: center;

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

        .people-list {
            display: flex;
            flex-direction: column;

            a {
                color: black;
                text-decoration: none;
                font-size: 1.4rem;
            }
        }

        .no-people {
            font-size: 2rem;
            text-align: center;
        }
    }
</style>

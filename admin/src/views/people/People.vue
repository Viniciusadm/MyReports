<template>
    <div class="people">
        <PersonModal
            v-if="modal"
            @close="modal = false"
            :id="id"
            @save="save()">
        </PersonModal>
        <div class="header">
            <h1>Pessoas</h1>
            <button @click="modal = true;" class="btn">Nova pessoa</button>
        </div>
        <template v-if="people.length > 0">
            <div class="people-list">
                <p @click="openModal(person.id)" v-for="person in people" :key="person.id">{{ person.name }}</p>
            </div>
        </template>
        <p v-else class="no-people">Não há pessoas cadastradas.</p>
    </div>
</template>

<script>
import PersonModal from "@/components/People/PersonModal";
import api from "@/services/api";
import { useToast} from "vue-toastification";

const toast = useToast();

export default {
    data() {
        return {
            modal: false,
            people: [],
            id: null,
        }
    },
    components: {
        PersonModal
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
                    toast.error(error.message);
                });
        },
        save() {
            this.modal = false;
            this.getPeople();
            this.id = null;
        },
        openModal(id) {
            this.id = id;
            this.modal = true;
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

        .no-people {
            font-size: 2rem;
            text-align: center;
        }
    }
</style>

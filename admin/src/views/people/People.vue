<template>
    <div class="people">
        <PersonModal
            v-if="modal"
            @close="modal = false"
            @save="save()"/>

        <div class="header">
            <h1>Pessoas</h1>
            <button @click="modal = true;" class="btn">Nova pessoa</button>
        </div>

        <div class="people-list">
            <p v-for="person in people" :key="person.id">{{ person.name }}</p>
        </div>
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
        }
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
    }
</style>

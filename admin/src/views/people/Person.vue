<template>
    <div class="person-page">
        <PersonModal
            v-if="modal"
            @close="modal = false;"
            :id="id"
            @save="save()">
        </PersonModal>

        <div class="total">
            <div class="menu_person">
                <p class="name">{{ person.name }}</p>
                <div class="buttons">
                    <button @click="modal = true" class="btn">Editar</button>
                    <button class="btn">Desativar</button>
                </div>
            </div>

            <div class="person">
                <div class="row">
                    <div class="column image">
                        <img v-if="person.image" class="foto" alt="foto da pessoa" :src="person.image">
                        <BIconPerson v-else class="foto" />
                    </div>
                    <div class="column infos">
                        <p class="info">Nome: <router-link :to="`/reports?person=${id}`">{{ person.name }}</router-link> </p>
                        <p v-if="person.nickname" class="info">Apelido: {{ person.nickname ?? 'Sem apelido' }}</p>
                        <p v-if="person.email" class="info">Email: <a target="_blank" :href="`mailto:${person.email}`">{{ person.email }}</a></p>
                        <p v-if="person.phone" class="info">Telefone: <a target="_blank" :href="`https://wa.me/55${person.phone}`">{{ person.phone }}</a></p>
                        <p v-if="person.birth_date" class="info">Data de nascimento: {{ date }}</p>
                        <p v-if="age" class="info">Idade: {{ age }}</p>
                        <p v-if="person.address" class="info">Endereço: {{ person.address }}</p>
                        <p v-if="person.twitter" class="info">Twitter: <a target="_blank" :href="`https://twitter.com/${person.twitter}`">{{ person.twitter }}</a></p>
                        <p v-if="person.instagram" class="info">Instagram: <a target="_blank" :href="`https://instagram.com/${person.instagram}`">{{ person.instagram }}</a></p>
                    </div>
                </div>
                <div class="row" v-if="person.description">
                    <div class="column content">
                        <p class="description"><span>Descrição:</span> {{ person.description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from "@/services/api";
import { useToast } from "vue-toastification";
import PersonModal from "@/components/People/PersonModal";
import { BIconPerson } from "bootstrap-icons-vue";

const toast = useToast();

export default {
    data() {
        return {
            id: this.$route.params.id,
            modal: false,
            person: {
                name: "",
                nickname: "",
                birth_date: "",
                email: "",
                phone: "",
                address: "",
                description: "",
                image: "",
                twitter: "",
                instagram: "",
            }
        };
    },
    methods: {
        getPerson() {
            api.get(`/people/${this.id}`)
                .then(response => {
                    if (response.data.success) {
                        this.setPerson(response.data.data);
                    } else {
                        toast.error(response.data.message);
                    }
                })
                .catch(error => {
                    toast.error(error.message);
                });
        },
        setPerson(person) {
            this.person = {
                name: person.name,
                nickname: person.nickname,
                birth_date: person.birth_date,
                email: person.email,
                phone: person.phone,
                address: person.address,
                description: person.description,
                twitter: person.twitter,
                instagram: person.instagram,
                image: person.image,
            }
        },
        save() {
            this.modal = false;
            this.getPerson();
        }
    },
    computed: {
        date() {
            return this.person.birth_date.split("-").reverse().join("/");
        },
        age() {
            if (this.person.birth_date) {
                const today = new Date();
                const birthDate = new Date(this.person.birth_date);
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            } else {
                return null;
            }
        },
    },
    components: {
        PersonModal,
        BIconPerson,
    },
    created() {
        this.getPerson();
    }
}
</script>

<style lang="scss" scoped>
    .person-page {
        display: flex;
        flex-direction: column;
        align-items: center;

        .total {
            width: 65%;

            @media (max-width: 768px) {
                width: 90%;
            }

            .menu_person {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;

                .name {
                    font-size: 2rem;
                    font-weight: 500;
                    color: #2c3e50;

                    @media (max-width: 768px) {
                        font-size: 1.5rem;
                    }

                    @media (max-width: 425px) {
                        font-size: 1.2rem;
                    }
                }

                .buttons {
                    display: flex;
                    flex-direction: row;

                    @media screen and (max-width: 570px) {
                        flex-direction: column;
                    }

                    .btn {
                        &:first-child {
                            margin-right: 1rem;
                        }

                        &:last-child {
                            background-color: red;
                        }

                        @media screen and (max-width: 570px) {
                            width: 8rem;
                            display: flex;
                            font-size: 1rem;
                            flex-direction: column;
                            align-items: center;
                            margin-bottom: 0.3rem;
                            margin-right: 0;
                        }

                        @media (max-width: 425px) {
                            width: 6.5rem;
                        }
                    }
                }
            }

            .person {
                .row {
                    display: flex;

                    @media screen and (max-width: 425px) {
                        flex-direction: column;
                    }

                    .column {
                        width: 100%;
                        height: 100%;

                        &.image {
                            display: flex;
                            justify-content: center;

                            .foto {
                                width: 80%;
                                height: auto;
                                max-width: 80%;
                                max-height: 80%;
                                margin-bottom: 1rem;
                                border: 1px solid #2c3e50;
                                border-radius: 30%;
                            }
                        }

                        &.infos {
                            .info {
                                font-size: 1.5rem;

                                @media screen and (max-width: 425px) {
                                    font-size: 1.2rem;
                                }

                                a {
                                    color: #2c3e50;
                                    font-size: 1.5rem;
                                    text-decoration: none;

                                    @media screen and (max-width: 425px) {
                                        font-size: 1.2rem;
                                    }

                                    &:hover {
                                        text-decoration: underline;
                                    }
                                }
                            }
                        }

                        &.content {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;

                            .description {
                                font-size: 1.5rem;
                                margin-top: 1rem;
                                width: 80%;

                                span {
                                    font-size: 1.6rem;
                                    font-weight: bold;
                                }

                                @media screen and (max-width: 425px) {
                                    font-size: 1.2rem;
                                    width: 100%;

                                    span {
                                        font-size: 1.4rem;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
</style>

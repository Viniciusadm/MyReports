<template>
    <BaseModal :scroll="true">
        <div class="person_modal">
            <h1 class="title_page">Nova pessoa</h1>
            <div class="form_report">
                <div class="form_group photo">
                    <label class="form_label" for="photo">Foto</label>
                    <img :src="person.image.url" alt="foto da pessoa" v-if="person.image.url" class="person_image">
                    <BIconFileEarmarkArrowUp class="icon_file" v-else></BIconFileEarmarkArrowUp>
                    <input @change="onFileChange" class="input_file" type="file" id="photo" placeholder="Foto">
                </div>
                <div class="form_group">
                    <label class="form_label" for="name">Nome</label>
                    <input v-model="person.name" class="input_text" type="text" id="name" placeholder="Nome">
                </div>
                <div class="form_group">
                    <label class="form_label" for="email">E-mail</label>
                    <input v-model="person.email" class="input_text" type="email" id="email" placeholder="E-mail">
                </div>
                <div class="form_group">
                    <label class="form_label" for="phone">Telefone</label>
                    <input v-model="person.phone" class="input_text" type="text" id="phone" placeholder="Telefone">
                </div>
                <div class="form_group">
                    <label class="form_label">Data de nascimento</label>
                    <div class="date">
                        <select v-model="person.birth_date.day" class="input_select">
                            <option value="">Selecione</option>
                            <option v-for="day in 31" :value="day" :key="day">{{ day }}</option>
                        </select>
                        <select v-model="person.birth_date.month" class="input_select">
                            <option value="">Selecione</option>
                            <option v-for="month in 12" :value="month" :key="month">{{ month }}</option>
                        </select>
                        <select v-model="person.birth_date.year" class="input_select">
                            <option value="">Selecione</option>
                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                    </div>
                </div>
                <div class="form_group">
                    <label class="form_label" for="address">Endereço</label>
                    <input v-model="person.address" class="input_text" type="text" id="address" placeholder="Endereço">
                </div>
                <div class="form_group">
                    <label class="form_label" for="description">Descrição</label>
                    <textarea v-model="person.description" class="input_text" id="description" placeholder="Descrição"></textarea>
                </div>
                <div class="form_group social_medias">
                    <label class="form_label">Redes sociais</label>
                    <div class="medias">
                        <div class="social_media">
                            <label class="form_label" for="instagram">Instagram</label>
                            <input v-model="person.instagram" class="input_text" type="text" id="instagram" placeholder="Instagram">
                        </div>
                        <div class="social_media">
                            <label class="form_label" for="twitter">Twitter</label>
                            <input v-model="person.twitter" class="input_text" type="text" id="twitter" placeholder="Twitter">
                        </div>
                    </div>
                </div>
                <div class="form_group buttons">
                    <button @click="$emit('close')" class="btn close_report">Fechar</button>
                    <button @click="sendPerson()" class="btn send_report">Criar</button>
                </div>
            </div>
        </div>
    </BaseModal>
</template>

<script>
import BaseModal from "@/components/BaseModal";
import { BIconFileEarmarkArrowUp } from "bootstrap-icons-vue";
import { useToast } from "vue-toastification";
import api from "@/services/api";

const toast = useToast();

export default {
    data() {
        return {
            formData: new FormData(),
            person: {
                name: "",
                birth_date: {
                    day: "",
                    month: "",
                    year: ""
                },
                email: "",
                phone: "",
                address: "",
                description: "",
                image: {
                    url: "",
                    file: null
                },
                twitter: "",
                instagram: "",
            }
        }
    },
    props: {
        id: {
            type: Number,
            required: false
        },
    },
    methods: {
        onFileChange(e) {
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
                this.person.image.url = e.target.result;
                this.person.image.file = file;
            }
            reader.readAsDataURL(file);
        },
        setFormData() {
            this.formData.append("name", this.person.name);
            this.formData.append("email", this.person.email);
            this.formData.append("phone", this.person.phone);
            this.formData.append("address", this.person.address);
            this.formData.append("description", this.person.description);
            this.formData.append("twitter", this.person.twitter);
            this.formData.append("instagram", this.person.instagram);
            this.formData.append("image", this.person.image.file);

            if (this.person.birth_date.day && this.person.birth_date.month && this.person.birth_date.year) {
                this.formData.append("birth_date", this.person.birth_date.year + "-" + this.person.birth_date.month + "-" + this.person.birth_date.day);
            }
        },
        sendPerson() {
            if (this.person.name === '') {
                toast.error('Preencha o nome da pessoa!');
                return false;
            }

            this.setFormData();

            if (this.id) {
                this.updatePerson();
            } else {
                this.createPerson();
            }
        },
        createPerson() {
            api.post("/people", this.formData)
                .then(response => {
                    if (response.data.success) {
                        toast.success('Pessoa criada com sucesso!');
                        this.$emit("save");
                    } else {
                        toast.error(response.data.message);
                    }
                })
                .catch(error => {
                    toast.error(error.message);
                });
        },
        updatePerson() {
            api.post(`/people/${this.id}`, this.formData)
                .then(response => {
                    if (response.data.success) {
                        toast.success('Pessoa atualizada com sucesso!');
                        this.$emit("save");
                    } else {
                        toast.error(response.data.message);
                    }
                })
                .catch(error => {
                    toast.error(error.message);
                });
        },
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
                email: person.email,
                phone: person.phone,
                address: person.address,
                description: person.description,
                twitter: person.twitter,
                instagram: person.instagram,
                image: {
                    url: person.image,
                    file: null
                }
            }

            if (person.birth_date) {
                this.person.birth_date = {
                    day: person.birth_date.split("-")[2],
                    month: person.birth_date.split("-")[1],
                    year: person.birth_date.split("-")[0]
                }
            } else {
                this.person.birth_date = {
                    day: "",
                    month: "",
                    year: ""
                }
            }
        },
    },
    components: {
        BaseModal,
        BIconFileEarmarkArrowUp,
    },
    computed: {
        years() {
            let years = [];
            for (let i = new Date().getFullYear(); i >= 1949; i--) {
                years.push(i);
            }
            return years;
        }
    },
    mounted() {
        if (this.id) {
            this.getPerson();
        }
    }
}
</script>

<style scoped lang="scss">
    .person_modal {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        margin-bottom: 1.5rem;

        .title_page {
            font-size: 2rem;
            margin: 1rem 0;
            font-weight: bold;
        }

        .form_report {
            width: 80%;
            max-width: 600px;

            .form_group {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;

                &.photo {
                    .person_image, .icon_file {
                        width: 60%;
                        height: auto;
                        max-width: 60%;
                        max-height: 60%;
                        margin-bottom: 1rem;
                    }

                    .input_file {
                        display: none;
                    }
                }

                &.social_medias {
                    .medias {
                        width: 100%;
                        display: flex;

                        @media screen and (max-width: 600px) {
                            flex-direction: column;
                        }

                        .social_media {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            margin-bottom: 1rem;

                            @media screen and (max-width: 600px) {
                                margin-bottom: 0;
                                width: 100%;

                                .input_text {
                                    width: 100%;
                                }
                            }

                            &:first-child {
                                margin-right: 0.8rem;
                            }

                            .form_label {
                                margin-bottom: 0.5rem;
                            }
                        }
                    }
                }

                &.buttons {
                    display: flex;
                    flex-direction: row;

                    button {
                        flex: 1;
                        margin-right: 0.5rem;

                        &:nth-child(1) {
                            background-color: #b6b6b6;

                            &:hover {
                                background-color: #ccc;
                            }
                        }
                    }
                }

                .form_label {
                    font-size: 1.6rem;
                    margin-bottom: 1rem;
                    cursor: pointer;
                }

                .input_text {
                    width: 100%;
                    padding: 1rem;
                    border: 1px solid #ccc;
                    border-radius: 0.5rem;
                    margin-bottom: 0.8rem;
                    font-size: 1.1rem;

                    &#description {
                        resize: none;
                        height: 15rem;
                    }
                }

                .date {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: center;
                    width: 100%;

                    @media screen and (max-width: 460px) {
                        flex-direction: column;
                    }

                    select {
                        flex: 1;
                        padding: 0.5rem;
                        margin-right: 0.5rem;
                        border: 1px solid #ccc;
                        border-radius: 0.5rem;
                        margin-bottom: 0.8rem;
                        font-size: 1.1rem;
                        cursor: pointer;
                        background-color: transparent;

                        @media screen and (max-width: 460px) {
                            width: 100%;
                            margin-right: 0;
                        }

                            &:last-child {
                            margin-right: 0;
                        }
                    }
                }
            }
        }
    }
</style>

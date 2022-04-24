<template>
    <div v-if="!carregando" class="person_modal">
        <h1 class="title_page">{{ title }}</h1>
        <div class="form_report">
            <div class="form_group photo">
                <label class="form_label" for="photo">Foto</label>
                <img :src="image" alt="foto da pessoa" v-if="person.image.url" class="person_image">
                <BIconFileEarmarkArrowUp class="icon_file" v-else></BIconFileEarmarkArrowUp>
                <input @change="onFileChange" class="input_file" type="file" id="photo" placeholder="Foto">
            </div>
            <div class="form_group">
                <label class="form_label" for="name">Nome</label>
                <input v-model="person.name" class="input_text" type="text" id="name" placeholder="Nome">
            </div>
            <div class="form_group">
                <label class="form_label" for="nickname">Apelido</label>
                <input v-model="person.nickname" class="input_text" type="text" id="nickname" placeholder="Apelido">
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
            <button @click="sendPerson()" class="btn send_report">{{ button_text }}</button>
        </div>
    </div>
    <loading v-else-if="carregando" />
</template>

<script>
import { BIconFileEarmarkArrowUp } from "bootstrap-icons-vue";
import { useToast } from "vue-toastification";
import api from "@/services/api";
import loading from "@/components/Loading";

const toast = useToast();

export default {
    data() {
        return {
            formData: new FormData(),
            person: {
                name: "",
                nickname: "",
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
            },
            carregando: true,
        }
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
            if (this.person.name) this.formData.append("name", this.person.name);
            if (this.person.nickname) this.formData.append("nickname", this.person.nickname);
            if (this.person.email) this.formData.append("email", this.person.email);
            if (this.person.phone) this.formData.append("phone", this.person.phone);
            if (this.person.address) this.formData.append("address", this.person.address);
            if (this.person.description) this.formData.append("description", this.person.description);
            if (this.person.twitter) this.formData.append("twitter", this.person.twitter);
            if (this.person.instagram) this.formData.append("instagram", this.person.instagram);
            if (this.person.image.file) this.formData.append("image", this.person.image.url);

            if (this.person.birth_date.day && this.person.birth_date.month && this.person.birth_date.year) {
                this.formData.append("birth_date", this.person.birth_date.year + "-" + this.person.birth_date.month + "-" + this.person.birth_date.day);
            }
        },
        sendPerson() {
            if (this.person.name === '') {
                toast.error('Preencha o nome da pessoa!');
                return false;
            }

            this.sendImage();
        },
        createPerson() {
            this.carregando = true;
            api.post("/people", this.formData)
                .then(response => {
                    if (response.data.success) {
                        toast.success('Pessoa adicionada com sucesso!');
                        this.$router.push("/people");
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
        updatePerson() {
            this.carregando = true;
            api.post(`/people/${this.id}`, this.formData)
                .then(response => {
                    if (response.data.success) {
                        toast.success('Pessoa atualizada com sucesso!');
                        this.$router.push("/people");
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
        sendImage() {
            if (this.person.image.file) {
                let formData = new FormData();
                formData.append("image", this.person.image.file);
                api.post(process.env.VUE_APP_URL_IMAGES, formData)
                    .then(response => {
                        if (response.data.success) {
                            this.person.image.url = response.data.data;
                            this.setFormData();

                            if (this.id) {
                                this.updatePerson();
                            } else {
                                this.createPerson();
                            }
                        } else {
                            toast.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        toast.error(error.response.data.message);
                    });
            } else {
                this.setFormData();

                if (this.id) {
                    this.updatePerson();
                } else {
                    this.createPerson();
                }
            }
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
                    toast.error(error.response.data.message);
                })
                .finally(() => {
                    this.carregando = false;
                });
        },
        setPerson(person) {
            this.person = {
                name: person.name,
                nickname: person.nickname,
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
        BIconFileEarmarkArrowUp,
        loading,
    },
    computed: {
        years() {
            let years = [];
            for (let i = new Date().getFullYear(); i >= 1949; i--) {
                years.push(i);
            }
            return years;
        },
        title() {
            return this.id ? "Editar pessoa" : "Adicionar pessoa";
        },
        button_text() {
            return this.id ? "Editar" : "Adicionar";
        },
        image() {
            if (this.person.image.url.substring(0, 5) === "data:") {
                return this.person.image.url;
            } else {
                return `${process.env.VUE_APP_URL_IMAGES}/${this.person.image.url}`;
            }
        },
        id() {
            if (this.$route.params.id) {
                return this.$route.params.id;
            } else {
                return null;
            }
        },
    },
    mounted() {
        if (this.id) {
            this.getPerson();
        } else {
            this.carregando = false;
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
            margin-bottom: 1rem;
            font-weight: bold;
        }

        .form_report {
            width: 80%;
            max-width: 800px;

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
                            width: 100%;

                            @media screen and (max-width: 600px) {
                                margin-bottom: 0;

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

            .btn {
                width: 100%;
            }
        }
    }
</style>

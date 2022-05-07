<template>
    <div class="new-collection">
        <h1 class="title_page">Nova coleção</h1>
        <div class="form_collection">
            <div class="form-group form-group-single">
                <input v-model="collection.name" class="input_text" placeholder="Nome da coleção">
            </div>
            <div class="form_group photo">
                <label class="form_label" for="photo">Foto</label>
                <img :src="image" alt="foto da pessoa" v-if="collection.image.url" class="person_image">
                <input @change="onFileChange" class="input_file" type="file" id="photo" placeholder="Foto">
            </div>
            <h2 class="title_assis">Detalhes do assis</h2>
            <div class="form-group form-group-single">
                <input v-model="collection.assis.name" class="input_text" placeholder="Nome">
            </div>
            <div class="form-group form-group-double">
                <input v-model="collection.assis.total"
                       @input="removeNumbers($event)"
                       class="input_text"
                       :disabled="collection.assis.type === 'movie' || collection.assis.type === 'special'"
                       placeholder="Total">
                <input v-model="collection.assis.average_time" @input="removeNumbers($event)" class="input_text" placeholder="Tempo médio">
            </div>
            <div class="form-group form-group-single">
                <input v-model="collection.assis.year" class="input_text" placeholder="Ano de lançamento">
            </div>
            <div class="form-group form-group-double">
                <select v-model="collection.assis.type" class="input_select">
                    <option value="anime">Anime</option>
                    <option value="dorama">Dorama</option>
                    <option value="cartoon">Desenho</option>
                    <option value="movie">Filme</option>
                    <option value="serie">Série</option>
                    <option value="special">Especial</option>
                    <option value="specials">Especiais</option>
                    <option value="youtube">YouTube</option>
                    <option value="other">Outro</option>
                </select>
                <select v-model="collection.assis.status" class="input_select">
                    <option value="assistindo">Assistindo</option>
                    <option value="para_assistir">Para Assistir</option>
                    <option value="desistido">Desistido</option>
                    <option value="completo">Completo</option>
                    <option value="pausado">Pausado</option>
                </select>
            </div>
            <div class="form-group form-group-single">
                <label class="label_checkbox">
                    <input v-model="collection.assis.hidden_collection" type="checkbox">
                    <span class="checkmark" :class="{ 'checked': collection.assis.hidden_collection }"></span>
                    <span>Ocultar nome da coleção</span>
                </label>
            </div>
            <div class="form-group form-group-single">
                <textarea v-model="collection.assis.sinopse" class="input_textarea" placeholder="Sinopse"></textarea>
            </div>
            <button @click="sendImage" class="btn">Criar</button>
        </div>
    </div>
</template>

<script>
import api from "@/services/api";

export default {
    data() {
        return {
            collection: {
                name: '',
                image: {
                    url: "",
                    file: null
                },
                assis: {
                    name: '',
                    total: '',
                    average_time: '',
                    hidden_collection: false,
                    year: '',
                    type: 'serie',
                    status: 'assistindo',
                    sinopse: ''
                }
            }
        }
    },
    methods: {
        removeNumbers($event) {
            $event.target.value = $event.target.value.replace(/\D/g, '');
        },
        sendCollection() {
            api.post('/assis/collection', this.collection)
                .then(response => {
                    if (response.data.success) {
                        this.$router.push('/assis');
                    }
                })
        },
        onFileChange(e) {
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onload = (e) => {
                this.collection.image.url = e.target.result;
                this.collection.image.file = file;
            }
            reader.readAsDataURL(file);
        },
        sendImage() {
            if (this.collection.image.file) {
                let formData = new FormData();
                formData.append("image", this.collection.image.file);
                api.post(process.env.VUE_APP_URL_IMAGES, formData)
                    .then(response => {
                        if (response.data.success) {
                            this.collection.image.url = response.data.data;
                            this.sendCollection();
                        }
                    })
            } else {
                this.sendCollection();
            }
        },
    },
    computed: {
        image() {
            if (this.collection.image.url.substring(0, 5) === "data:") {
                return this.collection.image.url;
            } else {
                return `${process.env.VUE_APP_URL_IMAGES}/${this.collection.image.url}`;
            }
        },
    },
    watch: {
        'collection.assis.type'(value) {
            if (value === 'special' || value === 'movie') {
                this.collection.assis.total = 1;
            }
        }
    }
}
</script>

<style scoped lang="scss">
    .new-collection {
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

        .title_assis {
            font-size: 1.6rem;
            margin-bottom: 1rem;
            font-weight: bold;
            text-align: center;
        }

        .form_collection {
            width: 80%;
            max-width: 800px;

            .form-group {
                .input_text {
                    padding: 1rem;
                    border: none;
                    border-bottom: 1px solid #ccc;
                    border-radius: 0;
                    font-size: 1.1rem;
                    width: 100%;
                    margin-bottom: 1rem;
                }

                .input_select {
                    padding: 1rem;
                    border: none;
                    border-bottom: 1px solid #ccc;
                    border-radius: 0;
                    font-size: 1.1rem;
                    width: 100%;
                    background: transparent;
                    margin-bottom: 1rem;

                    @media (max-width: 600px) {
                        margin-bottom: 1rem;
                    }
                }

                .input_textarea {
                    padding: 1rem;
                    border: none;
                    border-bottom: 1px solid #ccc;
                    font-size: 1.1rem;
                    width: 100%;
                    height: 200px;
                    resize: none;
                    margin-bottom: 1rem;
                }

                .label_checkbox {
                    margin-bottom: 1rem;

                    .checkmark {
                        background: #ccc;
                        display: inline-block;
                        width: 1.1rem;
                        height: 1.1rem;
                        margin-right: 0.2rem;

                        &.checked {
                            background: #00bfa5;
                        }
                    }

                    input {
                        display: none;
                    }

                    span {
                        font-size: 1.5rem;
                        cursor: pointer;
                        user-select: none;
                    }
                }

                &.form-group-double {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    width: 100%;

                    @media (max-width: 600px) {
                        flex-direction: column;
                    }

                    .input_text {
                        flex: 1;
                        margin-right: 0.5rem;
                        padding: 1rem;
                        border: none;
                        border-bottom: 1px solid #ccc;
                        border-radius: 0;
                        font-size: 1.1rem;
                        width: 100%;

                        @media (max-width: 600px) {
                            margin-bottom: 1rem;
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

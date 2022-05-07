<template>
    <div class="photo">
        <label class="form_label" for="photo">Foto</label>
        <img :src="image" alt="foto da pessoa" v-if="imageUpload.url" class="person_image">
        <input @change="onFileChange" class="input_file" type="file" id="photo" placeholder="Foto">
        <template v-if="!sending">
            <button @click="sendImage" class="btn" v-if="imageUpload.url && !sended">Enviar</button>
        </template>
        <template v-if="sending">
            <button class="btn" disabled>Enviando...</button>
        </template>
    </div>
</template>

<script>
import api from "@/services/api";

export default {
    data() {
        return {
            imageUpload: {
                url: null,
                file: null
            },
            sended: false,
            sending: false,
        };
    },
    props: {
        id: {
            type: String,
            required: true
        },
    },
    methods: {
        onFileChange(e) {
            let file = e.target.files[0];

            if (file.size > 500000) {
                alert("Tamanho máximo de imagem é de 500kb");
                e.target.value = "";
                return;
            }

            let reader = new FileReader();
            reader.onload = (e) => {
                this.imageUpload.url = e.target.result;
                this.imageUpload.file = file;
            }
            reader.readAsDataURL(file);
            this.sended = false;
        },
        sendImage() {
            this.sending = true;
            let formData = new FormData();
            formData.append("image", this.imageUpload.file);
            api.post(process.env.VUE_APP_URL_IMAGES, formData)
                .then(response => {
                    if (response.data.success) {
                        this.imageUpload.url = response.data.data;
                        this.$emit("upload", {
                            id: this.id,
                            image: response.data.data
                        });
                        this.sended = true;
                    } else {
                        alert(response.data.message);
                    }
                })
                .finally(() => {
                    this.sending = false;
                });
        },
    },
    computed: {
        image() {
            if (this.imageUpload.url.substring(0, 5) === "data:") {
                return this.imageUpload.url;
            } else {
                return `${process.env.VUE_APP_URL_IMAGES}/${this.imageUpload.url}`;
            }
        },
    },
}
</script>

<style scoped lang="scss">
    .photo {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;

        label {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        img {
            width: 10rem;
            height: 14rem;
            object-fit: cover;
            object-position: center;
            margin-bottom: 10px;
        }

        input[type="file"] {
            max-width: 40rem;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            margin-bottom: 0.8rem;

            &:focus {
                outline: none;
            }

            &::-webkit-file-upload-button {
                display: none;
            }
        }

        button {
            width: 8rem;
            padding: 0.5rem 0;
            border: none;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;

            &:focus {
                outline: none;
            }
        }
    }
</style>

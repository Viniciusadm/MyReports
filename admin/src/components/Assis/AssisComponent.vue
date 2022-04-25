<template>
    <div class="assis">
        <img v-if="image" :src="image" :alt="`Imagem de capa: ${assis.collection.name} - ${assis.name}`">
        <img v-else src="../../../assets/images/default.png" alt="imagem padrão">
        <div class="assis_info">
            <p>{{ assis.collection.name }}{{assis.name ? ` - ${assis.name}` : ''}}</p>
            <p>{{assis.episodes_count}} de {{assis.total}} episódios assistidos</p>
            <p>{{ type }}</p>
            <p>Status: {{ status }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        assis: {
            type: Object,
            required: true,
            default: () => ({
                id: 0,
                collection_id: 0,
                name: "",
                total: 0,
                status: 0,
                created_at: "",
                type: "",
                image: "",
                episodes_count: 0,
                collection: {
                    id: 0,
                    name: "",
                    image: "",
                }
            })
        }
    },
    computed: {
        image() {
            if (this.assis.image) {
                return `${process.env.VUE_APP_URL_IMAGES}/${this.assis.image}`;
            } else if (this.assis.collection.image) {
                return `${process.env.VUE_APP_URL_IMAGES}/${this.assis.collection.image}`;
            } else {
                return null;
            }
        },
        type() {
            const types = {
                anime: "Anime",
                dorama: "Dorama",
                cartoon: "Desenho",
                movie: "Filme",
                serie: "Série",
                other: "Outro"
            }

            return types[this.assis.type];
        },
        status() {
            return this.assis.status.replace(/_/g, " ");
        }
    },
}
</script>

<style scoped lang="scss">
    .assis {
        display: flex;
        width: 100%;
        margin-bottom: 1rem;

        img {
            width: 12rem;
            height: 10rem;
            object-fit: cover;
            object-position: center;
            margin-right: 0.8rem;
        }

        .assis_info {
            width: 100%;

            p {
                margin-bottom: 0.3rem;

                &:last-child {
                    margin-bottom: 0;
                }
            }
        }
    }
</style>

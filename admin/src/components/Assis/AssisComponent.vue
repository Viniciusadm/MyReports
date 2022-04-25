<template>
    <div class="assis">
        <img :src="image" :alt="`Imagem de capa: ${assis.collection.name} - ${assis.name}`">
        <div class="assis_info">
            <p>{{ assis.collection.name }}{{assis.name ? ` - ${assis.name}` : ''}}</p>
            <p>{{assis.episodes_count}} episódios assistidos de {{assis.total}} totais</p>
            <p>{{ type }}</p>
            <p>Status: {{ assis.status }}</p>
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
                return this.assis.image;
            } else if (this.assis.collection.image) {
                return this.assis.collection.image;
            } else {
                return "https://via.placeholder.com/150";
            }
        },
        type() {
            const types = {
                anime: "Anime",
                dorama: "Dorama",
                movie: "Filme",
                serie: "Série",
                other: "Outro"
            }

            return types[this.assis.type];
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
            width: 10rem;
            height: 10rem;
            object-fit: contain;
            margin-right: 0.8rem;
        }
    }
</style>

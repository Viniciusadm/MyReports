<template>
    <div class="assis">
        <router-link :to="`/assis/${assis.id}`">
            <img v-if="assis.image_url" :src="assis.image_url" :alt="`Imagem de capa: ${assis.collection.name} - ${assis.name}`">
            <img v-else src="../../../assets/images/default.png" alt="imagem padrão">
        </router-link>
        <div class="assis_info">
            <p><router-link :to="`/assis/${assis.id}`">{{ assis.full_name }}</router-link></p>
            <template v-if="assis.type_formatted === 'Especial' || assis.type_formatted === 'Filme'">
                <p>{{ assis.episodes_count === 1 ? 'Assistido' : 'Não assistido' }}</p>
            </template>
            <template v-else>
                <p>{{assis.episodes_count}} de {{assis.total}} episódios</p>
            </template>
            <p>{{ assis.type_formatted }}</p>
            <p v-if="assis.type_formatted !== 'Especial' && assis.type_formatted !== 'Filme'">{{ status }}</p>
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
                hidden_collection: false,
                full_name: "",
                image_url: "",
                type_formatted: "",
                collection: {
                    id: 0,
                    name: "",
                    image: "",
                }
            })
        }
    },
    computed: {
        status() {
            return this.assis.status.replace(/_/g, " ")[0].toUpperCase() + this.assis.status.replace(/_/g, " ").slice(1);
        },
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
            height: 14rem;
            object-fit: cover;
            object-position: center;
            margin-right: 0.8rem;
        }

        .assis_info {
            width: 100%;

            p {
                margin-bottom: 0.5rem;

                a {
                    color: #000;
                    text-decoration: none;
                }

                &:last-child {
                    margin-bottom: 0;
                }
            }
        }
    }
</style>

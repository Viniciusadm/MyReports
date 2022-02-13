<template>
    <div class="report">
        <div class="report_header report_collumn">
            <span class="title">{{ title }}</span>
            <span class="hyphen">-</span>
            <span class="humor">{{ humor_formatted }}</span>
            <span class="hyphen">-</span>
            <span class="type">{{ type_formatted }}</span>
            <span class="hyphen">-</span>
            <span class="date">{{ date_formatted }}</span>
        </div>
        <div class="report_body report_collumn">
            <p>{{ report }}</p>
        </div>
        <div v-if="people.length > 0" class="report_people report_collumn">
            <p class="title">Pessoas:</p>
            <p class="person" v-for="(person, index) in people" :key="person.id">
                <template v-if="index !== people.length - 1">{{ person.name }}, </template>
                <template v-else>{{ person.name }}</template>
            </p>
        </div>
        <div v-else class="report_people report_collumn">
            <p class="title">Sozinho</p>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .report {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 0.8rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        padding: 0.8rem 0;
        width: 90%;

        &:last-child {
            margin-bottom: 1.2rem;
        }

        &_collumn {
            padding: 0.4rem 0;

            p, span {
                font-size: 1.1rem;

                @media screen and (min-width: 425px) {
                    font-size: 1.05rem;
                }
            }
        }

        &_header {
            display: flex;
            width: 95%;

            .hyphen {
                margin: 0 0.5rem;
            }

            .title {
                font-weight: bold;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }

        &_body {
            width: 95%;

            p {
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }

        &_people {
            width: 95%;
            display: flex;

            .title {
                margin-right: 0.5rem;
            }

            .person {
                margin-right: 0.5rem;
            }
        }
    }
</style>

<script>
export default {
    data() {
        return {
            humors: this.$store.state.humors
        }
    },
    props: ['title', 'report', 'type', 'humor', 'date', 'people'],
    computed: {
        date_formatted() {
            return this.date.split('T')[0].split('-').reverse().join('/')
        },
        type_formatted() {
            if (this.type === 'daily') {
                return 'Diário'
            } else {
                return 'Pessoal'
            }
        },
        humor_formatted() {
            return this.humors[this.humor];
        }
    }
}
</script>
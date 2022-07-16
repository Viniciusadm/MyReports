<template>
    <div class="report">
        <div class="card">
            <div class="card-header">
                <router-link :to="`/report/${report.id}`" class="title">{{ report.title }}</router-link>
                <p class="type">Tipo: {{ getType(report.type) }}</p>
                <p class="humor">Humor: {{ humor }}</p>
                <p class="date">{{ dateFormat(report.created_at) }}</p>
            </div>
            <div class="card-body">
                <div class="participants" v-if="report.participants.length > 0">
                    <p class="title-participant">Participantes:</p>
                    <p class="participants">{{ participantsFormated(report.participants) }}</p>
                </div>
                <div class="participants" v-else>
                    <p class="title-participant">Nenhum participante</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        report: {
            type: Object,
            required: true,
            default() {
                return {
                    title: '',
                    created_at: '',
                    type: '',
                    humor: '',
                    participants: [{
                        person: {
                            name: '',
                            nickname: '',
                        }
                    }],
                };
            },
        }
    },
    computed: {
        humor() {
            return this.$store.state.humors[this.report.humor];
        },
    },
    methods: {
        dateFormat(date) {
            return date.split('T')[0].split('-').reverse().join('/') + ' ' + date.split('T')[1].split('.')[0];
        },
        getType(type) {
            return this.$store.state.types_report[type];
        },
        participantsFormated(participants) {
            return participants.map(participant => {
                if (participant.person.nickname) {
                    return participant.person.nickname;
                } else {
                    return participant.person.name;
                }
            }).join(', ');
        },
    },
}
</script>

<style scoped lang="scss">
    .report {
        border: black 1px solid;
        padding: 1rem;

        .card {
            &-header {
                .title {
                    font-size: 1.15rem;
                    font-weight: bold;
                    margin-bottom: 0.3rem;
                    text-decoration: none;
                    color: #000;
                    display: block;

                    &:hover {
                        text-decoration: underline;
                    }

                    @media screen and (max-width: 570px) {
                        font-size: 1rem;
                    }
                }

                .type {
                    font-size: 1.15rem;
                    font-weight: bold;
                    margin-bottom: 0.3rem;
                }

                .humor {
                    font-weight: bold;
                    margin-bottom: 0.3rem;
                }

                .date {
                    font-weight: bold;
                    color: #868686;
                    margin-bottom: 0.3rem;
                }
            }

            &-body {
                .participants {
                    .title-participant {
                        font-size: 1.15rem;
                        font-weight: bold;
                    }

                    .participant {
                        display: inline;
                        margin-right: 0.3rem;
                    }
                }
            }
        }
    }
</style>

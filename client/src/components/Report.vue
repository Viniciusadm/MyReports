<template>
    <div class="report">
        <div class="report_header">
            <span class="title">{{ title }}</span>
            <span class="hyphen">-</span>
            <span class="humor">{{ humor }}</span>
            <span class="hyphen">-</span>
            <span class="type">{{ type_formatted }}</span>
            <span class="hyphen">-</span>
            <span class="date">{{ date_formatted }}</span>
        </div>
        <div class="report_body">
            <p>{{ report_cuted }}</p>
        </div>
        <div class="report_participants">
            <p class="title">Participants:</p>
            <p class="participant" v-for="(participant, index) in participants" :key="participant.id">
                <template v-if="index !== participants.length - 1">{{ participant.person.name }}, </template>
                <template v-else>{{ participant.person.name }}</template>
            </p>
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
        padding: 0.6rem 0;
        width: 90%;
        
        &:last-child {
            margin-bottom: 0;
        }

        &_header {
            display: flex;
            width: 95%;

            .hyphen {
                margin: 0 0.5rem;
            }
        }

        &_body {
            margin-top: 0.3rem;
            width: 95%;
        }

        &_participants {
            width: 95%;
            display: flex;
            margin-top: 0.3rem;

            .title {
                margin-right: 0.5rem;
            }

            .participant {
                margin-right: 0.5rem;
            }
        }
    }
</style>

<script>
export default {
    props: ['title', 'report', 'type', 'humor', 'date', 'participants'],
    computed: {
        report_cuted() {
            if (this.report.length > 100) {
                return this.report.slice(0, 100) + '...';
            } else {
                return this.report;
            }
        },
        date_formatted() {
            return this.date.split('T')[0].split('-').reverse().join('/')
        },
        type_formatted() {
            if (this.type === 'daily') {
                return 'Diário'
            } else {
                return 'Pessoal'
            }
        }
    }
}
</script>
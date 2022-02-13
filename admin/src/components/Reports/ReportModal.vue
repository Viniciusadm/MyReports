<template>
    <BaseModal :scroll="true">
        <div class="base">
            <div class="modal_header">
                <h2>{{ report.title }}</h2>
                <p class="details">{{ humor }} - {{ type }} - {{ date }}</p>
                <p class="people">{{ people }}</p>
            </div>
            <div class="modal_body">
                <p class="report">{{ report.report }}</p>
            </div>
            <div class="modal_footer">
                <button class="btn btn-primary" @click="$emit('close')">Fechar</button>
            </div>
        </div>
    </BaseModal>
</template>

<script>
import BaseModal from "@/components/BaseModal";

export default {
    components: {
        BaseModal
    },
    props: {
        report: {
            type: Object,
            required: true,
            default: () => ({
                'title': '',
                'report': '',
                'type': '',
                'humor': '',
                'created_at': '',
                'persons_ids': [],
            })
        }
    },
    computed: {
        date() {
            return this.report.created_at.split('T')[0].split('-').reverse().join('/');
        },
        humor() {
            return this.$store.state.humors[this.report.humor];
        },
        type() {
            return this.report.type === 'personal' ? 'Pessoal' : 'Diário';
        },
        people() {
            let people = [];
            JSON.parse(this.report.persons_ids).forEach(id => {
                people.push(this.$store.state.people[id - 1].name);
            });
            return people.length > 0 ? people.join(', ') : 'Nenhum';
        },
    }
}
</script>

<style scoped lang="scss">
.base {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    .modal_header {
        padding-top: 1.5rem;
        width: 90%;

        h2 {
            font-size: 1.3em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .details {
            font-size: 1.15rem;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .people {
            font-size: 1.15rem;
            font-weight: normal;
            margin: 0;
            text-align: center;
            word-break: break-all;
        }
    }

    .modal_body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 90%;

        .report {
            width: 80%;
            font-size: 1.1em;

            @media (max-width: 768px) {
                width: 95%;
            }
        }
    }

    .modal_footer {
        margin-bottom: 1.5rem;
    }

}
</style>

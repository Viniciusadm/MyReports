<template>
    <BaseModal :scroll="true">
        <div class="new_report">
            <h1 class="title_page">Novo Relato</h1>
            <div class="form_report">
                <div class="form_group">
                    <label class="form_label" for="title">Título</label>
                    <input v-model="report.title" class="input_text" type="text" id="title" placeholder="Título do relato">
                </div>
                <div class="form_group">
                    <label class="form_label" for="report">Relato</label>
                    <textarea v-model="report.report" class="input_text" id="report" placeholder="Relato"></textarea>
                </div>
                <div class="form_group">
                    <label class="form_label" for="humor">Humor</label>
                    <select v-model="report.humor" id="humor">
                        <option value="">Selecione um humor</option>
                        <option v-for="humor in humors" :key="humor[0]" :value="humor[0]">{{ humor[1] }}</option>
                    </select>
                </div>
                <div class="form_group">
                    <label class="form_label">Tipo</label>
                    <div class="radios_type">
                        <input v-model="report.type" name="type" type="radio" id="type_personal" value="personal">
                        <label for="type_personal">Pessoal</label>
                    </div>
                    <div class="radios_type">
                        <input v-model="report.type" name="type" type="radio" id="type_daily" value="daily">
                        <label for="type_daily">Diário</label>
                    </div>
                </div>
                <div class="form_group" id="people_group">
                    <label class="form_label" for="people">Pessoas</label>
                    <input @input="searchPerson()" v-model="query" class="input_text" type="text" id="people" placeholder="Pessoas">
                    <div class="list_people">
                        <button class="person" @click="addPerson($event)" :value="person.id" :name="person.name" v-for="person in people_search" :key="person.id">{{ person.name }}</button>
                    </div>
                    <div class="list_people_selected">
                        <button class="person" @click="removePerson($event)" :value="person.id" :name="person.name" v-for="person in people" :key="person.id">{{ person.name }}</button>
                    </div>
                </div>
                <div class="form_group buttons">
                    <button @click="$emit('close')" class="btn close_report">Fechar</button>
                    <button @click="sendReport()" class="btn send_report">{{ button_text }}</button>
                </div>
            </div>
        </div>
    </BaseModal>
</template>

<script>
import api from '../../services/api'
import { useToast } from "vue-toastification";
import BaseModal from "@/components/BaseModal";

const toast = useToast();

export default {
    emits: ['close', 'save'],
    props: {
      id: {
          required: false,
      },
    },
    components: {
        BaseModal
    },
    computed: {
      button_text() {
          return this.id ? 'Editar' : 'Criar';
      },
    },
    data() {
        return {
            humors: Object.entries(this.$store.state.humors),
            query: '',
            people_search: [],
            people: [],
            report: {
                title: '',
                humor: '',
                type: '',
                participants: [{
                    id: '',
                    name: '',
                    person_id: '',
                }],
                report: ''
            }
        }
    },
    methods: {
        sendReport() {
            this.report.participants = this.people.map(person => person.id);

            if (this.validateForm()) {
                if (this.id) {
                    this.updateReport();
                } else {
                    this.createReport();
                }
            }
        },
        createReport() {
            api.post('/reports', this.report)
                .then(response => {
                    if (response.data.success) {
                        toast.success('Relato enviado com sucesso!');
                        this.$emit('save');
                    } else {
                        toast.error(response.data.message);
                    }
                })
                .catch(error => {
                    toast.error(error.message);
                });
        },
        updateReport() {
            api.put('/reports/' + this.id, this.report)
                .then(response => {
                    if (response.data.success) {
                        toast.success('Relato atualizado com sucesso!');
                        this.$emit('save');
                    } else {
                        toast.error(response.data.message);
                    }
                })
                .catch(error => {
                    toast.error(error.message);
                });
        },
        searchPerson() {
            const exclude = JSON.stringify(this.people.map(person => person.id));

            api.get(`people/search/?q=${this.query}&exclude=${exclude}`)
                .then(response => {
                    if (response.data.success) {
                        this.people_search = response.data.data;
                    } else {
                        toast.error(response.data.message);
                    }
                })
        },
        addPerson($event) {
            this.people.push({
                id: $event.target.value,
                name: $event.target.name
            });

            this.people_search = [];
            this.query = '';
        },
        removePerson($event) {
            this.people = this.people.filter(person => person.id !== Number($event.target.value));
        },
        validateForm() {
            if (this.report.title === '') {
                toast.error('Preencha o título do relato!');
                return false;
            }

            if (this.report.report === '') {
                toast.error('Preencha o relato!');
                return false;
            }

            if (this.report.humor === '') {
                toast.error('Selecione o humor do relato!');
                return false;
            }

            if (this.report.type === '') {
                toast.error('Preencha o tipo do relato!');
                return false;
            }

            if (this.report.participants.length === 0 && this.report.type === 'personal') {
                toast.error('Selecione pelo menos um participante!');
                return false;
            }

            return true;
        },
        getReport() {
            api.get(`/reports/${this.id}`)
                .then(response => {
                    if (response.data.success) {
                        this.setReport(response.data.data);
                    } else {
                        toast.error(response.data.message);
                    }
                })
        },
        setReport(report) {
            this.report = {
                title: report.title,
                humor: report.humor,
                type: report.type,
                report: report.report
            }

            this.people = report.participants.map(participant => {
                return {
                    id: participant.person_id,
                    name: participant.person.name
                }
            });
        },
    },
    created() {
        if (this.id) {
            this.getReport();
        }
    }
}
</script>

<style lang="scss" scoped>
    .new_report {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        margin-bottom: 1.5rem;

        .title_page {
            font-size: 2rem;
            margin: 1rem 0;
            font-weight: bold;
        }
        .form_report {
            width: 80%;
            max-width: 600px;

            .form_group {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 100%;

                .form_label {
                    font-size: 1.6rem;
                    margin-bottom: 1rem;
                }

                .input_text {
                    width: 100%;
                    padding: 1rem;
                    border: 1px solid #ccc;
                    border-radius: 0.5rem;
                    margin-bottom: 0.8rem;
                    font-size: 1.1rem;

                    &#report {
                        resize: none;
                        height: 20rem;
                    }
                }

                #humor {
                    width: 100%;
                    padding: 1rem;
                    border: 1px solid #ccc;
                    border-radius: 0.5rem;
                    margin-bottom: 0.8rem;
                    background: transparent;
                    cursor: pointer;
                    font-size: 1.1rem;

                    option {
                        background-color: #f5f5f5;
                    }
                }

                .radios_type {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    width: 100%;
                    margin-bottom: 0.5rem;

                    label {
                        cursor: pointer;
                        font-size: 1.2rem;
                    }

                    input {
                        margin-right: 0.5rem;
                        padding: 0.5rem;
                        cursor: pointer;
                    }
                }

                &#people_group {
                    position: relative;

                    .list_people {
                        display: flex;
                        flex-direction: column;
                        width: 100%;
                        margin-bottom: 0.5rem;
                        position: absolute;
                        top: 4rem;
                        background-color: white;

                        .person {
                            padding: 0.5rem;
                            width: 100%;
                            border-left: 1px solid #ccc;
                            border-right: 1px solid #ccc;
                            border-top: 1px solid #ccc;
                            border-bottom: none;
                            background: transparent;
                            cursor: pointer;

                            &:last-child {
                                border-bottom: 1px solid #ccc;
                            }
                        }
                    }

                    .list_people_selected {
                        display: flex;
                        flex-wrap: wrap;
                        width: 100%;
                        margin-bottom: 0.5rem;
                        background-color: white;
                        border-bottom: none;

                        .person {
                            background: transparent;
                            border: none;
                            cursor: pointer;
                            margin-right: 0.5rem;
                        }
                    }
                }

                &.buttons {
                    display: flex;
                    flex-direction: row;

                    button {
                        flex: 1;
                        margin-right: 0.5rem;

                        &:nth-child(1) {
                            background-color: #b6b6b6;

                            &:hover {
                                background-color: #ccc;
                            }
                        }
                    }
                }
            }
        }
    }
</style>

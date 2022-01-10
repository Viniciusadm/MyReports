<template>
    <div class="new_report">
        <h1 class="title_page">Novo Relatório</h1>
        <div class="form_report">
            <div class="form_group">
                <label class="form_label" for="title">Título</label>
                <input class="input_text" type="text" id="title" placeholder="Título do relatório">
            </div>
            <div class="form_group">
                <label class="form_label" for="report">Relatório</label>
                <textarea class="input_text" id="report" placeholder="Relatório"></textarea>
            </div>
            <div class="form_group">
                <label class="form_label" for="humor">Humor</label>
                <select id="humor">
                    <option value="">Selecione um humor</option>
                    <option value="Feliz">Feliz</option>
                    <option value="Triste">Triste</option>
                    <option value="Neutro">Neutro</option>
                    <option value="Raivoso">Raivoso</option>
                </select>
            </div>
            <div class="form_group">
                <label class="form_label">Tipo</label>
                <div class="radios_type">
                    <input name="type" type="radio" id="type_personal" value="personal">
                    <label for="type_personal">Pessoal</label>
                </div>
                <div class="radios_type">
                    <input name="type" type="radio" id="type_daily" value="daily">
                    <label for="type_daily">Diário</label>
                </div>
            </div>
            <div class="form_group" id="people_group">
                <label class="form_label" for="people">Pessoas</label>
                <input @input="searchPerson()" :v-bind="query" class="input_text" type="text" id="people" placeholder="Pessoas">
                <div class="list_people">
                    <button class="person" @click="addPerson($event)" :value="person.id" :name="person.name" v-for="person in people" :key="person.id">{{ person.name }}</button>
                </div>
                <div class="list_people_selected">
                    <button class="person" @click="removePerson($event)" :value="person.id" :name="person.name" v-for="person in people_selected" :key="person.id">{{ person.name }}</button>
                </div>
            </div>
            <div class="form_group">
                <button @click="sendReport()" class="send_report">Cadastrar</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .new_report {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;

        * {
            outline: none;
        }

        .form_group {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;

            .form_label {
                font-size: 1.2rem;
                margin-bottom: 0.5rem;
            }

            .input_text {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid #ccc;
                border-radius: 0.5rem;
                margin-bottom: 0.5rem;
                
                &#report {
                    resize: none;
                    height: 10rem;
                }
            }

            #humor {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid #ccc;
                border-radius: 0.5rem;
                margin-bottom: 0.5rem;
                background: transparent;
                cursor: pointer;

                option {
                    background-color: #f5f5f5;
                }
            }

            .radios_type {
                display: flex;
                flex-direction: row;
                width: 100%;
                margin-bottom: 0.5rem;

                label {                    
                    cursor: pointer;
                }

                input {
                    margin-right: 0.5rem;
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
                    width: 100%;
                    margin-bottom: 0.5rem;
                    background-color: white;
                    border-bottom: none;

                    .person {
                        background: transparent;
                        border: none;
                        cursor: pointer;
                    }
                }
            }
        }
        
        .send_report {
                width: 100%;
                padding: 0.5rem;
                border: 1px solid #ccc;
                border-radius: 0.5rem;
                margin-bottom: 0.5rem;
                background-color: #00a680;
                color: #fff;
                font-weight: bold;
                cursor: pointer;

                &:hover {
                    background-color: #00a680;
                }
            }
    }
</style>

<script>
import api from '../../services/api';

export default {    
    data() {
        return {
            query: '',
            people: [],
            people_selected: []
        }
    },
    methods: {
        sendReport() {
            console.log('sendReport');
        },
        searchPerson() {
            api.get(`people/search/?q=${this.query}`)
            .then((response) => {
                if (response.status === 200) {
                    this.people = response.data;
                } else {
                    console.log(response.data.message);
                }
            })
        },
        addPerson($event) {
            this.people_selected.push({
                id: $event.target.value,
                name: $event.target.name
            });

            this.people = [];
            this.query = '';
        }
    }
}

</script>
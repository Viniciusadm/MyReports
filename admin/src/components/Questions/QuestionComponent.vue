<template>
    <div class="question-component" :class="question.deactivated_at ? 'deactivated' : ''">
        <p class="question"
           @click="editQuestion()"
           v-if="input_question"
           :class="question.type">
            {{ question.question }}</p>
        <input @keyup.enter="changeQuestion()" ref="question_input" type="text" @keyup.esc="cancelEdit()" v-if="!input_question" class="question" :value="question.question" :class="question.type">
        <div class="question-type" v-if="!input_question">
            <label :class="question.type === 'good' ? 'active' : ''">
                <input type="radio" value="good" />
                <span @click="changeType('good')">Bom</span>
            </label>
            <label :class="question.type === 'neutral' ? 'active' : ''">
                <input type="radio" value="neutral" />
                <span @click="changeType('neutral')">Neutro</span>
            </label>
            <label :class="question.type === 'bad' ? 'active' : ''">
                <input type="radio" value="bad" />
                <span @click="changeType('bad')">Ruim</span>
            </label>
            <button class="edit" @click="changeQuestion()">Editar</button>
            <button class="disable" v-if="!question.deactivated_at" @click="disableQuestion()">Desativar</button>
        </div>
        <div v-if="question.answer" class="answer">
            <div class="inputs">
                <label :class="question.answer.answer === 'yes' ? 'active' : ''">
                    <input type="radio" value="yes" />
                    <span @click="reply('yes')">Sim</span>
                </label>
                <label :class="question.answer.answer === 'no' ? 'active' : ''">
                    <input type="radio" value="no" />
                    <span @click="reply('no')">Não</span>
                </label>
            </div>
            <input :disabled="!answered" class="input_comment" @keyup.enter="comment($event)" :value="question.answer.comment" type="text" placeholder="Comentário" />
        </div>
        <div v-else class="answer">
            <div class="inputs">
                <label>
                    <input type="radio" value="yes" />
                    <span @click="reply('yes')">Sim</span>
                </label>
                <label>
                    <input type="radio" value="no" />
                    <span @click="reply('no')">Não</span>
                </label>
            </div>
            <input :disabled="!answered" class="input_comment" @keyup.enter="comment($event)" type="text" placeholder="Comentário" />
        </div>
    </div>
</template>

<script>
import api from "@/services/api";
import { useToast } from "vue-toastification";

const toast = useToast();

export default {
    data() {
        return {
            answered: !!this.question.answer,
            input_question: true,
        }
    },
    computed: {
        url_reply() {
            if (this.answered) {
                return `/questions/answer/${this.question.answer.id}`;
            } else {
                return `/questions/answer/reply`;
            }
        }
    },
    methods: {
        changeQuestion() {
            if (this.question.question === this.$refs.question_input.value || this.$refs.question_input.value === '') {
                this.input_question = true;
                return;
            }

            api.post(`/questions/${this.question.id}/question`, {
                question: this.$refs.question_input.value,
                type: this.question.type,
            })
            .then(() => {
                this.input_question = true;
                toast.success("Pergunta alterada com sucesso!");
                this.$emit("question", {
                    question_id: this.question.id,
                    question: this.$refs.question_input.value,
                });
            })
        },
        editQuestion() {
            if (!this.question.deactivated_at) this.input_question = false;
        },
        disableQuestion() {
            api.delete(`/questions/${this.question.id}/disable`).then(() => {
                toast.success("Pergunta desativada com sucesso!");
                this.input_question = true;
                this.$emit("disable", {
                    question_id: this.question.id,
                });
            }).catch(() => {
                toast.error("Não foi possível desativar a pergunta!");
            });
        },
        changeType(type) {
            if (this.question.type === type) return;

            api.post(`/questions/${this.question.id}/type`, {
                type: type,
            })
            .then(() => {
                toast.success("Tipo alterado com sucesso!");
                this.$emit("type", {
                    question_id: this.question.id,
                    type: type,
                });
            })
        },
        cancelEdit() {
            this.input_question = true;
        },
        reply(answer) {
            if (this.question.answer && answer === this.question.answer.answer) return;

            api.post(this.url_reply, {
                question_id: this.question.id,
                answer: answer,
                date: this.date,
            })
            .then(response => {
                this.answered = true;
                this.$emit("answered", {
                    question_id: this.question.id,
                    id: response.data.data.id,
                    answer: answer,
                });
            })
            .catch(() => {
                toast.error("Erro ao salvar resposta");
            });
        },
        comment($event) {
            api.post(`/questions/answer/${this.question.answer.id}/comment`, {
                comment: $event.target.value,
            })
            .then(response => {
                this.$emit("comment", {
                    question_id: this.question.id,
                    comment: response.data.data.comment,
                });
            })
            .catch(() => {
                toast.error("Erro ao salvar comentário");
            });
        },
    },
    props: {
        date: {
            type: String,
            required: true,
        },
        question: {
            type: Object,
            required: true,
            default() {
                return {
                    id: null,
                    question: '',
                    type: '',
                    deactivated_at: null,
                    answer: {
                        id: null,
                        answer: '',
                        comment: '',
                    },
                }
            }
        }
    },
    updated() {
        this.answered = !!this.question.answer;
    },
}
</script>

<style scoped lang="scss">
    $red: #dd4b39;
    $blue: #00bcd4;
    $green: #00a65a;
    $orange: #f39c12;

    .question-component {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.2rem 0;
        border: 1px solid #ccc;

        &.deactivated {
            .question {
                text-decoration: line-through;
            }
        }

        .question {
            font-size: 1.3rem;
            font-weight: bold;
            margin-top: 0.5rem;
            padding: 0.2rem 0;
            width: 97%;
            text-align: center;
            border: none;
            cursor: pointer;

            &.good {
                color: $green;
            }

            &.neutral {
                color: $orange;
            }

            &.bad {
                color: $red;
            }

            @media screen and (max-width: 600px) {
                font-size: 1.2rem;
            }
        }

        .question-type {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            margin-top: 0.5rem;

            label {
                display: flex;
                border: 1px solid #ccc;
                cursor: pointer;
                color: black;
                margin-right: 0.3rem;

                &:hover {
                    background-color: $blue;
                    color: white;
                }

                &.active {
                    background-color: $blue;
                    color: white;
                }

                &:first-child {
                    border-top-left-radius: 0.2rem;
                    border-bottom-left-radius: 0.2rem;
                }

                &:last-child {
                    border-top-right-radius: 0.2rem;
                    border-bottom-right-radius: 0.2rem;
                    margin-right: 0;
                }

                span {
                    font-size: 1rem;
                    font-weight: bold;
                    padding: 0.4rem;
                    user-select: none;
                }

                input {
                    display: none;
                }
            }

            button {
                padding: 0.4rem;
                border: none;
                color: white;
                font-weight: bold;
                border-radius: 0.1rem;
                cursor: pointer;
                margin-right: 0.3rem;

                &.edit {
                    background-color: $green;
                }

                &.disable {
                    background-color: $red;
                }

                &:last-child {
                    margin-right: 0;
                }

                &:hover {
                    background-color: #00c5e0;
                }
            }
        }

        .answer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0.5rem;
            width: 90%;

            .inputs {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;

                label {
                    display: flex;
                    border: 1px solid #ccc;
                    cursor: pointer;
                    color: black;

                    &:hover {
                        background-color: $blue;
                        color: white;
                    }

                    &.active {
                        background-color: $blue;
                        color: white;
                    }

                    &:first-child {
                        border-top-left-radius: 0.2rem;
                        border-bottom-left-radius: 0.2rem;
                        margin-right: 0.3rem;
                    }

                    &:last-child {
                        border-top-right-radius: 0.2rem;
                        border-bottom-right-radius: 0.2rem;
                    }

                    span {
                        font-size: 1.3rem;
                        font-weight: bold;
                        padding: 0.7rem;
                        user-select: none;

                        @media screen and (max-width: 600px) {
                            font-size: 1.2rem;
                        }
                    }

                    input {
                        display: none;
                    }
                }
            }

            .input_comment {
                width: 100%;
                margin: 0.5rem 0;
                padding: 0.5rem;
                border: 1px solid #ccc;
            }
        }
    }
</style>

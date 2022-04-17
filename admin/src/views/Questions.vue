<template>
    <div class="questions-page">
        <div class="questions">
            <input type="date" v-model="date" @change="getQuestions()">
            <div class="list">
                <question :date="date"
                          @answered="reply($event)"
                          @comment="comment($event)"
                          @question="changeQuestion($event)"
                          @type="changeType($event)"
                          @disable="disable($event)"
                          v-for="question in questions" :question="question"
                          :key="question.id">
                </question>
                <div class="new-question">
                    <input type="text" @keyup.enter="addQuestion()" v-model="question.text" placeholder="Nova pergunta">
                    <div class="question-type">
                        <label :class="question.type === 'good' ? 'active' : ''">
                            <input v-model="question.type" type="radio" value="good" />
                            <span>Bom</span>
                        </label>
                        <label :class="question.type === 'neutral' ? 'active' : ''">
                            <input v-model="question.type" type="radio" value="neutral" />
                            <span @click="changeType('neutral')">Neutro</span>
                        </label>
                        <label :class="question.type === 'bad' ? 'active' : ''">
                            <input v-model="question.type" type="radio" value="bad" />
                            <span @click="changeType('bad')">Ruim</span>
                        </label>
                        <button @click="addQuestion()">Adicionar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from "@/services/api";
import moment from "moment";
import question from "@/components/Questions/QuestionComponent";
import {useToast} from "vue-toastification";

const toast = useToast();

export default {
    data() {
        return {
            questions: [],
            date: moment().format("YYYY-MM-DD"),
            question: {
                text: "",
                type: "good"
            },
        }
    },
    components: {
        question,
    },
    methods: {
        getQuestions() {
            if (!moment(this.date).isAfter(moment().format("YYYY-MM-DD"))) {
                api.get("/questions", {
                    params: {
                        date: this.date,
                    }
                }).then(response => {
                    if (response.data.success) {
                        this.questions = response.data.data;
                    } else {
                        toast.error(response.data.message);
                    }
                });
            } else {
                this.date = moment().format("YYYY-MM-DD");
            }
        },
        reply($event) {
            this.questions.forEach(question => {
                if (question.id === $event.question_id) {
                    if (!question.answer) question.answer = {}
                    question.answer.id = $event.id;
                    question.answer.question_id = $event.question_id;
                    question.answer.answer = $event.answer;
                }
            });
        },
        comment($event) {
            this.questions.forEach(question => {
                if (question.id === $event.question_id) {
                    question.answer.comment = $event.comment;
                    toast.success("Comentário salvo com sucesso!");
                }
            });
        },
        changeQuestion($event) {
            this.questions.forEach(question => {
                if (question.id === $event.question_id) {
                    question.question = $event.question;
                }
            });
        },
        changeType($event) {
            this.questions.forEach(question => {
                if (question.id === $event.question_id) {
                    question.type = $event.type;
                }
            });
        },
        disable($event) {
            this.questions.forEach(question => {
                if (question.id === $event.question_id) {
                    console.log(question);
                    question.deactivated_at = this.date;
                }
            });
        },
        addQuestion() {
            if (this.question.text) {
                api.post("/questions", {
                    question: this.question.text,
                    type: this.question.type,
                })
                .then(response => {
                    if (response.data.success) {
                        this.questions.push(response.data.data);
                        this.question = {
                            text: "",
                            type: "good"
                        };
                    } else {
                        toast.error(response.data.message);
                    }
                });
            }
        },
    },
    mounted() {
        this.getQuestions();
    }
}
</script>

<style scoped lang="scss">
    .questions-page {
        display: flex;
        flex-direction: column;
        align-items: center;

        .questions {
            display: flex;
            flex-direction: column;
            width: 95%;
            padding-bottom: 1rem;

            input {
                width: 200px;
                height: 30px;
                border-radius: 5px;
                border: 1px solid #ccc;
                padding: 5px;
                margin-bottom: 10px;
            }
        }

        .list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            grid-gap: 1rem;

            .new-question {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;

                input {
                    width: 100%;
                    padding: 1.5rem 1rem;
                    margin-bottom: 1rem;
                    font-size: 1.1rem;
                }

                .question-type {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: center;
                    width: 90%;

                    label {
                        display: flex;
                        border: 1px solid #ccc;
                        cursor: pointer;
                        color: black;
                        margin-right: 0.3rem;
                        flex: 1;

                        &:hover {
                            background-color: #00bcd4;
                            color: white;
                        }

                        &.active {
                            background-color: #00bcd4;
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
                            font-size: 1.1rem;
                            font-weight: bold;
                            padding: 0.9rem;
                            user-select: none;
                        }

                        input {
                            display: none;
                        }
                    }

                    button {
                        border: none;
                        background-color: #00a65a;
                        color: white;
                        padding: 0.9rem 1rem;
                        font-size: 1.1rem;
                        border-radius: 0.2rem;
                        cursor: pointer;
                        font-weight: bold;
                        user-select: none;
                    }
                }
            }
        }
    }
</style>

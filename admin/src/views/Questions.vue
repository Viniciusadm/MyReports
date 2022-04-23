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
                    <select v-model="question.type">
                        <option value="good">Bom</option>
                        <option value="neutral">Neutro</option>
                        <option value="bad">Ruim</option>
                    </select>
                    <button @click="addQuestion()">Adicionar</button>
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

            @media screen and (max-width: 768px) {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            }

            .new-question {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                height: 160px;

                input {
                    width: 100%;
                    height: calc(100% / 3 - 0.5rem);
                    font-size: 1.1rem;
                    margin-bottom: 0;
                    padding: 0 0.5rem;
                }

                select {
                    width: 100%;
                    height: calc(100% / 3 - 0.5rem);
                    font-size: 1.1rem;
                    border: 1px solid #ccc;
                    background-color: #fff;
                    cursor: pointer;
                    padding: 0 0.5rem;
                    border-radius: 5px;
                }

                button {
                    border: none;
                    height: calc(100% / 3 - 0.5rem);
                    background-color: #00a65a;
                    color: white;
                    font-size: 1.1rem;
                    border-radius: 0.2rem;
                    cursor: pointer;
                    font-weight: bold;
                    user-select: none;
                }
            }
        }
    }
</style>

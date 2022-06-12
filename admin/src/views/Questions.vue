<template>
    <div class="questions-page">
        <div class="questions">
            <input type="date" v-model="date" @change="getQuestions()">
            <p class="points">Pontuação: {{ points }}</p>
            <div v-if="!carregando" class="list">
                <question :date="date"
                          @answered="reply($event)"
                          @comment="comment($event)"
                          @question="changeQuestion($event)"
                          @type="changeType($event)"
                          @disable="disable($event)"
                          v-for="question in questions" :question="question"
                          :key="question.id">
                </question>
                <div v-if="!carregando" class="new-question">
                    <input type="text" @keyup.enter="addQuestion()" v-model="question.text" placeholder="Nova pergunta">
                    <div class="selects">
                        <select v-model="question.yes">
                            <option value="good">Bom</option>
                            <option value="neutral">Neutro</option>
                            <option value="bad">Ruim</option>
                        </select>
                        <select v-model="question.no">
                            <option value="good">Bom</option>
                            <option value="neutral">Neutro</option>
                            <option value="bad">Ruim</option>
                        </select>
                    </div>
                    <button @click="addQuestion()">Adicionar</button>
                </div>
            </div>
        </div>
        <loading v-if="carregando" />
    </div>
</template>

<script>
import api from "@/services/api";
import moment from "moment";
import question from "@/components/Questions/QuestionComponent";
import { useToast } from "vue-toastification";
import loading from "@/components/Loading";

const toast = useToast();

export default {
    data() {
        return {
            questions: [],
            debounce: null,
            date: moment().format("YYYY-MM-DD"),
            question: {
                text: "",
                yes: "good",
                no: "good",
            },
            points: 0,
            carregando: true,
        }
    },
    components: {
        question,
        loading,
    },
    methods: {
        getQuestions() {
            clearTimeout(this.debounce);

            this.debounce = setTimeout(() => {
                if (!moment(this.date).isAfter(moment().format("YYYY-MM-DD"))) {
                    this.carregando = true;
                    api.get("/questions", {
                        params: {
                            date: this.date,
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            this.questions = response.data.data;
                            this.setPoints(this.questions);
                        } else {
                            toast.error(response.data.message);
                        }
                    })
                    .finally(() => {
                        this.carregando = false;
                    });
                } else {
                    this.date = moment().format("YYYY-MM-DD");
                }
            }, 500);
        },
        setPoints(questions) {
            this.points = 0;
            const score = {
                good: 1,
                neutral: 0,
                bad: -1,
            };

            questions.forEach(question => {
                if (!question.answer) return;

                if (question.answer.answer === 'yes') {
                    this.points += score[question.yes];
                } else {
                    this.points += score[question.no];
                }
            });
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
                    question.yes = $event.type;
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
                    yes: this.question.yes,
                    no: this.question.no,
                })
                .then(response => {
                    if (response.data.success) {
                        this.questions.push(response.data.data);
                        this.question = {
                            text: "",
                            yes: "good",
                            no: "good",
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

            .points {
                font-size: 1.5rem;
                margin-bottom: 0.8rem;
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

                    .selects {
                        display: flex;
                        flex-direction: row;
                        justify-content: space-between;
                        height: calc(100% / 3 - 0.5rem);

                        select {
                            width: calc(100% / 2 - 0.2rem);
                            font-size: 1.1rem;
                            border: 1px solid #ccc;
                            background-color: #fff;
                            cursor: pointer;
                            padding: 0 0.5rem;
                            border-radius: 5px;
                        }
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
    }
</style>

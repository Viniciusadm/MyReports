import { createStore } from "vuex";

export default createStore({
    state: {
        humors: {
            anxious: "Ansioso",
            afraid: "Cansado",
            excited: "Excitado",
            happy: "Feliz",
            scared: "Assustado",
            nervous: "Nervoso",
            neutral: "Neutro",
            thoughtful: "Pensativo",
            angry: "Irritado",
            surprised: "Surpreso",
            sad: "Triste",
        },
        people: []
    },
    mutations: {
        setPeople(state, people) {
            state.people = people;
        }
    },
    actions: {},
    modules: {},
});

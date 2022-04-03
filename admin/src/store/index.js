import {createStore} from "vuex";

export default createStore({
    state: {
        humors: {
            anxious: "Ansioso",
            afraid: "Cansado",
            bored: "Entediado",
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
        },
    },
    modules: {},
    getters: {
        searchPeople: state => (search, exclude) => {
            return state.people.filter(person => {
                return person.name.toLowerCase().includes(search.toLowerCase()) && exclude.indexOf(person.id) === -1;
            });
        },
    }
});

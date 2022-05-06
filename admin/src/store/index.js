import {createStore} from "vuex";

export default createStore({
    state: {
        humors: {
            anxious: "Ansioso",
            good: "Bem",
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
        types: {
            anime: "Anime",
            dorama: "Dorama",
            cartoon: "Desenho",
            movie: "Filme",
            serie: "Série",
            special: "Especial",
            specials: "Especiais",
            youtube: "YouTube",
            other: "Outro",
        }
    },
    mutations: {},
    modules: {},
    getters: {}
});

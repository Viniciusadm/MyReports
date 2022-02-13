import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        humors: {
            anxious: 'Ansioso',
            afraid: 'Cansado',
            excited: 'Excitado',
            happy: 'Feliz',
            scared: 'Assustado',
            nervous: 'Nervoso',
            neutral: 'Neutro',
            thoughtful: 'Pensativo',
            angry: 'Irritado',
            surprised: 'Surpreso',
            sad: 'Triste'
        },
    },
    mutations: {

    },
    actions: {

    },
    modules: {

    }
})

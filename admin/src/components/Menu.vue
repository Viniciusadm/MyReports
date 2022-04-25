<template>
    <nav class="menu">
        <router-link to="/">Página Inicial</router-link>
        <router-link to="/reports">Relatos</router-link>
        <router-link to="/questions">Perguntas</router-link>
        <router-link to="/people">Pessoas</router-link>
        <router-link to="/assis">Assis</router-link>
    </nav>
    <nav class="menu-mobile">
        <div class="top">
            <button class="menu-mobile-button" @click="showMenu">Abrir Menu</button>
            <router-link to="/">Manager Life</router-link>
        </div>
        <div class="itens" ref="menuMobile">
            <router-link @click="showMenu" to="/">Página Inicial</router-link>
            <router-link @click="showMenu" to="/reports">Relatos</router-link>
            <router-link @click="showMenu" to="/questions">Perguntas</router-link>
            <router-link @click="showMenu" to="/people">Pessoas</router-link>
            <router-link @click="showMenu" to="/assis">Assis</router-link>
        </div>
    </nav>
</template>

<script>
    export default {
        methods: {
            showMenu() {
                this.$refs.menuMobile.classList.toggle('show');
            }
        }
    }
</script>

<style lang="scss" scoped>
    .menu {
        display: flex;
        margin-left: 1rem;

        @media (max-width: 425px) {
            display: none;
        }

        a {
            margin-left: 1rem;
            text-decoration: none;
            color: rgb(0, 0, 0);
            font-size: 1.2rem;
            font-weight: bold;
            transition: color 0.4s;
            padding: 1rem 0;

            &:first-child {
                margin-left: 0;
            }

            &:hover {
                color: rgb(43, 33, 187);
            }
        }
    }

    .menu-mobile {
        @media (min-width: 426px) {
            display: none;
        }

        .top {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 0.6rem 1rem;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            border-bottom: 1px solid #575757;

            button {
                border: 1px solid black;
                background: transparent;
                font-size: 1.2rem;
                font-weight: bold;
                transition: color 0.1s;
                padding: 0.6rem;
                border-radius: 4px;
            }

            a {
                font-size: 1.2rem;
                font-weight: bold;
                padding: 1rem;
                text-decoration: none;
                color: rgb(0, 0, 0);
            }
        }

        .itens {
            width: 100%;
            display: flex;
            flex-direction: column;
            background: #575757;
            height: 0;
            overflow: hidden;
            transition: height 0.4s;
            margin-bottom: 0.6rem;

            &.show {
                height: 275px;
            }

            a {
                text-decoration: none;
                color: white;
                font-size: 1.2rem;
                font-weight: bold;
                transition: color 0.4s;
                padding: 1rem 0;
                text-align: center;
                border-bottom: 1px solid white;
            }
        }
    }
</style>

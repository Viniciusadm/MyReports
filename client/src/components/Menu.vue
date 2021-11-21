<template>
    <nav class="menu">
        <router-link to="/">Página Inicial</router-link>
        <router-link to="/reports">Registros</router-link>
        <router-link to="/people">Pessoas</router-link>
    </nav>
</template>

<style lang="scss">
    .menu {
        display: flex;
        margin-left: 1rem;

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
</style>

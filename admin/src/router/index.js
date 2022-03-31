import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        name: "Home",
        component: () => import("../views/Home.vue"),
    },
    {
        path: "/reports",
        name: "Reports",
        component: () => import("../views/reports/Reports.vue"),
    },
    {
        path: "/reports/:id",
        name: "Report",
        component: () => import("../views/reports/Report.vue"),
    },
    {
        path: "/people",
        name: "People",
        component: () => import("../views/people/People.vue"),
    },
    {
        path: "/people/:id",
        name: "Person",
        component: () => import("../views/people/Person.vue"),
    },
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;

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
        path: "/report/:id",
        name: "Report",
        component: () => import("../views/reports/Report.vue"),
    },
    {
        path: "/reports/new",
        name: "NewReport",
        component: () => import("../views/reports/ReportEdit.vue"),
    },
    {
        path: "/reports/edit/:id",
        name: "EditReport",
        component: () => import("../views/reports/ReportEdit.vue"),
    },
    {
        path: "/people",
        name: "People",
        component: () => import("../views/people/People.vue"),
    },
    {
        path: "/person/:id",
        name: "Person",
        component: () => import("../views/people/Person.vue"),
    },
    {
        path: "/people/new",
        name: "NewPerson",
        component: () => import("../views/people/PersonEdit.vue"),
    },
    {
        path: "/people/edit/:id",
        name: "EditPerson",
        component: () => import("../views/people/PersonEdit.vue"),
    }
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router;

import type {MenuItem} from "~/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
    {
        pages: [
            {
                heading: "dashboard",
                route: "/admin/dashboard",
                keenthemesIcon: "element-11",
                bootstrapIcon: "bi-app-indicator",
            },
        ],
    },
    {
        heading: "baseFunctionality",
        route: "/base",
        pages: [
            {
                sectionTitle: "admins",
                route: "/admin/admins",
                keenthemesIcon: "profile-circle",
                bootstrapIcon: "bi-person",
                sub: [
                    {
                        heading: "admins",
                        route: "/admin/admins",
                    },
                    {
                        heading: "createAdmin",
                        route: "/admin/admins/create",
                    },
                ],
            },
            {
                sectionTitle: "categories",
                route: "/admin/categories",
                keenthemesIcon: "flag",
                bootstrapIcon: "bi bi-tags-fill",
                sub: [
                    {
                        heading: "categories",
                        route: "/admin/categories",
                    }, {
                        heading: "createCategory",
                        route: "/admin/categories/create",
                    },
                ]
            },
            {
                heading: "of_users",
                route: "/admin/of-users",
                keenthemesIcon: "user",
                bootstrapIcon: "bi-person"
            },
            {
                heading: "Telescope",
                tag: 'a',
                route: "/admin/telescope",
                keenthemesIcon: "glass",
                bootstrapIcon: "bi-person"
            }
        ],
    },
];

export default MainMenuConfig;

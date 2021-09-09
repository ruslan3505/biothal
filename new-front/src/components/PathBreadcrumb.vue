<template>
    <v-breadcrumbs class="breadcrumb" :items="items">
        <template v-slot:item="{ item }">
            <v-breadcrumbs-item
                append
                link>
                <router-link class="text-decoration-none black--text" :to="{name: item.name}">
                    {{ item.meta.label }}
                </router-link>
            </v-breadcrumbs-item>
        </template>
    </v-breadcrumbs>
</template>

<script>
    export default {
        name: "PathBreadcrumb",
        props: {
            labelCurrentRoute: {
                type: String,
                default: ''
            },
        },

        computed: {
            items() {
                const routes = this.$route.matched;
                routes[routes.length - 1].meta.label = this.labelCurrentRoute || routes[routes.length - 1].meta.label;

                return routes;
            },
        },
    }
</script>

<style lang="scss" scoped>

    .breadcrumb {
        padding: 0 0 38px !important;
        @media screen and (max-width: 600px) {
            padding: 10px 0 10px 20px !important;
            width: 100%;
        }

        & > * {
            font-size: 10px !important;
            line-height: 16px !important;

            & > a {
                color: black !important;
            }
        }
    }

</style>

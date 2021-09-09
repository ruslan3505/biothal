<template>
    <v-navigation-drawer
        app
        v-model="visible"
        absolute
        clipped
        height="auto"
        width="100%">
        <div :style="`height: ${marginTopNavigation}`"/>
        <v-expansion-panels accordion>
            <v-expansion-panel
                v-for="(item,index) in menuItems"
                :key="index"
                :readonly="true">
                <v-expansion-panel-header
                    style="background-color: #fff; color: #000"
                    :expand-icon="item.meta.icon"
                    @click="item.click ? item.click() : toPageMenu(item)">
                    {{item.name}}
                </v-expansion-panel-header>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-navigation-drawer>
</template>

<script>
    export default {
        name: "AccountMenuMobile",
        data: () => ({
            visible: false,
            marginTopNavigation: 0
        }),
        mounted() {
            this.marginTopNavigation = this.$parent.$refs['app-bar']?.styles.height

            const _this = this;
            const onScroll = function(){
              return _this.marginTopNavigation = (+document.scrollingElement.scrollTop) + (+_this.$parent.$refs['app-bar']?.height) + 'px'
            };
            onScroll();
            document.addEventListener('scroll', onScroll);
            this.$on('hook:beforeDestroy', () => document.removeEventListener('scroll', onScroll));
        },
        computed: {
            menuItems() {
                const isLogin = [
                    {
                        name: 'Личные данные',
                        meta: {
                            icon: 'account_circle',
                            rout: {name: 'account-settings'}
                        }
                    },
                    {
                        name: 'Изменить пароль',
                        meta: {
                            icon: 'mode_edit_outline',
                            rout: {name: 'password-edit'}
                        }
                    },
                    {
                        name: 'Список заказов',
                        meta: {
                            icon: 'view_quilt',
                            rout: {name: 'order-list'}
                        }
                    },
                    {
                        name: 'Участники групповой скидки',
                        meta: {
                            icon: 'people_alt',
                            rout: {name: 'group-discount-participants'}
                        }
                    },
                    {
                        name: 'Выйти',
                        meta: {
                            icon: 'logout',
                            rout: {name: 'home'}
                        },
                        click: this.logout
                    }
                ]

                const isLogout = [
                    {
                        name: 'Войти',
                        meta: {
                            icon: 'login',
                            rout: {name: 'authorization'}
                        }
                    },
                    {
                        name: 'Зарегистрироваться',
                        meta: {
                            icon: 'app_registration',
                            rout: {name: 'registration'}
                        }
                    }
                ]

                return this.isAuthorize ? isLogin : isLogout
            },
        },
        methods: {
            toPageMenu(item) {
                this.toPage({name: item.meta.rout.name, params: item.meta.rout.params})
            }
        }
    }
</script>

<style scoped lang="scss">

</style>

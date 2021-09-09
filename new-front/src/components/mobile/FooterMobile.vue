<template>
    <v-footer
        class="footer__wrapper"
        :padless="false">
<!--        <div v-if="isMobile" class="special-offer__wrapper">-->
<!--            <p style="margin: auto 0">-->
<!--                Будь в курсе специальных предложений-->
<!--            </p>-->
<!--            <v-icon color="#000" size="25">mdi-gift-outline</v-icon>-->
<!--        </div>-->
        <div v-if="isMobile" class="links__wrapper">
            <div class="footer__email">
                <input v-model="email_for_receive_list" type="text" placeholder="Электронная почта">
            </div>
<!--            <div style="border-right: 1px solid #808080; width: 0; height: 20px"/>-->
            <div class="footer__subscribe" @click="addEmailToReceiveList">
                Подписаться
            </div>
        </div>
        <div class="footer__block-1">
            <div @click="toPage({name: 'home'})">
                <img width="127" height="38" src="../../../public/logo-biothal.svg"/>
            </div>
            <div class="footer__links">
                <a @click="fbMethod" style="margin-right: 10px" href="https://www.youtube.com/channel/UCrfHUxmilxCSfhMG9TKLa1Q" target="_blank" class="link-to-site"><v-icon color="#fff" size="17">mdi-youtube</v-icon></a>
                <a @click="fbMethod" style="margin-right: 10px" href="https://www.facebook.com/biothal.ua/" target="_blank" class="link-to-site"><v-icon color="#fff" size="17">mdi-facebook</v-icon></a>
                <a @click="fbMethod" href="https://www.instagram.com/biothal.ua/" target="_blank" class="link-to-site"><v-icon color="#fff" size="17">mdi-instagram</v-icon></a>
            </div>
        </div>
        <div class="footer__block-2">
            <div class="footer__block-2__block-2-1">
                <v-btn text class="list-item" dark v-for="(item, index) in menuItems.slice(0, 4)" :key="index"
                       @click="toPage({name: 'category', params: {category: item.slug }})">
                    - {{ item.title }}
                </v-btn>
            </div>
            <div class="footer__block-2__block-2-2">
                <div>
                    +38 (099) 088-12-08<br>+38 (067) 568-08-13
                </div>
<!--                <div style="color: #2F7484">-->
<!--                    Обратный звонок-->
<!--                </div>-->
            </div>
        </div>
        <div class="footer__block-3">
            <div>
                <v-btn
                    elevation="0"
                    class="main-button"
                    style="height: 34px!important;"
                    @click="toPage({name: 'distributor'})">
                    Стать дистрибьютером
                </v-btn>
            </div>
            <div>
                <img style="margin-top: 34px" width="37" height="9" src="../../../public/visaMasterCard.svg"/>
            </div>
        </div>
        <div class="footer__block-4">
            <div style="font-size: 11px; font-weight: 400; line-height: 15px;">
                © BIOTHAL 2019—2020
            </div>
            <div style="cursor: pointer; font-size: 9px; font-weight: 400; line-height: 12px;" @click="toPage({name: 'info-page', params: { id: 'polzovatelskoe-soglasenie'}})">
                Пользовательское соглашение
            </div>
        </div>
    </v-footer>
</template>

<script>
    export default {
        name: "FooterMobile",
        data() {
            return {
                catalog: null,
                aboutUs: null,
                socialNetwork: null,
                menuItems: [],
                email_for_receive_list: '',
            }
        },
        created() {
            this.fetchFooterData()
        },
        methods: {
            async fetchFooterData() {
                let data = await this.axios.get('footer');

                this.menuItems = data.data.categories;
            },
            async addEmailToReceiveList(){
                let email = this.email_for_receive_list;
                let valide = /.+@.+/.test(email);
                this.$loading(true)
                this.fbMethod()
                if(valide){
                    try{
                        let data = await this.axios.post('addEmailForReceive', {
                            email:email
                        });
                        if(data){
                            let message = data.data.message
                            this.$notify({
                                type: 'success',
                                title: 'Успех!',
                                text: message
                            });
                            this.email_for_receive_list = ''
                        }
                        this.$loading(false)
                    } catch (e) {
                        this.$notify({
                            type: 'error',
                            title: 'Извините',
                            text: 'Вы ввели не коректный адресс електронной почты!'
                        });
                        this.$loading(false)
                    }
                } else {
                    this.$notify({
                        type: 'error',
                        title: 'Извините',
                        text: 'Вы ввели не коректный адресс електронной почты!'
                    });
                    this.$loading(false)
                }
            },
            fbMethod() {
                console.log('Contact')
                this.$analytics.fbq.event( 'Contact')
            }
        }
    }
</script>

<style scoped lang="scss">
    .footer {

        &__wrapper {
            display: flex;
            flex-direction: row;
            background-color: #000;
            color: #fff;
            padding: 0 0 20px 0;
            width: 100%;
        }

        &__block-1 {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 20px 20px 0 20px;
        }

        &__block-2 {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;

            &__block-2-1 {
                display: flex;
                flex-direction: column;

                button {
                    justify-content: flex-start;
                }
            }

            &__block-2-2 {
                font-size: 14px;
                line-height: 28px;
                text-align: right;
            }
        }

        &__block-3 {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
        }

        &__block-4 {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 0 20px;
        }
    }

    .list-item {
        font-weight: 400;
        font-size: 13px;
        line-height: 23px;
        text-transform: none;
    }

    .main-button {
        text-transform: none;
        color: #000;
        background-color: #fff;
        border-radius: 50px;
        width: 162px;
        height: 34px;
        font-size: 12px;
        font-weight: 300;
        line-height: 16px;
        margin-top: 10px;
    }

    .special-offer {
        &__wrapper {
            width: 100%;
            padding: 10px 20px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            background-color: #EFEFEF;
            color: #000;
        }
    }

    .links {
        &__wrapper {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 25px 30px 25px 19px;
            background-color: #fff;
            position: relative;

          &:before {
            position: absolute;
            line-height: 19px;
            font-size: 14px;
            font-weight: 500;
            content: '|';
            left: 50%;
            color: #808080;
          }
        }
    }

    .link-to-site {
        text-decoration: none;
    }

    .footer {
      &__email {
        text-align: left;
        color: #808080;
        line-height: 19px;
        font-size: 14px;
        font-weight: 200;
      }

      &__subscribe {
        text-align: right;
        color: #0BB7B5;
        line-height: 19px;
        font-size: 14px;
        font-weight: 200;
      }

      &__links {
          display: flex;
      }
    }

    .v-btn {
        height: 30px !important;
    }
</style>

<style lang="scss">
</style>

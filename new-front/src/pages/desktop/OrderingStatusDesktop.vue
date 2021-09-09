<template>
    <div>
        <div class="order-status__title">
            Ваш заказ № {{ id }}
        </div>
        <div class="order-status__content">
            Пожалуйста, ожидайте звонка оператора для
            <br>уточнения деталей заказа и условий доставки.
            <br>Call - центр работает по будням: <b>10:00 — 17:00.</b>
            <br>Суббота / Воскресенье: <b>Выходной.</b>
<!--            <br>Остались вопросы? <br>Звони <b>+38 (099) 088-12-08</b><br><b>+38 (067) 568-08-13</b>-->
<!--            <br>Или пиши нам в instagram <a class="text-decoration-none" href="https://www.instagram.com/biothal.ua/"><b>@biothal.ua</b></a>-->
            <br><h2><b>Написать нам</b></h2>
                <img class="mt-2" style="height: 85px; cursor: pointer" src="../../../public/viber.png" @click="toViber">
                <img class="ml-2 mr-3" style="height: 82px; cursor: pointer" src="../../../public/t_logo.png" @click="toTelegram">
                <img style="height: 80px; cursor: pointer" src="../../../public/instagram.png" @click="toInstagram">
        </div>
        <div class="button">
            <v-btn dark class="order-status__button" elevation="0" @click="toHome">
                На главную
            </v-btn>
        </div>

    </div>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "OrderingStatusDesktop",
        props: {
            token: {
                type: [Number, String],
                default: 1
            },
        },
        beforeRouteEnter(to, from, next) {
            if (to.query.frame) {
                window.parent.location.href = document.location.href.replace('?frame=true', '');
            } else {
                next()
            }
        },
        data() {
            return {
                title: '',
                message: '',
                redirect: '',
                id: '',
                total_sum: ''
            }
        },
        computed: {
            ...mapGetters('basket', [
                'products',
                'globalSales',
                'currentGlobalSales',
                'nextGlobalSales',
                'linear',
                'productsSum',
                'productsSumWithSales'
            ])
        },
        created() {
            this.fbMethod();
            this.fetchOrderStatus();
            this.redirect = setTimeout(
              function () {
                this.toPage({name: 'home'});
              }.bind(this),
                30000
            );
        },
        beforeRouteLeave (to, from, next) {
            clearTimeout(this.redirect);
            next()
        },
        methods: {
            ...mapActions('basket', {
                deleteProduct: 'DELETE_PRODUCT',
                clearCart: 'CLEAR_ALL_CART'
            }),
            clearCartProducts() {
                this.clearCart()
            },
            async fetchOrderStatus() {
                let data = await this.axios.get('order-status/' + this.token);

                this.id = data.data.order.id;
                this.total_sum = data.data.order.total_sum;
            },
            sendEmailToUser(id)
            {
                this.axios.post('sendOrderStatus', {
                    data:this.id
                });
            },
            toHome (){
                this.toPage({name: 'home'});
                clearTimeout(this.redirect);
            },
            fbMethod() {
                let productsIds = [];
                let sum = 0;
                this.products.map(product => {
                    console.log('Purchase',{
                        value: product.currency, currency: 'USD', content_ids: product.id, content_type: 'product', content_category: product.category
                    })
                    productsIds.push(product.id);
                    sum = (sum + (product.currency * product.quantity));
                })
                this.$analytics.fbq.event( 'Purchase', {
                    value: sum, currency: 'USD', content_ids: productsIds, content_type: 'product'
                })
            },
            toTelegram() {
                window.location.href = 'http://t.me/Biothal_ua_bot';
            },
            toViber() {
                window.location.href = 'viber://pa?chatURI=biothal';
            },
            toInstagram() {
                window.location.href = 'https://www.instagram.com/biothal.ua';
            }
        },
        mounted() {
            this.clearCartProducts();
            this.sendEmailToUser(this.id);
        }
    }
</script>

<style scoped lang="scss">
    .order-status {
        &__title {
            text-align: center;
            font-size: 27px;
            margin: 25px;

            @media screen and (max-width: 600px) {
                font-size: 23px;
                margin: 20px;
            }
        }
        &__content {
            text-align: center;
            font-size: 21px;
            margin: 25px;

            @media screen and (max-width: 600px) {
                margin: 20px;
            }
        }

        &__button {
            text-transform: none;
            border-radius: 50px;
            width: 180px;
            height: 48px !important;
            padding: 0 !important;
            background-color: #2F7484 !important;
            font-style: normal;
            font-weight: bold;
            font-size: 16px;
            line-height: 22px;
            margin-bottom: 20px;

            &:hover {
                box-shadow: 0 0 33px #f2f2f2;
                background-color: #000 !important;
            }
        }
    }

    .button {
        width: 100%;
        display: flex;
        justify-content: center;
        padding-bottom: 2.5em;
    }

    b {
        font-weight: bold;
    }

    .text-decoration-none{
        color: black;
        :hover{
            color: #2d687d;
        }
    }
</style>

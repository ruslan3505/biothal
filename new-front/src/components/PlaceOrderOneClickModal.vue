<template>
    <v-dialog
        v-model="visible"
        width="320">

        <v-card class="order-dialog__wrapper">
            <p class="order-dialog__title">Оформить заказ в 1 клик</p>

            <p class="order-dialog__text">Наш менеджер свяжется с вами в течении 30 минут в рабочее время</p>

            <v-form class="order-dialog__form" ref="orderQuickForm">
                <div>
                    <p class="main-input-label">Введите имя</p>
                    <v-text-field
                        v-model="name"
                        :error-messages="errorValid.name"
                        :rules="nameRules"
                        class="main-input-field"
                        background-color="#F7F7F7"
                        flat
                        rounded
                        height="34"/>
                </div>
                <div>
                    <p class="main-input-label">Введите номер телефона</p>
                    <v-text-field
                        v-model="number"
                        :error-messages="errorValid.number"
                        :rules="numberRules"
                        class="main-input-field"
                        background-color="#F7F7F7"
                        flat
                        rounded
                        placeholder="+38(___) ___-__-__"
                        v-mask="'+38(###) ###-##-##'"
                        height="34"/>
                </div>
            </v-form>
            <v-btn dark class="checkout-button" elevation="0" @click="checkout">Оформить быстрый заказ</v-btn>
        </v-card>
    </v-dialog>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "PlaceOrderOneClick",
        props: {
            number: {
                type: [Number, String],
                default: ''
            },
            name: {
                type: [Number, String],
                default: ''
            },
            user_id: {
                type: [Number, String],
                default: ''
            }
        },
        data() {
            return {
                visible: false,
                errorValid: {
                    name: '',
                    number: ''
                },
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
            ]),
            nameRules() {
                return [
                    v => !!v || 'Вы не ввели свое имя',
                    v => v.length >= 2 || 'Имя должно содержать больше чем 2 символа',
                ]
            },
            numberRules() {
                return [
                    v => !!v || 'Вы не ввели свое телефоный номер',
                    v => v.length >= 18 || 'Телефон должен содержать больше чем 12 символа',
                ];
            }
        },
        methods: {
            ...mapActions('basket', {
                deleteProduct: 'DELETE_PRODUCT',
                clearCart: 'CLEAR_ALL_CART'
            }),
            clearCartProducts() {
                this.clearCart()
            },
            async checkout() {
                this.$loading(true);
                try {
                    this.products.map(product => {
                        console.log('InitiateCheckout',{
                            value: product.currency, currency: 'USD', content_ids: product.id, content_type: 'product', content_category: product.category
                        })
                        this.$analytics.fbq.event( 'InitiateCheckout', {
                            value: product.currency, currency: 'USD', content_ids: product.id, content_type: 'product', content_category: product.category
                        })
                    })
                    this.clearValidation()
                    let validate = await this.$refs['orderQuickForm'].validate();

                    if (validate) {
                        const form = {
                            number: this.number,
                            name: this.name,
                            products: this.products,
                            user_id: this.user_id
                        };
                        await this.axios.post('checkout/create/orderQuick', form).then(({data}) => {

                            if (data) {
                                let message = data.message

                                this.$notify({
                                    type: 'success',
                                    title: 'Успех!',
                                    text: message
                                });
                                this.clearValidation();

                                this.toPage({name: 'order-status', params: {token: data.token}});

                                this.clearCartProducts()
                            }
                        })
                    }
                    this.$loading(false);
                } catch (e) {
                    this.$loading(false);
                    this.errorMessagesValidation(e);
                }
            },
            clearValidation() {
                this.errorValid = {
                    name: '',
                    number: '',
                }
            },
        }
    }
</script>

<style scoped lang="scss">

    .main-input-label {
        padding: 0 0 0 15px;
        margin: 0;
        font-weight: 200;
        font-size: 12px;
        line-height: 16px;
    }

    .main-input-field {
        margin: 0;
        padding: 5px;
    }

    .order-dialog {
        &__wrapper {
            text-align: center;
        }

        &__title {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            line-height: 19px;
        }

        &__text {
            margin: 11px 0 0 0;
            padding: 0 9px;
            font-size: 13px;
            line-height: 18px;
        }

        &__form {
          text-align: left;
          margin-top: 10px;
        }
    }

    .checkout-button {
        font-size: 11px;
        line-height: 15px;
        margin-top: 15px;
        height: 34px !important;
        width: max-content;
        padding: 0 46px !important;
    }
</style>

<style lang="scss">
    .order-dialog__wrapper {
        padding: 15px;

        & .v-input__slot {
            border-radius: 4px !important;
            margin: 0 0 5px 0 !important;
        }

        & .v-text-field__details {
            display: block;
        }
    }
</style>

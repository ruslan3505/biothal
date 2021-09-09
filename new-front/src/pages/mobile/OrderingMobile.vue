<template>
    <div class="page-form__wrapper">
        <div class="page-form__top">
            <div class="page-form__top__title">Оформление заказа</div>
        </div>
        <div class="page-form__middle">
            <v-form autocomplete="off" ref="orderForm" style="width: 100%;" v-model="validProfile">
                <div>
                    <p class="main-input-label">Введите номер телефона</p>
                    <v-text-field
                        placeholder="+38(___) ___-__-__"
                        v-mask="'+38(###) ###-##-##'"
                        class="main-input-field"
                        :error-messages="errorValid.number"
                        :rules="numberRules"
                        flat
                        v-model="number"
                        rounded/>
                </div>
                <div class="mt-25px">
                    <p class="main-input-label">Введите имя</p>
                    <v-text-field
                        class="main-input-field"
                        flat
                        v-model="name"
                        :error-messages="errorValid.name"
                        :rules="nameRules"
                        rounded/>
                </div>
                <div class="mt-25px">
                    <p class="main-input-label">Введите фамилию</p>
                    <v-text-field
                        class="main-input-field"
                        flat
                        v-model="surname"
                        :error-messages="errorValid.surname"
                        :rules="surnameRules"
                        rounded/>
                </div>
                <div class="mt-25px">
                    <p class="main-input-label">Введите область</p>
                    <v-autocomplete
                        type="search" autocomplete="off"
                        :items="regions"
                        color="#2F7484"
                        :loading="regionsLoading"
                        v-model="region"
                        :error-messages="errorValid.region"
                        :rules="regionRules"
                        class="main-input-field"
                        flat
                        rounded>
                    </v-autocomplete>
                </div>
                <div class="mt-25px">
                    <p class="main-input-label">Введите город</p>
                    <v-autocomplete
                        type="search" autocomplete="off"
                        :items="cities"
                        :loading="citiesLoading"
                        v-model="city"
                        color="#2F7484"
                        :item-text="(c) => c.name"
                        :error-messages="errorValid.city"
                        :rules="cityRules"
                        class="main-input-field"
                        flat
                        rounded>
                    </v-autocomplete>
                </div>
                <div v-if="deliveryMethod === 1" class="mt-25px">
                    <p class="main-input-label">Выберите отделение Новой Почты</p>
                    <v-autocomplete
                        type="search" autocomplete="off"
                        :items="postalOffices"
                        :loading="postalOfficesLoading"
                        v-model="postalOffice"
                        :error-messages="errorValid.postalOffice"
                        :rules="postalOfficeRules"
                        color="#2F7484"
                        :item-text="c => c.name"
                        :item-value="c => c"
                        class="main-input-field"
                        flat
                        rounded>
                    </v-autocomplete>
                </div>
                <div v-else class="mt-18px">
                    <p class="main-input-label">Введите адрес доставки</p>
                    <v-text-field
                        type="search" autocomplete="off"
                        v-model="postalOffice"
                        :error-messages="errorValid.postalOffice"
                        :rules="postalOfficeRulesInput"
                        color="#2F7484"
                        class="main-input-field"
                        flat
                        rounded>
                    </v-text-field>
                </div>
                <div class="mt-25px">
                    <p class="main-input-label">Выберите способ оплаты</p>
                    <v-select
                        :items="paymentMethods"
                        v-model="paymentMethod"
                        :rules="paymentMethodRules"
                        :item-text="(c) => c.title"
                        :item-value="(c) => c.id"
                        class="main-input-field"
                        flat
                        rounded
                        color="#2F7484"
                    ></v-select>
                </div>
                <div class="mt-18px">
                    <p class="main-input-label">Выберите способ доставки *</p>
                    <v-select
                        :items="deliveryMethods"
                        v-model="deliveryMethod"
                        :rules="deliveryMethodRules"
                        :item-text="(c) => c.title"
                        :item-value="(c) => c.id"
                        class="main-input-field"
                        flat
                        rounded
                        color="#2F7484"
                    ></v-select>
                </div>
            </v-form>
        </div>
        <div class="terms-use">
            <div>
                <v-checkbox
                    :color="variables.basecolor"
                    v-model="termsUse"/>
            </div>
            <div class="terms-use__right">
                <div>
                    Я прочитал и согласен с правилами
                </div>
                <div style="font-weight: 700" @click="toPage({name: 'info-page', params: { id: 'polzovatelskoe-soglasenie'}})">
                    Пользовательское соглашение
                </div>
            </div>
        </div>
        <div class="not-call">
            <v-checkbox
                class="not-call-check"
                :color="variables.basecolor"
                v-model="notCall"
                label="Не перезванивать для подтверждения заказа"/>
            <v-tooltip right>
                <template v-slot:activator="{ on, attrs }">
                    <span v-bind="attrs" v-on="on">
                        <v-icon color="#000" size="24" style="margin: 0 0 0 5px; cursor: help">
                            mdi-alert-circle-outline
                        </v-icon>
                    </span>
                </template>
                <span>
                    В случае выбора этой опции, Ваш заказ будет сформировать и отправлен без звонка менеджера.
                    <br>Пожалуйста, проверьте внимательно все ли данные внесены корректно.
                </span>
            </v-tooltip>
        </div>
        <div class="page-form__bottom">
            <v-btn
                   class="checkout-button white--text"
                   :style="{
                        opacity: !termsUse ? '.5' : '1'
                   }"
                   :disabled="!termsUse"
                   @click="checkout">
                Оформить заказ
            </v-btn>
        </div>
        <v-snackbar
            v-model="showMessage"
            v-bind="snackbar"
            rounded="5">
            <div>
                <span>
                В случае выбора этой опции, Ваш заказ будет сформировать и отправлен без звонка менеджера.
                <br>Пожалуйста, проверьте внимательно все ли данные внесены корректно.
                </span>
            </div>
            <div style="margin-top: 5px; display: flex; justify-content: center">
                <v-btn
                    small
                    bottom
                    center
                    @click="showMessage = false">
                    Закрыть
                </v-btn>
            </div>
        </v-snackbar>
    </div>
</template>

<script>

    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "OrderingMobile",
        components: {},
        data() {
            return {
                termsUse: false,
                deliveryPrice: 40,
                number: '',
                name: '',
                surname: '',
                region: '',
                city: '',
                user_id: '',
                postalOffice: '',
                paymentMethod: '',
                deliveryMethod: 1,
                recommendedProducts: [],
                regions: [],
                regionsLoading: false,
                cities: [],
                citiesLoading: false,
                postalOffices: [],
                postalOfficesLoading: false,
                paymentMethods: [],
                deliveryMethods: [],
                validProfile: false,
                notCall: false,
                showMessage: false,
                snackbar: {
                    top: true,
                    center: true,
                    color: 'rgba(97,97,97,.9)',
                    timeout: 7000,
                    multiLine: true
                },
                errorValid: {
                    name: '',
                    surname: '',
                    email: '',
                    number: '',
                    region: '',
                    city: '',
                    postalOffice: '',
                    deliveryMethod: ''
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
                'productsSumWithSales',
                'getUnfinishedOrderId'
            ]),
            numberRules() {
                return [
                    v => !!v || 'Вы не ввели свое телефоный номер',
                    v => v.length >= 18 || 'Телефон должен содержать больше чем 12 символа',
                ];
            },
            nameRules() {
                return [
                    v => !!v || 'Вы не ввели свое имя',
                    v => v.length >= 2 || 'Имя должно содержать больше чем 2 символа',
                ]
            },
            surnameRules() {
                return [
                    v => !!v || 'Вы не ввели свою фамилию',
                    v => v.length >= 2 || 'фамилия должна содержать больше чем 2 символа',
                ]
            },
            regionRules() {
                return [
                    v => !!v || 'Вы не выбрали регион',
                ]
            },
            cityRules() {
                return [
                    v => !!v || 'Вы не выбрали город',
                ]
            },
            postalOfficeRules() {
                return [
                    v => !!v || 'Вы не выбрали город',
                ]
            },
            postalOfficeRulesInput() {
                return [
                    v => !!v || 'Вы не ввели адрес',
                ]
            },
            paymentMethodRules() {
                return [
                    v => !!v || 'Вы не выбрали способ оплаты',
                ]
            },
            deliveryMethodRules() {
                return [
                    v => !!v || 'Вы не выбрали способ доставки',
                ]
            }
        },
        watch: {
            region() {
                if (this.region !== null && this.region !== '') {
                    this.getCities()
                    this.postalOffices = [];
                    this.city = '';
                }
            },
            city() {
                if (this.region !== null && this.region !== '') {
                    this.getPostalOffices()
                }
            },
            deliveryMethod: function (newValue, oldValue) {
                if (newValue !== 1) {
                    this.postalOffice = '';
                }
            },
            notCall: function (newValue) {
                if (newValue) {
                    this.showMessage = true
                }
            }
        },
        methods: {
            ...mapActions('basket', {
                deleteProduct: 'DELETE_PRODUCT',
                setUnfinishedOrderId: 'SET_UNFINISHED_ORDER_ID',
                clearUnfinishedOrderId: 'CLEAR_UNFINISHED_ORDER_ID'
            }),
            clearValidation() {
                this.errorValid = {
                    name: '',
                    surname: '',
                    email: '',
                    number: '',
                    region: '',
                    city: '',
                    postalOffice: '',
                    paymentMethod: '',
                    deliveryMethod: ''
                }
            },
            getRecommendedProduct() {
                this.axios.post('products/recommended')
                    .then(({data}) => {
                        this.recommendedProducts = data
                    })
            },
            getRegionsAndCities() {
                this.regionsLoading = true;

                this.axios.post('checkout/regions')
                    .then(({data}) => {
                        this.regions = data;
                        this.regionsLoading = false;
                    })
            },
            getCities() {
                this.citiesLoading = true;

                const data = {
                    region: this.region,
                }
                this.axios.post('checkout/cities', data)
                    .then(({data}) => {
                        this.cities = data;
                        this.citiesLoading = false;
                    })
            },
            getPostalOffices() {
                this.postalOfficesLoading = true;

                const city = this.cities.find(c => c.name === this.city)

                this.axios.post('checkout/postal/offices', {city})
                    .then(({data}) => {
                        this.postalOffices = data;
                        this.postalOfficesLoading = false;
                    })
            },
            getPaymentMethods() {
                this.axios.post('checkout/payment/methods')
                    .then(({data}) => {
                        this.paymentMethods = data;
                    })
            },
            getDeliveryMethods() {
                this.axios.post('checkout/delivery/methods')
                    .then(({data}) => {
                        this.deliveryMethods = data;
                    })
            },
            async checkout() {
                try {
                    let productIds = [];
                    let sum = 0;
                    this.products.map(product => {
                        console.log('InitiateCheckout',{
                            value: product.currency, currency: 'USD', content_ids: product.id, content_type: 'product', content_category: product.category
                        })
                        productIds.push(product.id);
                        sum = (sum + (product.currency * product.quantity));
                    })
                    this.$analytics.fbq.event( 'InitiateCheckout', {
                        value: sum, currency: 'USD', content_ids: productIds, content_type: 'product'
                    })
                    this.clearValidation()
                    let validate = await this.$refs['orderForm'].validate();

                    if (validate) {
                        this.$loading(true)
                        const form = {
                            number: this.number,
                            name: this.name,
                            surname: this.surname,
                            city: this.city,
                            region: this.region,
                            postalOffice: this.postalOffice,
                            paymentMethod: this.paymentMethod,
                            deliveryMethod: this.deliveryMethod,
                            products: this.products,
                            user_id: this.user_id,
                            notCall: this.notCall,
                            unfinished_order_id: this.getUnfinishedOrderId
                        };

                        await this.axios.post('checkout/create/order', form).then(({data}) => {
                            let message = data.message

                            this.$notify({
                                type: 'success',
                                title: 'Успех!',
                                text: message
                            });

                            this.clearUnfinishedOrderId();
                            this.clearValidation();
                            let postData = data.portmone
                            if (postData) {
                                this.toPage({name: 'payment', params: {paymentUrl: postData}});
                            } else {
                                this.$loading(false);
                                this.toPage({name: 'order-status', params: {token: data.token}});
                            }
                        })
                    } else {
                        if (this.number.length >= 18 && this.name.length >= 2) {
                            const form = {
                                number: this.number,
                                name: this.name,
                                products: this.products,
                                user_id: this.user_id,
                                unfinished_order_id: this.getUnfinishedOrderId,
                            };

                            await this.axios.post('checkout/create/unfinishedOrder', form).then(({data}) => {

                                this.setUnfinishedOrderId(data.order_id);
                            })
                        }
                    }
                } catch (e) {
                    this.$loading(false)
                    this.errorMessagesValidation(e);
                }
            },
            async getProfile() {
                await this.checkUserIsValid()
                try {
                    const token = this.$store.getters.getToken;
                    if (token) {
                        let data = await this.axios.post('profile', {}, {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        });
                        if (data) {
                            let user = data.data.user
                            this.number = user.phone_number,
                                this.name = user.name,
                                this.surname = user.sur_name,
                                this.user_id = user.id
                        }
                    }
                } catch (e) {
                    this.errorMessagesValidation(e);
                }
            },
            async checkUserIsValid() {
                try {
                    const token = this.$store.getters.getToken;
                    if (token) {
                        let data = await this.axios.post('checkUser', {}, {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        });
                        if (data) {
                            let exist = data.data.exist
                            if (!exist) {
                                await this.$store.dispatch('LOGIN', null);
                                return false;
                            }
                        } else {
                            await this.$store.dispatch('LOGIN', null);
                        }
                        return true;
                    } else {
                        return false;
                    }
                } catch (e) {
                    await this.$store.dispatch('LOGIN', null);
                    this.errorMessagesValidation(e);
                }
            }
        },
        mounted() {
            this.getProfile();
            this.getRecommendedProduct();
            this.getRegionsAndCities();
            this.getDeliveryMethods();
            this.getPaymentMethods();
        }
    }
</script>

<style scoped lang="scss">
    .page-form {
        &__wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: scroll;
            padding: 20px 20px 40px 20px;
        }

        &__top {
            display: flex;
            flex-direction: column;
            text-align: center;

            &__title {
                font-size: 16px;
                line-height: 19px;
                font-weight: 400;
            }
        }

        &__middle {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        &__bottom {
            width: 100%;
            display: flex;
            justify-content: center;

            .theme--light.v-btn {
                color: white !important;
                background-color: #2F7484 !important;
            }
        }
    }

    .terms-use {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        font-size: 14px;
        margin-top: 15px;

        &__right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
    }

    .mt-25px {
        margin-top: 25px;
    }

    .not-call {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        width: 100%;
        height: 1em;
        margin-top: 15px;

        @media screen and (max-width: 1000px) {
            margin-bottom: 1em;
        }
    }

    .message-not-call {
        color: black;
    }

    .main-input-label {
        font-size: 14px;
        color: #000000;
        margin-bottom: 10px;
    }
</style>

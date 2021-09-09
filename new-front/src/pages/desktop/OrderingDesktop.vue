<template>
    <div class="ordering__wrapper">
        <div class="ordering__content">
            <div class="ordering__top">
                <div class="ordering__top__title">Оформление заказа</div>
                <template v-if="globalSales.length > 0 && nextGlobalSales !== null">
                    <div style="margin-bottom: 10px; font-size: 16px">
                        Еще {{ nextGlobalSales.sum_modal - productsSum }} грн и сработает скидка
                        {{ nextGlobalSales.procent_modal }}%
                    </div>
                    <v-progress-linear :value="linear"
                                       color="#2F7484"
                                       background-color="#ddd"
                                       class="main-linear"
                                       height="12"/>
                </template>
            </div>
            <div class="ordering__middle">
                <div class="ordering__middle__left">
                    <div>
                        <v-form autocomplete="off" ref="orderForm" style="width: 100%;" v-model="validProfile">
                            <div>
                                <p class="main-input-label">Введите номер телефона *</p>
                                <v-text-field
                                    v-model="number"
                                    :error-messages="errorValid.number"
                                    :rules="numberRules"
                                    placeholder="+38(___) ___-__-__"
                                    v-mask="'+38(###) ###-##-##'"
                                    class="main-input-field"
                                    background-color="#F7F7F7"
                                    flat
                                    rounded
                                    color="#2F7484"
                                    height="44"/>
                            </div>
                            <div class="mt-18px">
                                <p class="main-input-label">Введите имя *</p>
                                <v-text-field
                                    v-model="name"
                                    :error-messages="errorValid.name"
                                    :rules="nameRules"
                                    class="main-input-field"
                                    background-color="#F7F7F7"
                                    flat
                                    rounded
                                    color="#2F7484"
                                    height="44"/>
                            </div>
                            <div class="mt-18px">
                                <p class="main-input-label">Введите фамилию *</p>
                                <v-text-field
                                    v-model="surname"
                                    :error-messages="errorValid.surname"
                                    :rules="surnameRules"
                                    class="main-input-field"
                                    background-color="#F7F7F7"
                                    flat
                                    rounded
                                    color="#2F7484"
                                    height="44"/>
                            </div>
                            <div class="mt-18px">
                                <p class="main-input-label">Введите область *</p>
                                <v-autocomplete
                                    type="search" autocomplete="off"
                                    :items="regions"
                                    color="#2F7484"
                                    :loading="regionsLoading"
                                    v-model="region"
                                    :error-messages="errorValid.region"
                                    :rules="regionRules"
                                    class="main-input-field"
                                    height="44"
                                    flat
                                    rounded
                                    background-color="#F7F7F7">
                                </v-autocomplete>
                            </div>
                            <div class="mt-18px">
                                <p class="main-input-label">Введите город *</p>
                                <v-autocomplete
                                    type="search" autocomplete="off"
                                    :items="cities"
                                    :loading="citiesLoading"
                                    v-model="city"
                                    :error-messages="errorValid.city"
                                    :rules="cityRules"
                                    color="#2F7484"
                                    :item-text="(c) => c.name"
                                    class="main-input-field"
                                    height="44"
                                    flat
                                    rounded
                                    background-color="#F7F7F7">
                                </v-autocomplete>
                            </div>
                            <div v-if="deliveryMethod === 1" class="mt-18px">
                                <p class="main-input-label">Выберите отделение Новой Почты *</p>
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
                                    height="44"
                                    flat
                                    rounded
                                    background-color="#F7F7F7">
                                </v-autocomplete>
                            </div>
                            <div v-else class="mt-18px">
                                <p class="main-input-label">Введите адрес доставки*</p>
                                <v-text-field
                                    v-model="postalOffice"
                                    :error-messages="errorValid.postalOffice"
                                    :rules="postalOfficeRulesInput"
                                    color="#2F7484"
                                    name="name"
                                    class="main-input-field"
                                    height="44"
                                    flat
                                    rounded
                                    background-color="#F7F7F7">
                                </v-text-field>
                            </div>
                            <div class="mt-18px">
                                <p class="main-input-label">Выберите способ оплаты *</p>
                                <v-select
                                    :items="paymentMethods"
                                    v-model="paymentMethod"
                                    :rules="paymentMethodRules"
                                    :item-text="(c) => c.title"
                                    :item-value="(c) => c.id"
                                    class="main-input-field"
                                    background-color="#F7F7F7"
                                    flat
                                    rounded
                                    color="#2F7484"
                                    height="44"
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
                                    background-color="#F7F7F7"
                                    flat
                                    rounded
                                    color="#2F7484"
                                    height="44"
                                ></v-select>
                            </div>
                        </v-form>
                    </div>
                    <div class="not-call">
                        <v-checkbox
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
                    <div class="ordering__middle__left__checkout">
                        <div class="checkout-button__wrapper" @click="checkout">
                            <v-btn dark class="checkout-button" elevation="0">Оформить заказ</v-btn>
                        </div>
                        <div class="checkout-link" @click="fbMethod(), $refs['PlaceOrderOneClick'].visible=true">
                            Оформить в 1 клик
                        </div>
                    </div>
                </div>
                <div class="ordering__middle__right">
                    <div class="ordering__middle__right__product-set">
                        <ProductCardsSet type-set="basket"
                                         @delete="deleteProduct"
                                         :product-data="products"
                                         :is-show-title="false"/>
                    </div>
                    <div class="total__wrapper">
                        <div class="total">
                            <div class="total__left">
                                Стоимость товаров:
                            </div>
                            <div class="total__right">
                                {{ productsSum }} грн.
                            </div>
                        </div>
                        <div class="total" v-if="currentGlobalSales !== null">
                            <div class="total__left">
                                Скидка:
                            </div>
                            <div class="total__right">
                                {{ currentGlobalSales.procent_modal }}%.
                            </div>
                        </div>
                        <div class="total" v-if="currentGroupSales !== null && currentGlobalSales === null">
                            <div class="total__left">
                                Скидка:
                            </div>
                            <div class="total__right">
                                {{currentGroupSales.percent}}%.
                            </div>
                        </div>
                        <div class="total">
                            <div class="total__left" style="font-weight: 700">
                                Итого к оплате:
                            </div>
                            <div class="total__right" style="font-weight: 700">
                                {{ (productsSumWithSales).toFixed(2) }} грн.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <ProductCardsSet title="Рекомендуемые товары" :product-data="recommendedProducts.slice(0, 6)"/>
        </div>

        <PlaceOrderOneClick ref="PlaceOrderOneClick" :name="name" :number="number" :user_id="user_id"/>
    </div>
</template>

<script>
    import PlaceOrderOneClick from "../../components/PlaceOrderOneClickModal";
    import ProductCardsSet from "../../components/desktop/ProductCardsSetDesktop";
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "OrderingDesktop",
        components: {
            PlaceOrderOneClick,
            ProductCardsSet
        },
        data() {
            return {
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
                errorValid: {
                    name: '',
                    surname: '',
                    email: '',
                    number: '',
                    region: '',
                    city: '',
                    postalOffice: '',
                    paymentMethod: '',
                    deliveryMethod: ''
                },
                profile: {
                    number: '',
                    name: '',
                    surname: '',
                    user_id: ''
                }
            }
        },
        computed: {
            ...mapGetters('basket', [
                'products',
                'globalSales',
                'currentGlobalSales',
                'nextGlobalSales',
                'groupSales',
                'currentGroupSales',
                'nextGroupSales',
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
            products: function (newProducts) {
                if (newProducts.length === 0) {
                    this.toPage({name: 'home'})
                }
            },
            deliveryMethod: function (newValue) {
                if (newValue !== 1) {
                    this.postalOffice = '';
                }
            }
        },
        methods: {
            ...mapActions('basket', {
                deleteProduct: 'DELETE_PRODUCT',
                setGlobalSales: 'SET_GLOBAL_SALES',
                setGroupSales: 'SET_GROUP_SALES',
                setUnfinishedOrderId: 'SET_UNFINISHED_ORDER_ID',
                clearUnfinishedOrderId: 'CLEAR_UNFINISHED_ORDER_ID'
            }),
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
            async checkout() {
                try {
                    let productIds = [];
                    let sum = 0;
                    this.products.map(product => {
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
                    this.$loading(false);
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
            },
            fbMethod() {
                this.products.map(product => {
                    console.log('InitiateCheckout',{
                        value: product.currency, currency: 'USD', content_ids: product.id, content_type: 'product', content_category: product.category
                    })
                    this.$analytics.fbq.event( 'InitiateCheckout', {
                        value: product.currency, currency: 'USD', content_ids: product.id, content_type: 'product', content_category: product.category
                    })
                })
            }
        },
        mounted() {
            this.getRecommendedProduct();
            this.getRegionsAndCities();
            this.getPaymentMethods();
            this.getDeliveryMethods();
            this.getProfile()
        }
    }
</script>

<style scoped lang="scss">

    @import 'src/styles/mixins';

    .ordering {

        &__wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: scroll;
        }

        &__content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        &__top {
            display: flex;
            flex-direction: column;
            text-align: center;

            &__title {
                font-size: 18px;
                margin: 20px;
            }
        }

        &__middle {
            display: flex;
            flex-direction: row;
            margin-top: 30px;
            flex-wrap: wrap;

            &__left {
                display: flex;
                flex-direction: column;
                width: 50%;

                &__checkout {
                    display: flex;
                    margin-top: 20px;

                    @include media(991) {
                        flex-direction: column;
                        justify-content: center;
                    }
                }
            }

            &__right {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                width: 50%;

                &__product-set {
                    height: 610px;
                    overflow: auto;
                    padding: 7px;
                }
            }
        }
    }

    .main-input-label {
        padding: 0 0 0 20px;
        margin: 0;
        font-weight: 200;
        font-size: 14px;
        line-height: 16px;
        color: black;
    }

    .main-input-field {
        margin: 0;
        padding: 5px;
    }

    .checkout-button {
        border-radius: 50px;
        width: 190px;
        height: 44px !important;
        font-size: 14px;
        line-height: 17px;
        font-weight: 500;

        &__wrapper {
            display: flex;

            @include media(991) {
                justify-content: center;
            }
        }
    }

    .checkout-link {
        display: flex;
        font-weight: 200;
        font-size: 14px;
        line-height: 16px;
        text-decoration-line: underline;
        margin: auto 36px;


        &:hover {
            cursor: pointer;
        }

        @media screen and (max-width: 991px) {
            justify-content: center;
            margin-top: 10px;
        }
    }

    .total {
        display: flex;
        flex-direction: row;
        font-weight: 200;
        font-size: 14px;
        line-height: 15px;
        padding-bottom: 5px;

        &__wrapper {
            padding: 20px 0 0 70px;
        }

        &__left {
            width: 50%;
        }

        &__right {
            width: 50%;
            text-align: right;
        }
    }

    .main-linear {
        border-radius: 60px;
    }

    .mt-18px {
        margin-top: 18px;
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
</style>

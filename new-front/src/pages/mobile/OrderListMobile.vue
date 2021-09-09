<template>
    <div class="order-list__wrapper">
        <div class="default-text__wrapper">
            <v-data-table
                :headers="orderListHeaders"
                :items="orderList"
                :expanded.sync="expanded"
                item-key="id"
                show-expand
                single-expand
                @item-expanded="fetchOrderProducts"
                class="elevation-1"
                mobile-breakpoint="0"
                :hide-default-footer="!orderList.length"
                :hide-default-header="!orderList.length"
                :footer-props="{
                'items-per-page-text':'Заказов на странице',
            }">
                <template v-if="orderList.length" v-slot:top>
                    <v-toolbar flat>
                        <v-spacer></v-spacer>
                        <v-toolbar-title>Список заказов</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                </template>
                <template v-if="orderList.length" slot="body.append, body.isMobile" >
                    <tr>
                        <th></th><th></th><th></th><th class="sum-orders">Сумма заказов</th>
                        <th class="sum-orders">{{ sumField('total_sum') }}</th>
                    </tr>
                </template>
                <template slot="no-data">
                    <div class="title">
                        Список заказов пуст
                    </div>
                </template>
                <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">
                    <div class="product-card__content">
                        <OrderProductCard class="product-card__item-three"
                                          v-for="product in orderProducts"
                                          :key="product.id"
                                          :data-card="product"/>
                    </div>
                    <hr>
                    <div class="product-basket__left">
                        <div>
                            Область: {{item.user_address ? item.user_address.region ? item.user_address.region : 'не указано' : ''}}
                        </div>
                        <div>
                            Город: {{item.user_address ? item.user_address.cities ? item.user_address.cities : 'не указано' : ''}}
                        </div>
                        <div>
                            {{item.user_address ? item.user_address.is_address_delivery ? 'Адрес:' : 'Отделение:' : ''}}
                            {{item.user_address ? item.user_address.department ? item.user_address.department : 'не указано' : ''}}
                        </div>
                        <div v-if="item.sale_id">
                            {{ item.sale_type === 1 ? 'Глобальная' : 'Групповая'}}
                            скидка: {{item.sale_type === 1 ? item.global_sales.procent_modal : item.group_sales.percent }}%
                        </div>
                    </div>
                </td>
            </template>
            </v-data-table>
        </div>
    </div>
</template>

<script>
    import OrderProductCard from "../../components/OrderProductCard";

    export default {
        name: "OrderListMobile",
        components: {
            OrderProductCard
        },
        data() {
            return {
                isOrders: true,
                expanded: [],
                singleExpand: false,
                orderListHeaders: [
                    {
                        text: 'Номер заказа',
                        align: 'start',
                        sortable: false,
                        value: 'id',
                    },
                    { text: 'Тип оплаты', sortable: false, value: 'order_type.title' },
                    { text: 'Статус', sortable: false, value: 'order_status.name' },
                    { text: 'Сумма (грн)', sortable: false, value: 'total_sum' },
                ],
                orderList: [
                    {
                        id: '',
                        order_status: {
                            name: ''
                        },
                        order_type: {
                            title: ''
                        },
                        total_sum: '',
                        // products: {
                        //     attr: {
                        //         image: {
                        //             name: ''
                        //         }
                        //     },
                        //     is_sales: '',
                        //     price: '',
                        //     price_with_sales: '',
                        //     quantity: ''
                        // }
                    }
                ],
                orderProducts: [

                ]
            }
        },
        mounted() {
            this.getProfile();
        },
        watch: {
            route: {
                deep: true,
                handler (newRoute, oldRoute) {
                    this.getProfile();
                }
            },
        },
        methods: {
            async getProfile(){
                await this.checkUserIsValid()
                try {
                    this.$loading(true)
                    const token = this.$store.getters.getToken;
                    if(token){
                        let data = await this.axios.post('profile', {

                        },  {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        });
                        if(data){
                            this.profile = data.data.user
                            this.orderList = data.data.orderList

                            if (this.orderList !== []) {
                                this.orderList.map(function(value, key) {
                                    if (value.order_status.name === 'active') {
                                        value.order_status.name = 'В обработке';
                                    } else if (value.order_status.name === 'payment_process'){
                                        value.order_status.name = 'В процессе оплаты';
                                    } else if (value.order_status.name === 'shipping_process'){
                                        value.order_status.name = 'Отправленна получателю';
                                    } else if (value.order_status.name === 'finish'){
                                        value.order_status.name = 'Получена';
                                    } else if (value.order_status.name === 'pre_order'){
                                        value.order_status.name = 'Предзаказ';
                                    }
                                });
                            }

                        }
                    }
                    this.$loading(false)
                } catch (e) {
                    this.$loading(false)
                    this.errorMessagesValidation(e);
                }
            },
            async checkUserIsValid(){
                try {
                    const token = this.$store.getters.getToken;
                    if(token){
                        let data = await this.axios.post('checkUser', {

                        },  {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        });
                        if(data){
                            let exist = data.data.exist
                            if(!exist){
                                await this.$store.dispatch('LOGIN', null);
                                this.toPage({name: 'AuthorizationMobile'})
                                return false;
                            }
                        } else {
                            await this.$store.dispatch('LOGIN', null);
                            this.toPage({name: 'AuthorizationMobile'})
                        }
                        return true;
                    } else {
                        return false;
                    }
                } catch (e) {
                    await this.$store.dispatch('LOGIN', null);
                    this.toPage({name: 'AuthorizationMobile'})
                    this.errorMessagesValidation(e);
                }
            },
            sumField(key) {
                return (this.orderList.reduce((a, b) => +a + (+b[key] || 0), 0)).toFixed(2)
            },
            async fetchOrderProducts({item, value}){
                if (value) {
                    this.$loading(true)
                    try {
                        const token = this.$store.getters.getToken;
                        if (token) {
                            await this.axios.post('profileOrderProducts/' + item.id, {}, {
                                headers: {
                                    'Authorization': `Bearer ${token}`
                                }
                            }).then(({data}) => {
                                this.orderProducts = data.orderProducts;
                            })
                        }
                        this.$loading(false)
                    } catch (e) {
                        this.$loading(false)
                        this.errorMessagesValidation(e);
                    }
                } else {
                    this.orderProducts = [];
                }
            }
        }
    }
</script>

<style scoped lang="scss">

    @import "src/styles/main";
    @import "@/styles/mixins";

    .order-list {
        &__wrapper {
            /*height: 100%;*/
            background-color: #f7f7f7;
            /*padding: 20px;*/
        }
    }

    .sum-orders {
        font-size: 12px !important;
        font-weight: 400;
    }

    .product-basket {

        &__left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
            margin: 10px 0;
            background-color: #fff;
            min-width: 40%;

        }
    }

</style>

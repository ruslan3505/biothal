<template>
    <div class="product-basket__wrapper">
        <div class="product-basket__sale" v-if="product.is_sales">-{{ product.percent }}%</div>
        <div class="product-basket__left">
            <img class="product-basket__image" height="130" width="130"
                 @click="toPage({name: 'product', params: {id: product.product_data.id}})"
                 :src="this.api+'/storage/img/products/' + product.product_data.image.name" :alt="product.product_data.image.name"/>
        </div>
        <div class="product-basket__right">
            <div class="product-basket__right__title" @click="toPage({name: 'product', params: {id: product.product_data.id}})">
                {{ product.product_data.product_description.name }}
            </div>
            <div class="product-basket__right__text">
                <div class="product-basket__right__text__quantity">Количество: {{product.quantity}} шт.</div>

                <div v-if="product.is_sales" class="product-basket__right__text__old-price">
                    Старая цена: {{product.price}} грн.
                </div>
                <div class="product-basket__right__text__price">
                    Цена: {{ product.is_sales ? product.price_with_sales : product.price }} грн.
                </div>

                <div class="product-basket__right__text__price">
                    Сумма: {{product.quantity * (product.is_sales ? product.price_with_sales : product.price) }} грн.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderProductCard",
        props: {
            dataCard: {
                type: Object,
            },
        },
        data() {
            return {
                product: [
                    {
                        product_data: {
                            id: '',
                            price: '',
                            price_with_sales: '',
                            image: {
                                name: ''
                            },
                            product_description: {
                                name: ''
                            },
                            is_sales: ''
                        },
                    }
                ],
            }
        },
        created() {
            this.product = this.dataCard;
        },
        methods: {
        },
    }
</script>

<style scoped lang="scss">

    @import "src/styles/main";
    @import "@/styles/mixins";

    .product-basket {

        &__wrapper {
            text-align: center;
            display: flex;
            flex-direction: row;
            position: relative;
            padding: 15px 4px;

            &:hover {
                box-shadow: 0 0 33px #f2f2f2;
            }

            @include _600 {
                width: fit-content;
                padding-top: 10px;
            }
        }

        &__image {
            max-width: 100%;
        }

        &__left {
            background-color: #fff;

            @include _600 {
                /*min-width: 40%;*/
                /*padding: 15px 4px;*/
            }
        }

        &__sale {
            vertical-align: middle;
            color: #fff;
            background-color: #000;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            left: 12px;
            top: 12px;
            line-height: 40px;
            font-weight: 300;
            font-size: 16px;
            position: absolute;
        }

        &__right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 50%;
            text-align: left;
            padding: 0 0 0 23px;

            @include _600 {
                padding: 15px 0 10px 0;
            }

            &__title {
                cursor: pointer;
                font-weight: 700;
                font-size: 12px;
                line-height: 16px;
            }

            &__text {
                font-size: 11px;

                &__old-price {
                    font-weight: 200;
                    font-size: 12px;
                    line-height: 16px;
                    margin-top: 5px;
                    text-decoration: line-through;
                }

                &__price {
                    font-weight: 700;
                    font-size: 12px;
                    line-height: 20px;
                    margin-top: 5px;
                }

                &__quantity {
                    font-weight: 400;
                    font-size: 12px;
                    line-height: 20px;
                    margin-top: 5px;
                }

                &__delete-basket {
                    color: $palette-light-text;
                    font-weight: 400;
                    font-size: 12px;
                    line-height: 16px;
                    margin-top: 5px;

                    &:hover {
                        cursor: pointer;
                        text-decoration: underline;
                    }
                }
            }

            &__counter {
                display: flex;
                column-gap: 1px;

                & > * {
                    background-color: white;
                    padding: 0 1.5px;
                }

                & > input {
                    font-size: 10px;
                }
            }
        }
    }

    input[type=number] {
        &::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        text-align: center;
    }

    .main-icon-btn {
        &:hover {
            cursor: pointer;
        }
    }

    .product-basket__image {
        cursor: pointer;
        max-width: 100%;
    }

</style>

<template>
    <div class="product-card__wrapper">
        <div class="product-card__wrapper-content">
            <div class="product-card__title" v-if="isShowTitle">{{title}}</div>
            <div class="product-card__content" v-if="typeSet === 'product'">
                <ProductCard class="product-card__item-three"
                             v-for="item in productData"
                             :key="item.id"
                             :data-card="item"
                             :is-show-stock="item.isShowStock"/>
            </div>
            <div class="product-card__content" v-if="typeSet === 'basket'">
                <ProductCardBasket v-for="item in productData"
                                   :key="item.id"
                                   :data-card="item"/>
            </div>
            <div class="product-card__content" v-if="typeSet === 'basket-menu'">
                <ProductCardBasketMenu class="product-card__item-two"
                                       v-for="item in productData.slice(0, 4)"
                                       :key="item.id"
                                       :data-card="item"
                                       :is-show-stock="item.isShowStock"/>
            </div>
        </div>
    </div>
</template>

<script>
    import ProductCard from "./productCards/ProductCard";
    import ProductCardBasket from "./productCards/ProductCardBasket";
    import ProductCardBasketMenu from "./productCards/ProductCardBasketMenu";

    export default {
        name: "ProductCardsSet",
        components: {
            ProductCard,
            ProductCardBasket,
            ProductCardBasketMenu
        },
        props: {
            isShowTitle: {
                type: Boolean,
                default: true
            },
            title: {
                type: String,
                default: ''
            },
            productData: {
                type: Array,
                default: () => [
                    {
                        id: 1,
                        img: '../../../public/product-images/product-images.svg',
                        isShowStock: false
                    },
                    {
                        id: 2,
                        img: '../public/product-images/product-images.svg',
                        isShowStock: false
                    },
                    {
                        id: 3,
                        img: '../../public/product-images/product-images.svg',
                        isShowStock: false
                    },
                    {
                        id: 4,
                        img: '../../public/product-images/product-images.svg',
                        isShowStock: false
                    },
                    {
                        id: 5,
                        img: '../../public/product-images/product-images.svg',
                        isShowStock: false
                    },
                    {
                        id: 6,
                        img: '../../public/product-images/product-images.svg',
                        isShowStock: false
                    }
                ]
            },
            typeSet: {
                type: String,
                default: 'product'
            }
        },
        data() {
            return {}
        }
    }
</script>

<style scoped lang="scss">
    .product-card {

        &__wrapper {
            margin-bottom: 40px;
            @media screen and (max-width: 600px) {
                margin-bottom: 0;
            }
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        &__wrapper-content {
            width: 90%;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        &__title {
            text-align: center;
            font-weight: 200;
            font-size: 34px;
            line-height: 46px;
            margin: 40px;
            text-transform: uppercase;
        }

        &__content {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-around;
            row-gap: 40px;
        }

        &__item-three {
            width: 30%;
        }

        &__item-two {
            width: 50%;

        }
    }
</style>

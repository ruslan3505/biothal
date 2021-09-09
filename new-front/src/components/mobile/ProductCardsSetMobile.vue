<template>
  <div class="product-card__wrapper">
    <div class="product-card__wrapper-content">
      <div class="product-card__title" v-if="isShowTitle">{{title}}</div>
      <div style="margin: 10px" v-if="isShowMessage">{{message}}</div>
      <div class="product-card__content" v-if="typeSet === 'product'">
        <ProductCardMobile class="product-card__item-two"
                           v-for="item in productData"
                           :key="item.id"
                           :data-card="item"
                           :is-show-stock="!!item.sale_id"/>
      </div>
      <!--            <div class="product-card__content" v-if="typeSet === 'basket'">-->
      <!--                <ProductCardBasket v-for="item in productData"-->
      <!--                                   :key="item.id"-->
      <!--                                   :data-card="item"/>-->
      <!--            </div>-->
      <!--            <div class="product-card__content" v-if="typeSet === 'basket-menu'">-->
      <!--                <ProductCardBasketMenu class="product-card__item-two"-->
      <!--                                       v-for="item in productData"-->
      <!--                                       :key="item.id"-->
      <!--                                       :data-card="item"-->
      <!--                                       :is-show-stock="item.isShowStock"/>-->
      <!--            </div>-->
    </div>
  </div>
</template>

<script>
  import ProductCardMobile from "./productCards/ProductCardMobile";
  import ProductCardBasket from "../desktop/productCards/ProductCardBasket";
  import ProductCardBasketMenu from "../desktop/productCards/ProductCardBasketMenu";

  export default {
    name: "ProductCardsSet",
    components: {
      ProductCardBasket,
      ProductCardBasketMenu,
      ProductCardMobile
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
      isShowMessage: {
        type: Boolean,
        default: true
      },
      message: {
        type: String,
        default: ''
      },
      typeSet: {
        type: String,
        default: 'product'
      },
      productData: {
        type: Array
      }
    },
    data() {
      return {}
    }
  }
</script>

<style scoped lang="scss">

@import 'src/styles/mixins';


  .product-card {
    &__wrapper {
      display: flex;
      flex-direction: row;
      justify-content: center;
      padding: 20px;
    }

    &__wrapper-content {
      display: flex;
      flex-direction: column;
      text-align: center;
    }

    &__title {
      text-align: center;
      font-weight: 400;
      font-size: 16px;
      line-height: 22px;
    }

    &__content {
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      justify-content: space-around;
      column-gap: 15px;
      margin-top: 20px;
    }

    &__item-three {
    }

    &__item-two {
      flex: 1 1 auto;
      max-width: 47%;
      margin: 0 0 10px;
      width: calc(1/2*100% - (1 - 1/2)*10px);

    }
  }
</style>

<template>
  <div class="product-card__wrapper">
    <div class="product-card__wrapper-content">
      <div class="product-card__title" v-if="isShowTitle">{{ title }}</div>

      <div style="margin: 10px" v-if="isShowMessage && message">{{ message }}</div>

      <agile class="slider" v-if="withSlider" infinite :key="productData.length" :slidesToShow="3" autoplay
             :autoplaySpeed="5000"
             :speed="1500" :navButtons="false" pauseOnHover pauseOnDotsHover>

        <div class="slide" v-for="item in productData" :key="item.id">
          <ProductCard
            v-if="typeSet === 'product'"
            class="product-card__content"
            :data-card="item"
            :is-show-stock="item.sale_id !== null"/>
        </div>
      </agile>

      <div class="product-card__content" v-else-if="typeSet === 'product'">
        <ProductCard class="product-card__item-three"
                     v-for="item in productData"
                     :key="item.id"
                     :data-card="item"
                     :is-show-stock="item.sale_id !== null"/>
      </div>

      <div class="product-card__content" v-if="typeSet === 'basket'">
        <ProductCardBasket v-for="item in productData"
                           @delete="$emit('delete', item.id)"
                           :key="item.id"
                           :is-show-stock="item.sale_id !== null"
                           :data-card="item"/>
      </div>

      <div class="product-card__content" v-if="typeSet === 'basket-menu'">
        <ProductCardBasketMenu class="product-card__item-two"
                               v-for="item in productData"
                               :key="item.id"
                               :data-card="item"
                               :is-show-stock="item.sale_id !== null"/>
      </div>

    </div>
  </div>
</template>

<script>
import ProductCard from "./productCards/ProductCard";
import ProductCardBasket from "./productCards/ProductCardBasket";
import ProductCardBasketMenu from "./productCards/ProductCardBasketMenu";
import {VueAgile} from "vue-agile";

export default {
  name: "ProductCardsSetDesktop",
  components: {
    agile: VueAgile,
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
    withSlider: {
      type: Boolean,
      default: false
    },
    productData: {
      type: Array
    },
    isShowStock: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {}
  },
  methods: {}
}
</script>

<style scoped lang="scss">

@import '/src/styles/mixins';

.product-card {

  &__wrapper {
    margin-bottom: 40px;
    display: flex;
    flex-direction: row;
    justify-content: center;
  }

  &__wrapper-content {
    display: flex;
    flex-direction: column;
    text-align: center;
    width: 100%;
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
    padding: 0 45px;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    row-gap: 40px;
    column-gap: 30px;
    align-items: stretch;
    height: 100%;
  }

  &__item-three {
    width: 30%;

    @include media(991) {
      width: 50%;
    }
  }

  &__item-two {
    width: 50%;
  }
}

.slider {

  & ::v-deep .agile {

    &__list {

    }

    &__slides {

      & .slide {
        display: flex;
        position: relative;
        height: 100%;
        justify-content: center;
      }

      & .product-card__content {
        align-content: space-between;
        padding: 10px 45px;
      }
    }

  }
}


</style>

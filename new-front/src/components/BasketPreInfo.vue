<template>
  <v-snackbar
    v-if="data_basket_info"
    :value="visible_basket_info"
    @input="action_visible_basket_info($event)"
    v-bind="snackbar">
    <p class="basket-pre-info__title">Товар добавлен в корзину</p>

    <v-divider class="basket-pre-info__divider"/>

    <div class="basket-pre-info__product">
      <img class="basket-pre-info__product__image"
           @click="toPage({name: 'product', params: {id: data_basket_info.id}})"
           :src="this.api + '/storage/img/products/' + data_basket_info.image.name"
           :alt="data_basket_info.image.name"/>
      <div class="basket-pre-info__product__description">
        <div @click="toPage({name: 'product', params: {id: data_basket_info.id}})"
             class="basket-pre-info__product__description__text">
          {{ data_basket_info['product_description']['name'] }}
        </div>
        <div class="basket-pre-info__product__description__price default-cursor">
          {{ data_basket_info.sale_id ? data_basket_info.price_with_sale : data_basket_info.price }} грн
        </div>
      </div>
    </div>

    <div class="basket-pre-info__product__control">
      <v-btn rounded class="basket-pre-info__control__button" :color="variables.basecolor" height="36" width="80%"
             @click="action_visible_basket_info(false); action_visible_basket(true)">
        Перейти в корзину
      </v-btn>
      <v-btn rounded :color="variables.basecolor" class="basket-pre-info__control__button" height="36" width="80%"
             @click="action_data_basket_info(null); action_visible_basket_info(false)">
        Продолжить покупки
      </v-btn>
    </div>


  </v-snackbar>
</template>

<script>
export default {
  name: "BasketPreInfo",
  data() {
    return {
      snackbar: {
        top: true,
        right: !this.isMobile,
        color: '#fff',
        timeout: 10000,
        multiLine: true,
        centered: this.isMobile,
        'content-class': 'basket-pre-info',
      },
    }
  },
}
</script>

<style lang="scss">
@import "src/styles/mixins";
@import "src/styles/main";

.basket-pre-info {
  font-weight: 200;
  max-width: rem(350);
  min-width: rem(300);
  $snackbar-top: 50px;


  @include _480 {
    max-width: 100%;
  }

  @include _600 {
    top: 0;
  }

  &__title {
    color: black;
    width: 100%;
    max-width: 100%;
    text-align: center;
    font-size: rem(16);
    line-height: 20px;
    font-weight: 550;
  }

  &__divider {
    width: 100%;
    margin: rem(5) 0;
    background-color: $palette-main-background-color;
  }

  &__product {
    display: flex;

    &__image {
      max-height: rem(150);
    }

    &__description {
      color: #000;
      margin-left: rem(10);

      &__text {
        margin-top: rem(10);
      }

      &__price {
        font-weight: 600;
        margin-top: rem(10);
      }
    }

    &__control {
      margin-top: rem(10);
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      height: rem(90);
    }
  }
}

</style>

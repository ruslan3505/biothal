<template>
  <div class="product-basket__wrapper">
    <div class="product-basket__sale" v-if="isShowStock">-{{ dataCard.get_sale.percent }}%</div>
    <div class="product-basket__left">
      <img class="product-basket__image" height="150" width="150"
           @click="toPage({name: 'product', params: {id: dataCard.id}})"
           :src="this.api+'/storage/img/products/' + dataCard.image.name" :alt="dataCard.image.name"/>
    </div>
    <div class="product-basket__right">
      <div class="product-basket__right__title" @click="toPage({name: 'product', params: {id: dataCard.id}})">
        {{ dataCard.product_description.name }}
      </div>
      <div class="product-basket__right__text">
        <div>Количество</div>
        <div class="product-basket__right__counter">
          <v-icon class="main-icon-btn" size="12"
                  @click="dataCard.quantity > dataCard.minimum ? decrementQuantity(dataCard.id) : null">
            mdi-minus
          </v-icon>
          <input style="width: 30px" v-model="dataCard.quantity" type="number" :min="dataCard.minimum" max="100"/>
          <v-icon class="main-icon-btn" size="12" @click="dataCard.quantity < 99 ? incrementQuantity(dataCard.id) : null">mdi-plus</v-icon>
        </div>

        <div v-if="isShowStock" class="product-basket__right__text__old-price">Старая цена: {{
            dataCard.price
          }} грн.
        </div>

        <input type="hidden" class="price_in_currency" :value=' dataCard.currency + " USD"'>
        <div class="product-basket__right__text__price">Цена: {{
            isShowStock ? dataCard.price_with_sale : dataCard.price }} грн.</div>
                <div class="product-basket__right__text__delete-basket" @click="$emit('delete')">Удалить из корзины
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {mapActions} from "vuex";

export default {
  name: "ProductCardBasket",
  props: {
    dataCard: {
      type: Object,
    },
    isShowStock: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    'dataCard.quantity': function (valueNew, valueOld) {
      if (String(valueNew) === '0') {
        this.dataCard.quantity = 1;
      }
      if (valueNew > 99) {
        this.dataCard.quantity = valueOld;
      } else if (valueNew < 0) {
        this.dataCard.quantity = 1;
      }
    }
  },
  methods: {
    ...mapActions('basket', {

      incrementQuantity: 'INCREMENT_PRODUCT_QUANTITY',
      decrementQuantity: 'DECREMENT_PRODUCT_QUANTITY'
    }),
  },
  mounted() {
    window.app = this
  }
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
    width: 100%;

    &:hover {
      box-shadow: 0 0 33px #f2f2f2;
    }

    @include _600 {
      padding: 0;
    }
  }

  &__image {
    max-width: 100%;
  }

  &__left {
    background-color: #fff;
    width: 50%;

    @include _600 {
      padding: 15px 4px;
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
    justify-content: space-between;
    width: 50%;
    text-align: left;
    padding: 0 0 0 23px;

    @include _600 {
      padding: 15px 0 10px 23px;
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
        font-size: 14px;
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

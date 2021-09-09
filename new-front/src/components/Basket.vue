<template>
  <v-navigation-drawer
    :width="isMobile ? '100%' : 'auto'"
    :right="!isMobile"
    :value="visible_basket"
    @input="visibleModal($event)"
    height="100vh"
    class="basket"
    absolute
    temporary>
    <v-card flat>
      <div class="basket-header">
        <div class="basket-header__title">Корзина ({{ products.length }})</div>
        <v-icon class="basket-header__icon-close" @click="visibleModal(false)">mdi-close</v-icon>
      </div>

      <div class="page-form__wrapper">
        <div>
          <div class="page-form__top" v-if="globalSales.length > 0 && nextGlobalSales">
            <div style="margin-bottom: 17px; font-size: 12px; line-height: 17px; font-weight: 200">
              Еще {{ (nextGlobalSales.sum_modal - productsSum).toFixed(0) }} грн и сработает скидка
              {{ nextGlobalSales.procent_modal }}%
            </div>
            <v-progress-linear :value="linear"
                               :color="variables.basecolor"
                               background-color="#ddd"
                               class="main-linear"
                               height="12"/>
          </div>
          <div class="page-form__middle" v-if="products.length > 0" :styles="['margin-top: 0']">
            <div class="page-form__middle__product-set">
              <ProductCardBasket v-for="item in products"
                                 @delete="deleteProduct(item.id)"
                                 :key="item.id"
                                 :is-show-stock="!!item.sale_id"
                                 :data-card="item"/>


              <!--              <ProductCardsSet type-set="basket"-->
              <!--                               :is-show-title="false"-->
              <!--                               @delete="deleteProduct"-->
              <!--                               :product-data="products"/>-->
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
                  {{ currentGlobalSales.procent_modal}}%.
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
            <div class="checkout-button__wrapper">
              <v-btn dark class="checkout-button" elevation="0" @click="fbMethod(), toPage({name: 'ordering'})">
                Оформить заказ
              </v-btn>
            </div>
          </div>
        </div>

        <div class="recommended-products">
          <div class="recommended-products__title">Рекомендуемые товары</div>

          <div class="recommended-products__goods">
            <product-card-basket-menu-new-version v-for="item in recommendedProducts.slice(0, 4)" :key="item.id"
                                                  :data-card="item" :is-show-stock="!!item.sale_id"/>
          </div>

          <!--          <ProductCardsSet v-if="!isMobile"-->
          <!--                           class="recommended-products__good"-->
          <!--                           :product-data="recommendedProducts.slice(0, 4)"-->
          <!--                           type-set="basket-menu"-->
          <!--                           :is-show-title="false"/>-->
          <!--          <ProductCardsSetMobile v-if="isMobile"-->
          <!--                                 :product-data="recommendedProducts.slice(0, 4)"-->
          <!--                                 class="recommended-products__good"-->
          <!--                                 ype-set="product"-->
          <!--                                 title="Рекомендуемые товары"/>-->
        </div>
      </div>
    </v-card>
  </v-navigation-drawer>
</template>

<script>
import ProductCardsSet from "./desktop/ProductCardsSetDesktop";
import ProductCardsSetMobile from "./mobile/ProductCardsSetMobile";
import {mapActions, mapGetters} from "vuex";
import ProductCardBasket from "@/components/desktop/productCards/ProductCardBasket";
import ProductCardBasketMenuNewVersion from "@/components/desktop/productCards/ProductCardBasketMenuNewVerison";

export default {
  name: "Basket",
  components: {
    ProductCardBasketMenuNewVersion,
    ProductCardBasket,
    ProductCardsSet,
    ProductCardsSetMobile
  },
  data() {
    return {
      deliveryPrice: 40,
      recommendedProducts: [],
    }
  },
  computed: {
    ...mapGetters('basket', [
      'products',
      'productData',
      'globalSales',
      'currentGlobalSales',
      'nextGlobalSales',
      'groupSales',
      'currentGroupSales',
      'nextGroupSales',
      'linear',
      'productsSum',
      'productsSumWithSales'
    ])
  },
  watch: {
    visible_basket(newValue) {
      console.log('visible_basket',newValue)
      this.fetchProducts()
      if (newValue) {
        document.querySelector('html')
          .classList
          .add('hide-scroll')
          // this.fetchProducts()
      } else {
        document.querySelector('html')
          .classList
          .remove('hide-scroll')
          // this.fetchProducts()
      }
    }
  },
  methods: {
    ...mapActions('basket', {
      addProduct: 'ADD_PRODUCT',
      setProducts: 'SET_PRODUCTS',
      deleteProduct: 'DELETE_PRODUCT',
      setGlobalSales: 'SET_GLOBAL_SALES',
      setGroupSales: 'SET_GROUP_SALES',
    }),
    getProducts() {
        this.axios.post('sales/global')
            .then(({data}) => {
                this.setProducts(data)
            })
    },    
    visibleModal(visible) {
        window.scrollTo(0, 0)
      this.action_visible_basket(visible);
    },
    getGlobalSales() {
        this.axios.post('sales/global')
            .then(({data}) => {
                this.setGlobalSales(data)
            })
    },
    getRecommendedProduct() {
        this.axios.post('products/recommended')
            .then(({data}) => {
                this.recommendedProducts = data
            })
    },
    getGroupSales(){
        this.axios.post('sales/group')
            .then(({data}) => {
                this.setGroupSales(data)
            })
    },
    fbMethod() {
        let productsIds = [];
        let sum = 0;
        this.products.map(product => {
            console.log('InitiateCheckout',{
                value: product.currency, currency: 'USD', content_ids: product.id, content_type: 'product', content_category: product.category
            })
            productsIds.push(product.id);
            sum = (sum + (product.currency * product.quantity));
        })
        this.$analytics.fbq.event( 'InitiateCheckout', {
            value: sum, currency: 'USD', content_ids: productsIds, content_type: 'product'
        })
    },
    async fetchProducts()
    {
      try {
      this.$loading(true)
        this.axios.post('fetchProducts', { products:this.productData })
          .then(({data}) => {
            this.setProducts(data)
        })
        
        this.$loading(false);

      } catch (e) {
          this.$loading(false);
          this.errorMessagesValidation(e);
      }
        console.log(this.products)
    }
  },

  mounted() {
    this.getGlobalSales();
    this.getRecommendedProduct();
    this.getGroupSales();
    this.getProducts();
  },

  created() {
    this.fetchProducts();
  }
}
</script>

<style scoped lang="scss">
@import "src/styles/mixins";
@import "src/styles/main";

.basket {
  width: rem(410) !important;

  &-header {
    width: 100%;
    display: flex;
    padding: 27px 19px;
    align-items: center;
    justify-content: space-between;

    &__title {
      font-size: 15px;
      line-height: 18px;
      font-weight: 400;
      font-family: Roboto, sans-serif;
    }

    &__icon-close {
      color: black;
      font-size: 14px;
    }
  }

  &__info {
    overflow: auto;
    height: 100vh;
  }

}

.total {
  display: flex;
  flex-direction: row;
  font-weight: 200;
  font-size: 14px;
  line-height: 19px;

  &__wrapper {
    margin-top: 16px;
  }

  &__left {
  }

  &__right {
    margin-left: 4px;
  }
}

.main-linear {
  border-radius: 60px;
}

.page-form {
  &__wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px 22px 20px 41px;
    background-color: white;

    @include _600 {
      background-color: #F7F7F7;
      padding: 20px;
    }
  }

  &__content {
    width: 70%;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  &__top {
    display: flex;
    flex-direction: column;
    text-align: center;
  }

  &__middle {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 100%;
    margin-top: 30px;

    &__product-set {
      max-height: 580px;
      overflow: auto;
      display: flex;
      flex-direction: column;
      row-gap: 20px;

      & ::v-deep {

      }
    }
  }
}

.checkout-button {
  margin-top: 15px;

  &__wrapper {
    display: flex;
    justify-content: center;
  }
}

.recommended-products {
  text-align: center;
  margin-top: 20px;
  width: 100%;

  &__title {
    font-size: 16px;
    line-height: 22px;
    margin-bottom: 14px;

    @include _600 {
      margin-bottom: 18px;
    }
  }

  &__goods {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
    column-gap: 18px;
    row-gap: 10px;

    //@media screen and (max-width: 400px) {
    //  justify-content: space-between;
    //  column-gap: 0;
    //}

    @include _600 {
      justify-content: center;
    }

    @include _400 {
      justify-content: space-between;
      column-gap: 0;
    }
  }
}
</style>

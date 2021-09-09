<template>
  <div :style="{'opacity': dataCard.stock_status_id === 2 ? '55%':'100%' }" class="product-basket__wrapper">
    <img class="product-basket__image" height="150" width="150"
         :src="dataCard.image ? this.image_uri + dataCard.image.name : ''"
         :alt="dataCard.image ? dataCard.image.name : ''"/>
    <div class="product-basket__text">
      <div class="product-basket__text__title" @click="toPage({name: 'product', params: {id: dataCard.id}})">
        {{ dataCard.product_description.name }}
      </div>
      <div class="product-basket__position-bottom">
        <div class="product-basket__text__price">{{ isShowStock ? dataCard.price_with_sale : dataCard.price }} грн</div>
        <input type="hidden" class="price_in_currency" :value=' dataCard.currency + " USD"'>
        <v-btn v-if="dataCard.stock_status_id === 2" dark class="product__button" elevation="0" @click="preOrder">
            Предзаказ
        </v-btn>
        <v-btn v-else dark class="product__button" elevation="0" @click="addToCart">
          Добавить
        </v-btn>
      </div>
    </div>

      <PreOrderOneClickModal ref="PreOrderOneClickModal" :data-card="dataCard" :name="name" :phone="phone"
                             :user_id="user_id"/>
  </div>
</template>

<script>

import {mapActions} from "vuex";
import PreOrderOneClickModal from "../../PreOrderOneClickModal.vue";

export default {
  name: "ProductCardBasketMenuNewVersion",
  components: {
      PreOrderOneClickModal
  },
  props: {
    dataCard: {
      type: Object,
      default: () => {
      }
    },
    isShowStock: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      name: '',
      phone: '',
      user_id: ''
    }
  },
  methods: {
    ...mapActions('basket', {
      addProduct: 'ADD_PRODUCT'
    }),
          async fetchProducts()
    {
      try {
      // this.$loading(true)
        this.axios.post('fetchProducts', { products:this.productData })
          .then(({data}) => {
            this.setProducts(data)
        })
        
        // this.$loading(false);

      } catch (e) {
          // this.$loading(false);
          this.errorMessagesValidation(e);
      }
        // console.log(this.dataCard)
  },
    addToCart() {
      const product = this.dataCard;
      this.fetchProducts()
      console.log(this.dataCard)
      product.quantity = (product.minimum !== 0) ? product.minimum : 1;

        // console.log('AddToCart',{
        //     id: this.dataCard.id, 
        //     title: this.dataCard.model,
        //     price: this.dataCard.price
        // })

      this.$analytics.fbq.event( 'AddToCart', {
          value: this.dataCard.currency, currency: 'USD', content_ids: this.dataCard.id, content_type: 'product', content_category: this.dataCard.category
      })
      this.addProduct(product)
    },
    preOrder() {
        this.getProfile();

        this.$refs['PreOrderOneClickModal'].visible = true;
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
                      let user = data.data.user;
                      this.name = user.name;
                      this.phone = user.phone_number;
                      this.user_id = user.id;
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
  }
}
</script>

<style scoped lang="scss">

.product-basket {

  &__wrapper {
    display: flex;
    text-align: center;
    flex-direction: column;
    align-items: center;
    width: 160px;
    background-color: white;

    &:hover {
      box-shadow: 0 0 33px #f2f2f2;
    }
  }

  &__image {
    max-width: 100%;
  }

  &__text {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    font-size: 11px;
    align-items: center;
    height: 100%;
    padding: 7px 11px 11px 11px;

    &__title {
      font-weight: 400;
      font-size: 12px;
      line-height: 16px;
      display: flex;
      align-items: center;
      height: 100%;
    }

    &__price {
      font-weight: 700;
      font-size: 13px;
      line-height: 18px;
      margin-top: 3px;
    }

    &__delete-basket {
      color: #C2C2C2;

      &:hover {
        cursor: pointer;
        text-decoration: underline;
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

.product__button {
  border-radius: 50px;
  height: 28px !important;
  background-color: #2F7484 !important;
  text-transform: none !important;

  font-weight: 700;
  font-size: 11px;
  line-height: 15px;
  width: 100px;

  margin-top: 7px;
}
</style>

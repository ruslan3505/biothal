<template>
  <div :style="{'opacity': dataCard.stock_status_id === 2 ? '55%':'100%' }" class="product" @mouseover="isFavoritesShow = true" @mouseleave="isFavoritesShow = false">
    <div class="product__sale" v-if="isShowStock">-{{ dataCard.get_sale.percent }}%</div>
    <div class="product__heart" v-if="isShowFavorite">
      <v-btn
        v-show="isFavoritesShow || isFavorites"
        icon
        large
        size="18"
        color="#000"
        @click="()=>{isFavorites = !isFavorites}">
        <v-icon>{{ isFavorites ? 'mdi-cards-heart' : 'mdi-heart-outline' }}</v-icon>
      </v-btn>
    </div>
    <div>
      <img class="product__image" @click="toPage({name: 'product', params: {id: dataCard['id']}})"
           :src="dataCard.image ? this.api+'/storage/img/products/' + dataCard.image.name : ''"
           :alt="dataCard.image ? dataCard.image.name : ''"/>

      <span @click="toPage({name: 'product', params: {id: dataCard['id']}})" class="product__text"
            style=" display: block; text-overflow: ellipsis; white-space: normal;">{{
          dataCard['product_description']['name']
        }}
    </span>
    </div>

    <div class="product__info">
        <div>
            <span class="info-price__discount" v-if="isShowStock">{{ dataCard.price }} грн</span>
            <input type="hidden" class="price_in_currency" :value=' dataCard.currency + " USD"'>
            <span class="product__info__price default-cursor">
                {{ isShowStock ? dataCard.price_with_sale : dataCard.price }} грн
            </span>
        </div>

        <v-btn v-if="dataCard.stock_status_id === 2" class="product__button white--text"
               :color="variables.basecolor" elevation="0"
               @click="preOrder">
            Предзаказ
        </v-btn>
        <v-btn v-else class="product__button white--text" elevation="0" @click="addProductToCart">
            Купить
      </v-btn>
    </div>

    <PreOrderOneClickModal ref="PreOrderOneClickModal" :data-card="dataCard" :name="name" :phone="phone"
                           :user_id="user_id"/>
  </div>
</template>

<script>

import {mapActions} from "vuex";
import PreOrderOneClickModal from "../../PreOrderOneClickModal.vue";

export default {
  name: "ProductCard",
  components: {
      PreOrderOneClickModal
  },
  metaInfo() {
    return {
      title: this.dataCard.product_description.meta_title,
      meta: [
        {
          vmid: 'description',
          name: 'description',
          content: this.dataCard.product_description.meta_description
        },
        {vmid: 'keyword', name: 'keyword', content: this.dataCard.product_description.meta_keyword},
        {vmid: 'h1', name: 'h1', content: this.dataCard.product_description.meta_h1},
        {vmid: 'tag', name: 'tag', content: this.dataCard.product_description.tag},
      ]
    }
  },
  props: {
    dataCard: {
      type: Object,
      image: {
        name: ''
      },
      product_description: {},
      stock_status: {
        stock_status_id: '',
        name: ''
      },
      stock_status_id: ''
    },
    isShowStock: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      isFavorites: false,
      isFavoritesShow: false,
      showMessage: false,
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
        console.log(this.products)
  },
    addProductToCart() {
      const product = this.dataCard;
      product.quantity = (product.minimum !== 0) ? product.minimum : 1;

        console.log('AddToCart',{
            value: this.dataCard.currency, currency: 'USD', content_ids: this.dataCard.id, content_type: 'product', content_category: this.dataCard.category,test_event_code: 'TEST60059'
        })
      this.$analytics.fbq.event('AddToCart', {
          value: product.quantity * this.dataCard.currency, currency: 'USD', content_ids: this.dataCard.id, content_type: 'product', content_category: this.dataCard.categorys
      })
        console.log(this.$analytics)
      this.addProduct(product)

      this.action_data_basket_info(product);
      this.action_visible_basket_info(true);
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
    },
      openBasket (){
          this.showMessage = false;
          this.visibleBasket(true);
      }
  }
}
</script>

<style scoped lang="scss">

.product {
  text-align: center;
  display: flex;
  flex-direction: column;
  padding: 17px 30px 20px 30px;
  width: 350px !important;
  position: relative;
  justify-content: space-between;

  &:hover {
    box-shadow: 0 0 33px #f2f2f2;

    & .product__button[disabled] {
      background-color: #bbbbbb !important;
    }

    & .product__button {
      background-color: #000 !important;
    }
  }

  &__sale {
    color: #fff;
    background-color: #000;
    border-radius: 50%;
    width: 72px;
    height: 72px;
    line-height: 72px;
    font-weight: 300;
    font-size: 21px;
    position: absolute;
    left: 20px;
    top: 20px;
  }

  &__image {
    width: 290px;

    &:hover {
      cursor: pointer;
    }
  }
  &__snackbar {
    width: 170px;

    &:hover {
      cursor: pointer;
    }
  }

  &__text {
    font-weight: 500;
    font-size: 18px;
    line-height: 25px;
    text-align: center;
  }

  &__info {
    display: flex;
    flex-direction: column;
    text-transform: none;
    align-items: center;
    margin-top: 10px;

    &__text {
      justify-content: center;
      display: flex;
      overflow: hidden;
      font-style: normal;
      font-weight: 500;
      font-size: 18px;
      line-height: 25px;
      text-align: center;

      &:hover {
        cursor: pointer;
      }
    }

    &__price {
      font-style: normal;
      font-weight: 800;
      font-size: 18px;
      line-height: 25px;
    }
  }

  &__button {
    text-transform: none;
    border-radius: 50px;
    width: 180px;
    height: 48px !important;
    padding: 0 !important;
    background-color: #2F7484 !important;
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    line-height: 22px;
    margin-top: 10px;

      &__snackbar {
          text-transform: none;
          border-radius: 50px;
          width: 70%;
          height: 34px !important;
          padding: 0 !important;
          background-color: #2F7484 !important;
          font-style: normal;
          font-weight: bold;
          font-size: 16px;
          line-height: 11px;
          margin-top: 5px;
      }
  }
}
.info {

    &-price {

        &__price {
            font-weight: 800;
            font-size: 24px;
            line-height: 32px;
        }

        &__discount {
            margin-left: 5px;
            font-size: 15px;
            text-decoration: line-through;
        }
    }
}
</style>

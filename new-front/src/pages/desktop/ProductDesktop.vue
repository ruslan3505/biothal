<template>
  <div class="base-page-wrapper product-wrapper">

    <div class="breadcrumb">
      <span class="breadcrumb-item" @click="toPage( {name:'home'} )">Главная</span>
      <span>/</span>
      <span class="breadcrumb-item"
            @click="toPage({name: 'category', params:{ category: category['main_category']['slug'] }} )">
        {{ category['main_category']['title'] }}</span>
      <span v-if="category['main_category'].length !== 0">/</span>
      <span v-if="category['sub_category']" class="breadcrumb-item"
            @click="toPage({name: 'category', params:{ category: category['main_category']['slug'], subCategory: category['sub_category']['slug'] }} )">
        {{ category['sub_category']['title'] }}</span>
      <span v-if="category['sub_category']">/</span>
      <span class="breadcrumb-item">{{ description['name'] }}</span>
    </div>

    <div class="block-product">
      <div class="block-product-base-info">

        <div class="block-product-base-info__image">
          <img :src="image" :alt="productData['image'] ? productData['image']['name'] : ''"
               class="image__product" :class="subImages" @click="getSubImages()"/>
          <!--                    <img :src="require('../../../public/product-images/' + productData['image']['name'] || '')" :alt="productData['image']['name'] || ''"-->
          <!--                         class="image__product"/>-->
          <div class="image__discount" v-if="is_discount">- {{ productData.get_sale.percent }}%</div>
        </div>

        <div class="block-product-base-info__info">
          <div class="info-title">
            <span class="info-title__title">{{ description['name'] || '' }}</span>
            <span class="info-title__subtitle">{{ productData['upc'] || '' }}</span>
          </div>

          <div class="info-price">
            <span class="info-price__price">{{ is_discount ? productData['price_with_sale'] : productData['price'] }} грн</span>
              <input type="hidden" class="price_in_currency" :value=' productData["currency"] + " USD"'>
            <span class="info-price__discount" v-if="is_discount">{{ productData['price'] }} грн</span>
            <p class="info-price__in-stock">{{
                stock_status === 1 ? 'В наличии' :
                  stock_status === 2 ? 'Нет в наличии' :
                    stock_status === 3 ? '2-3 Дня' : ''
              }}
            </p>
            <span class="info-count__title">{{ productData['jan'] || '' }}</span>
          </div>
          <span
            class="info-title__subtitle">{{ productData['product_description']['short_description'] }}</span>

          <div class="info-count">
            <span class="info-count__title">Количество</span>
            <div>
              <v-icon
                @click="incrementCountGood"
                :disabled="stock_status === 2"
                :style="{'background-color': stock_status === 2 || count_good > 98 ? variables.disablecolor : variables.basecolor, color: stock_status === 2 || count_good > 98 ? '#000000' : '#ffffff'}"
                class="info-count__input-control">
                mdi-plus
              </v-icon>
              <input v-model="count_good" :disabled="stock_status === 2" type="number" max="100" class="input-count-good"/>
              <v-icon
                @click="decrementCountGood"
                :disabled="stock_status === 2"
                :style="{'background-color': count_good <= minimum_quantity ? variables.disablecolor : variables.basecolor, color: count_good <= minimum_quantity ? '#000000' : '#ffffff'}"
                class="info-count__input-control">
                mdi-minus
              </v-icon>
            </div>
          </div>

          <div class="info-pay-control">
            <div class="info-pay-control__buy">
              <v-btn class="white--text" :disabled="stock_status === 2"
                     :color="variables.basecolor" elevation="0" @click="addToCart">
                {{ stock_status === 2 ? 'Нет в наличии' : 'Купить' }}
              </v-btn>
              <!--                            <span class="info-pay-control__text">Добавить в избранное</span>-->
            </div>
            <div class="info-pay-control__buy-fast">
              <v-form ref="orderQuickForm">
                <v-text-field
                  class="info-pay-control__buy-fast__input"
                  v-model="phone" :error-messages="errorValid.phone"
                  :rules="numberRules"
                  flat
                  rounded
                  placeholder="+38(___) ___-__-__"
                  v-mask="'+38(###) ###-##-##'"/>
              </v-form>
              <span v-if="stock_status === 2" class="info-pay-control__text"
                    @click="preOrder()">Оформить предзаказ</span>
              <span v-else class="info-pay-control__text"
                                  @click="checkout()">Оформить товар в 1 клик</span>
            </div>
          </div>
        </div>
      </div>
      <div class="block-product__tabs">
        <v-tabs
          color="#000"
          centered
          v-model="tab">
          <v-tabs-slider color="#000"></v-tabs-slider>
          <v-tab
            :href="`#tab-description`">
            Описание
          </v-tab>
          <v-tab
            :href="`#tab-${idx}`"
            v-for="(item, idx) in this.items"
            :key="idx"
            v-html="item['tab_title']">
          </v-tab>
        </v-tabs>

        <v-tabs-items style="margin-top: 30px" v-model="tab">
          <v-tab-item
            :value="'tab-description'">
            <v-card class="description-content" flat v-html="productDescription"/>
          </v-tab-item>
          <v-tab-item
            v-for="(item, idx) in this.items"
            :key="idx"
            :value="'tab-' + idx">
            <v-card class="description-content" flat v-html="item['tab_desc']"/>
          </v-tab-item>
        </v-tabs-items>
      </div>
    </div>

    <ProductCardsSet type-set="product" :with-slider="true" title="C ЭТИМ ТОВАРОМ ПОКУПАЮТ"
                     :product-data="recommendedProduct"/>

    <vue-gallery-slideshow :images="images" :index="index" @close="index = null"/>
    <!--        <v-snackbar-->
    <!--            v-model="showMessage"-->
    <!--            v-bind="snackbar">-->
    <!--            <span style="color: black; display: flex; justify-content: center; margin-bottom: 7px">-->
    <!--                Товар добавлен в корзину-->
    <!--            </span>-->
    <!--            <v-divider style="color: #c7c7c7;"/>-->

    <!--            <img :src="image" :alt="productData['image'] ? productData['image']['name'] : ''"-->
    <!--                 style="width: 50%" :class="subImages" @click="getSubImages()"/>-->
    <!--            &lt;!&ndash;                    <img :src="require('../../../public/product-images/' + productData['image']['name'] || '')" :alt="productData['image']['name'] || ''"&ndash;&gt;-->
    <!--            &lt;!&ndash;                         class="image__product"/>&ndash;&gt;-->
    <!--            <div class="image__discount" v-if="is_discount">- {{ productData.get_sale.percent }}%</div>-->
    <!--            <span class="info-price__price">{{ is_discount ? productData['price_with_sale'] : productData['price'] }} грн</span>-->
    <!--            <span class="info-price__discount" v-if="is_discount">{{ productData['price'] }} грн</span>-->

    <!--            <div class="product__info">-->
    <!--                <v-btn class="product__button__snackbar white&#45;&#45;text" elevation="0" @click="openBasket()">-->
    <!--                    Перейти в корзину-->
    <!--                </v-btn>-->
    <!--                <v-btn class="product__button__snackbar white&#45;&#45;text" elevation="0" @click="showMessage = false">-->
    <!--                    Продолжить покупки-->
    <!--                </v-btn>-->
    <!--            </div>-->
    <!--        </v-snackbar>-->
  </div>
</template>

<script>
import {TheMask} from 'vue-the-mask';
import ProductCardsSet from "../../components/desktop/ProductCardsSetDesktop";
import VueGallerySlideshow from 'vue-gallery-slideshow';
import {mapActions, mapGetters} from "vuex";

    export default {
        name: "ProductDesktop",
        components: {
            TheMask,
            ProductCardsSet,
            VueGallerySlideshow
        },
        props: {
            id: {
                type: [Number, String],
                default: 1
            },
        },
        computed: {
            validPhoneInput() {
                return this.phone.length === 10
            },
            route() {
                return this.$route.params;
            },
            numberRules() {
                return [
                    v => !!v || 'Вы не ввели свой телефоный номер',
                    v => v.length >= 18 || 'Телефон должен содержать больше чем 12 символов',
                ];
            }
        },
        watch: {
            route: {
                deep: true,
                handler(newRoute, oldRoute) {
                    this.fetchProductDetails();
                },
            },
            count_good: function (valueNew, valueOld) {
                if (String(valueNew) === '0') {
                    this.count_good = 1;
                }
                if (valueNew > 99) {
                    this.count_good = valueOld;
                } else if (valueNew < 0) {
                    this.count_good = 1;
                }
            }
        },
        created() {
            this.getProfile();
            this.fetchProductDetails();
        },
        data() {
            return {
                tab: null,
                count_good: 1,
                minimum_quantity: '',
                items: [],
                is_discount: false,
                phone: '', user_id: '',
                productData: {
                    image: {
                        name: ''
                    },
                    product_description: {}
                }, stock_status: '',
                attr: [],
                description: [],
                productImages: [],
                recommendedProduct: [],
                images: [],
                image: '',
                index: null,
                subImages: null,
                category: {
                    main_category: {
                        title: ''
                    },
                    sub_category: {
                        title: ''
                    }
                },
                productDescription: '',
                showMessage: false,
                snackbar: {
                    top: true,
                    right: true,
                    color: 'white',
                    timeout: 900,
                    multiLine: true
                },
                errorValid: {
                    phone: ''
                }
            }
        },
        methods: {
            ...mapActions('basket', {
                addProduct: 'ADD_PRODUCT',
                visibleBasket: 'VISIBLE_BASKET'
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
            addToCart() {
                // this.showMessage = true;
      const product = this.productData;
      product.quantity = this.count_good;

        console.log('AddToCart',{
            value: this.productData["currency"], currency: 'USD', content_ids: this.productData["id"], content_type: 'product', content_category: this.category['sub_category']['slug']
        })
      this.$analytics.fbq.event( 'AddToCart', {
          value: product.quantity * this.productData["currency"], currency: 'USD', content_ids: this.productData["id"], content_type: 'product', content_category: this.category['sub_category']['slug']
      })
      this.addProduct(product);

      this.action_data_basket_info(product);
      this.action_visible_basket_info(true);
    },
    incrementCountGood() {
        if (this.count_good < 99) {
            ++this.count_good;
        }
    },
    decrementCountGood() {
      if (this.count_good > this.minimum_quantity) {
        --this.count_good;
      }
    },
    async fetchProductDetails() {

      this.is_discount = false
      let data = await this.axios.get('product/' + this.id);

      this.productData = data.data.productDetails;
      this.description = this.productData['product_description'];
      this.productDescription = data.data.description;
      this.items = this.productData['product_apts'];
      this.productImages = this.productData.product_images;
      this.recommendedProduct = data.data.recommendedProduct;
      this.image = this.productData['image'] ? this.api + '/storage/img/products/' + this.productData['image']['name'] : '';
      this.category = data.data.product_category;
      this.count_good = (data.data.productDetails.minimum !== 0) ? data.data.productDetails.minimum : 1;
      this.minimum_quantity = (data.data.productDetails.minimum !== 0) ? data.data.productDetails.minimum : 1;
      this.stock_status = data.data.productDetails.stock_status_id ? data.data.productDetails.stock_status_id : '';

      console.log('ViewContent',{
          value: this.productData["currency"], currency: 'USD', content_ids: this.productData["id"], content_type: 'product', content_category: this.category['sub_category']['slug']
      })
      this.$analytics.fbq.event( 'ViewContent', {
          value: this.productData["currency"], currency: 'USD', content_ids: this.productData["id"], content_type: 'product', content_category: this.category['sub_category']['slug']
      })

      if (this.productImages) {
        let url = [];
        let api = this.api + '/storage/img/products/';
        this.productImages.map(function (value, key) {
          url.push(api + value['images']['name']);
        });
        this.images = url;
      }

      if (this.images[0]) {
        this.subImages = 'images'
      }

      if (this.productData.sale_id !== null) {
        this.is_discount = true;
      }
    },
    async getSubImages() {
      if (this.images[0]) {
        this.index = 0;
      }
    },
    async checkout() {
      this.$loading(true);
      console.log(this.productData)
        console.log('InitiateCheckout', {
            value: this.productData["currency"], currency: 'USD', content_type: 'product', content_category: this.category['sub_category']['slug'], content_ids: this.productData["id"]
        })
      this.$analytics.fbq.event( 'InitiateCheckout', {
          value: this.productData["currency"], currency: 'USD', content_type: 'product', content_category: this.category['sub_category']['slug'], content_ids: this.productData["id"]
      })
      try {
        this.clearValidation()
        let validate = await this.$refs['orderQuickForm'].validate();

                    if (validate) {
                        const product = this.productData;
                        product.quantity = this.count_good;

                        const form = {
                            phone: this.phone,
                            product: product,
                            user_id: this.user_id
                        };

                        await this.axios.post('checkout/create/orderQuickFromProduct', form).then(({data}) => {

                            if (data) {
                                this.$notify({
                                    type: 'success',
                                    title: 'Успех!',
                                    text: data.message
                                });
                                this.clearValidation();

                                this.toPage({name: 'order-status', params: {token: data.token}});

                                this.clearCartProducts()
                            }
                        })
                    }
                    this.$loading(false);
                } catch (e) {
                    this.$loading(false);
                    this.errorMessagesValidation(e);
                }

            },
            async preOrder() {
                this.$loading(true);

                this.$analytics.fbq.event( 'InitiateCheckout', {
                    value: this.productData["currency"], currency: 'USD', content_type: 'product', content_category: this.category['sub_category']['slug'], content_ids: this.productData["id"]
                })
                try {
                    this.clearValidation()
                    let validate = await this.$refs['orderQuickForm'].validate();

                    if (validate) {
                        const product = this.productData;
                        product.quantity = this.count_good;

                        const form = {
                            phone: this.phone,
                            product: product,
                            user_id: this.user_id
                        };

                        await this.axios.post('checkout/create/preOrder', form).then(({data}) => {

                            this.clearValidation();

          this.toPage({name: 'order-status', params: {token: data.token}});
                        })
        }
        this.$loading(false);
      } catch (e) {
        this.$loading(false);
        this.errorMessagesValidation(e);
      }
    },
    clearValidation() {
      this.errorValid = {
        phone: ''
      }
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
    openBasket() {
      this.showMessage = false;
      this.visibleBasket(true);
    }
  }
}
</script>

<style lang="scss" scoped>

@import "src/styles/main";

.product-wrapper {
  padding: 18px 45px 0 45px;
}

.block-product-base-info {
  width: 100%;
  display: flex;
  gap: 30px;

  &__image {
    width: 50%;
    height: 380px;
    position: relative;
    justify-content: center;
    display: inline-flex;
  }

  .image {
    &__product {
      max-width: 100%;
      height: 100%;
    }

    &__discount {
      position: absolute;
      top: 20px;
      left: 20px;
      background-color: black;
      color: white;
      width: 50px;
      height: 50px;
      text-align: center;
      font-weight: 300;
      border-radius: 50%;
      vertical-align: center;
      line-height: 50px;
    }
  }

  &__info {
    width: 50%;
    display: flex;
    flex-direction: column;
    row-gap: 9px;
  }
}

.info {

  &-title {
    display: flex;
    flex-direction: column;
    font-size: 18px;

    &__title {
      font-weight: 700;
    }

    &__subtitle {
    }

  }


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

    &__in-stock {
      font-size: 11px;
      color: $palette-base-color;
      margin-bottom: 0;
    }

  }

  &-description {
    font-size: 15px;
    line-height: 20px;
    color: black;
  }

  &-count {
    display: flex;
    flex-direction: column;
    row-gap: 10px;

    &__title {
      font-weight: 400;
      font-size: 13px;
      line-height: 18px;
    }

    &__input {
      padding: 0;
      width: calc(42px + (21px * 2));
      text-align: center;

      &-control {
        padding: 4px;
        margin: 0;
        cursor: pointer;
        font-size: 13px;
        $input-slot-margin-bottom: 0;
      }
    }
  }

  &-pay-control {
    margin-top: 23px;
    display: flex;
    align-items: flex-start;
    column-gap: 15px;

    @media screen and (max-width: 767px) {
      flex-direction: column;
      row-gap: 15px;
    }

    &__buy {
      display: flex;
      flex-direction: column;
      justify-content: center;
      row-gap: 10px;
      @media screen and (max-width: 767px) {
        width: 100%;
        max-width: 243px;
      }

      button {
        text-transform: none;
        text-align: center;
        font-size: 16px;
        line-height: 22px;
        font-weight: bold;
        padding: 13px 62px !important;
        background-color: $palette-base-color;
        height: 48px !important;
        border-radius: 50px;
        @media screen and (max-width: 767px) {
          width: 100%;
        }

        &[disabled].theme--light {
          background-color: $palette-disable-color !important;
        }
      }

      &__input {
        background-color: transparent;
        color: white;
        width: 177px;
        font-size: 16px;
        line-height: 22px;
        font-weight: 500;
        text-align: center;

        &::placeholder {
          color: #C4C4C4;
        }
      }

      &-fast {
        display: flex;
        flex-direction: column;
        justify-content: center;
        row-gap: 10px;

        &__input {
          margin: 0 !important;
          display: flex;
          justify-content: center;
          background-color: $palette-disable-color;
          height: 48px;
          text-align: center;
          border-radius: 50px;
          position: relative;
          align-items: center;
          padding: 0 50px !important;

            &.v-text-field {
              padding: 0;
            }

            & ::v-deep {
              & .v-input__slot {
                padding: 0;
                margin: 0;

              & input {
                padding: 0;
                line-height: 22px;
                font-size: 16px;
                font-weight: 500;
                font-family: Manrope, sans-serif !important;
                color: black;
                  width: 145px;
              }
            }

                & .v-text-field {
                    &__details {
                        position: absolute;
                        bottom: -15px;
                        white-space: nowrap;
                        transform: translateX(0);
                        min-width: fit-content;
                        text-align: center;
                    }
                }
            }
        }
      }
    }

    &__text {
      font-size: 12px;
      font-style: normal;
      line-height: 16px;
      text-align: center;
      cursor: pointer;
      font-weight: 200;
    }
  }
}

.block-product {
  display: flex;
  flex-direction: column;
  margin-top: 38px;

  &__tabs {
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 100%;
  }
}

input[type=number] {
  &::-webkit-inner-spin-button {
    -webkit-appearance: none;
  }

  text-align: center;
}

.images {
  &:hover {
    cursor: pointer;
    //box-shadow: 0 2px 8px rgb(0 0 0 / 25%);
  }
}


.breadcrumb {
  cursor: pointer;
  display: flex;
  column-gap: 12px;
  font-size: 10px;
  font-weight: 200;
  line-height: 16px;

  &-item {

    &:last-of-type {
      color: #C9C9C9;
    }
  }
}


::v-deep {

  & .v-tabs-bar {
    height: 25px;
  }

        .description-content {
            padding: 0 20px;

            & > div {
                float: left;
            }

            & * {
                font-family: inherit;
            }
        }
    }

.input-count-good {
  width: 42px;

  &[disabled] {
    background-color: white !important;
  }
}

</style>

<template>
  <div class="product-mobile__wrapper">
<!--    <div class="breadcrumb" style="margin:10px; text-align: center; justify-content: center">-->
<!--      <span class="breadcrumb-item" @click="toPage( {name:'home'} )">Главная</span>-->
<!--      <span class="breadcrumb-item arrow"/>-->
<!--      <span class="breadcrumb-item"-->
<!--            @click="toPage({name: 'category-page', params:{ category: category['main_category']['slug'] }} )">{{-->
<!--          category['main_category']['title']-->
<!--        }}</span>-->
<!--      <span v-if="category['main_category'].length !== 0" class="breadcrumb-item arrow"/>-->
<!--      <span v-if="category['sub_category'] !== null" class="breadcrumb-item"-->
<!--            @click="toPage({name: 'sub-category-page', params:{ category: category['sub_category']['slug'], subCategory: category['sub_category']['slug'] }} )">{{-->
<!--          category['sub_category']['title']-->
<!--        }}</span>-->
<!--      <span v-if="category['sub_category'] !== null" class="breadcrumb-item arrow"/>-->
<!--      <span class="breadcrumb-item">{{ description['name'] }}</span>-->
<!--    </div>-->
<!--    <v-system-bar color="#000" class="product-mobile__system-bar" dark height="34">-->
<!--      <div>Бесплатная доставка от <span style="font-weight: 700">1500 грн</span></div>-->
<!--      <div style="margin-top: 5px;"> <img width="18" height="18" src="../../../public/package.svg"/></div>-->
<!--    </v-system-bar>-->

<!--    <v-system-bar color="#000" class="product-mobile__system-bar" dark height="44">-->
<!--        <div style="font-size: 11px; text-align: center">Заказы в которых есть "Крем Жиросжигающий Антицеллюлитный с охлаждающим эффектом" - отправляются в течении 7 рабочих дней.</div>-->
<!--    </v-system-bar>-->

    <div class="product-info__wrapper">
      <div class="product-info__discount" v-if="is_discount">
        <span>{{ -productData.get_sale.percent }}%</span>
      </div>
      <div class="product-info__image">
        <img style="width: 100%" :src="image" :alt="productData['image']['name']" :class="subImages" @click="getSubImages()"/>
      </div>
      <!--            <div class="product-info__other">-->
      <!--                <div class="product-info__other__icons">-->
      <!--                    <ThreeDotsSlides/>-->
      <!--                    <v-icon size="10" color="#000">mdi-gift-outline</v-icon>-->
      <!--                    <v-icon size="10" color="#000" v-if="isShowFavorite">mdi-heart-outline</v-icon>-->
      <!--                </div>-->
      <!--            </div>-->

      <div class="product-info__title">
        {{ description['name'] || '' }}
      </div>
      <div class="product-info__title">
        {{ productData['upc'] || '' }}
          <span>
            {{ productData['jan'] || '' }}
          </span>
      </div>

      <div class="product-info__price">
        <span class="product-info__price__price">{{
            is_discount ? productData['price_with_sale'] : productData['price']
          }} грн</span>
          <input type="hidden" class="price_in_currency" :value='productData["currency"] + " USD"'>
          <span class="product-info__price__discount" v-if="is_discount">Старая цена: {{
            productData['price']
          }} грн.</span>
      </div>

      <div class="product-info__pay">
        <v-btn v-if="stock_status === 2"  @click="preOrder" class="product-info__pay__button white--text" height="54" color="#2F7484"
               elevation="0">
          Предзаказ
        </v-btn>
          <v-btn v-else @click="addToCart"
                 class="product-info__pay__button white--text" height="54" color="#2F7484" elevation="0">
              Купить
          </v-btn>
      </div>

      <div class="product-info__tabs">
        <v-tabs
          style="justify-content: left"
          hide-slider
          color="#000"
          background-color="#f7f7f7"
          v-model="tab">
          <v-tab
            active-class="product-info__tabs__active"
            class="product-info__tabs__default"
            :href="`#tab-description`">
            Описание
          </v-tab>
          <v-tab
            active-class="product-info__tabs__active"
            class="product-info__tabs__default"
            :href="`#tab-${idx}`"
            v-for="(item, idx) in items"
            :key="idx"
            v-html="item['tab_title']">
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="tab">
          <v-tab-item
            :value="'tab-description'">
            <v-card flat class="tab-text" v-html="productDescription">
            </v-card>
          </v-tab-item>
          <v-tab-item
            v-for="(item, idx) in items"
            :key="idx"
            :value="'tab-' + idx">
            <v-card flat class="tab-text" v-html="item['tab_desc']">
            </v-card>
          </v-tab-item>
        </v-tabs-items>
      </div>
    </div>
    <div class="product-mobile__recommended">
      <ProductCardsSetMobile type-set="product" title="Рекомендуемые товары"
                             :product-data="recommendedProduct.slice(0, 4)"/>
    </div>

    <vue-gallery-slideshow :images="images" :index="index" @close="index = null"></vue-gallery-slideshow>
    <v-snackbar
      v-model="showMessage"
      v-bind="snackbar">
      Товар добавлен в корзину
    </v-snackbar>

    <PreOrderOneClickModal ref="PreOrderOneClickModal" :data-card="productData" :name="name" :phone="phone"
                           :user_id="user_id"/>

  </div>
</template>

<script>
import variables from '@/styles/main.scss'
import {TheMask} from 'vue-the-mask';
import PathBreadcrumb from "@/components/PathBreadcrumb";
import ProductCardsSet from "../../components/desktop/ProductCardsSetDesktop";
import ThreeDotsSlides from "../../components/ThreeDotsSlides";
import ProductCardsSetMobile from "../../components/mobile/ProductCardsSetMobile";
import VueGallerySlideshow from 'vue-gallery-slideshow';
import PreOrderOneClickModal from "../../components/PreOrderOneClickModal.vue";
import {mapActions} from "vuex";

export default {
  name: "Product",
  components: {
    PathBreadcrumb,
    TheMask,
    ProductCardsSet,
    ThreeDotsSlides,
    ProductCardsSetMobile,
    VueGallerySlideshow,
    PreOrderOneClickModal
  },
  props: {
    id: {
      type: [Number, String],
      default: 1
    },
  },
  computed: {
    maskOptions() {
      return {
        mask: '+38(###) ###-##-##',
        masked: false,
        placeholder: "+38(___) ___-__-__"
      }
    },
    validPhoneInput() {
      return this.phone.length === 10
    },
    route() {
      return this.$route.params;
    },
    is_discount() {
      return !!this.productData.get_sale
    }
  },
  watch: {
    route: {
      deep: true,
      handler(newRoute, oldRoute) {
        this.fetchProductDetails();
      },
    }
  },
  created() {
    this.fetchProductDetails();
  },
  data() {
    return {
      tab: null,
      items: [],
      variables,
      count_good: 1,
      name: '',
      phone: '',
      user_id: '',
      productData: {
        image: {},
        product_description: {},
                    get_sale: {
                        percent: ''
                    }
                },
                stock_status: '',
                attr: [],
                description: [],
                productImages: [],
                recommendedProduct: [],
                images: [],
                image: '',
                index: null,
                subImages: null,
                category: {
                    main_category: {},
                    sub_category: {}
                },
                productDescription: '',
                showMessage: false,
                snackbar: {
                    top: true,
                    right: true,
                    color: 'green',
                    timeout: 900,
                    multiLine: true
                }
            }
        },
        methods: {
            ...mapActions('basket', {
                addProduct: 'ADD_PRODUCT'
            }),
                    async fetchProducts()
    {
      try {
      this.$loading(true)
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
            incrementCountGood() {
                ++this.count_good;
            },
            decrementCountGood() {
                if (this.count_good > 1) {
                    --this.count_good;
                }
            },
            addToCart() {
                const product = this.productData;
                product.quantity = 1;

                console.log('AddToCart',{
                    value: this.productData["currency"], currency: 'USD', content_ids: this.productData["id"], content_type: 'product', content_category: this.category['sub_category']['slug']
                })
                this.$analytics.fbq.event( 'AddToCart', {
                    value: this.productData["currency"], currency: 'USD', content_ids: this.productData["id"], content_type: 'product', content_category: this.category['sub_category']['slug']
                })
                this.addProduct(product);

              this.action_data_basket_info(product);
              this.action_visible_basket_info(true);
    },
    async fetchProductDetails() {
      let data = await this.axios.get('product/' + this.id);

      this.productData = data.data.productDetails;
      this.description = this.productData['product_description'];
      this.productDescription = data.data.description;
      this.items = this.productData['product_apts'];
      this.productImages = this.productData.product_images;
      this.recommendedProduct = data.data.recommendedProduct;
      this.image = this.api + '/storage/img/products/' + this.productData['image']['name'];
      this.category = data.data.product_category;
      this.stock_status = data.data.productDetails.stock_status_id ? data.data.productDetails.stock_status_id : '';

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
        console.log('ViewContent',{
            value: this.productData["currency"], currency: 'USD', content_ids: this.productData["id"], content_type: 'product', content_category: this.category['sub_category']['slug']
        })
      this.$analytics.fbq.event( 'ViewContent', {
          value: this.productData["currency"], currency: 'USD', content_ids: this.productData["id"], content_type: 'product', content_category: this.category['sub_category']['slug']
      })
    },
    async getSubImages() {
      if (this.images[0]) {
        this.index = 0;
      }
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
  },
}
</script>

<style lang="scss" scoped>

.product-mobile {
  &__wrapper {
    background-color: #f7f7f7;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    padding: 0 20px;
  }
}

.product-info {

  &__wrapper {
    width: 100%;
    position: relative;
    margin-top: 25px;
  }

  &__discount {
    left: 20px;
    top: 20px;
    position: absolute;
    background-color: black;
    color: white;
    width: 48px;
    height: 48px;
    line-height: 48px;
    text-align: center;
    font-weight: 300;
    border-radius: 50%;

    & span {

    }
  }

  &__image {
    width: 100%;
    display: flex;
    justify-content: center;
    background-color: #fff;

    /* on Safari and Chrome  */
    @media screen and (-webkit-min-device-pixel-ratio:0) {
      max-height: 100vw;
    }
  }

  &__other {
    display: flex;
    justify-content: space-between;

    &__icons {
      display: flex;
      column-gap: 10px;
      align-items: center;
    }
  }

  &__title {
    margin: 10px 0;
    font-weight: 700;
    font-size: 15px;
    line-height: 20px;
    text-align: center;
  }

  &__price {

    display: flex;
    justify-content: space-evenly;
    align-items: center;
    margin: 0 40px;

    &__price {
      font-weight: 800;
      font-size: 21px;
      line-height: 29px;
      color: #2F7484;
    }

    &__discount {
      font-weight: 200;
      font-size: 16px;
      line-height: 22px;
      text-decoration-line: line-through;
    }
  }

  &__pay {
    display: flex;
    justify-content: center;
    padding: 15px;

    &__button {
      border-radius: 60px;
      text-transform: none;
      width: 100%;
      max-width: 335px;
    }
  }

  &__tabs {
    text-transform: none;

    &__active {
      background-color: #fff !important;

      &:before {
        opacity: 0 !important;
      }

      &:hover:before {
        opacity: 0 !important;
      }

      &:focus:before {
        opacity: 0 !important;
      }
    }

    &__default {
      text-transform: none;
      background-color: #f7f7f7;
      font-size: 12px;
      line-height: 16px;
      padding: 3px 5px;
      height: 17px;
    }
  }


}

.tab-text {
  font-size: 12px;
  line-height: 16px;
  padding: 10px;
  width: 100%;

  & ::v-deep {
    & > * {
      max-width: 100%;
    }
  }
}

.product-mobile {
  &__system-bar {
    width: 100vw;
    justify-content: center;
    color: #fff;
    column-gap: 5px;
  }

  &__recommended {
    margin-top: 20px;

    & > .product-card__wrapper {
      padding: 0;
    }
  }
}

input[type=number] {
  &::-webkit-inner-spin-button {
    -webkit-appearance: none;
  }

  text-align: center;
}

.breadcrumb {

  &-item {

    &:last-of-type {
      color: rgba(29, 70, 84, 0.69);
    }
  }
}

.arrow {
  width: 10px;
  height: 1px;
  margin: 0 5px;
  font-size: 12px;
  line-height: 16px;
  display: inline-block;
  vertical-align: middle;
  border-radius: 1px;
  position: relative;
  background-color: black;

  &:before {
    content: ' ';
    position: absolute;
    right: 0;
    top: 0;
    width: 7px;
    height: 1px;
    border-radius: 1px;
    transform: rotate(45deg) translate(0, -300%);
    background-color: black;
  }

  &:after {
    content: ' ';
    position: absolute;
    right: 0;
    top: 0;
    width: 7px;
    height: 1px;
    border-radius: 1px;
    transform: rotate(-45deg) translate(0, 300%);
    background-color: black;
  }
}
</style>

<style lang="scss">
.product-info__wrapper {
  & .v-slide-group__prev {
    display: none !important;
  }

  & .v-tabs {
    height: 17px !important;
  }
}

</style>

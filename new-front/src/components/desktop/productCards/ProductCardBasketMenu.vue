<template>
    <div class="product-basket__wrapper">
        <div class="product-basket">
            <img @click="toPage({name: 'product', params: {id: dataCard.id}})" class="product-basket__image" height="150" width="150"
                 :src="dataCard.image ? this.api + '/storage/img/products/' + dataCard.image.name : ''" :alt="dataCard.image ? dataCard.image.name : ''"/>
        </div>
        <div class="product-basket">
            <div class="product-basket__title" @click="toPage({name: 'product', params: {id: dataCard.id}})">
                {{ dataCard.product_description.name }}
            </div>
            <div class="product-basket__text">
                <div class="product-basket__text__price">{{ isShowStock ? dataCard.price_with_sale : dataCard.price }} грн</div>
                <input type="hidden" class="price_in_currency" :value=' dataCard.currency + " USD"'>
                <div class="product-basket__text__delete-basket">
                    <v-btn :disabled="dataCard.stock_status_id === 3" class="product__button white--text" elevation="0" @click="addToCart">
                        {{ dataCard.stock_status_id === 3 ? 'Нет в наличии' : 'Добавить'}}
                    </v-btn>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {mapActions} from "vuex";

    export default {
        name: "ProductCardBasketMenu",
        props: {
            dataCard: {
                type: Object,
                default: () => {},
                stock_status_id: ''
            },
            isShowStock:{
                type: Boolean,
                default: false
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
            addToCart() {
                const product = this.dataCard;
                product.quantity = (product.minimum !== 0) ? product.minimum : 1;

                console.log('AddToCart',{
                    value: this.dataCard.currency, currency: 'USD', content_ids: this.dataCard.id, content_type: 'product', content_category: this.dataCard.category
                })
                this.$analytics.fbq.event( 'AddToCart', {
                    value: product.quantity * this.dataCard.currency, currency: 'USD', content_ids: this.dataCard.id, content_type: 'product', content_category: this.dataCard.category
                })
                this.addProduct(product)
            },
        }
    }
</script>

<style scoped lang="scss">

    .product-basket {

        &__wrapper {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 10px;
            column-gap: 10px;

            &:hover {
                box-shadow: 0 0 33px #f2f2f2;
            }
        }

        &__image {
            cursor: pointer;
            max-width: 100%;
        }

        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 100%;
        text-align: center;

        &__title {
            cursor: pointer;
            font-weight: 700;
            font-size: 10px;
            line-height: 14px;
        }

        &__text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            font-size: 11px;

            &__price {
                font-weight: 700;
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
        width: 100px;
        height: 28px !important;
        background-color: #2F7484 !important;
        text-transform: none !important;
        font-weight: 700;
        font-size: 11px;
        line-height: 15px;
    }
</style>

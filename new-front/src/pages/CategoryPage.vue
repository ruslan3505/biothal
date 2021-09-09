<template>
    <div>
        <agile class="agile-slider" :autoplay="carousel.length!==1" :infinite="carousel.length!==1" :autoplaySpeed="5000" :navButtons="false" :speed="1000" :key="carousel.length">
            <div class="slide" v-for="(item, index) in carousel" :key="index">
                <a v-if="item.href" :href="item.href"><img width="100%" :src="api + '/storage/img/carousel/' + item['name']"/></a>
                <img v-else width="100%" :src="api + '/storage/img/carousel/' + item['name']"/>
            </div>
        </agile>
        <ProductsPaginate ref="productsPaginate" :title="$route.params.category == 'new_products' ? 'Новинки Biothal' : categoryDetails.title" :is-empty-message="this.$route.params.accessory ? accessoryMessage : categoryMessage" :url="productsUrl"/>
        <div class="main-title seo-text-title">{{categoryDetails.seo_title}}</div>
        <div class="seo-text-description" v-html="categoryDetails.seo_description"></div>
    </div>
</template>

<script>
    import ProductCardsSetDesktop from "../components/desktop/ProductCardsSetDesktop";
    import ProductCardsSetMobile from "../components/mobile/ProductCardsSetMobile";
    import ProductsPaginate from "@/components/ProductsPaginate";

    export default {
        name: "CategoryPage",
        components: {
            ProductsPaginate,
            ProductCardsSetDesktop,
            ProductCardsSetMobile
        },
        metaInfo() {
            return {
                title: this.categoryDetails.seo_title,
                meta: [
                    { vmid: 'description', name: 'description', content: this.categoryDetails.seo_description },
                    { vmid: 'slug', name: 'slug', content: this.categoryDetails.slug }
                ]
            }
        },
        prop: {
            category: {
                type: [Number, String],
                default: 0
            },
            subCategory: {
                type: [Number, String],
                default: 0
            },
        },
        data() {
            return {
                carousel: [],
                productData: [],
                categoryDetails: [],
                categoryMessage: 'В данной категории нет товаров.',
                accessoryMessage: 'В данной потребности нет товаров.',
                seoText: 'SEO-ТЕКСТ ДЛЯ КАТЕГОРИИ',
                productsUrl: null,
            }
        },
        computed: {
            route() {
                return this.$route.params;
            }
        },
        created() {
            this.productsUrl = (this.$route.params.accessory) ? 'accessory/products/' + this.$route.params.accessory : (!this.$route.params.subCategory) ? 'category/products/' + this.$route.params.category : 'category/products/' + this.$route.params.category + '/' +  this.$route.params.subCategory;
            this.fetchCarouselImage();
            this.getCategoryDetails();
        },
        watch: {
            route: {
                deep: true,
                handler (newRoute, oldRoute) {
                    this.fetchCarouselImage();
                    this.getCategoryDetails();

                    this.productsUrl = (newRoute.accessory) ? 'accessory/products/' + newRoute.accessory : (!newRoute.subCategory) ? 'category/products/' + newRoute.category : 'category/products/' + newRoute.category + '/' +  newRoute.subCategory;
                },
            },
            productsUrl(val) {
                setTimeout(() => {
                    this.$refs.productsPaginate.getProducts();
                }, 1)
            }
        },
        methods: {
            async fetchCarouselImage() {
                let data = await this.axios.get('image');

                this.carousel = this.isMobile ? data.data.carouselMobile : data.data.carouselDesktop;
            },
            async getCategoryDetails() {
                let id = (this.$route.params.accessory) ? this.$route.params.accessory : (!this.$route.params.subCategory) ? this.$route.params.category : this.$route.params.subCategory;
                let data = await this.axios.get(((this.$route.params.accessory) ? 'accessoryDetails/' : 'categoryDetails/') + id);

                this.categoryDetails = data.data;

            }
        }
    }
</script>

<style scoped lang="scss">

    .seo-text-description {
        padding: 20px;
        column-count: 2;
        column-gap: 30px;

        @media screen and (max-width: 600px) {
            column-count: 1;
        }
    }

    .agile-slider {
        padding: 0 !important;
    }
</style>

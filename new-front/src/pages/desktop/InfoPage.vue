<template>
    <div>
        <agile class="agile-slider" :autoplay="carousel.length!==1" :infinite="carousel.length!==1" :autoplaySpeed="5000" :navButtons="false" :speed="1000" :key="carousel.length">
            <div class="slide" v-for="(item, index) in carousel" :key="index">
                <img width="100%" :src="api + '/storage/img/carousel/' + item['name']"/>
            </div>
        </agile>
        <div>
            <div class="info-page__title">
                {{article.title || 'Статья еще не добавлена'}}
            </div>
            <div class="info-page__content__wrapper" v-if="this.$route.params.id !== 'sertifikaty'" v-html="article.description">
            </div>
            <vue-gallery-slideshow :images="images" :index="index" @close="index = null"/>
            <div v-if="this.$route.params.id === 'sertifikaty'" id="app" class="certificates-image">
                <img class="image" v-for="(image, i) in images" :src="image" :key="i" @click="index = i">
                <vue-gallery-slideshow :images="images" :index="index" @close="index = null"></vue-gallery-slideshow>
            </div>
        </div>

    </div>
</template>

<script>

    import VueGallerySlideshow from 'vue-gallery-slideshow';

    export default {
        name: "InfoPage",
        components: {
            VueGallerySlideshow
        },
        data() {
            return {
                title: '',
                article: [],
                carousel: [],
                isCertificate: false,
                images: [
                    '/storage/img/certificate/1-min.jpg',
                    '/storage/img/certificate/2-min.jpg',
                    '/storage/img/certificate/3-min.jpg',
                    '/storage/img/certificate/4-min.jpg',
                    '/storage/img/certificate/5-min.jpg',
                    '/storage/img/certificate/6-min.jpg',
                    '/storage/img/certificate/7-min.jpg',
                    '/storage/img/certificate/8-min.jpg',
                    '/storage/img/certificate/9-min.jpg',
                    '/storage/img/certificate/10-min.jpg',
                    '/storage/img/certificate/11-min.jpg',
                    '/storage/img/certificate/12-min.jpg',
                ],
                index: null
            }
        },
        metaInfo() {
            return {
                title: this.article.meta_title,
                meta: [
                    { vmid: 'description', name: 'description', content: this.article.meta_description },
                    { vmid: 'keywords', name: 'keywords', content: this.article.meta_keywords },
                    { vmid: 'slug', name: 'slug', content: this.article.slug }
                ]
            }
        },
        computed: {
            route() {
                return this.$route.params;
            }
        },
        watch: {
            route: {
                deep: true,
                handler (newRoute, oldRoute) {
                    this.fetchInfoPage();
                    this.isCertificate = this.$route.params.id === 'sertifikaty';
                },
            }
        },
        created() {
            this.fetchInfoPage();

            let img = [];
            let api = this.api;
            this.images.forEach( function (value, index) {
                img[index] = api + value;
            })
            this.images = img;

            this.isCertificate = this.$route.params.id === 'sertifikaty';
        },
        methods: {
            async fetchInfoPage() {
                this.$loading(true)
                let data = await this.axios.get('info-page/' + this.$route.params.id)

                try {
                    if (data) {
                        this.article =  data.data.article;
                        this.carousel = this.isMobile ? data.data.carouselMobile : data.data.carouselDesktop;
                        this.title =  this.$route.params.id;

                        this.$loading(false)
                    }
                } catch (e) {
                    this.$loading(false);
                    this.errorMessagesValidation(e);
                }

            },
            async getSubImages() {
                if (this.images[0]) {
                    this.index = 0;
                }
            },
        }
    }
</script>

<style scoped lang="scss">

    .info-page {
        &__title {
            text-align: center;
            text-transform: uppercase;
            font-size: 34px;
            margin: 50px 50px 0 50px;

            @media screen and (max-width: 600px) {
                margin: 20px;
                font-size: 27px;
            }
        }

        &__content {
            &__wrapper {
                max-width: 100%;
                padding: 0 45px 45px 45px !important;

                @media screen and (max-width: 600px) {
                    padding: 0 20px 20px 20px !important;
                }
            }
        }
    }

    .agile-slider {
        padding: 0 !important;
    }

    .image {
        width: 174px;
        height: 250px;
        background-size: cover;
        cursor: pointer;
        margin: 5px;
        border-radius: 3px;
        border: 1px solid lightgray;
        object-fit: contain;
    }

    .certificates-image {
        text-align: center;
        padding-top: 30px!important;
        padding-bottom: 40px!important;
    }

    @media screen and (max-width: 600px) {
        .vgs{
            & ::v-deep &__container {
                top: 43%;
                height: 52vh!important;
            }
        }
    }

    @media screen and (min-width: 600px) {
        .vgs{
            & ::v-deep &__container {
                height: 90vh!important;
            }

            & ::v-deep &__gallery__container {
                height: 62px;
            }

            & ::v-deep &__gallery__container__img {
                width: 60px;
                height: 60px;
                margin-right: 18px;
            }
        }
    }

</style>

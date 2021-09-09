<template>
  <div class="home-wrapper">
    <agile class="agile-slider" :autoplay="carousel.length!==1" :infinite="carousel.length!==1" :autoplaySpeed="5000" :navButtons="false" :speed="1000" :key="carousel.length">
      <div class="slide" v-for="(item, index) in carousel" :key="index">
          <a v-if="item.href" :href="item.href"><img width="100%" :src="api + '/storage/img/carousel/' + item['name']"/></a>
          <img v-else width="100%" :src="api + '/storage/img/carousel/' + item['name']"/>
      </div>
    </agile>

    <products-paginate v-if="newProductsData.data.length" url="category/products/new_products" title="Новинки Biothal" />

    <products-paginate v-if="productsData.data.length" url="home/sales-products" title="Подарки и скидки"
                       :isShowStock="true"/>

    <div class="category-card__wrapper">
      <div class="category-card__inner">
        <CategoryCard class="category-card__block" :face="true"/>
        <CategoryCard class="category-card__block" :face="false"/>
      </div>
    </div>

    <products-paginate class="product__best-sellers" v-if="bestSellersData.data.length" url="home/best-sellers" title="Бестселлеры"/>

    <div class="description-biothal">
      <span class="main-title description-biothal__title">Интернет-магазин Biothal</span>
      <div class="description-biothal__inner">
        <div v-if="isMobile" class="description-biothal__text">
          <p>
            Хоть раз открыв каталог интернет-магазина BIOTHAL уже не захочется тратить время на походы по
            торговым центрам – каталог косметики и парфюмерии, представленный здесь, превосходит даже смелые
            ожидания и удовлетворяет любые требования. Добавьте к этому понятный интерфейс, универсальную
            систему поиска, возможность получить свой заказ на дом в удобное время и вы получите идеальный
            ресурс, который постоянно совершенствуется уже 10 лет – с 2009 года.
          </p>
          <span @click="isShowDescription = true" v-show="!isShowDescription" style="font-weight: 700">
            Читать далее
          </span>
        </div>
        <div v-show="!isMobile || isShowDescription" class="description-biothal__text"
             style="padding-right: 30px">
          <p>Во Франции, на севере Бретани, находится заповедная территория, дикая и нетронутая природа,
            крупнейшая долина и историческое место сбора водорослей в Европе.</p>

          <p>На протяжении многих лет здесь производят косметику на основе морских ингредиентов.</p>

          <p>Именно здесь Специалисты компании Biothal в тесном сотрудничестве с известными микробиологами и
            альгологами разрабатывают уникальные формулы продуктов являющихся бесспорным лидером в области
            Спа
            услуг, ухода за кожей лица и тела. Успешное сочетание натуральных ингредиентов с современными
            технологиями позволило нам создать уникальные средства для продления молодости кожи с
            максимальным
            терапевтическим эффектом</p>

          <p>Каждый продукт Biothal представляет собой настоящий эликсир красоты и молодости, концентрат
            морской
            силы, который работает в абсолютной синергии с кожей и соответствует самым высоким мировым
            стандартам качества. Тысячи женщин уже оценили профессиональный подход марки и высокую
            эффективность
            продуктов Biothal.</p>

          <p>Водоросли - источник жизни и целебных свойств известный с древних времен, они концентрируют в
            себе
            все богатство морской воды: йод, магний, калий, железо, селен, цинк, медь, витамины и
            аминокислоты,
            передавая им свою силу детоксикации, минерализации и регенерации.</p>

          <p>Бурая водоросль ламинария, произрастающая в морских водах, обладает целым комплексом минералов и
            активных элементов, необходимых нашей коже. Экстракт ламинарии регулирует работу сальных желёз,
            способствует выводу лишней жидкости, а также оказывают дезинфицирующее действие, убивая
            болезнетворные бактерии.</p>
        </div>
        <div v-show="!isMobile || isShowDescription" class="description-biothal__text">
          <p>Высокое содержание йода способствует нормализации функции щитовидной железы, активизирует все
            виды
            обмена веществ, нормализует обмен жиров и способствует липолизу, повышает потребление кислорода
            клетками, снижает вязкость крови, повышает тонус сосудов.</p>

          <p>Экстракт морской водоросли фукус обладает целым комплексом полезных веществ, которые укрепляют и
            тонизируют кожу. Концентрат полезных элементов, содержащийся в водоросли, способствует
            регенерации
            клеток кожи за счёт стимуляции кровообращения. Фукус имеет высокое содержание йода, также
            витамин С
            и полисахариды. Его свойства благотворно влияют на кожу, восстанавливая эластичность и создавая
            защитную плёнку.</p>

          <p>Фукус содержит вещества полифенолы, обладающие антисептическим и дезинфицирующим действием.
            Водоросль высоко ценится как источник тонизирующих жиросжигающих соединений. Применяется в
            детокс-программах, обладая антицеллюлитным и тонизирующим действием.</p>

          <p>Морской латук произрастает на солнечных скалах, найти его можно на различных глубинах. Растение
            предпочитает спокойные воды на побережье Атлантического океана, в Черном море и в Тихом океане.
            Ярко-зеленую морскую водоросль морской латук, растущую на скалах, собирают только во время
            отлива.
            Растение пропускает через себя морскую воду, оставляя только самые полезные минералы и
            микроэлементы. Растет в мелководных районах, богатых минералами. В составе водоросли содержится
            большой процент магния. Также витамины Е и С. Морской латук богат кальцием, железом и
            магнием.</p>

          <p>Насыщенная хлорофиллом зеленая водоросль, действующая как увлажняющий и противовоспалительный
            компонент. Экстракт оказывает на кожу омолаживающее воздействие, придаёт эластичность и выводит
            токсины.</p>
        </div>
        <div v-if="isMobile" class="description-biothal__text">
          <p @click="isShowDescription = false" v-show="isShowDescription" style="font-weight: 700">
            Показать меньше
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {VueAgile} from 'vue-agile';
import ProductCard from "../components/desktop/productCards/ProductCard";
import CategoryCard from "../components/CategoryCard";
import ProductCardsSet from "../components/desktop/ProductCardsSetDesktop";
import ProductCardsSetMobile from "../components/mobile/ProductCardsSetMobile";
import ProductsPaginate from "@/components/ProductsPaginate";

export default {
  name: "Home",
  props: {
    token: {
      type: [Number, String],
      default: null,
    }
  },
  components: {
    ProductsPaginate,
    agile: VueAgile,
    ProductCard,
    CategoryCard,
    ProductCardsSet,
    ProductCardsSetMobile
  },
  data() {
    return {
      carousel: [],
      isShowDescription: false,
      bestSellerPage: 1,
      bestSellersData: {
        data: {}
      },
      productsData: {
        data: {}
      },
      newProductsData: {
        data: {}
      },
      productsPage: 1
    }
  },
  mounted() {
    this.getProductData();
    this.getBestSellers();
    this.getSalesProducts();
    this.getNewProducts();
    this.addUserToGroup();
  },
  watch: {
    bestSellerPage() {
      this.getBestSellers(this.bestSellerPage);
    },
    productsPage() {
      this.getSalesProducts(this.productsPage);
    }
  },
  computed: {
    bestsellersPagesCount() {
      let count = 0;

      if (this.bestSellersData !== null) {
        if (this.bestSellersData.total <= this.bestSellersData.per_page) {
          count = 1
        } else {
          count = Math.ceil(this.bestSellersData.total / this.bestSellersData.per_page);
        }
      }
      return count;
    },
    productsPagesCount() {
      let count = 0;

      if (this.productsData !== null) {
        if (this.productsData.total <= this.productsData.per_page) {
          count = 1
        } else {
          count = Math.ceil(this.productsData.total / this.productsData.per_page);
        }
      }
      return count;
    }
  },
  methods: {
    async getProductData() {
      let data = await this.axios.get('home');

      this.carousel = this.isMobile ? data.data.carouselMobile : data.data.carouselDesktop;
      this.productData = data.data.products.data;
    },
    async getBestSellers(page = 1) {
      let data = await this.axios.post('home/best-sellers?page=' + page);

      this.bestSellersData = data.data;
    },
    async getSalesProducts(page = 1) {
      let data = await this.axios.post('home/sales-products?page=' + page);

      this.productsData = data.data;
    },
    async getNewProducts(page = 1) {
      let data = await this.axios.post('home/new-products?page=' + page);

      this.newProductsData = data.data;
    },
    async addUserToGroup() {
      if (this.token) {
        this.axios.post('addUserToGroup', {token: this.token});
      }
    }
  }
}
</script>

<style scoped lang="scss">

@import 'src/styles/mixins';
@import 'src/styles/main';

.category-card {
  &__wrapper {
    display: flex;
    flex-direction: row;
    width: 100%;
    justify-content: center;
  }

  &__inner {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    width: 100%;
    padding: 0 45px;

    @include _600 {
      flex-direction: column;
      padding: 0 20px;

      & > div:nth-last-child(1) {
        margin-top: 20px;
      }
    }
  }

  &__block {

    width: 540px;

    @include _600 {
      width: 100%;
      height: 160px !important;
    }
  }
}

.slider-wrapper {
  width: 100%;
}

.home-wrapper {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;

  padding: 0;
}

.description-biothal {
  text-align: center;

  @include _600 {
    padding-top: rem(20) !important;
    background-color: $palette-main-background-color;
  }

  &__text {
    width: 50%;
    font-weight: 200;
    font-size: 14px;
    text-align: left;
    font-style: normal;
    line-height: 19px;
    margin-top: 20px;

    @include _600 {
      width: 100%;
    }
  }

  &__inner {
    display: flex;
    flex-direction: row;
    width: 100%;
    font-style: normal;
    font-weight: 200;
    font-size: 14px;
    line-height: 19px;

    @include _600 {
      flex-direction: column;
    }

    @include _1200 {
      padding: 0 45px;
    }
  }
}

.container {
  display: flex;
  flex-direction: row;
  justify-content: center;
}

.product__best-sellers {
  margin-top: 20px;
}

.agile-slider {
  padding: 0 !important;
}
</style>

<template>
  <div class="menu-wrapper">
    <v-system-bar class="menu-wrapper__system-bar default-cursor black_line_style">
<!--        <div>Отправка заказов в течении 3-5 рабочих дней</div>-->
        <div class="text-center" v-html="black_line_content"></div>
<!--        <div><img class="package" src="../../../public/package.svg"/></div>-->
<!--        <div>Заказы в которых есть "Крем Жиросжигающий Антицеллюлитный с охлаждающим эффектом" - отправляются в течении 7 рабочих дней.</div>-->
    </v-system-bar>

    <v-app-bar class="menu-wrapper__menu">
      <img class="menu-wrapper__logo point-cursor" @click="toPage({name: 'home'})" src="../../../public/logo-biothal.svg"/>
      <div class="app-bar-menu-wrapper">
        <v-slide-group
          multiple
          show-arrows>
          <v-slide-item v-for="(item, index) in menuItemsCategory" :key="item.id">
            <v-menu v-if="item.children.length || item.accessory.length" open-on-hover offset-y>
              <template v-slot:activator="{ on, attrs, value }">
                <v-btn
                  v-bind="attrs"
                  v-on="on"
                  plain
                  @click="toPage({name: 'category', params:{ category: item.slug }})">
                  <span class="bar-menu__category">{{ item.title }}</span>
                    <span v-if="value">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" stroke="olivedrab"
                             stroke-linecap="round" class="bi bi-chevron-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/>
                        </svg>
                    </span>
                    <span v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" stroke="olivedrab"
                             stroke-linecap="round" class="bi bi-chevron-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </span>
                </v-btn>
              </template>
                <div class="bar-menu__wrapper__categories">
                    <v-list v-if="item.children.length" class="bar-menu__wrapper__left">
                        <div v-if="item.accessory.length" class="bar-menu__main-title">Категории</div>
                        <v-list-item
                            class="point-cursor bar-menu__sub-category"
                            v-for="(item, index) in item.children"
                            :key="index"
                            @click="toPage({name: 'category', params:{ category: item.category.slug, subCategory: item.slug }})">
                            <v-list-item-title class="bar-menu__sub-category-title">
                                - {{ item.title }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                    <v-list v-if="item.accessory.length" :class="item.children.length ? 'bar-menu__wrapper__right' : 'bar-menu__wrapper__left'">
                        <div class="bar-menu__main-title">Потребности</div>
                        <v-list-item
                            class="point-cursor bar-menu__sub-category"
                            v-for="(item, index) in item.accessory"
                            :key="index"
                            @click="toPage({name: 'accessories', params:{ accessory: item.slug }})">
                            <v-list-item-title class="bar-menu__sub-category-title-right">
                                - {{ item.title }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </div>

            </v-menu>
            <v-btn v-else @click="toPage({name: 'category', params:{ category: item.slug }})" plain>
              <span class="bar-menu__category">{{ item.title}}</span>
            </v-btn>
          </v-slide-item>
          <v-slide-item v-for="(itemInfoPage, indexInfoPage) in menuItemsInfoPage" :key="itemInfoPage.id">
            <v-menu v-if="itemInfoPage.children_article.length !== 0" open-on-hover offset-y>
              <template v-slot:activator="{ on, attrs, value }">
                <v-btn
                  v-bind="attrs"
                  v-on="on"
                  plain
                  @click="toPage({name: 'info-page', params:{ id: itemInfoPage.slug }})">
                  <span class="bar-menu__category">{{ itemInfoPage.title }}</span>
                    <span v-if="value">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" stroke="olivedrab"
                             stroke-linecap="round" class="bi bi-chevron-up" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/>
                        </svg>
                    </span>
                    <span v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" stroke="olivedrab"
                             stroke-linecap="round" class="bi bi-chevron-down" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </span>
                </v-btn>
              </template>
              <v-list class="bar-menu__wrapper">
                <v-list-item
                  class="point-cursor bar-menu__sub-category"
                  v-for="(itemInfoPage, indexInfoPage) in itemInfoPage.children_article"
                  :key="indexInfoPage"
                  @click="toPage({name: 'info-page', params:{ id: itemInfoPage.attribute.slug }})">
                  <v-list-item-title class="bar-menu__sub-category-title">
                    - {{ itemInfoPage.attribute.title }}
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
            <v-btn v-else @click="toPage({name: 'info-page', params:{ id: itemInfoPage.slug }})" plain>
              <span>{{ itemInfoPage.title}}</span>
            </v-btn>
          </v-slide-item>
            <v-slide-item>
                <v-btn @click="toPage({name: 'distributor'})" plain>
                    <span class="bar-menu__category">Стать дистрибьютором</span>
                </v-btn>
            </v-slide-item>
        </v-slide-group>
      </div>
      <div class="app-bar-menu-icon">
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs, value }">
            <v-btn
              width="20"
              height="20"
              icon
              v-bind="attrs"
              v-on="on">
              <v-icon color="#000" size="18">mdi-account-outline</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item
              class="point-cursor"
              v-for="(item, index) in accountMenuItems"
              :key="index"
              @click="item.click ? item.click() : toPage(item.meta.rout)">
              <v-list-item-title>
                {{ item.name }}
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-icon color="#000" size="18" @click="toPage({name: 'favorites'})" v-if="isShowFavorite">
          mdi-heart-outline
        </v-icon>

        <v-icon color="#000" size="18" @click="action_visible_basket(true)">
          mdi-briefcase-outline
        </v-icon>
        <v-badge
          v-if="products.length > 0"
          color="black"
          :content="products.length"
          overlap>
        </v-badge>
      </div>
    </v-app-bar>
    <Basket ref="Basket"/>
  </div>
</template>

<script>
  import Basket from "../Basket";
  import {mapGetters} from "vuex";

  export default {
    name: "MenuDesktop",
    components: {
      Basket
    },
    data() {
      return {
        menuItemsCategory: [],
        menuItemsInfoPage: [],
          black_line_content: null,
        orders: 0,
      }
    },
    computed: {
      ...mapGetters('basket', [
        'products'
      ]),
      accountMenuItems() {
        const isLogin = [
          {
            name: 'Войти',
            meta: {
              icon: 'login',
              rout: {name: 'authorization'}
            }
          },
          {
            name: 'Зарегистрироваться',
            meta: {
              icon: 'app_registration',
              rout: {name: 'registration'}
            }
          }
        ]

        const isLogout = [
          {
            name: 'Личный кабинет',
            meta: {
              icon: 'mode_edit_outline',
              rout: {name: 'account-settings'}
            }
          },
          {
            name: 'Выйти',
            meta: {
              icon: 'logout',
              rout: {name: 'home'}
            },
            click: this.logout
          }
        ]

        return this.isAuthorize ? isLogout : isLogin
      }
    },
    created() {
      this.fetchMenuData();
    },
    methods: {
      async fetchMenuData() {
        let data = await this.axios.get('menu');
        console.log(data);
        this.menuItemsCategory = data.data.categories;
        this.menuItemsInfoPage = data.data.info_categories;
        this.black_line_content = data.data.black_header.setting_content;
      },
      test() {
        this.orders = this.$refs['Basket'].products.length
      }
    }
  }
</script>

<style scoped lang="scss">
  .menu-wrapper {
    &__system-bar {
      display: flex;
      justify-content: center;
      color: #fff;
      font-weight: normal;
      font-style: normal;
      font-size: 16px;
      line-height: 26px;
      column-gap: 5.5px;
      background-color: #000;
      height: 48px !important;
    }

    &__menu {
      display: flex;
      align-items: center;
      height: 80px !important;
      width: 100%;
      padding: 0;
      background-color: #fff !important;
      box-shadow: 0px 0px 21px rgba(0, 0, 0, 0.05) !important;
    }

    &__logo {
      height: 38px;
      margin-left: 43px;
    }
  }

  .app-bar-menu-wrapper {
    display: flex;
    flex-direction: row;
    /*127px - width logo, 43px - padding left logo, 11px - padding icon, 18px - width icon, 43px - padding right icons*/
    width: calc(100% - 127px - 43px - 18px - 11px - 18px - 11px - 18px - 43px);
    justify-content: center;
  }

  .app-bar-menu-icon {
    padding-right: 43px;
    display: flex;
    flex-direction: row;
    column-gap: 11px;
    height: 21px;

    &:hover {
      cursor: pointer;
    }
  }

  .bar-menu {
    &__wrapper {
      padding: 0 0 6px 0 !important;

        &__categories {
            text-align: center;
            display: flex;
            flex-direction: row;
            position: relative;
        }

        &__left {
            width: 100%;
            flex-direction: column;
            justify-content: space-between;
            text-align: left;
            padding: 0 0 6px 14px;
        }

        &__right {
            flex-direction: column;
            justify-content: space-between;
            /*width: 50%;*/
            text-align: left;
            padding: 0 0 6px 0;
        }
    }

    &__main-title {
        display: flex;
        justify-content: center;
        padding: 5px 14px 6px 0;
    }

    &__category {
      text-transform: none;
      font-style: normal;
      font-size: 14px;
      line-height: 24px;

      font-weight: bold;
    }

    &__sub-category {
      min-height: 24px;
      padding: 0 0 5px 0;
      font-size: 16px;
      line-height: 24px;
    }

    &__sub-category-title {
      font-style: normal;
      font-weight: 200;
      font-size: 14px;
      line-height: 24px;
      padding: 0 54px 0 14px;
    }
    &__sub-category-title-right {
      font-style: normal;
      font-weight: 200;
      font-size: 14px;
      line-height: 24px;
      padding: 0 14px 0 14px;
    }
  }

    ::v-deep .app-bar-menu-wrapper .v-btn__content{
        font-size: 0;
        line-height: 0;
        margin-left: 5px;
    }
</style>

<style lang="scss">
.black_line_style {
    height: 100%;
    justify-content: center;
}
  .menu-wrapper {
    & .v-toolbar__content {
      padding: 0 !important;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      width: 100%;
    }
  }

  .app-bar-menu-wrapper {
    & .v-btn__content {
      font-size: 13px;
      line-height: 18px;
      color: #000;
    }
  }

  .basket_lenght {
    margin: -2px;
  }

  .package{
      height: 3.5vh;
      width: 3.5vh;
      margin-top: 1vh;
  }
</style>

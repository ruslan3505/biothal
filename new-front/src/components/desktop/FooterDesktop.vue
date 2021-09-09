<template>
    <v-footer
      class="footer__wrapper"
      :padless="false">
      <div class="footer__top">
        <div class="footer__top-title">
          Узнавайте первыми о распродажах и новинках!
        </div>
        <div style="max-width: 270px; width: 100%;">
          <v-text-field v-model="email_for_receive_list" label="Электронный адрес" dark class="email__wrapper">
            <v-btn icon slot="append" @click="addEmailToReceiveList">
              <v-icon size="14" class="footer__top__email-icon">
                mdi-arrow-right
              </v-icon>
            </v-btn>
          </v-text-field>
        </div>
      </div>
      <div class="footer__middle" style="justify-content: space-between">

        <div class="footer__middle__block footer-middle-block-description">
          <img class="footer-middle-block__image" @click="toPage({name: 'home'})" width="127" height="38" src="../../../public/logo-biothal.svg"
               style="cursor: pointer; margin-bottom: 8px"/>
          <div class="footer-middle-block__text">
            Каждый продукт Biothal представляет собой настоящий эликсир красоты и молодости, концентрат морской
            силы, который работает в абсолютной синергии с кожей и соответствует самым высоким мировым
            стандартам.
          </div>
        </div>
        <div class="links-wrapper">
           <div v-if="visibleInfoBottom" class="footer__middle__block" >
               <div v-for="(item, index) in menuItemsBottomInfo.slice(0, 4)"
                    :key="index">
                   <v-list v-if="item.children_article_bottom.length" class="v-list-title-name" dense>
                       <v-list-item-title class="list-item__title">{{item.title}}</v-list-item-title>
                       <div v-for="(item, index) in item.children_article_bottom"
                            :key="index">
                           <v-list-item v-if="item.info_for_bottom" class="list-item"
                                        @click="toPage({name: 'info-page', params: {id: item.info_for_bottom ? item.info_for_bottom.attributes.slug : '' }})">
                               <v-list-item-content>
                                   - {{ item.info_for_bottom ? item.info_for_bottom.attributes.title : ''}}
                               </v-list-item-content>
                           </v-list-item>
                       </div>

                   </v-list>
               </div>
           </div>

          <div class="footer__middle__block">
            <v-list class="v-list-title-name" dense>
              <v-list-item-title class="list-item__title">Каталог</v-list-item-title>
              <v-list-item class="list-item" v-for="(item, index) in menuItemsCategory.slice(0, 4)"
                           :key="index"
                           @click="toPage({name: 'category', params: {category: item.slug }})">
                <v-list-item-content>
                  - {{ item.title }}
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </div>
          <div class="footer__middle__block">
            <v-list class="v-list-title-name" dense>
              <v-list-item-title class="list-item__title">О нас</v-list-item-title>
              <v-list-item class="list-item" v-for="(item, index) in menuItemsInfoPage.slice(0, 4)"
                           :key="index"
                           @click="toPage({name: 'info-page', params: {id: item.slug}, })">
                <v-list-item-content>
                  - {{ item.title }}
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </div>
          <div class="footer__middle__block">
            <v-list class="v-list-title-name" style="padding-top: 16px" dense>
              <v-list-item-title class="list-item__title">Мы в сетях</v-list-item-title>
              <v-list-item v-for="(item, index) in menuItemsLinksPage" :key="index" class="list-item"
                           style="display: flex; align-items: center" :href="item.href" @click="fbMethod" target="_blank">
                <v-list-item-icon>
                  <v-icon style="margin: 0" size="20" color="#000">{{ item.icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  {{ item.title }}
                </v-list-item-content>
              </v-list-item>
                <img style="height:30px; margin-top: 10px" src="../../../public/visaMasterCard.png">
            </v-list>
          </div>
        </div>
      </div>
      <div class="footer__bottom">
        <div>
          Copyright © 2020. Все права защищены.
        </div>
        <div style="cursor: pointer;" @click="toPage({name: 'info-page', params: { id: 'polzovatelskoe-soglasenie'}})">
          Пользовательское соглашение
        </div>
      </div>
</v-footer>
</template>

<script>
export default {
  name: "FooterDesktop",
  data() {
    return {
      menuItemsBottomInfo: [],
      menuItemsCategory: [],
      menuItemsInfoPage: [],
      visibleInfoBottom: false,
      menuItemsLinksPage: [
        {
          href: 'https://www.facebook.com/biothal.ua/',
          icon: 'mdi-facebook',
          title: 'Facebook'
        },
        {
          href: 'https://www.youtube.com/channel/UCrfHUxmilxCSfhMG9TKLa1Q',
          icon: 'mdi-youtube',
          title: 'YouTube'
        },
        {
          href: 'https://www.instagram.com/biothal.ua/',
          icon: 'mdi-instagram',
          title: 'Instagram'
        }
      ],
      email_for_receive_list: '',
    }
  },
  created() {
    this.fetchFooterData()
  },
  methods: {
    async fetchFooterData() {
      let data = await this.axios.get('footer');

      this.menuItemsBottomInfo = data.data.article_bottom;
      this.menuItemsCategory = data.data.categories;
      this.menuItemsInfoPage = data.data.article;
      let visible = false;
      this.menuItemsBottomInfo.forEach( function (value, index) {
        if (value.children_article_bottom.length) {
          visible = true;
          return
        }
      })
        this.visibleInfoBottom = visible;
    },
    async addEmailToReceiveList() {
      let email = this.email_for_receive_list;
      let valide = /.+@.+/.test(email);
      this.$loading(true)

        console.log('Contact')
      this.$analytics.fbq.event( 'Contact')
      if (valide) {
        try {
          let data = await this.axios.post('addEmailForReceive', {
            email: email
          });
          if (data) {
            let message = data.data.message
            this.$notify({
              type: 'success',
              title: 'Успех!',
              text: message
            });
            this.email_for_receive_list = ''
          }
          this.$loading(false)
        } catch (e) {
          this.$notify({
            type: 'error',
            title: 'Извините',
            text: 'Вы ввели не коректный адресс електронной почты!'
          });
          this.$loading(false)
        }
      } else {
        this.$notify({
          type: 'error',
          title: 'Извините',
          text: 'Вы ввели не коректный адресс електронной почты!'
        });
        this.$loading(false)
      }
    },
    fbMethod() {
        console.log('Contact')
        this.$analytics.fbq.event( 'Contact')
    }
  }
}
</script>

<style scoped lang="scss">
@import 'src/styles/mixins';
@import 'src/styles/main';

.links-wrapper {
  display: flex;
  justify-content: space-between;
  width: 100%;
  margin-left: 100px;

  @media screen and (max-width: 991px) {
    flex-wrap: wrap;
  }

  @include _1200 {
    padding: 0 rem(45);

    &:last-child {
      margin: 0;
    }
  }
}

.footer {
  &__wrapper {
    display: flex;
    flex-direction: column;
    background-color: #fff;
    padding: 0;
  }

  &__top-title {
    font-style: normal;
    font-weight: 200;
    font-size: 18px;
    line-height: 25px;
  }

  &__top {
    width: 100%;
    background-color: $palette-base-color;
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    color: #fff;
    height: 80px;
    padding: 0 calc((100vw - #{$basic-styles-screen-width}) / 2 - 9px);

    &__email-icon {
      margin: 0;
      padding: 0;
    }
  }

  &__middle {
    display: flex;
    justify-content: space-between;
    width: 100%;
    padding: 60px calc((100vw - #{$basic-styles-screen-width}) / 2 - 9px) 0;

    @include _1200 {
      padding: 60px 0;
    }

    @media screen and (max-width: 991px) {
      flex-wrap: wrap;
    }

    &__block {

      &__1 {
        margin-right: 100px;
        max-width: 500px;

        @media screen and (max-width: 991px) {
          width: 100%;
          margin: 0 0 20px 0;
        }

        img {
          @media screen and (max-width: 991px) {
            margin-bottom: 20px;
          }
        }

      }
    }
  }

  &__bottom {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    font-style: normal;
    font-weight: 200;
    font-size: 13px;
    line-height: 18px;
    width: 100%;
    color: #9A9A9A;
    background-color: $palette-disable-color;
    padding: 25px calc((100vw - #{$basic-styles-screen-width}) / 2 - 9px) 20px;
  }
}

.list-item {
  font-style: normal;
  font-weight: normal;
  font-size: 15px;
  line-height: 20px;
  letter-spacing: -0.204545px;

  &__title {
    font-style: normal;
    font-weight: bold;
    font-size: 17px;
    line-height: 23px;
    letter-spacing: -0.231818px;
  }
}

.email {
  &__wrapper {
    width: 100%;
    color: #fff;
    padding: 0;
    margin: 0;
  }
}

.footer-middle-block {

  &-description {
    @include _1200 {
      width: 100%;
    }
  }

  &__image {
    @include _1200 {
      width: 100%;
      max-width: 100%;
    }
  }

  &__text {
    max-width: 300px;
    font-style: normal;
    font-size: 13px;
    line-height: 18px;
    letter-spacing: -0.204545px;

    @include _1200 {
      max-width: 450px;
      padding: 0 60px 45px;
      margin: 0 auto;
    }
  }
}
.v-list-title-name {
    padding: 16px 0;
}
</style>

<style lang="scss">
.list-item {
  padding: 0 !important;

  & .v-list-item__icon {
    margin-right: 10px !important;
  }
}

.email__wrapper {

  & .v-label {
    font-style: normal;
    font-weight: 200;
    font-size: 14px;
    line-height: 19px;
  }

  & .v-input__slot {
    margin: 0;
    padding: 0 10px 0 10px;
  }

  & input {
    padding: 0 0 3px 0;
  }
}

</style>

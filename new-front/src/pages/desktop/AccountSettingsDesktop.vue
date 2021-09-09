<template>
  <div class="account-settings__wrapper">
    <div class="account-settings__title">
      Мой профиль
    </div>
    <div class="account-settings__content__wrapper">
      <v-navigation-drawer class="account-settings__navigation" permanent>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title style="display: flex; justify-content: space-between">
              <div class="default-cursor" style="text-overflow: ellipsis; white-space: normal">
                {{profile.name + ' ' + profile.sur_name}}
              </div>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-divider/>
        <v-list>
          <v-list-item-group v-model="changeItem">
            <v-list-item
              v-for="(item, i) in items"
              :key="i">
              <v-list-item-content>
                <v-list-item-title style="text-align: left" v-text="item.text"/>
              </v-list-item-content>
            </v-list-item>
            <v-list-item @click="logout">
                <div class="point-cursor account-settings__logout">
                    <v-list-item-title style="text-align: left">Выйти</v-list-item-title>

                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.877 5.375L13.502 8L10.877 10.625" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.5 8H13.5" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.5 13.5H3C2.86739 13.5 2.74021 13.4473 2.64645 13.3536C2.55268 13.2598 2.5 13.1326 2.5 13V3C2.5 2.86739 2.55268 2.74021 2.64645 2.64645C2.74021 2.55268 2.86739 2.5 3 2.5H7.5" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-navigation-drawer>

      <div class="account-settings__profile">
          <AccountProfile v-if="changeItem === 0"/>
          <PasswordEditDesktop v-if="changeItem === 1"/>
          <OrderList v-if="changeItem === 2"/>
          <GroupDiscountParticipants v-if="changeItem === 3"/>
      </div>
    </div>
  </div>
</template>

<script>
import AccountProfile from "../../components/desktop/accountSettingsProfile/AccountProfile";
import BankCards from "../../components/desktop/accountSettingsProfile/BankCards";
import OrderList from "../../components/desktop/accountSettingsProfile/OrderList";
import GroupDiscountParticipants from "../../components/desktop/accountSettingsProfile/GroupDiscountParticipants";
import PasswordEditDesktop from "../../components/desktop/accountSettingsProfile/PasswordEditDesktop";

export default {
  name: "AccountSettingsDesktop",
  components: {
    AccountProfile,
    OrderList,
    GroupDiscountParticipants,
    PasswordEditDesktop
  },
  data() {
    return {
      rememberMe: false,

      items: [
        {
          text: 'Личные данные',
        },
        {
          text: 'Изменить пароль',
        },
        {
          text: 'Список заказов',
        },
        {
          text: 'Участники групповой скидки',
        },
      ],
      changeItem: 0,
      profile: {
        name: '',
        sur_name: '',
        date: '',
        email: '',
        phone_number: '',
        email_receive: {
          is_receive: 0
        },
        image_id: null,
        image: {
          name: ''
        }
      },
      orderList: {}
    }
  },
  created() {
    this.checkUserIsValid()
    this.isValid()
    this.getProfile()
  },
  methods: {
    async getProfile() {
      await this.checkUserIsValid()
      try {
        this.$loading(true)
        const token = this.$store.getters.getToken;
        if (token) {
          let data = await this.axios.post('profile', {}, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (data) {
            this.profile = data.data.user
            this.orderList = data.data.orderList

          }
        }
        this.$loading(false)
      } catch (e) {
        this.$loading(false)
        this.errorMessagesValidation(e);
      }
    },
    async isValid() {
      await this.checkUserIsValid()
      const token = this.$store.getters.getToken;
      if (!token) {
        this.toPage({name: 'AuthorizationMobile'})
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
              this.toPage({name: 'AuthorizationMobile'})
              return false;
            }
          } else {
            await this.$store.dispatch('LOGIN', null);
            this.toPage({name: 'AuthorizationMobile'})
          }
          return true;
        } else {
          return false;
        }
      } catch (e) {
        await this.$store.dispatch('LOGIN', null);
        this.toPage({name: 'AuthorizationMobile'})
        this.errorMessagesValidation(e);
      }
    }
  }
}
</script>

<style scoped lang="scss">
@import 'src/styles/mixins';

.account-settings {
  &__wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    text-align: center;
    padding: 20px 145px 213px 140px;
  }

  &__title {
    font-size: 18px;
    font-weight: 200;
    line-height: 25px;
    letter-spacing: 0;
    color: black;
  }

  &__logout {
    font-size: 12px;
    line-height: 16px;
    font-weight: 300;
    display: flex;
    column-gap: 8.5px;
    align-items: center;
    justify-content: center;
  }

  &__content {
    &__wrapper {
      display: flex;
      width: 100%;
      margin-top: 20px;
    }
  }

  &__profile {
    width: 100%;
    margin-left: 30px;
  }

  &__navigation {
    width: 255px !important;
    min-width: 255px;

    & .v-list {
      padding: 0;

      &-item {
        padding: 0 15px;
        height: 44px;

        &__content {
          padding: 0;
        }
      }
    }
  }
}
</style>


<style lang="scss">

.account-settings__wrapper {
  .v-navigation-drawer__content {
    background-color: #F7F7F7;
  }

  .v-list-item--active {
    background-color: #2F7484;

    & .v-list-item__content {
      color: #fff;
    }
  }

  .v-navigation-drawer__border {
    display: none;
  }
}
</style>

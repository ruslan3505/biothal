<template>
  <div class="account-profile__wrapper">
    <div class="page-form__wrapper">
      <div class="page-form__middle">
        <v-form ref="changeProfile" style="width: 100%;" v-model="validProfile">
          <div>
            <span class="input_label main-input-label">Ваш номер телефона</span>
            <v-text-field
              placeholder="+38(___) ___-__-__"
              v-mask="'+38(###) ###-##-##'"
              v-model="change_profile.phone_number"
              :error-messages="errorValid.phone_number"
              :rules="phoneRules"
              class="main-input-field"
              flat
              rounded/>
          </div>

          <div>
            <span class="input_label main-input-label">Ваше имя</span>
            <v-text-field
              class="main-input-field"
              v-model="change_profile.name"
              :error-messages="errorValid.name"
              :rules="nameRules"
              flat
              rounded/>
          </div>
          <div>
            <span class="input_label main-input-label">Ваша фамилию</span>
            <v-text-field
              class="main-input-field"
              v-model="change_profile.sur_name"
              :error-messages="errorValid.sur_name"
              flat
              rounded/>
          </div>
          <div>
            <span class="input_label main-input-label">Ваша дата рождения</span>
            <v-text-field
              class="main-input-field"
              placeholder="****-**-**"
              v-mask="'####-##-##'"
              prop="date"
              autocomplete="off"
              v-model="change_profile.date_of_birth"
              :error-messages="errorValid.date_of_birth"
              flat
              rounded/>
          </div>
          <div>
            <span class="input_label main-input-label">Ваш email</span>
            <v-text-field
              class="main-input-field"
              v-model="change_profile.email"
              :error-messages="errorValid.email"
              :rules="emailRules"
              flat
              rounded/>
          </div>
        </v-form>
      </div>
      <div class="remember-me">
        <div>
          <v-checkbox
            :color="variables.basecolor"
            v-model="change_profile.is_receive"
            label="Не хочу получать писем с акциями"/>
        </div>
      </div>
      <div class="page-form__bottom">
        <v-btn dark class="checkout-button" elevation="0" @click="changeProfile()">
          Сохранить данные
        </v-btn>
      </div>
    </div>
    <div class="user-image__wrapper">
      <div v-if="!$parent.profile.image_id"
           class="user-image__no-image" @click="changeImage()">
        <!--                <v-icon size="85" class="user-image__no-image__icon">-->
        <!--                    person_add-->
        <!--                </v-icon>-->

        <!--              Было взято с фигмы -->
        <svg width="114" height="114" viewBox="0 0 114 114" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M57 71.25C66.8376 71.25 74.8125 63.2751 74.8125 53.4375C74.8125 43.5999 66.8376 35.625 57 35.625C47.1624 35.625 39.1875 43.5999 39.1875 53.4375C39.1875 63.2751 47.1624 71.25 57 71.25Z"
            stroke="#D2D2D2" stroke-width="2" stroke-miterlimit="10"/>
          <path
            d="M28.4102 88.7838C31.0937 83.5077 35.1848 79.0772 40.2307 75.9826C45.2765 72.888 51.0803 71.25 56.9996 71.25C62.9188 71.25 68.7226 72.8879 73.7685 75.9825C78.8144 79.0771 82.9055 83.5076 85.5891 88.7836"
            stroke="#D2D2D2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M78.375 24.9375H99.75" stroke="#D2D2D2" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"/>
          <path d="M89.0625 14.25V35.625" stroke="#D2D2D2" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"/>
          <path
            d="M99.2217 50.257C100.637 59.1658 99.1947 68.2938 95.1022 76.3325C91.0098 84.3711 84.477 90.9075 76.4407 95.0045C68.4044 99.1014 59.2771 100.549 50.3676 99.1385C41.4581 97.7284 33.2239 93.5334 26.8455 87.155C20.4671 80.7766 16.2721 72.5423 14.862 63.6328C13.4519 54.7233 14.8991 45.5961 18.9961 37.5597C23.0931 29.5234 29.6295 22.9907 37.6681 18.8982C45.7068 14.8058 54.8348 13.3637 63.7435 14.7788"
            stroke="#D2D2D2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

      </div>
      <div v-if="$parent.profile.image_id"
           class="user-image__no-image" @click="changeImage()">
        <img class="user-image__no-image"
             width="100%"
             :src="$parent.profile.image ? this.api+'/storage/img/users/' + $parent.profile.image.name : ''"/>
      </div>
      <input type="file" ref="file" id="input_file" class="input_file" v-on:change="handleFileUpload()"/>
      <div class="user-image__text">
        <div v-if="$parent.profile.image_id" class="second-text delete_image" @click="deleteImage()">
          <v-icon size="10">
            close
          </v-icon>
          Удалить фото
        </div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  name: "AccountProfile",
  data() {
    return {
      errorValid: {
        old_password: '',
        password: '',
        password_confirmation: '',
        name: '',
        surname: '',
        date_of_birth: '',
        email: '',
        phone_number: ''
      },
      validProfile: false,
      rememberMe: false,
      dialog: false,
      valid: false,
      file: '',
      oldPassRules: [
        v => !!v || 'Вы не ввели старый пароль',
        v => v.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
      ],
      passRules: [
        v => !!v || 'Вы не ввели пароль',
        v => v.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
        v => v !== this.change_password.old_password || 'Новый пароль не должен содержать старый'
      ],
      passConfirmRules: [
        v => !!v || 'Вы не подтвердили пароль',
        v => v.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
        v => v === this.change_password.password || 'Пароли не совпадают'
      ],
      nameRules: [
        v => !!v || 'Вы не ввели свое имя',
        v => v.length >= 2 || 'Имя должно содержать больше чем 2 символа',
      ],
      emailRules: [
        v => !!v || 'Вы не ввели електронную почту',
        v => /.+@.+/.test(v) || 'Електронная почта не коректна',
      ],
      phoneRules: [
        v => !!v || 'Вы не ввели свое телефоный номер',
        v => v.length >= 18 || 'Телефон должен содержать больше чем 12 символа',
      ],
      change_password: {
        old_password: '',
        password: '',
        password_confirmation: ''
      },
      change_profile: {
        name: '',
        surname: '',
        date: '',
        email: '',
        phone_number: '',
        is_receive: ''
      }
    }
  },
  mounted() {
    this.fetchProfile()
  },
  watch: {
    '$parent.profile.name': function (val) {
      this.fetchProfile()
    }
  },
  methods: {
    async fetchProfile() {
      let profile = await this.$parent.profile
      this.change_profile = profile
      this.change_profile.is_receive = profile.email_receive.is_receive
    },
    async changePassword() {
      await this.$parent.checkUserIsValid();
      try {
        this.$loading(true)
        this.clearValidation()
        let validate = await this.$refs['change'].validate();

        if (validate) {
          const token = this.$store.getters.getToken;
          if (token) {
            let data = await this.axios.post('changePassword', this.change_password,
              {
                headers: {
                  'Authorization': `Bearer ${token}`
                }
              });
            if (data) {
              let message = data.data.message
              this.$notify({
                type: 'success',
                title: 'Успех!',
                text: message
              });
              this.closeModal()
            }
          }
        }
        this.$loading(false)
      } catch (e) {
        this.$loading(false)
        this.errorMessagesValidation(e);
      }
    },
    clearValidation() {
      this.errorValid.old_password = '',
        this.errorValid.password = '',
        this.errorValid.password_confirmation = '',
        this.errorValid.name = '',
        this.errorValid.surname = '',
        this.errorValid.date_of_birth = '',
        this.errorValid.email = '',
        this.errorValid.phone_number = ''
    },
    closeModal() {
      this.clearValidation()
      this.$refs.change.reset()
      this.dialog = false
    },
    async changeProfile() {
      await this.$parent.checkUserIsValid();
      try {
        this.$loading(true)
        this.clearValidation()
        let validate = await this.$refs['changeProfile'].validate();

        if (validate) {
          const token = this.$store.getters.getToken;
          if (token) {
            let data = await this.axios.post('updateProfile', this.change_profile,
              {
                headers: {
                  'Authorization': `Bearer ${token}`
                }
              });
            if (data) {
              let message = data.data.message
              this.$notify({
                type: 'success',
                title: 'Успех!',
                text: message
              });
              this.clearValidation()
            }
          }
        }
        this.$loading(false)
      } catch (e) {
        this.$loading(false)
        this.errorMessagesValidation(e);
      }
    },
    changeImage() {
      document.getElementById('input_file').click();
    },
    async handleFileUpload() {
      await this.$parent.checkUserIsValid();
      this.$loading(true)
      this.file = this.$refs.file.files[0];
      try {
        const token = this.$store.getters.getToken;
        if (token) {
          let formData = new FormData();
          formData.append('img', this.file);
          let data = await this.axios.post('addImage',
            formData,
            {
              headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${token}`
              }
            }
          )
          if (data) {
            let message = data.data.message
            this.$notify({
              type: 'success',
              title: 'Успех!',
              text: message
            });
            let profile = data.data.profile
            this.$parent.profile = profile
          }
        }
        this.$loading(false)
      } catch (e) {
        this.$loading(false)
        this.errorMessagesValidation(e);
      }
    },
    async deleteImage() {
      await this.$parent.checkUserIsValid();
      this.$loading(true)
      const token = this.$store.getters.getToken;
      try {
        if (token) {
          let data = await this.axios.post('deleteImage', {},
            {
              headers: {
                'Authorization': `Bearer ${token}`
              }
            });
          if (data) {
            let message = data.data.message
            this.$notify({
              type: 'success',
              title: 'Успех!',
              text: message
            });
            let profile = data.data.profile
            this.$parent.profile = profile
          }
        }
        this.$loading(false)
      } catch (e) {
        this.$loading(false)
        this.errorMessagesValidation(e);
      }
    }
  }
}
</script>

<style scoped lang="scss">
.page-form {

  &__wrapper {
    background-color: #fff;
    text-align: left;
    width: 100%;
    padding: 0;
  }

  &__top {
    display: flex;
    flex-direction: column;
    text-align: center;

    &__title {
      font-size: 14px;
      line-height: 19px;
      font-weight: 700;
    }
  }

  &__middle {
    width: 100%;
    display: flex;
    justify-content: center;

    & > form > div {
      margin-top: 20px;

      &:first-of-type {
        margin-top: 0;
      }
    }
  }

  &__bottom {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }
}

.main-input-label {
  font-weight: 200;
  font-size: 12px;
  line-height: 16px;
  color: #000;
  margin-top: 20px;
}

.main-input-field {
  width: 100%;
  height: 54px;
  background: #F7F7F7;
  border-radius: 2px;
}

.remember-me {
  display: flex;
  justify-content: flex-start;
  align-items: center;
  width: 100%;
  font-size: 14px;
  margin-top: 13px;

  // обнуление идиотских отступов у чекбокса
  & ::v-deep {
    &.v-input {
      &--checkbox {
        padding: 0 !important;
        margin: 0 !important;
      }

      &__slot {
        padding: 0 !important;
        margin: 0 !important;
      }

      &--selection-controls__input {
        margin: 0 !important;
      }

      & .v-label {
         margin-left: 10px;
      }
    }

    &.v-messages {
      height: 0 !important;
      min-height: 0 !important;
    }
  }
}

.account-profile__wrapper {
  display: flex;
  justify-content: space-between;
}

.user-image {
  &__wrapper {
    display: flex;
    flex-direction: column;
    row-gap: 10px;
    margin-left: 25px;
  }

  &__no-image {
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    background-color: #F7F7F7;
    width: 255px;
    height: 255px;

    &__icon {
      color: #D2D2D2;
    }
  }

  &__text {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    line-height: 16px;
    font-weight: 500;
    vertical-align: middle;
  }
}

.change_password_button {
  position: absolute;
  margin-top: 10px;
  background-color: #2F7484 !important;
}

.register_input {
}

.user-image__no-image:hover {
  background-color: lightgoldenrodyellow;
  cursor: pointer;
}

.input_file {
  display: none;
}

.delete_image:hover {
  color: red;
  cursor: pointer;
}
</style>

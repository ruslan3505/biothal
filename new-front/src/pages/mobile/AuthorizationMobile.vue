<template>
    <div class="page-form__wrapper">
        <div class="page-form__top">
            <div class="page-form__top__title">Авторизация</div>
        </div>
        <div class="page-form__middle">
            <v-form style="width: 100%;" ref="form" @keyup.enter.native="login">
                <div class="register_input">
                    <p class="main-input-label">Введите номер телефона</p>
                    <v-text-field
                        placeholder="+38(___) ___-__-__"
                        v-mask="'+38(###) ###-##-##'"
                        class="main-input-field"
                        :rules="phoneRules"
                        v-model="user.phone_number"
                        :error-messages="errorValid.phone_number"
                        flat
                        rounded/>
                </div>
                <div class="register_input">
                    <p class="main-input-label">Введите пароль</p>
                    <v-text-field
                        class="main-input-field"
                        :rules="passRules"
                        v-model="user.password"
                        type="password"
                        :error-messages="errorValid.password"
                        flat
                        rounded/>
                </div>
            </v-form>
        </div>
        <div class="remember-me">
            <div>
                <v-checkbox
                    :color="variables.basecolor"
                    v-model="user.rememberMe"
                    label="Запомнить меня"/>
            </div>
        </div>
        <div class="page-form__bottom">
            <v-btn dark class="checkout-button" elevation="0" @click="login">Войти</v-btn>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AuthorizationMobile",
        data() {
            return {
                errorValid: {
                    phone_number: '',
                    password: '',
                },
                user: {
                    phone_number: '',
                    password: '',
                    rememberMe: false
                },
                phoneRules: [
                    v => !!v || 'Вы не ввели свое телефоный номер',
                    v => v?.length >= 12 || 'Телефон должен содержать больше чем 12 символа',
                ],
                passRules: [
                    v => !!v || 'Вы не ввели пароль',
                    v => v?.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
                ],
            }
        },
        methods: {
            async login() {
                this.$loading(true)
                this.clearValidation()
                let validate = this.$refs.form.validate();

                if(validate){
                    let user = this.user
                    let data
                    try {
                        data = await this.axios.post('login' , user);
                    } catch (e) {
                        this.errorMessagesValidation(e);
                    }
                    if(data){
                        this.$refs.form.reset()
                        let login = data.data.access_token
                        try {
                            await this.$store.dispatch('LOGIN', login);
                            this.toPage({name: 'account-settings'})
                        } catch (e) {
                            console.log(e)
                        }
                        this.$notify({
                            type: 'success',
                            title: 'Добро Пожаловать!',
                            text: 'Вы успешно вошли в свою учетную запись'
                        });
                    }
                    this.$loading(false)
                } else {
                    this.$loading(false)
                }
            },
            clearValidation(){
                this.errorValid.phone_number = '',
                this.errorValid.password = ''
            }
        }
    }
</script>

<style scoped lang="scss">
    .page-form {
        &__wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: scroll;
            padding: 20px 20px 15px 20px;
        }

        &__top {
            display: flex;
            flex-direction: column;
            text-align: center;

            &__title {
                font-size: 14px;
                line-height: 19px;
                font-weight: 400;
            }
        }

        &__middle {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        &__bottom {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    }

    .main-input-label {
        font-weight: 200;
        font-size: 12px;
        line-height: 16px;
        color: #7E7E7E;
        margin: 15px 0 0 0;
    }

    .main-input-field {
        width: 100%;
        height: 54px;
        background: #fff;
        border-radius: 2px;
    }

    .remember-me {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        width: 100%;
        height: 2em;
        font-size: 14px;

        @media screen and (max-width: 1000px) {
            margin-bottom: 1em;
        }
    }
    .register_input{
        margin-bottom: 30px;
    }
</style>

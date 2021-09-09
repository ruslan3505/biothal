<template>
    <div class="page-form__wrapper">
        <div class="page-form__top">
            <div class="page-form__top__title">Регистрация</div>
        </div>
        <div class="page-form__middle">
            <v-form ref="form" style="width: 100%;" v-model="valid" @keyup.enter.native="saveUser" lazy-validation>
                <div class="register_input">
                    <p class="main-input-label">Введите имя</p>
                    <v-text-field
                        class="main-input-field"
                        placeholder="Владислав"
                        :rules="nameRules"
                        prop="name"
                        v-model="user.name"
                        :error-messages="errorValid.name"
                        required
                        flat
                        rounded/>
                </div>
                <div class="register_input">
                    <p class="main-input-label">Введите фамилию</p>
                    <v-text-field
                        class="main-input-field"
                        placeholder="Иванов"
                        prop="surname"
                        v-model="user.surname"
                        :error-messages="errorValid.surname"
                        flat
                        rounded/>
                </div>
                <div class="register_input">
                    <p class="main-input-label">Введите дату рождения</p>
                    <v-text-field
                        class="main-input-field"
                        placeholder="****-**-**"
                        v-mask="'####-##-##'"
                        prop="date"
                        v-model="user.date"
                        :error-messages="errorValid.date"
                        flat
                        rounded/>
                </div>
                <div class="register_input">
                    <p class="main-input-label">Введите номер телефона</p>
                    <v-text-field
                        placeholder="+38(___) ___-__-__"
                        v-mask="'+38(###) ###-##-##'"
                        v-model="user.phone_number"
                        :error-messages="errorValid.phone_number"
                        :rules="phoneRules"
                        prop="phone_number"
                        class="main-input-field"
                        flat
                        rounded/>
                </div>
                <div class="register_input">
                    <p class="main-input-label">Введите email</p>
                    <v-text-field
                        placeholder="biothal@biothal.com"
                        v-model="user.email"
                        :rules="emailRules"
                        prop="email"
                        class="main-input-field"
                        :error-messages="errorValid.email"
                        required
                        flat
                        rounded/>
                </div>
                <div class="register_input">
                    <p class="main-input-label">Введите пароль</p>
                    <v-text-field
                        placeholder="******"
                        type="password"
                        v-model="user.password"
                        :rules="passRules"
                        :error-messages="errorValid.password"
                        prop="password"
                        class="main-input-field"
                        required
                        flat
                        rounded/>
                </div>
                <div class="register_input">
                    <p class="main-input-label">Подтвердите пароль</p>
                    <v-text-field
                        placeholder="******"
                        type="password"
                        v-model="user.password_confirmation"
                        :error-messages="errorValid.password_confirmation"
                        :rules="passConfirmRules"
                        prop="password_confirmation"
                        class="main-input-field"
                        required
                        flat
                        rounded/>
                </div>
            </v-form>
        </div>
        <div class="remember-me">
            <div>
                <v-checkbox
                    v-model="user.is_receive"
                    label="Не хочу получать писем с акциями"/>
            </div>
        </div>
        <div class="page-form__bottom">
            <v-btn dark class="checkout-button" @click="saveUser" elevation="0">
                Зарегистрироваться
            </v-btn>
        </div>
    </div>
</template>

<script>

    export default {
        name: "RegistrationMobile",
        props:{
            token:{
                type: [Number, String],
                default: null,
            }
        },
        data() {
            return {
                errorValid: {
                    name: '',
                    surname: '',
                    date: '',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirmation: ''
                },
                valid: false,
                nameRules: [
                    v => !!v || 'Вы не ввели свое имя',
                    v => v?.length >= 2 || 'Имя должно содержать больше чем 2 символа',
                ],
                emailRules: [
                    v => !!v || 'Вы не ввели електронную почту',
                    v => /.+@.+/.test(v) || 'Електронная почта не коректна',
                ],
                phoneRules: [
                    v => !!v || 'Вы не ввели свое телефоный номер',
                    v => v?.length >= 18 || 'Телефон должен содержать больше чем 12 символа',
                ],
                passRules: [
                    v => !!v || 'Вы не ввели пароль',
                    v => v?.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
                ],
                passConfirmRules: [
                    v => !!v || 'Вы не подтвердили пароль',
                    v => v?.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
                    v => v === this.user.password || 'Пароли не совпадают'
                ],
                user: {
                    name: '',
                    surname: '',
                    date: '',
                    email: '',
                    phone_number: '',
                    is_receive: false,
                    password: '',
                    password_confirmation: ''
                }
            }
        },
        computed:{

        },
        created() {
            this.checkUser();
        },
        methods:{
            async saveUser(){
                this.$loading(true)
                this.clearValidation()
                let validate = this.$refs.form.validate();

                if(validate){
                    let user = this.user
                    let data
                    user.token = this.token
                    try {
                        data = await this.axios.post('register' , user);
                    } catch (e) {
                        this.errorMessagesValidation(e);
                    }
                    this.$loading(false)
                    if(data){
                        let message = data.data.message;
                        this.$notify({
                            type: 'success',
                            title: message,
                            text: 'Вы можете войти в свою учетную запись'
                        });
                        let phone = this.user.phone_number
                        this.$refs.form.reset()
                        this.toPage({name: 'Verified' , params: { phone: phone}})
                    }
                } else {
                    this.$loading(false)
                }
            },
            clearValidation(){
                this.errorValid.name = '',
                this.errorValid.surname =  '',
                this.errorValid.date = '',
                this.errorValid.email = '',
                this.errorValid.phone_number = '',
                this.errorValid.password = '',
                this.errorValid.password_confirmation = ''
            },
            async checkUser()
            {
                const tokenForAuth = this.$store.getters.getToken;
                if(tokenForAuth){
                    this.toPage({name: 'home'})
                }
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
        font-size: 14px;
        height: 2em;

        @media screen and (max-width: 1000px) {
            margin-bottom: 1em;
        }
    }
    .page-form__wrapper {

        & .v-input__slot {
        }

        & .v-text-field__details {
            display: block !important;
        }
    }
    .register_input{
        margin-bottom: 30px;
    }
</style>

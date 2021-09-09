<template>
    <div class="page-form__wrapper">
        <div class="page-form__top">
            <div class="page-form__top__title">Подтверждение учетной записи</div>
        </div>
        <div class="page-form__middle">
            <v-form ref="form" style="width: 100%;" v-model="valid" @keyup.enter.native="saveUser" lazy-validation>
                <div class="register_input">
                    <p class="main-input-label">Введите код подтвержения полученый в смс</p>
                    <v-text-field
                        class="main-input-field"
                        :rules="codeRules"
                        prop="name"
                        v-model="code"
                        :error-messages="errorValid.code"
                        required
                        flat
                        rounded/>
                </div>
            </v-form>
        </div>
        <div class="re-request" @click="getNewVerifyCode">Отправить код повторно</div>
        <div class="page-form__bottom">
            <v-btn dark class="checkout-button" @click="saveUser" elevation="0">
                Подтвердить
            </v-btn>
        </div>
    </div>
</template>

<script>

    export default {
        name: "Verified",
        props: {
            phone: {
                type: [Number, String],
                required: true
            },
        },
        data() {
            return {
                errorValid: {
                    code: '',
                },
                valid: false,
                codeRules: [
                    v => !!v || 'Вы не ввели код с смс',
                ],
                code: ''
            }
        },
        computed:{

        },
        methods:{
            async saveUser(){
                this.$loading(true)
                this.clearValidation()
                let validate = this.$refs.form.validate();

                if(validate){
                    let user = {
                        code: this.code,
                        phone_number: this.phone
                    }
                    let data
                    try {
                        data = await this.axios.post('verifyUser' , user);
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
                        this.$refs.form.reset()
                        this.toPage({name: 'authorization' })
                    }
                } else {
                    this.$loading(false)
                }
            },
            clearValidation(){
                this.errorValid.code = ''
            },
            async getNewVerifyCode() {

                this.$loading(true)
                let user = {
                    code: this.code,
                    phone_number: this.phone
                }
                try {
                    await this.axios.post('newVerifyCode' , user).then(({data}) => {

                        if (data) {
                            this.$loading(false)
                            this.$notify({
                                type: 'success',
                                title: 'Код отправлен повторно'
                            });
                        }
                    });
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
        justify-content: space-between;
        align-items: center;
        width: 100%;
        font-size: 14px;
        margin-top: 15px;

        &__right {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
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

    .re-request {
        margin-bottom: 20px;
        color: #1b4b72;
        cursor: pointer;
    }
</style>

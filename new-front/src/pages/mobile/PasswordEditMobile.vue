<template>
    <div class="page-form__wrapper">
        <div class="page-form__top">
            <div class="page-form__top__title">Изменить пароль</div>
        </div>
        <div class="page-form__middle">

            <v-form ref="changePassword" style="width: 100%;" v-model="valid">
                <div style="margin-top: 25px;">
                    <p class="main-input-label">Введите старый пароль</p>
                    <v-text-field
                        class="main-input-field"
                        v-model="change_password.old_password"
                        :error-messages="errorValid.old_password"
                        :rules="oldPassRules"
                        type="password"
                        flat
                        rounded/>
                </div>
                <div style="margin-top: 25px;">
                    <p class="main-input-label">Введите новый пароль</p>
                    <v-text-field
                        class="main-input-field"
                        v-model="change_password.password"
                        :error-messages="errorValid.password"
                        :rules="passRules"
                        type="password"
                        flat
                        rounded/>
                </div>
                <div style="margin-top: 25px;">
                    <p class="main-input-label">Введите еще раз новый пароль</p>
                    <v-text-field
                        class="main-input-field"
                        v-model="change_password.password_confirmation"
                        :error-messages="errorValid.password_confirmation"
                        :rules="passConfirmRules"
                        type="password"
                        flat
                        rounded/>
                </div>
            </v-form>
        </div>
        <div class="page-form__bottom">
            <v-btn dark class="checkout-button" elevation="0" @click="changePassword()">
                Сохранить данные
            </v-btn>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PasswordEditMobile",
        data() {
            return {
                valid: false,
                errorValid: {
                    old_password: '',
                    password: '',
                    password_confirmation: ''
                },
                change_password:{
                    old_password: '',
                    password: '',
                    password_confirmation: ''
                },
                oldPassRules: [
                    v => !!v || 'Вы не ввели старый пароль',
                    v => v?.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
                ],
                passRules: [
                    v => !!v || 'Вы не ввели пароль',
                    v => v?.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
                    v => v !== this.change_password.old_password || 'Новый пароль не должен содержать старый'
                ],
                passConfirmRules: [
                    v => !!v || 'Вы не подтвердили пароль',
                    v => v?.length >= 6 || 'Пароль должен содержать больше чем 6 символов',
                    v => v === this.change_password.password || 'Пароли не совпадают'
                ],
            }
        },
        methods: {
            clearValidation(){
                this.errorValid.old_password = '',
                this.errorValid.password = '',
                this.errorValid.password_confirmation = ''
            },
            async changePassword(){
                await this.checkUserIsValid();
                try {
                    this.$loading(true)
                    this.clearValidation()
                    let validate = await this.$refs['changePassword'].validate();

                    if(validate){
                        const token = this.$store.getters.getToken;
                        if(token){
                            let data = await this.axios.post('changePassword', this.change_password,
                                {
                                    headers: {
                                        'Authorization': `Bearer ${token}`
                                    }
                                });
                            if(data){
                                let message = data.data.message
                                this.$notify({
                                    type: 'success',
                                    title: 'Успех!',
                                    text: message
                                });
                                this.$refs.changePassword.reset()
                            }
                        }
                    }
                    this.$loading(false)
                } catch (e) {
                    this.$loading(false)
                    this.errorMessagesValidation(e);
                }
            },
            async checkUserIsValid(){
                try {
                    const token = this.$store.getters.getToken;
                    if(token){
                        let data = await this.axios.post('checkUser', {

                        },  {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        });
                        if(data){
                            let exist = data.data.exist
                            if(!exist){
                                await this.$store.dispatch('LOGIN', null);
                                return false;
                            }
                        } else {
                            await this.$store.dispatch('LOGIN', null);
                        }
                        return true;
                    } else {
                        return false;
                    }
                } catch (e) {
                    await this.$store.dispatch('LOGIN', null);
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
                font-weight: 700;
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
            margin-top: 30px;
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
</style>

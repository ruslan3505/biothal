<template>
    <div class="account-profile__wrapper">
        <div class="page-form__wrapper">
            <div class="page-form__middle">
                <v-form ref="changePassword" style="width: 100%;">
                    <div class="register_input">
                        <span class="input_label main-input-label">Введите старый пароль</span>
                        <v-text-field
                            class="main-input-field"
                            v-model="change_password.old_password"
                            :error-messages="errorValid.old_password"
                            :rules="oldPassRules"
                            type="password"
                            flat
                            rounded/>
                    </div>
                    <div class="register_input">
                        <span class="input_label main-input-label">Введите новый пароль</span>
                        <v-text-field
                            class="main-input-field"
                            v-model="change_password.password"
                            :error-messages="errorValid.password"
                            :rules="passRules"
                            type="password"
                            flat
                            rounded/>
                    </div>
                    <div class="register_input">
                        <span class="input_label main-input-label">Введите еще раз новый пароль</span>
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
                    Изменить пароль
                </v-btn>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PasswordEditDesktop",
        data() {
            return {
                errorValid: {
                    old_password: '',
                    password: '',
                    password_confirmation: ''
                },
                validProfile: false,
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
                change_password: {
                    old_password: '',
                    password: '',
                    password_confirmation: ''
                },
            }
        },
        methods: {
            async changePassword() {
                await this.$parent.checkUserIsValid();
                try {
                    this.$loading(true)
                    this.clearValidation()
                    let validate = await this.$refs['changePassword'].validate();

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
            clearValidation() {
                this.errorValid.old_password = '',
                this.errorValid.password = '',
                this.errorValid.password_confirmation = ''
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

    .account-profile__wrapper {
        display: flex;
        justify-content: space-between;
    }
</style>

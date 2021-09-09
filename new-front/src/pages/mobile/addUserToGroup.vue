<template>
    <v-dialog
        v-model="visible"
        width="420">

        <v-card class="order-dialog__wrapper">
            <div class="order-dialog__title">Пригласите друга в группу</div>

            <div class="order-dialog__text">Нажмите пригласить и мы отправим ему письмо с приглашением</div>

            <v-form class="order-dialog__form" ref="addUserToGroup">
                <div>
                    <p class="main-input-label">Введите email</p>
                    <v-text-field
                        class="main-input-field"
                        v-model="email"
                        :error-messages="errorValid.email"
                        :rules="emailRules"
                        background-color="#F7F7F7"
                        flat
                        rounded/>
                </div>
            </v-form>
            <v-btn dark class="checkout-button" elevation="0" @click="invite">Пригласить</v-btn>
        </v-card>
    </v-dialog>
</template>

<script>
    import {mapActions, mapGetters} from "vuex";

    export default {
        name: "addUserToGroup",
        data() {
            return {
                visible: false,
                errorValid: {
                    email: ''
                },
                emailRules: [
                    v => !!v || 'Вы не ввели електронную почту',
                    v => /.+@.+/.test(v) || 'Електронная почта не коректна',
                ],
                email: ''
            }
        },
        methods: {
            async invite() {
                this.$loading(true);
                try {
                    this.clearValidation()
                    let validate = await this.$refs['addUserToGroup'].validate();

                    if (validate) {
                        const token = this.$store.getters.getToken;
                        if(token) {
                            const form = {
                                email: this.email
                            };
                            let data = await this.axios.post('sendInvite', form,
                                {
                                    headers: {
                                        'Authorization': `Bearer ${token}`
                                    }
                                })

                            if (data) {
                                let message = data.data.message

                                this.$notify({
                                    type: 'success',
                                    title: 'Успех!',
                                    text: message
                                });
                                this.clearValidation();
                                this.visible = false;
                            }
                        }
                    }
                    this.$loading(false);
                } catch (e) {
                    this.$loading(false);
                    this.errorMessagesValidation(e);
                }
            },
            clearValidation() {
                this.errorValid = {
                    email: ''
                }
            },
        }
    }
</script>

<style scoped lang="scss">

    .main-input-label {
        padding: 0 0 0 15px;
        margin: 0;
        font-weight: 200;
        font-size: 12px;
        line-height: 16px;
    }

    .main-input-field {
        margin: 0;
        padding: 5px;
    }

    .order-dialog {
        &__wrapper {
            text-align: center;
        }

        &__form {
            text-align: left;
        }

        &__title {
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            margin-bottom: 5px;
        }

        &__text {
            font-size: 13px;
            margin-bottom: 5px;
        }
    }

    .checkout-button {
      font-size: 11px;
      line-height: 15px;
      margin-top: 15px;
      height: 34px !important;
      width: max-content;
      padding: 0 46px !important;
    }
</style>

<style lang="scss">
    .order-dialog__wrapper {
        padding: 15px;

        & .v-input__slot {
            border-radius: 4px !important;
            margin: 0 0 5px 0 !important;
        }

        & .v-text-field__details {
            display: block;
        }
    }
</style>

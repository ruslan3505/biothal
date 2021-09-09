<template>
    <div>
        <agile class="agile-slider" :autoplay="carousel.length!==1" :infinite="carousel.length!==1" :autoplaySpeed="5000" :navButtons="false" :speed="1000" :key="carousel.length">
            <div class="slide" v-for="(item, index) in carousel" :key="index">
                <img width="100%" :src="api + '/storage/img/carousel/' + item['name']"/>
            </div>
        </agile>
        <div>
            <div class="info-page__title"><b>МИССИЯ BIOTHAL</b></div>
            <div class="info-page__content__wrapper" v-html="text"></div>

            <div class="info-page__title"><b>ПОЛУЧИТЕ ЭКСКЛЮЗИВНОЕ ПРЕДЛОЖЕНИЕ</b></div>
            <div class="info-page__content__wrapper">
                <div class="page-form__middle">
                    <v-form ref="createOffer" autocomplete="off">
                        <div>
                            <span class="input_label main-input-label">Ваше имя</span>
                            <v-text-field
                                class="main-input-field"
                                v-model="offer.name"
                                :error-messages="errorValid.name"
                                :rules="nameRules"
                                flat
                                rounded/>
                        </div>
                        <div>
                            <span class="input_label main-input-label">Ваш номер телефона</span>
                            <v-text-field
                                placeholder="+38(___) ___-__-__"
                                v-mask="'+38(###) ###-##-##'"
                                v-model="offer.phone"
                                :error-messages="errorValid.phone"
                                :rules="phoneRules"
                                class="main-input-field"
                                flat
                                rounded/>
                        </div>
                        <div>
                            <span class="input_label main-input-label">Ваш email</span>
                            <v-text-field
                                class="main-input-field"
                                v-model="offer.email"
                                :error-messages="errorValid.email"
                                :rules="emailRules"
                                flat
                                rounded/>
                        </div>
                        <div>
                            <span class="input_label main-input-label">Сообщение</span>
                            <v-textarea
                                filled
                                name="input-7-4"
                                v-model="offer.text"
                                :error-messages="errorValid.text"/>
                        </div>
                    </v-form>
                </div>
                <div class="page-form__bottom">
                    <button class="checkout-button white--text" @click="send">
                        Получить
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DistributorPage",
        data() {
            return {
                carousel: [],
                text: '<p style="outline: 0px; margin-bottom: 10px; font-size: 16px; color: rgb(41, 37, 63); font-family: standard; ' +
                    'line-height: 20px !important;">УХОД ЗА КОЖЕЙ И ТЕЛОМ - главная миссия компании BIOTHAL.</p><p style="outline: 0px; ' +
                    'margin-bottom: 10px; font-size: 16px; color: rgb(41, 37, 63); font-family: standard; line-height: 20px !important;">' +
                    'Результат исследований нашей команды учёных и специалистов по уходу за кожей - новые эффективные формулы, сочетающие в себе' +
                    ' ДОСТИЖЕНИЯ науки и НАТУРАЛЬНОСТЬ природных компонентов.</p><p style="outline: 0px; margin-bottom: 10px; font-size: 16px; ' +
                    'color: rgb(41, 37, 63); font-family: standard; line-height: 20px !important;">Используя природные богатства, щедро представл' +
                    'енные в морских водах и на суше, внимательное отношение к мелочам, революционные составы наших продуктов и впечатляющая замет' +
                    'ная ЭФФЕКТИВНОСТЬ - всё ставит линию косметики BIOTHAL на впечатляющий уровень по сравнению с другими брендами.</p>',
                errorValid: {
                    name: '',
                    email: '',
                    phone: '',
                    text: ''
                },
                nameRules: [
                    v => !!v || 'Вы не ввели свое имя',
                    v => v?.length >= 2 || 'Имя должно содержать больше чем 2 символа',
                ],
                emailRules: [
                    v => !!v || 'Вы не ввели електронную почту',
                    v => /.+@.+/?.test(v) || 'Електронная почта не коректна',
                ],
                phoneRules: [
                    v => !!v || 'Вы не ввели свое телефоный номер',
                    v => v?.length >= 18 || 'Телефон должен содержать больше чем 12 символа',
                ],
                offer: {
                    name: '',
                    phone: '',
                    email: '',
                    text: ''
                }
            }
        },
        created() {
            this.fetchCarouselImage();
        },
        methods: {
            async fetchCarouselImage() {
                let data = await this.axios.get('image');

                this.carousel = this.isMobile ? data.data.carouselMobile : data.data.carouselDesktop;
            },
            async send() {
                this.$loading(true);
                console.log('Lead')
                this.$analytics.fbq.event( 'Lead')
                try {
                    this.clearValidation()
                    let validate = await this.$refs['createOffer'].validate();

                    if (validate) {
                        let data

                        try {
                            data = await this.axios.post('distributionOffer', this.offer);
                        } catch (e) {
                            this.$loading(false);
                            this.errorMessagesValidation(e);
                        }
                        if (data) {
                            this.$refs.createOffer.reset()
                            let message = data.data.message
                            this.$notify({
                                type: 'success',
                                title: 'Успех!',
                                text: message
                            });
                            this.clearValidation();

                            this.$loading(false);
                        }
                    } else {
                        this.$loading(false);
                    }
                } catch (e) {
                    this.errorMessagesValidation(e);
                    this.$loading(false);
                }
            },
            clearValidation() {
                this.errorValid.name = '',
                this.errorValid.email = '',
                this.errorValid.phone = '',
                this.errorValid.text = ''
            },
        }
    }
</script>

<style scoped lang="scss">

    .info-page {
        &__title {
            text-align: center;
            text-transform: uppercase;
            font-size: 34px;
            margin: 50px;

            @media screen and (max-width: 600px) {
                margin: 20px;
            }
        }

        &__content {
            &__wrapper {
                max-width: 100%;
                padding: 0 45px 45px 45px;

                @media screen and (max-width: 600px) {
                    padding: 0 20px 20px 20px;
                }
            }
        }

        .slider-wrapper {
            width: 100%;
        }
    }

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

            & > form {
                width: 50em;
            }

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
            margin-bottom: 28px;
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

    .agile-slider {
        padding: 0 !important;
    }
</style>

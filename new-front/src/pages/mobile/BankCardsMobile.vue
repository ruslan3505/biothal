<template>
    <div class="bank-cards__wrapper">
        <div class="default-text__title">
            Банковские карты
        </div>
        <div v-if="isOrders" class="default-text__wrapper">
            <div>
                Карты, на которые вы можете получить возврат
                средств и оплачивать заказы
            </div>
            <div>
                <v-btn dark class="checkout-button" elevation="0">
                    Добавить карту
                </v-btn>
            </div>
            <div class="second-text">
                Добавляя карту, я соглашаюсь с правилами и условиями
                зачисления денег за возвращенный товар на мою карту
            </div>
        </div>
        <div v-else class="default-text__wrapper">
            <div>
                Информация о платежной карте
            </div>
            <div class="page-form__wrapper">
                <v-form style="width: 100%;">
                    <div>
                        <p class="main-input-label">Номер карты</p>
                        <v-text-field
                            class="main-input-field"
                            flat
                            rounded/>
                    </div>
                    <div style="display: flex; column-gap: 15px">
                        <div>
                            <p class="main-input-label">Срок действия</p>
                            <v-text-field
                                class="main-input-field"
                                flat
                                rounded/>
                        </div>
                        <div>
                            <p class="main-input-label">CVV</p>
                            <v-text-field
                                class="main-input-field"
                                flat
                                rounded/>
                        </div>
                    </div>
                </v-form>
            </div>
            <div>
                <v-btn dark class="checkout-button" elevation="0">
                    Сохранить карту
                </v-btn>
            </div>
            <div class="second-text">
                Интернет платежи защищены сертификатом SSL
                и протоколом 3D Secure. Мы не передаем
                Ваши платёжные данные, в том числе данные
                карты третьим лицам.
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "BankCardsMobile",
      data() {
        return {
            isOrders: false
        }
      }
  }
</script>

<style scoped lang="scss">
    .bank-cards {
        &__wrapper {
            height: 100%;
            background-color: #f7f7f7;
            text-align: center;
            padding: 20px;
            display: flex;
            flex-direction: column;
            row-gap: 20px;
        }
    }

    .page-form {
        &__wrapper {
            text-align: left;
        }
    }
</style>

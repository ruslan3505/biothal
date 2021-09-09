<template>
    <div class="group-discount-participants__wrapper">
        <div class="group-discount-participants__title">Участники групповой скидки</div>
        <div v-for="(item, index) in participants" :key="index">
            <v-simple-table>
                <template v-slot:default>
                    <tbody>
                    <tr>
                        <td class="text-left">Порядковый номер</td>
                        <td class="text-right">{{item.id}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Имя и Фамилия участника</td>
                        <td class="text-right">{{item.name}}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Сумма накопления для группы</td>
                        <td class="text-right">{{item.sum}} грн</td>
                    </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <tbody>
                        <tr>
                            <td class="text-left">Общая сумма</td>
                            <td class="text-right">{{ total_sum }} грн</td>
                        </tr>
                        <tr>
                            <td class="text-left">Процент групповой скидки</td>
                            <td class="text-right">{{ percent }} %</td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
        </div>
        <div>
            <v-btn dark class="checkout-button" @click="$refs['AddUserToGroup'].visible=true" elevation="0">
                Добавить друга
            </v-btn>
            <AddUserToGroup ref="AddUserToGroup"/>
        </div>
    </div>
</template>

<script>
    import AddUserToGroup from "./addUserToGroup";
    export default {
        name: "GroupDiscountParticipantsMobile",
        components: {AddUserToGroup},
        data() {
            return {
                total_sum: 0,
                percent: 0,
                participants: [
                    id => '',
                    name => '',
                    sum => 0
                ]
            }
        },
        created() {
            this.getProfile()
        },
        methods:{
            async getProfile(){
                await this.checkUserIsValid()
                try {
                    const token = this.$store.getters.getToken;
                    if(token){
                        let data = await this.axios.get('getGroupSales', {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        });
                        if(data){
                            let resp = data.data;
                            this.participants = resp.group
                            this.total_sum = resp.total_sum
                            this.percent = resp.percent
                        }
                    }
                } catch (e) {
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
    .group-discount-participants {
        &__wrapper {
            display: flex;
            flex-direction: column;
            background-color: #f7f7f7;
            height: 100%;
            text-align: center;
            row-gap: 20px;
            padding: 20px;
        }

        &__title {
            font-weight: 700;
        }
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }
</style>

<template>
    <div class="group-discount-participants__wrapper">
        <v-simple-table style="width: 100%">
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">
                        Порядковый номер
                    </th>
                    <th class="text-left">
                        Имя и Фамилия участника
                    </th>
                    <th class="text-left">
                        Сумма накопления для группы
                    </th>
                </tr>
                </thead>
                <tbody v-if="!defaultTable">
                <tr  class="text-left"
                    v-for="(item, index) in participants" :key="index">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.sum }} грн</td>
                </tr>
                <tr class="text-left">
                    <td>Общая сумма</td>
                    <td></td>
                    <td>{{ total_sum }} грн</td>
                </tr>
                <tr class="text-left">
                    <td>Процент групповой скидки</td>
                    <td></td>
                    <td>{{ percent }} %</td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
        <v-btn dark class="checkout-button" @click="$refs['AddUserToGroup'].visible=true" elevation="0">
            Добавить друга
        </v-btn>
        <AddUserToGroup ref="AddUserToGroup"/>
    </div>
</template>

<script>
    import AddUserToGroup from "./addUserToGroup";
    export default {
        name: "GroupDiscountParticipants",
        components: {AddUserToGroup},
        data() {
            return {
                total_sum: 0,
                percent: 0,
                defaultTable: true,
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
                            this.defaultTable = false
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
            background-color: #fff;
            justify-content: flex-end;
            align-items: flex-end;
            text-align: center;
            row-gap: 20px;
            padding: 0 20px 20px 20px;
        }
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .checkout-button {
        max-width: 190px;
        width: 100%;
    }

    .v-data-table__wrapper {

        & th {
            color: #000 !important;
        }
    }
</style>

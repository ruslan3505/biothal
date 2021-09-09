<template>
    <div class="category-card" :class="face ? 'category-card__face' : 'category-card__body'" @click="goToPages()">
        <div class="category-card__title">
            {{ face ? 'Косметика по уходу за лицом' : 'Косметика по уходу за телом' }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "CategoryCard",
        data() {
            return {
                slug: ''
            }
        },
        props:{
            face:{
                type: Boolean
            }
        },
        created() {
            this.fetchMainCategories()
        },
        methods:{
            goToPages()
            {
                if(this.face){
                    this.toPage({name: 'category', params: {category: this.slug }})
                } else {
                    this.toPage({name: 'category', params: {category: this.slug }})
                }
            },
            async fetchMainCategories()
            {
                try{
                    let data = await this.axios.get('getMainCategories');
                    if(data){
                        for (const [key, value] of Object.entries(data.data.categories)) {
                            if(this.face){
                                if(value.id === 33){
                                    this.slug = value.slug
                                }
                            } else {
                                if(value.id === 61){
                                    this.slug = value.slug
                                }
                            }
                        }
                    }
                } catch (e) {
                    console.log(e);
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .category-card {
        width: 100%;
        height: 250px !important;
        border-radius: 0px 0px 4px 4px;
        column-gap: 30px;
        position: relative;
        display: flex;
        flex-direction: row;

        @media screen and (max-width: 600px) {
            height: 160px !important;
        }

        &__face{
            background: url('../../public/face.svg');
            background-size: cover;
        }

        &__body{
            background: url('../../public/telo.svg');
            background-size: cover;
        }
        &:hover {
            cursor: pointer;

            .category-card__title {
                height: 100%;
                transition: .3s;
                border-radius: 0px 0px 4px 4px;
            }
        }

        &__title {
            transition: .3s;
            background-color: #2F7484;
            opacity: 0.5;
            color: #fff;
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 58px;
            font-weight: 200;
            font-size: 19px;
            padding: 20px;
            border-radius: 0px 0px 4px 4px;

            /*Не влезал шрифт в блок*/
            @media screen and (min-width : 601px) and (max-width: 768px) {
                height: 90px;
            }
        }
    }
</style>

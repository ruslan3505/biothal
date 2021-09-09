<template>
    <div class="three-dots__wrapper">
        <v-icon v-for="(item, index) in icons" :key="index" size="10" class="three-dots__icon" :color="item.active ? '#000' : '#E7E7E7'" @click="setActive(item)">
            mdi-circle
        </v-icon>
    </div>
</template>

<script>
    export default {
        name: "ThreeDotsSlides",
        data() {
            return {
                icons: [
                    {
                        id: 1,
                        active: true
                    },
                    {
                        id: 2,
                        active: false
                    },
                    {
                        id: 3,
                        active: false
                    }
                ]
            }
        },
        methods: {
            setActive(item) {
                this.icons.forEach(icon => {
                    if (item.id === icon.id) {
                        icon.active = true
                    } else {
                        icon.active = false
                    }
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .three-dots {
        &__wrapper {
            display: flex;
            column-gap: 4px;
        }

        &__icon {
            height: 10px;
        }
    }
</style>

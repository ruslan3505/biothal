<template>
    <v-rating
        :color="color"
        :background-color="color"
        hover
        length="5"
        :value="3">
        <template v-slot:item="props">
            <v-icon
                class="icon-star"
                :color="color"
                :size="size"
                @click="props.click">
                {{ props.isFilled ? 'mdi-star' : 'mdi-star-outline' }}
            </v-icon>
        </template>
    </v-rating>
</template>

<script>
    export default {
        name: "Rating",
        props: {
            color: {
                type: String,
                default: '#000'
            },
            size: {
                type: Number,
                default: 13
            }
        }
    }
</script>

<style scoped lang="scss">
    .icon-star {
        padding: 1.5px !important;
    }
</style>

<script lang="ts">
import {defineComponent} from 'vue'
import TheButton from "~/components/ui/TheButton.vue";

export default defineComponent({
    name: "TextShortening",
    components: {TheButton},
    props: {
        text: {
            type: String,
            required: true
        },
        maxTextLength: {
            type: Number,
            default: 200
        },
        withBtnMore: {
            type: Boolean,
            default: false
        }
    },
    data: () => ({
        showMore: false,
        makeTextShorter: false
    }),
    computed: {
        textShortening() {
            if(this.text.length > this.maxTextLength && !this.showMore){
                this.makeTextShorter = true;
                return `${this.text.slice(0,this.maxTextLength)}...`
            }
            this.makeTextShorter = false;
            return this.text;
        }
    }
})
</script>

<template>
    <span>
        {{textShortening}}
        <template v-if="withBtnMore && makeTextShorter">
            <a v-if="!showMore" href="#" @click="showMore = true" class="btn-link">more</a>
            <a v-else href="#" @click="showMore = false" class="btn-link">hide</a>
        </template>
    </span>
</template>

<style scoped lang="scss">

</style>

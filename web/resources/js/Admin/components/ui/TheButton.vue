<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
    name: "TheButton",
    props: {
        targetModal: {
            type: String,
            required: false
        }
    }
})
</script>

<template>
    <template v-if="targetModal">
        <button
            id="kt_modal_new_target_cancel"
            data-bs-toggle="modal"
            :data-bs-target="targetModal"
            class="btn"
        >
            <slot></slot>
        </button>
    </template>
    <template v-else>
        <button
            id="kt_modal_new_target_cancel"
            class="btn"
        >
            <slot></slot>
        </button>
    </template>
</template>

<style scoped lang="scss">

</style>

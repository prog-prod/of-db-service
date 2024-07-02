<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
    name: "TheDropdownMenuItem"
})
</script>

<template>
    <div class="menu-item px-3">
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">

</style>

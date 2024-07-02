<script lang="ts">
import {defineComponent} from 'vue'
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "TheDropdownButton",
    components: {Link, KTIcon},
    props: {
        btnName: {
            type:String
        }
    }
})
</script>

<template>
    <a
        href="#"
        class="btn btn-sm btn-light btn-active-light-primary d-flex flex-center"
        data-kt-menu-trigger="click"
        data-kt-menu-placement="bottom-end"
        data-kt-menu-flip="top-end"
    >
        <slot>{{btnName}}</slot>
        <KTIcon icon-name="down" icon-class="fs-5 m-0" />
    </a>
    <!--begin::Menu-->
    <div
        class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
        data-kt-menu="true"
    >
        <!--begin::Menu item-->
        <slot name="menu-items"></slot>
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</template>

<style scoped lang="scss">

</style>

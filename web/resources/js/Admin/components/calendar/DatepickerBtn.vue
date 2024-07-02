<script lang="ts">
import {defineComponent} from 'vue'
import {DatePicker} from 'v-calendar';
import 'v-calendar/style.css';
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";
export default defineComponent({
    name: "DatepickerBtn",
    props: {
        dateRange: { type: Array<string>, required: true },
    },
    data: () => ({
        attributes: {
            mode: "date",
            highlight: 'blue',
        },
        range: {
            start: new Date(),
            end: new Date(),
        }
    }),
    computed: {
        fromDateRange() {
            let options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'short', day: 'numeric' };
            return this.range.start.toLocaleDateString('en-GB', options);
        },
        toDateRange() {
            let options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'short', day: 'numeric' };
            return this.range.end.toLocaleDateString('en-GB', options);
        }
    },
    components: {
        KTIcon,
        DatePicker
    },
    created() {
        this.range.start = new Date(this.dateRange[0]);
        this.range.end = new Date(this.dateRange[1]);
    }
})
</script>

<template>
    <date-picker v-model.range="range" :attributes="attributes">
        <template #default="{ togglePopover }">
            <div class="btn btn-sm btn-light d-flex align-items-center px-4"  @click="togglePopover">
                <div class="text-gray-600 fw-bold">{{fromDateRange}} - {{toDateRange}}</div>
                <KTIcon icon-name="calendar-8" icon-class="fs-1 ms-2 me-0" />
            </div>
        </template>
    </date-picker>
</template>

<style scoped lang="scss">

</style>

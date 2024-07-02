<template>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <ParserInfoWidget
                className="mb-5 mb-xl-10 h-100"
                :icon="false"
                :stats="activeParsers"
                description="Active parsers"
                :parsersInfo="parsersInfo"
                labelColor="dark"
                textColor="gray-300"
            />
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <CardInfoWidget
                className="mb-5 mb-xl-10 h-100"
                :icon="false"
                :stats="ofUsersNumber"
                description="OF users"
                :sub-description="`Indexed: ${indexedOfUsers}`"
                labelColor="dark"
                textColor="gray-300"
            />
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <CardInfoWidget
                className="mb-5 mb-xl-10 h-100"
                :icon="false"
                :stats="ofTagsNumber"
                description="Categories"
                :sub-description="`Indexed: ${indexedOfTags}`"
                labelColor="dark"
                textColor="gray-300"
            />
        </div>
        <!--end::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <CardInfoWidget
                className="mb-5 mb-xl-10 h-100"
                :icon="false"
                :stats="usersNumber"
                description="Users"
                labelColor="dark"
                textColor="gray-300"
            />
        </div>
        <div class="col-xxl-6">
            <ParserStatusWidget
                title="Total parsed performers"
                :parser-data="parserData"
                :chart-data="chartDataCreatedPerformers"
                @set-chart-data="setChartDataPerformers"
                className="h-lg-100"
                :height="300"
                :date-range="[startDate, endDate]"
            >
                <template #before-title>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">{{ addedPerformers }} added performers for this period</span>
                </template>
            </ParserStatusWidget>
        </div>
        <div class="col-xxl-6">
            <ParserStatusWidget
                title="Total parsed regulars"
                :parser-data="parserData"
                :chart-data="chartDataCreatedRegulars"
                :show-datepicker="false"
                @set-chart-data="setChartDataRegulars"
                className="h-lg-100"
                :height="300"
                :date-range="[startDate, endDate]"
            >
                <template #before-title>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">{{ addedRegulars }} added regulars for this period</span>
                </template>
            </ParserStatusWidget>
        </div>
        <div class="col-xxl-6">
            <ParserStatusWidget
                title="Total updated performers"
                :parser-data="parserDataUpdated"
                :show-datepicker="false"
                :chart-data="chartDataUpdatedPerformers"
                @set-chart-data="setChartDataUpdatedPerformers"
                className="h-lg-100"
                :height="300"
                :date-range="[startDate, endDate]"
            >
                <template #before-title>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">{{ updatedPerformers }} updated performers for this period</span>
                </template>
            </ParserStatusWidget>
        </div>
        <div class="col-xxl-6">
            <ParserStatusWidget
                title="Total regulars became performers"
                :parser-data="parserCheckingRegularsData"
                :show-datepicker="false"
                :chart-data="chartCheckingRegularsData"
                @set-chart-data="setChartCheckingRegularsData"
                className="h-lg-100"
                :height="300"
                :date-range="[startDate, endDate]"
            >
                <template #before-title>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">{{ totalRegularsBecameModelCount }} regulars became performers for this period</span>
                </template>
            </ParserStatusWidget>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-xl-6">
            <SpeedChartWidget
                widget-classes="card-xl-stretch mb-xl-8 h-md-100"
                chart-color="primary"
                :parser-data="parserData"
                :chart-data="chartDataParserSpeed"
                @set-series-data="setSpeedSeries"
                chart-height="200"
            ></SpeedChartWidget>
        </div>
        <div class="col-xxl-6">
            <ChartWidget
                title="Site load"
                :initial-data="requestsPerSecondsData"
                :chart-data="chartRequestsPerSecondData"
                @set-chart-data="setRequestsPerSecondData"
                className="h-lg-100"
                :show-datepicker="false"
                :height="300"
                growth-arrow
                :date-range="[startDate, endDate]"
            >
            </ChartWidget>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</template>

<script lang="ts">
import {getAssetPath} from "~/core/helpers/assets";
import {defineComponent} from "vue";
import Widget1 from "~/components/dashboard-default-widgets/Widget1.vue";
import CardInfoWidget from "~/components/dashboard-default-widgets/CardInfoWidget.vue";
import Widget3 from "~/components/dashboard-default-widgets/Widget3.vue";
import Widget4 from "~/components/dashboard-default-widgets/Widget4.vue";
import Widget5 from "~/components/dashboard-default-widgets/Widget5.vue";
import Widget6 from "~/components/dashboard-default-widgets/Widget6.vue";
import Widget7 from "~/components/dashboard-default-widgets/Widget7.vue";
import Widget8 from "~/components/dashboard-default-widgets/Widget8.vue";
import ParserStatusWidget from "~/components/dashboard-default-widgets/ParserStatusWidget.vue";
import Widget10 from "~/components/dashboard-default-widgets/Widget10.vue";
import SpeedChartWidget from "~/components/widgets/mixed/SpeedChartWidget.vue";
import DefaultLayout from "~/layouts/default-layout/DefaultLayout.vue";
import {types} from "sass";
import String = types.String;
import type {ParserStatusType} from "@/Types/ParserStatusType";
import type {PointType} from "@/Types/PointType";
import type {ChartDataType} from "@/Types/ChartDataType";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import ParserInfoWidget from "~/components/dashboard-default-widgets/ParserInfoWidget.vue";
import {useDashboardStore} from "~/stores/dashboard";
import type {ParserUpdatingStatusType} from "@/Types/ParserUpdatingStatusType";
import type {ParserCheckingRegularsStatusType} from "@/Types/ParserCheckingRegularsStatusType";
import ChartWidget from "~/components/widgets/charts/ChartWidget.vue";


export default defineComponent({
    name: "main-dashboard",
    layout: [DefaultLayout],
    components: {
        ChartWidget,
        ParserInfoWidget,
        Widget1,
        CardInfoWidget,
        Widget3,
        Widget4,
        Widget5,
        Widget6,
        Widget7,
        Widget8,
        ParserStatusWidget,
        Widget10,
        SpeedChartWidget,
    },
    props: {
        startDate: {
            type: String,
            required: true
        },
        endDate: {
            type: String,
            required: true
        },
        indexedOfUsers: {
            type: String,
            default: 0
        },
        indexedOfTags: {
            type: String,
            default: 0
        },
        ofTagsNumber: {
            type: String,
            default: 0
        },
        usersNumber: {
            type: String,
            default: 0
        },
        parserData: {
            type: Object as () => Array<ParserStatusType>,
            required: true
        },
        parserDataUpdated: {
            type: Object as () => Array<ParserUpdatingStatusType>,
            required: true
        },
        parserCheckingRegularsData: {
            type: Object as () => Array<ParserCheckingRegularsStatusType>,
            required: true
        },
        requestsPerSecondsData: {
            type: Array<Object>,
            required: true
        },
    },
    computed: {
        activeParsers() {
            return useDashboardStore().countActiveParsers;
        },
        parsersInfo() {
            return useDashboardStore().parserData;
        },
        addedPerformers(): number {
            return this.parserData.reduce((acc, item) => acc + item.parsed_performers, 0);
        },
        totalRegularsBecameModelCount(): number {
            return this.parserCheckingRegularsData.reduce((acc, item) => acc + item.regulars_became_model_count, 0);
        },
        updatedPerformers(): number {
            return this.parserDataUpdated.reduce((acc, item) => acc + item.updated_performers, 0);
        },
        addedRegulars(): number {
            return this.parserData.reduce((acc, item) => acc + item.parsed_regulars, 0);
        },
        dates(): string[] {
            return this.parserData.map(data => data.date);
        }
    },
    data: () => ({
        ofUsersNumber: null,
        chartRequestsPerSecondData: [
            {
                name: "Requests per second to the site",
                data: [] as PointType[],
            }
        ] as ChartDataType[],
        chartDataCreatedPerformers: [
            {
                name: "Created performers",
                data: [] as PointType[],
            }
        ] as ChartDataType[],
        chartDataCreatedRegulars: [
            {
                name: "Created regulars",
                data: [] as PointType[],
            }
        ] as ChartDataType[],
        chartDataUpdatedPerformers: [
            {
                name: "Updated performers",
                data: [] as PointType[],
            }
        ] as ChartDataType[],
        chartCheckingRegularsData: [
            {
                name: "Became performers",
                data: [] as PointType[],
            }
        ] as ChartDataType[],
        chartDataParserSpeed: [
            {
                name: "Speed",
                data: [] as PointType[],
            }
        ] as ChartDataType[],
    }),
    methods: {
        getAssetPath,
        setRequestsPerSecondData(data: Array<Object>) {
            this.chartRequestsPerSecondData[0].data = data.map((item, index) => ({
                x: item.date,
                y: Number(item.value).toFixed(2),
            }));
        },
        setChartDataPerformers(data: Array<ParserStatusType>) {
            this.chartDataCreatedPerformers[0].data = data.map((item, index) => ({
                x: this.dates[index],
                y: item.parsed_performers,
            }));
        },
        setChartDataRegulars(data: Array<ParserStatusType>) {
            this.chartDataCreatedRegulars[0].data = data.map((item, index) => ({
                x: this.dates[index],
                y: item.parsed_regulars,
            }));
        },
        setChartDataUpdatedPerformers(data: Array<ParserUpdatingStatusType>) {
            this.chartDataUpdatedPerformers[0].data = data.map((item, index) => ({
                x: this.dates[index],
                y: item.updated_performers,
            }));
        },
        setChartCheckingRegularsData(data: Array<ParserCheckingRegularsStatusType>) {
            this.chartCheckingRegularsData[0].data = data.map((item, index) => ({
                x: this.dates[index],
                y: item.regulars_became_model_count,
            }));
        },
        setSpeedSeries(data: Array<ParserStatusType>) {
            this.chartDataParserSpeed[0].data = data.map((item, index) => ({
                x: this.dates[index],
                y: item.speed,
            }));
        },
        async fetchOfUsersNumber() {
            let {data: usersCount} = await axios.get(route('admin.get-total-models'));
            this.ofUsersNumber = usersCount;
        },
        async fetchParserData() {
            router.reload();
            setTimeout(this.fetchOfUsersNumber, 1000);
            setTimeout(this.fetchActiveParsers, 1000);
        },
        async fetchActiveParsers() {
            await useDashboardStore().fetchActiveParsers()
        }
    },
    mounted() {
        setTimeout(this.fetchOfUsersNumber, 1000);
        setTimeout(this.fetchActiveParsers, 1000);
        setInterval(this.fetchParserData, 20000);
    }
});
</script>

<template>
  <div class="card card-flush" :class="className">
    <!--begin::Header-->
    <div class="card-header pt-5">
      <!--begin::Title-->
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bold text-gray-900">{{title}}</span>
          <slot name="before-title"></slot>
      </h3>
      <!--end::Title-->
      <!--begin::Toolbar-->
      <div class="card-toolbar" v-if="showDatepicker">
          <DatepickerBtn :date-range="dateRange" @update:modelValue="updateDatePicker"/>
      </div>
      <!--end::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Card body-->
    <div class="card-body d-flex align-items-end p-0">
      <!--begin::Chart-->
      <apexchart
        class="min-h-auto w-100 ps-4 pe-6"
        ref="chartRef"
        :options="chart"
        :series="series"
        :height="height"
      ></apexchart>
      <!--end::Chart-->
    </div>
    <!--end::Card body-->
  </div>
</template>

<script lang="ts">
import { getAssetPath } from "~/core/helpers/assets";
import {computed, defineComponent, onMounted, onUpdated, ref, watch} from "vue";
import type { ApexOptions } from "apexcharts";
import { getCSSVariableValue } from "~/assets/ts/_utils";
import type VueApexCharts from "vue3-apexcharts";
import { useThemeStore } from "~/stores/theme";
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";
import DatepickerBtn from "~/components/calendar/DatepickerBtn.vue";
import { router } from '@inertiajs/vue3'
import {HelperService} from "@/Services/HelperService";
import type {ParserStatusType} from "@/Types/ParserStatusType";
import route from "../../../../../vendor/tightenco/ziggy";
import type { ChartDataType } from "@/Types/ChartDataType";
import type {ParserUpdatingStatusType} from "@/Types/ParserUpdatingStatusType";
import type {ParserCheckingRegularsStatusType} from "@/Types/ParserCheckingRegularsStatusType";

export default defineComponent({
  name: "ParserStatusWidget",
  components: {DatepickerBtn, KTIcon},
  props: {
    title: { type: String, default: "Parsing" },
    showDatepicker: { type: Boolean, default: true },
    className: { type: String, required: false },
    height: { type: Number, required: true },
    chartData: { type: Object as () => ChartDataType[], required: true },
    dateRange: { type: Array<String>, required: true },
    parserData: { type: Object as () => Array<ParserStatusType | ParserUpdatingStatusType | ParserCheckingRegularsStatusType>, required: true },
  },
  emits: ['setChartData'],
  setup(props, { emit }) {
    const chartRef = ref<typeof VueApexCharts | null>(null);
    const chart = ref<ApexOptions>({});
    const store = useThemeStore();

    const series = ref<number[]>([]);

    const themeMode = computed(() => {
      return store.mode;
    });
    const dates = computed(() => props.chartData[0].data.map(data => data.x));

    onMounted(() => {
      series.value = props.chartData.map(item => {
          item.data = item.data.map(d => d.y);
          return item;
      })
      Object.assign(chart.value, chartOptions(props.height, dates.value));
      emit('setChartData', props.parserData);
    });
    onUpdated(() => {
        emit('setChartData', props.parserData);
    });

    const updateDatePicker = (value) => {
        router.visit(route('admin.dashboard'), {
            method: 'get',
            data: {
                start: HelperService.formatDate(value.start),
                end: HelperService.formatDate(value.end),
            },
            preserveState: true,
        })
    };

    const refreshChart = () => {
      if (!chartRef.value) {
        return;
      }
      chartRef.value.updateOptions(chartOptions(props.height, dates.value));
    };
      watch(themeMode, () => {
      refreshChart();
    });

    return {
      chart,
      chartRef,
      series,
      updateDatePicker,
      getAssetPath,
    };
  },
});

const chartOptions = (height: number, xaxisData: Array<string> = []): ApexOptions => {
  const labelColor = getCSSVariableValue("--bs-gray-500");
  const borderColor = getCSSVariableValue("--bs-border-dashed-color");
  const baseprimaryColor = getCSSVariableValue("--bs-primary");
  const lightprimaryColor = getCSSVariableValue("--bs-primary");
  const basesuccessColor = getCSSVariableValue("--bs-success");
  const lightsuccessColor = getCSSVariableValue("--bs-success");

  return {
    chart: {
      fontFamily: "inherit",
      type: "area",
      height: height,
      toolbar: {
        show: false,
      },
    },
    plotOptions: {},
    legend: {
      show: false,
    },
    dataLabels: {
      enabled: false,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.4,
        opacityTo: 0.2,
        stops: [15, 120, 100],
      },
    },
    stroke: {
      curve: "smooth",
      show: true,
      width: 3,
      colors: [baseprimaryColor, basesuccessColor],
    },
    xaxis: {
      categories: xaxisData,
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: false,
      },
      tickAmount: 6,
      labels: {
        rotate: 0,
        rotateAlways: true,
        style: {
          colors: labelColor,
          fontSize: "12px",
        },
      },
      crosshairs: {
        position: "front",
        stroke: {
          width: 1,
          dashArray: 3,
        },
      },
      tooltip: {
        enabled: true,
        formatter: undefined,
        offsetY: 0,
        style: {
          fontSize: "12px",
        },
      },
    },
    yaxis: {
      tickAmount: 6,
      labels: {
        style: {
          colors: labelColor,
          fontSize: "12px",
        },
      },
    },
    states: {
      normal: {
        filter: {
          type: "none",
          value: 0,
        },
      },
      hover: {
        filter: {
          type: "none",
          value: 0,
        },
      },
      active: {
        allowMultipleDataPointsSelection: false,
        filter: {
          type: "none",
          value: 0,
        },
      },
    },
    tooltip: {
      style: {
        fontSize: "12px",
      },
    },
    colors: [lightprimaryColor, lightsuccessColor],
    grid: {
      borderColor: borderColor,
      strokeDashArray: 4,
      yaxis: {
        lines: {
          show: true,
        },
      },
    },
    markers: {
      strokeWidth: 3,
    },
  };
};
</script>

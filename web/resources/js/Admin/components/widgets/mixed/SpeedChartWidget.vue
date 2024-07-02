<template>
  <!--begin::Mixed Widget 5-->
  <div :class="widgetClasses" class="card">
    <!--begin::Beader-->
    <div class="card-header border-0 py-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bold fs-3 mb-1">Parser Speed</span>

        <span class="text-muted fw-semibold fs-7">Requests per second</span>
      </h3>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body d-flex flex-column">
      <!--begin::Chart-->
      <apexchart
        ref="chartRef"
        class="mixed-widget-5-chart card-rounded-top"
        :options="chart"
        :series="series"
        type="area"
        :height="chartHeight"
      ></apexchart>
      <!--end::Chart-->
    </div>
    <!--end::Body-->
  </div>
  <!--end::Mixed Widget 5-->
</template>

<script lang="ts">
import { getAssetPath } from "~/core/helpers/assets";
import { computed, defineComponent, onMounted, ref, watch, onUpdated } from "vue";
import Dropdown3 from "~/components/dropdown/Dropdown3.vue";
import { getCSSVariableValue } from "~/assets/ts/_utils";
import type { ApexOptions } from "apexcharts";
import type VueApexCharts from "vue3-apexcharts";
import { useThemeStore } from "~/stores/theme";
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";
import type {ParserStatusType} from "@/Types/ParserStatusType";

export default defineComponent({
  name: "SpeedChartWidget",
  components: {
      KTIcon,
    Dropdown3,
  },
  props: {
    widgetClasses: String,
      chartData: { type: Array, required: true },
      parserData: { type: Object as () => Array<ParserStatusType>, required: true },
      title: { type: String, default: "Parsing" },
    chartColor: String,
    chartHeight: String,
  },
    emits: ['setSeriesData'],
  setup(props, { emit }) {
    const chartRef = ref<typeof VueApexCharts | null>(null);
    const chart = ref<ApexOptions>({});
    const store = useThemeStore();

    const series = ref([
      {
        name: "Requests",
        data: [],
      },
    ]);

    const themeMode = computed(() => {
      return store.mode;
    });
      const dates = computed(() => props.chartData[0].data.map(data => data.x));
    onMounted(() => {
        series.value = props.chartData.map(item => {
            item.data = item.data.map(d => d.y);
            return item;
        })

      Object.assign(
        chart.value,
        chartOptions(props.chartColor, props.chartHeight, dates.value)
      );
        emit('setSeriesData', props.parserData);
    });
      onUpdated(() => {
          emit('setSeriesData', props.parserData);
      });
    const refreshChart = () => {
      if (!chartRef.value) {
        return;
      }

      chartRef.value.updateOptions(
        chartOptions(props.chartColor, props.chartHeight, dates.value)
      );
    };

    watch(themeMode, () => {
      refreshChart();
    });
      watch(chartOptions, () => {
          refreshChart();
      });
    return {
      chart,
      series,
      chartRef,
      getAssetPath,
    };
  },
});

const chartOptions = (
  color: string = "primary",
  height: string = "auto",
  xaxisData: string[] = []
): ApexOptions => {
  const labelColor = getCSSVariableValue("--bs-gray-800");
  const strokeColor = getCSSVariableValue("--bs-gray-300");
  const baseColor = getCSSVariableValue(`--bs-${color}`);
  const lightColor = getCSSVariableValue(`--bs-${color}-light`);

  return {
    chart: {
      fontFamily: "inherit",
      type: "area",
      height: height,
      toolbar: {
        show: true,
      },
      zoom: {
        enabled: false,
      },
      sparkline: {
        enabled: true,
      },
    },
    plotOptions: {},
    legend: {
      show: true,
    },
    dataLabels: {
      enabled: true,
    },
    fill: {
      type: "solid",
      opacity: 1,
    },
    stroke: {
      curve: "smooth",
      show: true,
      width: 3,
      colors: [baseColor],
    },
    xaxis: {
      categories: xaxisData,
      axisBorder: {
        show: false,
      },
      axisTicks: {
        show: true,
      },
      labels: {
        show: true,
        style: {
          colors: labelColor,
          fontSize: "12px",
        },
      },
      crosshairs: {
        show: false,
        position: "front",
        stroke: {
          color: strokeColor,
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
      labels: {
        show: true,
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
      y: {
        formatter: function (val) {
          return val + " req/sec";
        },
      },
    },
    colors: [lightColor],
    markers: {
      colors: [lightColor],
      strokeColors: [baseColor],
      strokeWidth: 3,
    },
  };
};
</script>

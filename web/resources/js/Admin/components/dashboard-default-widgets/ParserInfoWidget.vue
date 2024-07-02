<template>
  <div class="card card-flush" :class="className">
    <!--begin::Header-->
    <div class="card-header pt-5">
      <!--begin::Title-->
      <div class="card-title d-flex flex-column">
        <!--begin::Amount-->
        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">
            <template v-if="stats !== null">
                {{ stats }}
            </template>
            <template v-else>
                <RollingLoader/>
            </template>
        </span>
        <!--end::Amount-->

        <!--begin::Subtitle-->
        <span class="text-gray-500 pt-1 fw-semibold fs-6">{{
          description
        }}</span>
        <!--end::Subtitle-->
      </div>
      <!--end::Title-->
    </div>
    <!--end::Header-->

    <!--begin::Card body-->
    <div class="card-body d-flex flex-column justify-content-end pe-0">
      <!--begin::Title-->
      <span class="fs-6 fw-bolder text-gray-800 d-block mb-2 h-100">
         <template v-if="parsersInfo?.parser?.status">
             Parser: <br>
             Speed: {{parsersInfo.parser.speed}} req/s |
             Avg res time: {{parsersInfo.parser.average_response_time_sec}} |
             Progress: {{parsersInfo.parser.total_progress_percent}} % |
             Used proxies: {{parsersInfo.parser.used_proxies_count}} |
             Time: {{(parsersInfo.parser.time_sec / 60).toFixed(1)}} / min

         </template>
          <template v-if="parsersInfo?.parserUpdating?.status">
              <br>Parser updating models: <br>
             Speed: {{parsersInfo.parserUpdating.speed}} req/s |
             Updated performers: {{parsersInfo.parserUpdating.updated_performers}} |
             Used proxies: {{parsersInfo.parserUpdating.used_proxies}} |
             Time: {{(parsersInfo.parserUpdating.time_sec / 60).toFixed(1)}} / min
         </template>
          <template v-if="parsersInfo?.parserCheckingRegulars?.status">
              <br>Parser checking regulars: <br>
              Speed: {{parsersInfo.parserCheckingRegulars.speed}} req/s |
             Regulars became model: {{parsersInfo.parserCheckingRegulars.regulars_became_model_count}} |
             Regulars left check: {{parsersInfo.parserCheckingRegulars.regulars_need_to_be_checked_count}} |
             Used proxies: {{parsersInfo.parserCheckingRegulars.used_proxies}} |
             Progress: {{parsersInfo.parserCheckingRegulars.total_progress_percent}} % |
             Time: {{(parsersInfo.parserCheckingRegulars.time_sec / 60).toFixed(1)}} / min
         </template>
      </span>
      <!--end::Title-->
    </div>
    <!--end::Card body-->
  </div>
</template>

<script lang="ts">
import { getAssetPath } from "~/core/helpers/assets";
import { defineComponent } from "vue";
import RollingLoader from "~/components/RollingLoader.vue";
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";

export default defineComponent({
  name: "ParserInfoWidget",
  components: {KTIcon, RollingLoader},
  props: {
    stats: { type: String, required: true },
    description: { type: String, required: true },
    parsersInfo: { type: Array, default: [], required: false },
    className: { type: String, required: false },
  },
  setup() {
    return {
      getAssetPath,
    };
  },
});
</script>

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
      <span class="fs-6 fw-bolder text-gray-800 d-block mb-2"
        >{{ subDescription }}</span
      >
      <!--end::Title-->
    </div>
    <!--end::Card body-->
  </div>
</template>

<script lang="ts">
import { getAssetPath } from "~/core/helpers/assets";
import { defineComponent } from "vue";
import RollingLoader from "~/components/RollingLoader.vue";

export default defineComponent({
  name: "CardInfoWidget",
  components: {RollingLoader},
  props: {
    stats: { type: String, required: true },
    description: { type: String, required: true },
    subDescription: { type: String, default: '' },
    className: { type: String, required: false },
  },
  setup() {
    const profiles = [
      {
        name: "Alan Warden",
        initials: "A",
        state: "warning",
      },
      {
        name: "Michael Eberon",
        src: getAssetPath("media/avatars/300-11.jpg"),
      },
      {
        name: "Susan Redwood",
        initials: "S",
        state: "primary",
      },
      {
        name: "Melody Macy",
        src: getAssetPath("media/avatars/300-2.jpg"),
      },
      {
        name: "Perry Matthew",
        initials: "P",
        state: "danger",
      },
      {
        name: "Barry Walter",
        src: getAssetPath("media/avatars/300-12.jpg"),
      },
    ];

    return {
      profiles,
      getAssetPath,
    };
  },
});
</script>

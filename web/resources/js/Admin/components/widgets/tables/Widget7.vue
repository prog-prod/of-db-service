<template>
  <!--begin::Table Widget 7-->
  <div :class="widgetClasses" class="card">
    <!--begin::Header-->
    <div class="card-header border-0 pt-5">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bold fs-3 mb-1">Latest Orders</span>

        <span class="text-muted mt-1 fw-semibold fs-7"
          >More than 100 new orders</span
        >
      </h3>
      <div class="card-toolbar">
        <ul class="nav">
          <li class="nav-item">
            <a
              class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary active fw-bold px-4 me-1"
              data-bs-toggle="tab"
              href="#kt_table_widget_7_tab_1"
              >Month</a
            >
          </li>

          <li class="nav-item">
            <a
              class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bold px-4 me-1"
              data-bs-toggle="tab"
              href="#kt_table_widget_7_tab_2"
              >Week</a
            >
          </li>

          <li class="nav-item">
            <a
              class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bold px-4"
              data-bs-toggle="tab"
              href="#kt_table_widget_7_tab_3"
              >Day</a
            >
          </li>
        </ul>
      </div>
    </div>
    <!--end::Header-->

    <!--begin::Body-->
    <div class="card-body py-3">
      <div class="tab-content">
        <template v-for="(item, index) in items" :key="index">
          <!--begin::Tap pane-->
          <div
            :class="[index === 0 && 'active show']"
            class="tab-pane fade"
            :id="`kt_table_widget_7_tab_${index + 1}`"
          >
            <!--begin::Table container-->
            <div class="table-responsive">
              <!--begin::Table-->
              <table class="table align-middle gs-0 gy-3">
                <!--begin::Table head-->
                <thead>
                  <tr>
                    <th class="p-0 w-50px"></th>
                    <th class="p-0 min-w-150px"></th>
                    <th class="p-0 min-w-140px"></th>
                    <th class="p-0 min-w-120px"></th>
                  </tr>
                </thead>
                <!--end::Table head-->

                <!--begin::Table body-->
                <tbody>
                  <template v-for="(item1, index1) in item" :key="index1">
                    <tr>
                      <td>
                        <div class="symbol symbol-50px me-2">
                          <span
                            :class="`bg-light-${item1.icon.color}`"
                            class="symbol-label"
                          >
                            <KTIcon
                              :icon-name="item1.icon.file"
                              :icon-class="`text-${item1.icon.color} fs-2x`"
                            />
                          </span>
                        </div>
                      </td>
                      <td>
                        <a
                          href="#"
                          class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6"
                          >{{ item1.info.title }}</a
                        >
                        <span class="text-muted fw-semibold d-block fs-7">{{
                          item1.info.description
                        }}</span>
                      </td>
                      <td class="text-end">
                        <span class="text-muted fw-semibold d-block fs-8"
                          >Ordered</span
                        >

                        <span class="text-gray-900 fw-bold d-block fs-7">{{
                          item1.date
                        }}</span>
                      </td>
                      <td class="text-end">
                        <span
                          :class="`badge-light-${item1.badge.color}`"
                          class="badge fs-7 fw-bold"
                          >{{ item1.badge.label }}</span
                        >
                      </td>
                      <td class="text-end">
                        <a
                          href="#"
                          class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                        >
                          <i class="bi bi-three-dots fs-5"></i>
                        </a>
                      </td>
                    </tr>
                  </template>
                </tbody>
                <!--end::Table body-->
              </table>
            </div>
            <!--end::Table-->
          </div>
          <!--end::Tap pane-->
        </template>
      </div>
    </div>
    <!--end::Body-->
  </div>
  <!--end::Tables Widget 7-->
</template>

<script lang="ts">
import { defineComponent } from "vue";
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";

export default defineComponent({
  name: "kt-widget-7",
  components: {KTIcon},
  props: {
    widgetClasses: String,
  },
  setup() {
    const items = [
      [
        {
          icon: {
            file: "scroll",
            color: "success",
          },
          info: {
            title: "Media Device",
            description: "Voice and video recorder",
          },
          date: "5 day ago",
          badge: {
            label: "Delivered",
            color: "success",
          },
        },
        {
          icon: {
            file: "category",
            color: "danger",
          },
          info: {
            title: "Special Meal",
            description: "Quona Rice",
          },
          date: "2 day ago",
          badge: {
            label: "Delivered",
            color: "danger",
          },
        },
        {
          icon: {
            file: "compass",
            color: "primary",
          },
          info: {
            title: "New Users",
            description: "Awesome Users",
          },
          date: "4 day ago",
          badge: {
            label: "Delivered",
            color: "primary",
          },
        },
        {
          icon: {
            file: "abstract-26",
            color: "warning",
          },
          info: {
            title: "Active Customers",
            description: "Best Customers",
          },
          date: "1 day ago",
          badge: {
            label: "Delivered",
            color: "warning",
          },
        },
        {
          icon: {
            file: "bucket",
            color: "info",
          },
          info: {
            title: "Strawberry Boxes",
            description: "From Spain",
          },
          date: "7 day ago",
          badge: {
            label: "Delivered",
            color: "info",
          },
        },
      ],

      [
        {
          icon: {
            file: "abstract-41",
            color: "info",
          },
          info: {
            title: "Strawberry Boxes",
            description: "From Spain",
          },
          date: "4 week ago",
          badge: {
            label: "Delivered",
            color: "info",
          },
        },
        {
          icon: {
            file: "category",
            color: "danger",
          },
          info: {
            title: "Special Meal",
            description: "Quona Rice",
          },
          date: "2 week ago",
          badge: {
            label: "Delivered",
            color: "danger",
          },
        },
        {
          icon: {
            file: "abstract-26",
            color: "warning",
          },
          info: {
            title: "Active Customers",
            description: "Best Customers",
          },
          date: "5 week ago",
          badge: {
            label: "Delivered",
            color: "warning",
          },
        },
      ],

      [
        {
          icon: {
            file: "package",
            color: "success",
          },
          info: {
            title: "Media Device",
            description: "Voice and video recorder",
          },
          date: "3 month ago",
          badge: {
            label: "Delivered",
            color: "success",
          },
        },
        {
          icon: {
            file: "category",
            color: "danger",
          },
          info: {
            title: "Special Meal",
            description: "Quona Rice",
          },
          date: "5 month ago",
          badge: {
            label: "Delivered",
            color: "danger",
          },
        },
        {
          icon: {
            file: "abstract-26",
            color: "warning",
          },
          info: {
            title: "Active Customers",
            description: "Best Customers",
          },
          date: "6 month ago",
          badge: {
            label: "Delivered",
            color: "warning",
          },
        },
        {
          icon: {
            file: "abstract-41",
            color: "info",
          },
          info: {
            title: "Strawberry Boxes",
            description: "From Spain",
          },
          date: "4 month ago",
          badge: {
            label: "Delivered",
            color: "info",
          },
        },
      ],
    ];

    return {
      items,
    };
  },
});
</script>

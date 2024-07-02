<template>
  <template v-for="(item, i) in MainMenuConfig" :key="i">
    <template v-if="!item.heading">
      <template v-for="(menuItem, j) in item.pages" :key="j">
        <div v-if="menuItem.heading" class="menu-item me-lg-1">
          <Link
            v-if="menuItem.route"
            class="menu-link"
            :href="menuItem.route"
            active-class="active"
          >
            <span class="menu-title">{{ translate(menuItem.heading) }}</span>
          </Link>
        </div>
      </template>
    </template>
    <div
      v-if="item.heading"
      data-kt-menu-trigger="click"
      data-kt-menu-placement="bottom-start"
      class="menu-item menu-lg-down-accordion me-lg-1"
    >
      <span
        v-if="item.route"
        class="menu-link py-3"
        :class="{ active: hasActiveChildren(item.route) }"
      >
        <span class="menu-title">{{ translate(item.heading) }}</span>
        <span class="menu-arrow d-lg-none"></span>
      </span>
      <div
        class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-225px"
      >
        <template v-for="(menuItem, j) in item.pages" :key="j">
          <div
            v-if="menuItem.sectionTitle"
            data-kt-menu-trigger="{default:'click', lg: 'hover'}"
            data-kt-menu-placement="right-start"
            class="menu-item menu-lg-down-accordion"
          >
            <span
              v-if="menuItem.route"
              class="menu-link py-3"
              :class="{ active: hasActiveChildren(menuItem.route) }"
            >
              <span class="menu-icon">
                <i
                  v-if="headerMenuIcons === 'bootstrap'"
                  :class="menuItem.bootstrapIcon"
                  class="bi fs-3"
                ></i>
                <KTIcon
                  v-if="headerMenuIcons === 'keenthemes'"
                  :icon-name="menuItem.keenthemesIcon"
                  icon-class="fs-2"
                />
              </span>
              <span class="menu-title">{{
                translate(menuItem.sectionTitle)
              }}</span>
              <span class="menu-arrow"></span>
            </span>
            <div
              class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px"
            >
              <template v-for="(menuItem1, k) in menuItem.sub" :key="k">
                <div
                  v-if="menuItem1.sectionTitle"
                  data-kt-menu-trigger="{default:'click', lg: 'hover'}"
                  data-kt-menu-placement="right-start"
                  class="menu-item menu-lg-down-accordion"
                >
                  <span
                    v-if="menuItem1.route"
                    class="menu-link py-3"
                    :class="{ active: hasActiveChildren(menuItem1.route) }"
                  >
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">{{
                      translate(menuItem1.sectionTitle)
                    }}</span>
                    <span class="menu-arrow"></span>
                  </span>
                  <div
                    class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-active-bg py-lg-4 w-lg-225px"
                  >
                    <template v-for="(menuItem2, l) in menuItem1.sub" :key="l">
                      <div class="menu-item">
                        <Link
                          v-if="menuItem2.route && menuItem2.heading"
                          class="menu-link py-3"
                          active-class="active"
                          :href="menuItem2.route"
                        >
                          <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                          </span>
                          <span class="menu-title">{{
                            translate(menuItem2.heading)
                          }}</span>
                        </Link>
                      </div>
                    </template>
                  </div>
                </div>
                <div v-if="menuItem1.heading" class="menu-item">
                  <Link
                    v-if="menuItem1.route"
                    class="menu-link"
                    active-class="active"
                    :href="menuItem1.route"
                  >
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">{{
                      translate(menuItem1.heading)
                    }}</span>
                  </Link>
                </div>
              </template>
            </div>
          </div>
          <div v-if="menuItem.heading" class="menu-item">
              <template v-if="menuItem.tag === 'a'">
                <a
                  v-if="menuItem.route && menuItem.heading"
                  class="menu-link"
                  :href="menuItem.route"
                >
                  <span class="menu-icon">
                    <KTIcon icon-name="element-8" icon-class="fs-2" />
                  </span>
                  <span class="menu-title">{{ translate(menuItem.heading) }}</span>
                </a>
              </template>
            <Link
              v-else-if="menuItem.route && menuItem.route"
              class="menu-link"
              active-class="active"
              :href="menuItem.route"
            >
              <span class="menu-icon">
                <KTIcon icon-name="element-8" icon-class="fs-2" />
              </span>
              <span class="menu-title">{{ translate(menuItem.heading) }}</span>
            </Link>
          </div>
        </template>
      </div>
    </div>
  </template>
</template>

<script lang="ts">
import { getAssetPath } from "~/core/helpers/assets";
import { defineComponent } from "vue";
import { useI18n } from "vue-i18n";
import MainMenuConfig from "~/layouts/default-layout/config/MainMenuConfig";
import { headerMenuIcons } from "~/layouts/default-layout/config/helper";
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";
import {Link, usePage} from "@inertiajs/vue3";

export default defineComponent({
  name: "KTMenu",
  components: {KTIcon, Link},
  setup() {
    const page = usePage();
    const { t, te } = useI18n();

    const hasActiveChildren = (match: string) => {

        console.log(page.url)
      return page.url.indexOf(match) !== -1;
    };

    const translate = (text: string) => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      hasActiveChildren,
      headerMenuIcons,
      MainMenuConfig,
      translate,
      getAssetPath,
    };
  },
});
</script>

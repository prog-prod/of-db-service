<template>
    <div>
        <slot name="default"></slot>
    </div>
</template>

<script lang="ts" setup>
import { nextTick, onBeforeMount, onMounted } from "vue";
import { useConfigStore } from "~/stores/config";
import { useThemeStore } from "~/stores/theme";
import { useBodyStore } from "~/stores/body";
import { themeConfigValue } from "~/layouts/default-layout/config/helper";
import { initializeComponents } from "~/core/plugins/keenthemes";

const configStore = useConfigStore();
const themeStore = useThemeStore();
const bodyStore = useBodyStore();

onBeforeMount(() => {
    /**
     * Overrides the layout config using saved data from localStorage
     * remove this to use static config (@/layouts/default-layout/config/DefaultLayoutConfig.ts)
     */
    configStore.overrideLayoutConfig();

    /**
     *  Sets a mode from configuration
     */
    themeStore.setThemeMode(themeConfigValue.value);
});

onMounted(() => {
    nextTick(() => {
        initializeComponents();

        bodyStore.removeBodyClassName("page-loading");
    });
});
</script>

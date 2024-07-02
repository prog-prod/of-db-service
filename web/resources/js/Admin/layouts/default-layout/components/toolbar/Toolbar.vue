<template>
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div
            id="kt_app_toolbar_container"
            class="app-container d-flex flex-stack"
            :class="{
        'container-fluid': toolbarWidthFluid,
        'container-xxl': !toolbarWidthFluid,
      }"
        >
            <KTPageTitle/>
            <!--begin::Actions-->
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!--begin::Primary button-->
                <div id="toolbar-buttons">
                    <template v-if="parserData?.parser?.status == 1">
                        <button @click="stopParsingModels" type="button" class="btn btn-sm btn-light-primary ms-1">
                            <KTIcon icon-name="minus-circle" icon-class="fs-3" /> Stop parsing models
                        </button>
                    </template>
                    <template v-else-if="parserData?.parser?.status == 0">
                        <button @click="runParsingModels" type="button" class="btn btn-sm btn-light-primary ms-1">
                            <KTIcon icon-name="rocket" icon-class="fs-3" /> Run parsing models
                        </button>
                    </template>
                    <template v-if="parserData?.parserUpdating?.status == 1">
                        <button @click="stopUpdatingModels" type="button" class="btn btn-sm btn-light-primary ms-1">
                            <KTIcon icon-name="minus-circle" icon-class="fs-3" /> Stop updating models
                        </button>
                    </template>
                    <template v-else-if="parserData?.parserUpdating?.status == 0">
                        <button @click="runUpdatingModels" type="button" class="btn btn-sm btn-light-primary ms-1">
                            <KTIcon icon-name="rocket" icon-class="fs-3" /> Run updating models
                        </button>
                    </template>
                    <template v-if="parserData?.parserCheckingRegulars?.status == 1">
                        <button @click="stopCheckingRegulars" type="button" class="btn btn-sm btn-light-primary ms-1">
                            <KTIcon icon-name="minus-circle" icon-class="fs-3" /> Stop checking regulars
                        </button>
                    </template>
                    <template v-else-if="parserData?.parserCheckingRegulars?.status == 0">
                        <button @click="runCheckingRegulars" type="button" class="btn btn-sm btn-light-primary ms-1">
                            <KTIcon icon-name="rocket" icon-class="fs-3" /> Run checking regulars
                        </button>
                    </template>
                    <button @click="syncParserStatuses" type="button" class="btn btn-sm btn-primary ms-1">
                        <KTIcon icon-name="arrows-circle" icon-class="fs-3" :class="{'rotating': isSyncing }"/> Sync
                    </button>
                </div>
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
</template>

<script lang="ts">
import {defineComponent} from "vue";
import KTPageTitle from "~/layouts/default-layout/components/toolbar/PageTitle.vue";
import axios from "axios";
import {useDashboardStore} from "~/stores/dashboard";
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";
import {router} from "@inertiajs/vue3";

export default defineComponent({
    name: "layout-toolbar",
    components: {
        KTIcon,
        KTPageTitle,
    },
    data: () => ({
        isSyncing: false,
        toolbarWidthFluid:true,
    }),
    methods: {
        async fetchActiveParsers() {
            await useDashboardStore().fetchActiveParsers()
        },
        async syncParserStatuses() {
            this.isSyncing = true;
            await axios.post(route('admin.sync-parser-statuses'));
            this.isSyncing = false;
            await this.fetchActiveParsers();
            router.reload();
        },
        async runParsingModels() {
            await axios.post(route('admin.start-parsing-models'));
            await this.syncParserStatuses();
        },
        async stopParsingModels() {
            await axios.post(route('admin.stop-parsing-models'));
            await this.syncParserStatuses();
        },
        async runUpdatingModels() {
            await axios.post(route('admin.start-updating-models'));
            await this.syncParserStatuses();
        },
        async stopUpdatingModels() {
            await axios.post(route('admin.stop-updating-models'));
            await this.syncParserStatuses();
        },
        async runCheckingRegulars() {
            await axios.post(route('admin.start-checking-regulars'));
            await this.syncParserStatuses();
        },
        async stopCheckingRegulars() {
            await axios.post(route('admin.stop-checking-regulars'));
            await this.syncParserStatuses();
        }
    },
    computed: {
        parserData() {
            return useDashboardStore().parserData;
        }
    },
});
</script>
<style>
@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
.rotating {
    animation: rotate 2s linear infinite;
}
</style>

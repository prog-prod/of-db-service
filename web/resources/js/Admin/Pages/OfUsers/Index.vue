<script lang="ts">
import {computed, defineComponent, onMounted, ref} from 'vue'
import DefaultLayout from "~/layouts/default-layout/DefaultLayout.vue";
import type {OfUserType} from "@/Admin/Types/OfUserType";
import arraySort from "array-sort";
import type {Sort} from "~/components/kt-datatable/table-partials/models";
import {Link, router} from "@inertiajs/vue3";
import {RouterService} from "~/services/RouterService";
import route from "../../../../../vendor/tightenco/ziggy";
import TextShortening from "~/components/ui/TextShortening.vue";
import TheButton from "~/components/ui/TheButton.vue";
import KTIcon from "~/core/helpers/kt-icon/KTIcon.vue";
import KTDatatable from "~/components/kt-datatable/KTDataTable.vue";
import TheDropdownButton from "~/components/ui/TheDropdownButton.vue";
import TheDropdownMenuItem from "~/components/ui/TheDropdownMenuItem.vue";
import {NotifyService} from "~/services/NotifyService";

type PaginatorInterface  = {
    data: Array<OfUserType>;
    meta: {
        total: number;
    }
}

export default defineComponent({
    name: "Index",
    layout: [DefaultLayout],
    components: {
        TextShortening,
        TheDropdownMenuItem,
        TheDropdownButton,
        TheButton,
        KTIcon,
        KTDatatable,
        Link
    },
    props: {
        ofUsers: {
            type: Object as () => PaginatorInterface,
            required: true
        }
    },
    setup(props) {
        const editUser = ref<number | null>(null);
        const freeTrialLinkModel = ref<number | null>(null);
        const data = computed(() => props.ofUsers.data);
        const deletedModels = ref({});
        const headerConfig = ref([
            {
                columnName: "Avatar",
                columnLabel: "avatar",
                sortEnabled: false,
            },
            {
                columnName: "Name",
                columnLabel: "name",
                sortEnabled: true,
            },
            {
                columnName: "Username",
                columnLabel: "username",
                sortEnabled: true,
            },
            {
                columnName: "About",
                columnLabel: "about",
                sortEnabled: false,
            },
            {
                columnName: "Campaign URL",
                columnLabel: "free_trial_link",
                sortEnabled: true,
            },
            {
                columnName: "Join Date",
                columnLabel: "join_date",
                sortEnabled: true,
            },
            {
                columnName: "Deleted",
                columnLabel: "deleted",
                sortEnabled: true,
            },
        ]);

        const initData = ref<Array<OfUserType>>([]);

        onMounted(() => {
            initData.value.splice(0, props.ofUsers.data.length, ...props.ofUsers.data);
        });

        const selectedIds = ref<Array<number>>([]);
        const deleteFewModels = () => {
            console.log('delete: ', selectedIds)
            RouterService.post(route('admin.of_models.delete-few'), {
                params: {
                    ids: selectedIds.value
                },
                onSuccess: () => {
                    router.reload({ only: ['ofUsers']});
                }
            });
        };
        const deleteModel = async (id: number) => {
            RouterService.delete(route('admin.of_users.destroy', id), {
                onSuccess: () => {
                    router.reload({ only: ['ofUsers']});
                }
            });
        };
        const sort = (sort: Sort) => {
            const reverse: boolean = sort.order === "asc";
            if (sort.label) {
                arraySort(data.value, sort.label, { reverse });
            }
        };
        const onItemSelect = (selectedItems: Array<number>) => {
            selectedIds.value = selectedItems;
        };

        const search = ref<string >(route().params.search as string ?? '');
        const searchItems = () => {
            if (search.value) {
                RouterService.get(route('admin.of-users.index'), {
                    params: {
                        search: search.value
                    },
                });
            }
        };

        const changeCampaignUrl = (ofUser: OfUserType) => {
            freeTrialLinkModel.value = null;
            editUser.value = ofUser.id
        }
        const cancelEditingCampaignUrl = () => {
            freeTrialLinkModel.value = null;
            editUser.value = null;
        }

        const updateModelDeletedAttr = (ofUser: OfUserType) => {
            const deleted = deletedModels.value[ofUser.id] ?? false;
            const confirm = window.confirm('do you want to delete this model?')
            if(confirm) {
                RouterService.post(route('admin.of-users.update', ofUser.id), {
                    params: {
                        deleted
                    },
                    onSuccess: () => {
                        router.reload({only: ['ofUsers']});
                        NotifyService.showSuccess('Of model updated successfully');
                    }
                });
            } else {
                deletedModels.value[ofUser.id] = false;
            }
        }

        const saveCampaignUrl = (ofUser: OfUserType) => {
            RouterService.post(route('admin.of-users.update', ofUser.id), {
                params: {
                    free_trial_link: freeTrialLinkModel.value
                },
                onSuccess: () => {
                    router.reload({ only: ['ofUsers']});
                    NotifyService.showSuccess('Campaign URL updated successfully');
                    cancelEditingCampaignUrl();
                }
            });
        }

        return {
            search,
            editUser,
            deletedModels,
            freeTrialLinkModel,
            cancelEditingCampaignUrl,
            updateModelDeletedAttr,
            searchItems,
            data,
            headerConfig,
            sort,
            onItemSelect,
            selectedIds,
            deleteFewModels,
            changeCampaignUrl,
            saveCampaignUrl,
            deleteModel,
        };
    },
})
</script>

<template>
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <KTIcon
                        icon-name="magnifier"
                        icon-class="fs-1 position-absolute ms-6"
                    />
                    <input
                        v-model="search"
                        @change="searchItems()"
                        type="text"
                        data-kt-subscription-table-filter="search"
                        class="form-control form-control-solid w-250px ps-14"
                        placeholder="Search Models"
                    />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">

                <!--end::Group actions-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
            <KTDatatable
                @on-sort="sort"
                @on-items-select="onItemSelect"
                v-bind="{data}"
                :header="headerConfig"
            >
                <template v-slot:name="{ row: ofModel }">
                    <a
                        href="#"
                        class="text-gray-800 text-hover-primary mb-1"
                    >
                        {{ ofModel?.name }}
                    </a>
                </template>
                <template v-slot:username="{ row: ofModel }">
                    {{ ofModel?.username }}
                </template>
                <template v-slot:deleted="{ row: ofModel }">
                    <input type="checkbox" v-model="deletedModels[ofModel.id]" @change="updateModelDeletedAttr(ofModel)">
                </template>
                <template v-slot:about="{ row: ofModel }">
                    <TextShortening :text="ofModel?.about" with-btn-more/>
                </template>
                <template v-slot:avatar="{ row: ofModel }">
                    <img width="32" height="35" class="rounded-3" :src="ofModel?.avatar" alt=""/>
                </template>
                <template v-slot:free_trial_link="{ row: ofModel }">
                    <div class="d-flex gap-3 text-center">
                        {{ ofModel?.free_trial_link ?? '-' }}
                        <template v-if="editUser !== ofModel.id">
                            <a href="#" @click.prevent="changeCampaignUrl(ofModel)" class="inline-block btn-link"><KTIcon icon-name="pencil" class="text-primary "/></a>
                        </template>
                    </div>
                    <template v-if="editUser === ofModel.id">
                        <div class="d-flex align-items-center gap-1">
                            <a href="#" @click.prevent="cancelEditingCampaignUrl(ofModel)" class="btn-link"><KTIcon icon-name="cross-circle" class="text-danger"/></a>
                            <input type="text" v-model="freeTrialLinkModel" placeholder="https://" class="form-control form-control-sm"/>
                            <TheButton @click="saveCampaignUrl(ofModel)" class="btn-primary btn-sm">Save</TheButton>
                        </div>
                    </template>

                </template>
                <template v-slot:join_date="{ row: ofModel }">
                    {{ ofModel?.join_date }}
                </template>
            </KTDatatable>
        </div>
        <!--end::Card body-->
    </div>
</template>

<style scoped lang="scss">

</style>

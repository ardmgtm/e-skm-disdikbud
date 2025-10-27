<template>

    <Head title="Manajemen E-SKM" />
    <AdminLayout title="Manajemen E-SKM" :breadcrumbs="breadcrumbs">
        <template #action>
            <Button label="Tambah SKM" icon="pi pi-plus" @click="addSkmAction" />
        </template>
        <AppDataTableServer :handler="dtHandler" v-model:selection="selectedData" :filters="filters" data-key="id" size="large"
            empty-message="Tidak ada data SKM.">
            <!-- <template #header-start>
                <Transition name="fadetransition" mode="out-in" appear>
                    <div v-if="selectedData?.length > 0">
                        <div class="border border-gray-300 rounded-lg px-2 flex items-center">
                            <div class="font-bold">
                                <Button icon="pi pi-times" variant="text" severity="secondary"
                                    @click="selectedData = []" rounded />
                                <span>{{ selectedData.length }} selected</span>
                            </div>
                            <Divider layout="vertical"/>
                        </div>
                    </div>
                </Transition>
            </template> -->
            <Column field="id" selectionMode="multiple" headerStyle="width: 3rem" />
            <Column field="name" header="Nama" class="min-w-72" :show-clear-button="false" sortable>
                <template #filter="{ filterModel, filterCallback }">
                    <InputText size="small" v-model="filterModel.value" type="text" @change="filterCallback()" fluid />
                </template>
                <template #body="slotProps">
                    <Link :href="route('skm.show', slotProps.data.uuid)">
                    <Button variant="link" class="font-bold flex items-center gap-2">
                        {{ slotProps.data.name }}
                        <i class="pi pi-external-link text-sm"></i>
                    </Button>
                    </Link>
                </template>
            </Column>
            <Column field="start_date" header="Periode Mulai" class="max-w-24" sortable data-type="date">
                <template #body="slotProps">
                    {{ new Date(slotProps.data.start_date).toLocaleDateString('id-ID', {
                        day: '2-digit', month: 'short',
                        year: 'numeric', timeZone: 'Asia/Makassar'
                    }) }}
                </template>
            </Column>
            <Column field="end_date" header="Periode Selesai" class="max-w-24" sortable data-type="date">
                <template #body="slotProps">
                    {{ new Date(slotProps.data.end_date).toLocaleDateString('id-ID', {
                        day: '2-digit', month: 'short',
                        year: 'numeric', timeZone: 'Asia/Makassar'
                    }) }}
                </template>
            </Column>
            <Column field="is_published" header="Status" class="w-24 text-center" :showFilterMenu="false">
                <template #body="slotProps">
                    <Tag icon="pi pi-circle-fill" :severity="slotProps.data.is_published ? 'success' : 'danger'"
                        :value="slotProps.data.is_published ? 'Dipublikasikan' : 'Draft'" />
                </template>
            </Column>
            <Column field="id" class="w-16" header="Action">
                <template #body="slotProps">
                    <div class="flex gap-2">
                        <Button icon="pi pi-pen-to-square" severity="info"
                            label="Edit" size="small" @click="editSkmAction(slotProps.data)" />
                        <Button icon="pi pi-trash" severity="danger" label="Delete"
                            size="small" @click="deleteSkmAction(slotProps.data)" />
                    </div>
                </template>
            </Column>
        </AppDataTableServer>
    </AdminLayout>
    <SkmHeaderFormModal ref="skmHeaderFormModalRef" @data-created="refreshData" @data-updated="refreshData"
        @data-deleted="refreshData" />
</template>
<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, Ref } from 'vue';
import { MenuItem } from 'primevue/menuitem';
import SkmHeaderFormModal from './Components/SkmHeaderFormModal.vue';
import { DataTableFilterMetaData, useToast } from 'primevue';
import { createDataTableHandler } from '@/Core/Handlers/data-table-handler';
import AppDataTableServer from '@/Components/AppDataTable/AppDataTableServer.vue';
import { FormModalExpose } from '@/Core/Models/form-modal';
import axios from 'axios';

const breadcrumbs: Ref<MenuItem[]> = ref([
    {
        label: 'E-SKM',
        url: route('skm.browse'),
    }
])

const skmHeaderFormModalRef = ref<FormModalExpose<any>>();
const selectedRowData = ref<any>();

const refreshData = () => {
    dtHandler.loadData();
    selectedData.value = null;
}

const addSkmAction = () => skmHeaderFormModalRef.value?.addAction();
const editSkmAction = (data: any) => {
    if (data) {
        skmHeaderFormModalRef.value?.editAction(data);
    }
};
const deleteSkmAction = (data: any) => {
    if (data) {
        skmHeaderFormModalRef.value?.deleteAction(data);
    }
}

// Datatable
const selectedData = ref();
const dtHandler = createDataTableHandler(route('skm.data_table'));

const filters: Ref<{ [key: string]: DataTableFilterMetaData }> = ref({
    '__global': { value: null, matchMode: 'contains' },
    'name': { value: null, matchMode: 'contains' },
    'title': { value: null, matchMode: 'contains' },
    'start_date': { value: null, matchMode: 'contains' },
    'end_date': { value: null, matchMode: 'contains' },
    'is_published': { value: null, matchMode: 'equals' },
});
</script>
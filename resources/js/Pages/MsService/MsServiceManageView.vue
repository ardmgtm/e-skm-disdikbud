<template>
    <Head title="Manajemen Layanan" />
    <AdminLayout title="Manajemen Layanan" :breadcrumbs>
        <template #action>
            <Button label="Tambah Layanan" icon="pi pi-plus" @click="addServiceAction" />
        </template>
        <AppDataTableServer :handler="dtHandler" v-model:selection="selectedData" :filters="filters" data-key="id" filter-display="row"
            empty-message="Tidak ada data layanan.">
            <template #header-start>
                <Transition name="fadetransition" mode="out-in" appear>
                    <div v-if="selectedData?.length > 0">
                        <div class="border border-gray-300 rounded-lg px-2 flex items-center">
                            <div class="font-bold">
                                <Button icon="pi pi-times" variant="text" severity="secondary"
                                    @click="selectedData = []" rounded />
                                <span>{{ selectedData.length }} selected</span>
                            </div>
                        </div>
                    </div>
                </Transition>
            </template>
            <Column field="id" selectionMode="multiple" headerStyle="width: 3rem" />
            <Column field="service_name" header="Nama Layanan" class="min-w-72" :show-clear-button="false" sortable>
                <template #filter="{ filterModel, filterCallback }">
                    <InputText size="small" v-model="filterModel.value" type="text" @change="filterCallback()" fluid />
                </template>
            </Column>
            <Column field="id" class="w-16">
                <template #body="slotProps">
                    <div class="flex gap-2">
                        <Button icon="pi pi-ellipsis-v" severity="secondary" variant="text" rounded
                            @click="(e) => { ($refs.op as any).toggle(e); selectedRowData = slotProps.data; }" />
                    </div>
                </template>
            </Column>
        </AppDataTableServer>
        <Popover ref="op">
            <div class="flex flex-col gap-1 w-48" @click="($refs.op as any).hide()">
                <span class="font-bold">Options</span>
                <Button icon="pi pi-pen-to-square" severity="secondary" variant="text" class="w-full flex justify-start"
                    label="Edit" size="small" @click="editServiceAction" />
                <Button icon="pi pi-trash" severity="danger" variant="text" class="w-full flex justify-start"
                    label="Delete" size="small" @click="deleteServiceAction" />
            </div>
        </Popover>
    </AdminLayout>
    <ServiceFormModal ref="serviceFormModalRef" @data-created="refreshData" @data-updated="refreshData"
        @data-deleted="refreshData" />
</template>
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { FilterMatchMode } from '@primevue/core/api';
import { ref, Ref } from 'vue';
import { MenuItem } from 'primevue/menuitem';
import ServiceFormModal from './Components/ServiceFormModal.vue';
import { DataTableFilterMetaData, useToast } from 'primevue';
import { createDataTableHandler } from '@/Core/Handlers/data-table-handler';
import AppDataTableServer from '@/Components/AppDataTable/AppDataTableServer.vue';
import { FormModalExpose } from '@/Core/Models/form-modal';
import axios from 'axios';

const breadcrumbs: Ref<MenuItem[]> = ref([
    {
        label: 'Konfigurasi SKM',
        url: route('service.browse'),
    }
])

const serviceFormModalRef = ref<FormModalExpose<any>>();
const selectedRowData = ref<any>();

const refreshData = () => {
    dtHandler.loadData();
    selectedData.value = null;
}

const addServiceAction = () => serviceFormModalRef.value?.addAction();
const editServiceAction = () => {
    if (selectedRowData.value) {
        serviceFormModalRef.value?.editAction(selectedRowData.value);
    }
};
const deleteServiceAction = () => {
    if (selectedRowData.value) {
        serviceFormModalRef.value?.deleteAction(selectedRowData.value);
    }
}

// Datatable
const selectedData = ref();
const dtHandler = createDataTableHandler(route('service.data_table'));

const filters: Ref<{ [key: string]: DataTableFilterMetaData }> = ref({
    '__global': { value: null, matchMode: FilterMatchMode.CONTAINS },
    'service_name': { value: null, matchMode: FilterMatchMode.CONTAINS },
});
</script>

<template>
    <div class="mb-2">
        <Button label="Tambah Layanan" icon="pi pi-plus" severity="primary" size="small" @click="addService" />
    </div>
    <div class="flex flex-col min-h-96" v-if="(modelValue.length > 0)">
        <div class="flex-1 flex flex-col gap-2">
            <div v-for="service in paginatedServices" :key="service.id"
                class="p-2 rounded-lg border border-gray-300 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="pi pi-users"></i>
                    <p>{{ service.service_name }}</p>
                </div>
                <div class="flex">
                    <Button icon="pi pi-pencil" label="Edit" variant="text" severity="contrast" size="small"
                        @click="editService(service)" />
                    <Button icon="pi pi-trash" label="Hapus" variant="text" severity="danger" size="small"
                        @click="deleteService(service)" />
                </div>
            </div>
        </div>
        <div class="flex-none">
            <Paginator :rows="rows" :totalRecords="modelValue.length" :first="first" @page="onPageChange" />
        </div>
    </div>
    <div v-else class="py-5 flex items-center justify-center">
        <p class="text-gray-500 italic">Tidak ada layanan yang tersedia. Silahkan tambahkan layanan baru.</p>
    </div>
    <ServiceFormModal ref="serviceFormModalRef"
        @data-created="onServiceCreated"
        @data-updated="onServiceUpdated"
    />
</template>
<script setup lang="ts">
import { ref, computed } from 'vue';
import ServiceFormModal from './ServiceFormModal.vue';

interface Service {
    id: number;
    service_name: string;
}

const props = defineProps<{
    modelValue: Service[]
}>();
const emits = defineEmits(['update:modelValue']);

const serviceFormModalRef = ref<any>();
const first = ref(0);
const rows = ref(5);

// Use modelValue for v-model
const modelValue = computed({
    get: () => props.modelValue,
    set: (val: Service[]) => emits('update:modelValue', val)
});

const paginatedServices = computed(() => {
    const start = first.value;
    const end = start + rows.value;
    return modelValue.value.slice(start, end);
});

function onPageChange(event: any) {
    first.value = event.first;
    rows.value = event.rows;
}

function addService() {
    serviceFormModalRef.value?.addAction();
}
function editService(service: Service) {
    serviceFormModalRef.value?.editAction(service);
}
function deleteService(service: Service) {
    const index = modelValue.value.indexOf(service);
    if (index !== -1) {
        const updated = modelValue.value.slice(0, index).concat(modelValue.value.slice(index + 1));
        modelValue.value = updated;
    }
}

function onServiceCreated(newService: Service) {
    modelValue.value = [...modelValue.value, newService];
}
function onServiceUpdated(updatedService: Service) {
    const idx = modelValue.value.findIndex(s => s.id === updatedService.id);
    if (idx !== -1) {
        const updated = [...modelValue.value];
        updated[idx] = { ...updatedService };
        modelValue.value = updated;
    }
}
</script>

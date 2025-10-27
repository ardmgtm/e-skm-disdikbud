<template>
    <AppForm v-model="formSurvey" label-position="top">
        <div class="flex gap-4 items-start">
            <div class="flex-1">
                <h2 class="text-xl font-bold">Informasi Umum</h2>
                <Divider />
                <AppFormField label="Judul Survei" required>
                    <AppFormInput v-model="formSurvey.title" type="text" placeholder="Masukkan judul survei" :disabled="props.headerData.is_published" />
                </AppFormField>
                <AppFormField label="Kata Pengantar" required>
                    <Textarea v-model="formSurvey.description" placeholder="Masukkan Kata Pengantar" fluid rows="6" :disabled="props.headerData.is_published" />
                </AppFormField>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold">Daftar Layanan</h2>
                <Divider />
                <ServiceForm v-model="formSurvey.services" field-name="service_name" :disabled="props.headerData.is_published" />
            </div>
        </div>
    </AppForm>
    <Divider />
    <div class="w-full flex justify-end">
        <Button label="Simpan Perubahan" icon="pi pi-save" @click="saveOrUpdate" :loading="loading" v-if="!props.headerData.is_published" />
    </div>
</template>
<script setup lang="ts">
// Interface sesuai migration ms_service
export interface Service {
    id?: number;
    service_name: string;
}
import { reactive, ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';


const toast = useToast();
const loading = ref(false);

function saveOrUpdate() {
    if (props.headerData.is_published) return;
    loading.value = true;
    const payload = {
        title: formSurvey.title,
        description: formSurvey.description,
        services: formSurvey.services.map((s: any) => ({
            id: s.id ?? null,
            service_name: s.service_name,
        })),
    };
    const routeName = 'skm.save_detail';
    axios.post(route(routeName, props.headerData?.id), payload)
        .then(() => {
            toast.add({
                severity: 'success',
                summary: 'Berhasil',
                detail: 'Data SKM berhasil disimpan.',
                life: 3000,
            });
        })
        .catch(() => {
            toast.add({
                severity: 'error',
                summary: 'Gagal',
                detail: 'Gagal menyimpan data SKM.',
                life: 3000,
            });
        })
        .finally(() => {
            loading.value = false;
        });
}
import AppForm from '@/Components/AppForm/AppForm.vue';
import AppFormField from '@/Components/AppForm/AppFormField.vue';
import AppFormInput from '@/Components/AppForm/AppFormInput.vue';
import ServiceForm from './ServiceForm.vue';

const props = defineProps<{ headerData: any, skmIndicators: any[] }>();

const formSurvey = reactive({
    title: props.headerData?.title ?? '',
    description: props.headerData?.description ?? '',
    services: (props.headerData?.services ?? []).map((s: any) => ({
        id: s.id ?? null,
        service_name: s.service_name ?? '',
    })),
});

</script>
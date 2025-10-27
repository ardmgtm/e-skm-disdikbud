<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Daftar Responden Survei</h2>
        <Divider />
        <AppDataTableServer
            :handler="dtHandler"
            data-key="id"
            empty-message="Tidak ada data responden."
        >
            <Column field="timestamps" header="Waktu Submit">
                <template #body="slotProps">
                    <span>{{ formatDateTime(slotProps.data.timestamps) }}</span>
                </template>
            </Column>
            <Column field="service.service_name" header="Nama Layanan" />
            <Column field="respondent.name_occupation" header="Pekerjaan Responden" />
            <Column field="respondent.gender" header="Jenis Kelamin Responden">
                <template #body="slotProps">
                    <span>{{ slotProps.data.respondent?.gender === true || slotProps.data.respondent?.gender === 1 ? 'Laki-laki' : 'Perempuan' }}</span>
                </template>
            </Column>
            <Column field="respondent.age" header="Usia Responden" />
        </AppDataTableServer>
    </div>
</template>
<script setup lang="ts">
import Divider from 'primevue/divider';
import AppDataTableServer from '@/Components/AppDataTable/AppDataTableServer.vue';
import Column from 'primevue/column';
import { createDataTableHandler } from '@/Core/Handlers/data-table-handler';
import { formatDateTime } from '@/Core/Utils/datetime-util';

const props = defineProps<{ skmHeaderId: number | string }>();
const dtHandler = createDataTableHandler(route('skm.respondents', props.skmHeaderId));
</script>

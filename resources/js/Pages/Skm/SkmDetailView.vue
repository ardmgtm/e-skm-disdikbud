<template>

    <Head title="E-SKM" />
    <AdminLayout :title="headerData.name" :breadcrumbs="breadcrumbs">
        <template #title>
            <div class="flex flex-col items-start">
                <div>
                    <div>{{ headerData.name }}</div>
                    <div class="text-lg text-gray-500">
                        {{ new Date(headerData.start_date).toLocaleDateString('id-ID', {
                            day: '2-digit', month: 'short',
                            year: 'numeric', timeZone: 'Asia/Makassar'
                        }) }}
                        -
                        {{ new Date(headerData.end_date).toLocaleDateString('id-ID', {
                            day: '2-digit', month: 'short',
                            year: 'numeric', timeZone: 'Asia/Makassar'
                        }) }}
                    </div>
                </div>
                <div>
                    <span class="px-3 py-1 rounded font-semibold text-sm"
                        :class="headerData.is_published ? 'bg-green-500' : 'bg-yellow-500'">
                        {{ headerData.is_published ? 'Publish' : 'Draft' }}
                    </span>
                </div>
            </div>
        </template>
        <template #action>
            <div class="flex justify-end mb-4 gap-2">
                <Button label="Publish" icon="pi pi-check" severity="success" size="small"
                    @click.prevent="confirmPublish" v-if="!headerData.is_published" />
                <Link :href="route('skm.preview', headerData.uuid)">
                <Button label="Pratinjau Survei" icon="pi pi-eye" size="small" />
                </Link>
            </div>
        </template>
        <div class="flex items-center gap-2 mb-6 bg-gray-50 border border-gray-200 rounded px-4 py-3" v-if="headerData.is_published">
            <span class="text-sm font-medium mr-2">Link Survei:</span>
            <InputText :value="surveyLink" readonly class="w-full max-w-xs bg-white border-gray-300 rounded"
                style="font-size: 0.95rem;" />
            <Button label="Copy" icon="pi pi-copy" size="small" @click="copySurveyLink" />
            <Button label="Buka" icon="pi pi-external-link" size="small" class="ml-1" @click="onOpenSurveyLink" />
        </div>
        <Tabs value="0">
            <TabList>
                <Tab value="0">Kelola Template SKM</Tab>
                <Tab value="1">Hasil Responden</Tab>
                <Tab value="2">Laporan</Tab>
            </TabList>
            <TabPanels>
                <TabPanel value="0">
                    <div v-if="headerData.is_published" class="mb-4">
                        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-2 rounded">
                            <b>Informasi:</b> SKM sudah dipublish dan tidak dapat diedit.
                        </div>
                    </div>
                    <SurveyTemplateManage :header-data="headerData" :skm-indicators="skmIndicators" />
                </TabPanel>
                <TabPanel value="1">
                    <template v-if="!headerData.is_published">
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-2 rounded mb-4">
                            <b>Informasi:</b> Data responden belum tersedia karena status SKM masih <b>Draft</b> atau
                            belum dipublish.
                        </div>
                    </template>
                    <template v-else>
                        <SurveyRespondent :skm-header-id="headerData.id" />
                    </template>
                </TabPanel>
                <TabPanel value="2">
                    <template v-if="!headerData.is_published">
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-2 rounded mb-4">
                            <b>Informasi:</b> Laporan belum tersedia karena status SKM masih <b>Draft</b> atau belum
                            dipublish.
                        </div>
                    </template>
                    <template v-else>
                        <SkmDashboardReport :skm-header-id="headerData.id" />
                    </template>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </AdminLayout>
</template>
<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import SurveyTemplateManage from './Components/SurveyTemplateManage.vue';
import axios from 'axios';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import SurveyRespondent from './Components/SurveyRespondent.vue';
import SkmDashboardReport from './Components/SkmDashboardReport.vue';

const headerData = ref(usePage().props.skm_header) as any;
const breadcrumbs = ref([
    {
        label: 'E-SKM',
        url: route('skm.browse'),
    },
    {
        label: headerData.value.name,
        url: route('skm.show', headerData.value.uuid),
    }
]);

const skmIndicators = ref(usePage().props.skm_indicators as any[]);

const toast = useToast();
const confirm = useConfirm();

function confirmPublish() {
    confirm.require({
        message: 'Apakah Anda yakin ingin mempublikasikan SKM ini ? Data yang sudah dipublish tidak dapat diubah kembali.',
        header: 'Konfirmasi Publish',
        icon: 'pi pi-exclamation-triangle',
        acceptLabel: 'Ya, Publish',
        accept: async () => {
            try {
                await axios.post(route('skm.publish', headerData.value.uuid));
                headerData.value.status = 'publish';
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'SKM berhasil dipublish', life: 3000 });
            } catch (e) {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Gagal mempublikasikan SKM', life: 3000 });
            }
            window.location.reload();
        },
        rejectProps: {
            label: 'Batalkan',
            severity: 'danger'
        },
        reject: () => {
            // Tidak melakukan apa-apa jika dibatalkan
        }
    });
}

const surveyLink = `${window.location.origin}/skm/${headerData.value.uuid}`;

function copySurveyLink() {
    navigator.clipboard.writeText(surveyLink);
    toast.add({ severity: 'info', summary: 'Tersalin', detail: 'Link survei berhasil dicopy', life: 2000 });
}

function onOpenSurveyLink() {
    window.open(surveyLink, '_blank');
}
</script>
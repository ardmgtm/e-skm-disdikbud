<template>

    <Head title="Survei E-SKM" />
    <div class="max-w-2xl mx-auto mt-8 p-4 flex flex-col justify-center items-center text-center">
        <img src="/images/logo.png" class="w-20" />
        <h1 class="text-2xl font-bold">
            {{ skmHeader.title || 'Survei Kepuasan Masyarakat Dinas Pendidikan dan Kebudayaan Kota Bontang' }}
        </h1>
        <div v-if="isPreview"
            class="w-full bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-2 rounded mt-4">
            <b>Pratinjau Survei</b> - Data yang diisi tidak akan dikirim atau disimpan.
        </div>
    </div>
    <div class="max-w-2xl mx-auto my-8 p-4 rounded-lg border border-gray-300 shadow-lg bg-white">
        <Steps :model="steps" :activeStep="activeStep" class="mb-6" />
        <div v-if="activeStep === 0">
            <Card>
                <template #content>
                    <div class="p-4 text-lg leading-relaxed">
                        <p v-html="(skmHeader.description || 'Selamat datang di Survei Kepuasan Masyarakat (SKM) Dinas Pendidikan dan Kebudayaan. Survei ini bertujuan untuk mengukur tingkat kepuasan Anda terhadap layanan yang kami berikan. Mohon isi survei ini dengan jujur dan objektif. Data Anda akan dijaga kerahasiaannya dan hanya digunakan untuk perbaikan layanan.').replace(/\n/g, '<br>')"></p>
                        <p class="mt-4">Klik <b>Berikutnya</b> untuk memulai.</p>
                    </div>
                </template>
            </Card>
        </div>
        <div v-else-if="activeStep === 1">
            <Card>
                <template #title>Data Diri</template>
                <template #content>
                    <AppForm v-model="formData" label-position="top">
                        <AppFormField label="Jenis Kelamin" required>
                            <div class="flex gap-4">
                                <RadioButton v-model="formData.respondent.gender" inputId="jk-l" :value="true" />
                                <label for="jk-l">Laki-laki</label>
                                <RadioButton v-model="formData.respondent.gender" inputId="jk-p" :value="false" />
                                <label for="jk-p">Perempuan</label>
                            </div>
                        </AppFormField>
                        <AppFormField label="Usia" required>
                            <AppFormInput v-model="formData.respondent.age" type="number" min="1" max="120"
                                placeholder="Usia" />
                        </AppFormField>
                        <AppFormField label="Pendidikan Terakhir" required>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_education" inputId="pend-sd"
                                        value="SD" />
                                    <label for="pend-sd">SD</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_education" inputId="pend-smp"
                                        value="SMP" />
                                    <label for="pend-smp">SMP</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_education" inputId="pend-sma"
                                        value="SMA" />
                                    <label for="pend-sma">SMA</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_education" inputId="pend-d1d3"
                                        value="D1-D3" />
                                    <label for="pend-d1d3">D1-D3</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_education" inputId="pend-d4s1"
                                        value="D4/S1" />
                                    <label for="pend-d4s1">D4/S1</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_education" inputId="pend-s2s3"
                                        value="S2-S3" />
                                    <label for="pend-s2s3">S2-S3</label>
                                </div>
                            </div>
                        </AppFormField>
                        <AppFormField label="Pekerjaan" required>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_occupation" inputId="kerja-pns"
                                        value="PNS" />
                                    <label for="kerja-pns">PNS</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_occupation" inputId="kerja-tni"
                                        value="TNI/Polri" />
                                    <label for="kerja-tni">TNI/Polri</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_occupation" inputId="kerja-swasta"
                                        value="Peg. Swasta" />
                                    <label for="kerja-swasta">Peg. Swasta</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_occupation" inputId="kerja-wira"
                                        value="Wiraswasta" />
                                    <label for="kerja-wira">Wiraswasta</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_occupation" inputId="kerja-pelajar"
                                        value="Pelajar/Mahasiswa" />
                                    <label for="kerja-pelajar">Pelajar/Mahasiswa</label>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <RadioButton v-model="formData.respondent.name_occupation" inputId="kerja-other"
                                        value="Other" />
                                    <label for="kerja-other">Other:</label>
                                </div>
                                <AppFormInput v-if="formData.respondent.name_occupation === 'Other'"
                                    v-model="formData.respondent.name_occupation_other" type="text"
                                    placeholder="Sebutkan pekerjaan lain..." />
                            </div>
                        </AppFormField>
                    </AppForm>
                </template>
            </Card>
        </div>
        <div v-else-if="activeStep === 2">
            <Card>
                <template #title>Pilih Layanan</template>
                <template #content>
                    <div class="flex flex-col gap-2">
                        <div v-for="service in services" :key="service.id"
                            class="border rounded p-2 flex items-center gap-2">
                            <RadioButton v-model="formData.resultHeader.id_service" :inputId="'service-' + service.id"
                                :value="service.id" />
                            <label :for="'service-' + service.id">{{ service.service_name }}</label>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
        <div v-else-if="activeStep === 3">
            <Card>
                <template #content>
                    <div class="flex flex-col gap-4">
                        <div v-for="(question, idx) in questions" :key="question.id" class="mb-6">
                            <div class="font-semibold mb-1">{{ idx + 1 }}. {{ question.text }}</div>
                            <div class="flex items-center gap-4 mb-2">
                                <div class="flex flex-col gap-2">
                                    <template v-for="(option, optIdx) in question.options" :key="optIdx">
                                        <div class="flex gap-2 items-center">
                                            <RadioButton v-model="formData.answers[question.id].value"
                                                :inputId="'q' + question.id + '-opt' + optIdx" :value="optIdx + 1" />
                                            <label :for="'q' + question.id + '-opt' + optIdx">{{ option }}</label>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div v-if="formData.answers[question.id]?.value === 1 || formData.answers[question.id]?.value === 2"
                                class="text-sm text-red-600 font-semibold">{{ question.explanationLabel }}</div>
                            <div
                                v-if="formData.answers[question.id]?.value === 1 || formData.answers[question.id]?.value === 2">
                                <AppFormInput v-model="formData.answers[question.id].feedback" type="text"
                                    placeholder="Penjelasan..." />
                            </div>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
        <div v-else-if="activeStep === 4">
            <Card>
                <template #title>Terima Kasih</template>
                <template #content>
                    <div class="text-center py-8">
                        <i class="pi pi-check-circle text-green-500 text-4xl mb-4"></i>
                        <h2 class="tex`t-2xl font-bold mb-2">Terima kasih telah mengisi survei!</h2>
                        <p>Jawaban Anda telah direkam. Silakan tutup halaman ini.</p>
                    </div>
                </template>
            </Card>
        </div>
        <div class="flex justify-between mt-6">
            <Button label="Sebelumnya" icon="pi pi-arrow-left" :disabled="activeStep === 0 || activeStep === 4"
                @click="prevStep" v-if="activeStep < 4" />
            <Button v-if="activeStep < 3" label="Berikutnya" icon="pi pi-arrow-right" iconPos="right" @click="nextStep"
                :disabled="!canProceed" />
            <Button v-else-if="activeStep === 3" label="Submit" icon="pi pi-check" severity="success"
                :loading="loadingSubmit" @click="submitSurvey" :disabled="!canSubmit" />
        </div>
    </div>
</template>
<script setup lang="ts">
import { ref, computed, reactive } from 'vue';

// Migration-based interfaces
export interface SkmResultHeader {
    id?: number;
    id_skm_header: number;
    id_service: number | null;
    notes?: string;
    timestamps?: string;
}

export interface Respondent {
    id?: number;
    id_skm_result_header?: number;
    gender: boolean | null;
    age: number | null;
    id_education?: number | null;
    name_education?: string;
    id_occupation?: number | null;
    name_occupation: string;
    name_occupation_other?: string;
}

export interface SkmResultAnswer {
    id?: number;
    id_skm_result_header?: number;
    id_skm_question: number;
    desc_skm_question: string;
    id_skm_answer?: number | null;
    desc_skm_answer?: string;
    id_skm_indicator?: number | null;
    desc_skm_indicator?: string;
    value: number;
    feedback?: string;
}
import AppForm from '@/Components/AppForm/AppForm.vue';
import AppFormField from '@/Components/AppForm/AppFormField.vue';
import AppFormInput from '@/Components/AppForm/AppFormInput.vue';
import Card from 'primevue/card';
import Steps from 'primevue/steps';
import Button from 'primevue/button';
import RadioButton from 'primevue/radiobutton';
import Rating from 'primevue/rating';
import { Head, usePage } from '@inertiajs/vue3';

const skmHeader = ref(usePage().props.skm_header as any);
const loadingSubmit = ref(false);
const isPreview = ref(usePage().props.is_preview as boolean);

const activeStep = ref(0);
const steps = [
    { label: 'Pengantar' },
    { label: 'Data Diri' },
    { label: 'Pilih Layanan' },
    { label: 'Isi Survei' },
    { label: 'Selesai' },
];

const formData = reactive({
    respondent: {
        gender: null,
        age: null,
        id_education: null,
        name_education: '',
        id_occupation: null,
        name_occupation: '',
        name_occupation_other: '',
    },
    resultHeader: {
        id_skm_header: skmHeader.value?.id ?? 0,
        id_service: null,
        notes: '',
    },
    answers: {} as Record<number, Partial<SkmResultAnswer>>,
});

// Dummy data, replace with props or API
const services = ref(skmHeader.value.services);
const questions = ref([
    {
        id: 1,
        text: 'Apakah syarat yang harus dipenuhi sesuai dengan persyaratan pelayanan yang diinformasikan/ dipublikasikan?',
        options: ['Tidak Sesuai', 'Kurang Sesuai', 'Sesuai', 'Sangat Sesuai'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu TIDAK SESUAI atau KURANG SESUAI, mohon berikan penjelasan:'
    },
    {
        id: 2,
        text: 'Bagaimana pendapat anda tentang kemudahan prosedur untuk mendapatkan pelayanan?',
        options: ['Berbelit, Tidak Mudah', 'Agak Mudah', 'Mudah', 'Sangat Mudah'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu AGAK MUDAH atau TIDAK MUDAH, mohon berikan penjelasan:'
    },
    {
        id: 3,
        text: 'Bagaimana pendapat anda terkait kecepatan pemberian pelayanan dengan yang diinformasikan/ dipublikasikan?',
        options: ['Tidak Cepat', 'Kurang Cepat', 'Cepat', 'Sangat Cepat'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu TIDAK CEPAT atau KURANG CEPAT, mohon berikan penjelasan:'
    },
    {
        id: 4,
        text: 'Bagaimana pendapat anda tentang kesesuaian/ kewajaran biaya pelayanan yang dibayarkan dengan yang diinformasikan/ dipublikasikan?',
        options: ['Sangat Mahal', 'Cukup Mahal', 'Murah', 'Gratis'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu CUKUP MAHAL atau SANGAT MAHAL, mohon berikan penjelasan:'
    },
    {
        id: 5,
        text: 'Apakah pelayanan yang anda terima sesuai dengan Standar Pelayanan yang Tercantum?',
        options: ['Tidak Sesuai', 'Kurang Sesuai', 'Sesuai', 'Sangat Sesuai'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu TIDAK SESUAI atau KURANG SESUAI, mohon berikan penjelasan:'
    },
    {
        id: 6,
        text: 'Bagaimana pendapat anda tentang kemampuan/ kompetensi petugas dalam memberikan pelayanan?',
        options: ['Tidak Mampu', 'Kurang Mampu', 'Mampu/Kompeten', 'Sangat Mampu dan Terampil'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu TIDAK MAMPU atau KURANG MAMPU, mohon berikan penjelasan:'
    },
    {
        id: 7,
        text: 'Bagaimana pendapat anda dengan tutur kata, sikap, dan perilaku petugas pada saat memberikan pelayanan kepada saudara?',
        options: ['Tidak Sopan dan Ramah', 'Kurang Sopan dan Ramah', 'Sopan dan Ramah', 'Sangat Sopan dan Ramah'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu TIDAK SOPAN atau KURANG RAMAH, mohon berikan penjelasan:'
    },
    {
        id: 8,
        text: 'Bagaimana pendapat anda tentang penanganan pengaduan pengguna layanan?',
        options: ['Tidak Ada', 'Ada, Tetapi Tidak Berfungsi', 'Berfungsi Kurang Maksimal', 'Dikelola Dengan Baik'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu TIDAK ADA atau KURANG BERFUNGSI, mohon berikan penjelasan:'
    },
    {
        id: 9,
        text: 'Bagaimana pendapat anda tentang kualitas sarana dan prasana pada pelayanan ini?',
        options: ['Buruk', 'Cukup', 'Baik', 'Sangat Baik'],
        explanationRequired: [1, 2],
        explanationLabel: 'Jika pilihan Bapak/ Ibu CUKUP atau BURUK, mohon berikan penjelasan:'
    }
]);

// Inisialisasi jawaban survei agar binding tidak error
questions.value.forEach(q => {
    if (!formData.answers[q.id]) {
        formData.answers[q.id] = {
            id_skm_question: q.id,
            desc_skm_question: q.text,
            value: 0,
            feedback: ''
        };
    }
});

const canProceed = computed(() => {
    if (activeStep.value === 0) {
        return true; // Kata pengantar, selalu bisa lanjut
    }
    if (activeStep.value === 1) {
        return formData.respondent.gender !== null &&
            formData.respondent.age !== null &&
            formData.respondent.name_education &&
            formData.respondent.name_occupation &&
            (formData.respondent.name_occupation !== 'Other' || formData.respondent.name_occupation_other);
    }
    if (activeStep.value === 2) {
        return formData.resultHeader.id_service !== null;
    }
    return true;
});

const canSubmit = computed(() => {
    return questions.value.every(q => formData.answers[q.id]?.value);
});

function nextStep() {
    if (activeStep.value < 4 && canProceed.value) {
        activeStep.value++;
    }
}
function prevStep() {
    if (activeStep.value > 0 && activeStep.value < 4) {
        activeStep.value--;
    }
}
function submitSurvey() {
    console.log('Submitting survey data:', formData);
    if (isPreview.value) {
        loadingSubmit.value = true;
        setTimeout(() => {
            activeStep.value = 4;
            loadingSubmit.value = false;
        }, 1200);
        return;
    }
    if (canSubmit.value) {
        loadingSubmit.value = true;
        setTimeout(() => {
            activeStep.value = 4;
            loadingSubmit.value = false;
            // Here you can send formData to backend if needed
        }, 1200);
    }
}
</script>
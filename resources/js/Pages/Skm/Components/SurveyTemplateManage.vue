<template>
    <AppForm v-model="formSurvey" label-position="top">
        <div class="flex gap-4 items-start">
            <div class="flex-1">
                <h2 class="text-xl font-bold">Informasi Umum</h2>
                <Divider />
                <AppFormField label="Judul Survei" required>
                    <AppFormInput v-model="formSurvey.title" type="text" placeholder="Masukkan judul survei" />
                </AppFormField>
                <AppFormField label="Kata Pengantar" required>
                    <Textarea v-model="formSurvey.description" placeholder="Masukkan Kata Pengantar" fluid rows="13" />
                </AppFormField>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold">Daftar Layanan</h2>
                <Divider />
                <ServiceForm v-model="formSurvey.services" />
            </div>
        </div>
        <div>
            <div class="flex-1">
                <h2 class="text-xl font-bold">Daftar Pertanyaan</h2>
                <Divider />
            </div>
        </div>
    </AppForm>
    <Divider />
    <div class="w-full flex justify-end">
        <Button label="Simpan Perubahan" icon="pi pi-save"/>
    </div>
</template>
<script setup lang="ts">
import AppForm from '@/Components/AppForm/AppForm.vue';
import AppFormField from '@/Components/AppForm/AppFormField.vue';
import AppFormInput from '@/Components/AppForm/AppFormInput.vue';
import { reactive } from 'vue';
import ServiceForm from './ServiceForm.vue';

const formSurvey = reactive({
    title: '',
    description: '',
    services: [],
    questions: [],
});

</script>
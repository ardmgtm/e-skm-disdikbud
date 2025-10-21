<template>
    <Dialog :header="(!editMode ? 'Tambah' : 'Edit') + ' SKM'" v-model:visible="dialogVisible" class="w-full max-w-3xl" :draggable="false" modal>
        <AppForm class="flex flex-col gap-2" v-model="formData" v-model:errors="formErrors" :resolver label-position="top"
            @submit="(e) => !editMode ? addSubmitAction(e) : editSubmitAction(e)">
            <AppFormField name="name" label="Nama" required>
                <AppFormInput id="name" placeholder="Judul SKM dan Periode" v-model="formData.name" type="text" />
            </AppFormField>
            <div class="flex gap-2 items-center w-full">
                <div class="flex-1">
                    <AppFormField name="start_date" label="Periode Mulai" required>
                        <DatePicker v-model="formData.start_date" showIcon fluid iconDisplay="input" dateFormat="dd/mm/yy" :timezone="'Asia/Makassar'"/>
                    </AppFormField>
                </div>
                <div class="flex-1">
                    <AppFormField name="end_date" label="Periode Selesai" required>
                        <DatePicker v-model="formData.end_date" showIcon fluid iconDisplay="input" dateFormat="dd/mm/yy" :timezone="'Asia/Makassar'"/>
                    </AppFormField>
                </div>
            </div>
            <div class="flex justify-end w-full gap-2 mt-2">
                <Button label="Cancel" severity="secondary" @click.prevent="closeDialog" />
                <Button :label="!editMode ? 'Create' : 'Update'" severity="primary" type="submit" :loading="loading" />
            </div>
        </AppForm>
    </Dialog>
</template>
<script setup lang="ts">
import { reactive, Ref, ref } from 'vue';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from 'yup';
import { FormSubmitEvent } from '@primevue/forms';
import { useConfirm, useToast } from 'primevue';
import AppForm from '@/Components/AppForm/AppForm.vue';
import AppFormField from '@/Components/AppForm/AppFormField.vue';
import AppFormInput from '@/Components/AppForm/AppFormInput.vue';
import InputSwitch from 'primevue/inputswitch';
import axios from 'axios';

const toast = useToast();
const confirm = useConfirm();

const emit = defineEmits(['data-created', 'data-updated', 'data-deleted']);

const dialogVisible: Ref<boolean> = ref(false);
const editMode: Ref<boolean> = ref(false);
const loading: Ref<boolean> = ref(false);

const formData = reactive({
    id: null,
    name: null,
    start_date: null,
    end_date: null,
});
const formErrors = ref();
const resolver = yupResolver(
    yup.object().shape({
        uuid: yup.string().required('UUID wajib diisi'),
        name: yup.string().required('Nama wajib diisi'),
        title: yup.string().required('Judul wajib diisi'),
        start_date: yup.string().required('Tanggal mulai wajib diisi'),
        end_date: yup.string().required('Tanggal selesai wajib diisi'),
    })
);

function closeDialog() {
    dialogVisible.value = false;
}
function addAction() {
    dialogVisible.value = true;
    editMode.value = false;
    formErrors.value = [];
    formData.id = null;
    formData.name = null;
    formData.start_date = null;
    formData.end_date = null;
}
function addSubmitAction(event: FormSubmitEvent) {
    formErrors.value = {};
    if (event.valid) {
        loading.value = true;
        axios.post(route('skm.create'), formData)
            .then((response) => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: response.data.message,
                    life: 3000,
                });
                closeDialog();
                emit('data-created');
            })
            .catch((error) => {
                formErrors.value = error.response.data.errors;
                toast.add({
                    severity: 'error',
                    summary: 'Failed',
                    detail: error.response.data.message ?? 'Failed to create SKM!',
                    life: 3000,
                });
            })
            .finally(() => {
                loading.value = false;
            });
    }
}
function editAction(data: any) {
    dialogVisible.value = true;
    editMode.value = true;
    formErrors.value = [];
    formData.id = data.id;
    formData.name = data.name;
    formData.start_date = data.start_date;
    formData.end_date = data.end_date;
}
function editSubmitAction(event: FormSubmitEvent) {
    if (event.valid) {
        loading.value = true;
        axios.put(route('skm.update', { id: formData.id }), formData)
            .then((response) => {
                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: response.data.message,
                    life: 3000,
                });
                closeDialog();
                emit('data-updated');
            })
            .catch((error) => {
                formErrors.value = error.response.data.errors;
                if (error.response.data.message) {
                    toast.add({
                        severity: 'error',
                        summary: 'Failed',
                        detail: error.response.data.message,
                        life: 3000,
                    });
                }
            })
            .finally(() => {
                loading.value = false;
            });
    }
}
function deleteAction(data: any) {
    confirm.require({
        message: 'Hapus data SKM ini?',
        header: 'Warning',
        icon: 'pi pi-info-circle',
        rejectLabel: 'Cancel',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Delete',
            severity: 'danger'
        },
        accept: () => {
            loading.value = true;
            axios.delete(route('skm.delete', { id: data.id }))
                .then((response) => {
                    toast.add({
                        severity: 'success',
                        summary: 'Success',
                        detail: response.data.message,
                        life: 3000,
                    });
                    emit('data-deleted');
                })
                .catch((error) => {
                    if (error.response.data.message) {
                        toast.add({
                            severity: 'error',
                            summary: 'Failed',
                            detail: error.response.data.message,
                            life: 3000,
                        });
                    }
                })
                .finally(() => {
                    loading.value = false;
                });
        },
        reject: () => {
            toast.add({
                severity: 'info',
                summary: 'Info',
                detail: 'Action Canceled',
                life: 3000
            });
        }
    });
}

defineExpose({
    addAction,
    editAction,
    deleteAction,
});
</script>

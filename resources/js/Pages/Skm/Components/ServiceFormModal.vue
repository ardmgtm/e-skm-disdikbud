<template>
    <Dialog :header="editMode ? 'Edit Layanan' : 'Tambah Layanan'" v-model:visible="dialogVisible" class="w-full max-w-md" :draggable="false" modal>
        <AppForm class="flex flex-col gap-2" v-model="formData" v-model:errors="formErrors" :resolver label-position="top"
            @submit="(e) => !editMode ? addSubmitAction(e) : editSubmitAction(e)">
            <AppFormField name="service_name" label="Nama Layanan" required>
                <AppFormInput id="service_name" placeholder="Nama Layanan" v-model="formData.service_name" type="text" />
            </AppFormField>
            <div class="flex justify-end w-full gap-2 mt-2">
                <Button label="Cancel" severity="secondary" @click.prevent="closeDialog" />
                <Button :label="!editMode ? 'Create' : 'Update'" severity="primary" type="submit" />
            </div>
        </AppForm>
    </Dialog>
</template>
<script setup lang="ts">
import { reactive, Ref, ref } from 'vue';
import { yupResolver } from '@primevue/forms/resolvers/yup';
import * as yup from 'yup';
import { FormSubmitEvent } from '@primevue/forms';
import AppForm from '@/Components/AppForm/AppForm.vue';
import AppFormField from '@/Components/AppForm/AppFormField.vue';
import AppFormInput from '@/Components/AppForm/AppFormInput.vue';

const emit = defineEmits(['data-created', 'data-updated']);

const dialogVisible: Ref<boolean> = ref(false);
const editMode: Ref<boolean> = ref(false);

const formData = reactive({
    id: null,
    service_name: '',
});
const formErrors = ref();
const resolver = yupResolver(
    yup.object().shape({
        service_name: yup.string().required('Nama layanan wajib diisi'),
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
    formData.service_name = '';
}
function addSubmitAction(event: FormSubmitEvent) {
    formErrors.value = {};
    if (event.valid) {
        closeDialog();
        emit('data-created', { id: formData.id, service_name: formData.service_name });
    }
}
function editAction(data: any) {
    dialogVisible.value = true;
    editMode.value = true;
    formErrors.value = [];
    formData.id = data.id;
    formData.service_name = data.service_name;
}
function editSubmitAction(event: FormSubmitEvent) {
    if (event.valid) {
        closeDialog();
        emit('data-updated', { id: formData.id, service_name: formData.service_name });
    }
}

defineExpose({
    addAction,
    editAction,
});
</script>

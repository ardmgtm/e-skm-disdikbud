<template>
    <Head title="E-SKM" />
    <AdminLayout :title="headerData.name" :breadcrumbs="breadcrumbs">
        <template #title>
            <div class="flex gap-4 items-start">
                <div>
                    <div>{{ headerData.name }}</div>
                    <div class="text-lg text-gray-500">
                        {{ new Date(headerData.start_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', timeZone: 'Asia/Makassar' }) }}
                        -
                        {{ new Date(headerData.end_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', timeZone: 'Asia/Makassar' }) }}
                    </div>
                </div>
                <div>
                    <span class="px-3 py-1 rounded font-semibold text-sm" :class="headerData.status === 'publish' ? 'bg-green-500' : 'bg-yellow-500'">
                        {{ headerData.status === 'publish' ? 'Publish' : 'Draft' }}
                    </span>
                </div>
            </div>
        </template>
        <template #action>
            <div class="flex justify-end mb-4 gap-2">
                <Button label="Publish" icon="pi pi-check" severity="success" size="small"/>
                <Button label="Preview Survei" icon="pi pi-eye" size="small"/>
            </div>
        </template>
        <Tabs value="0">
            <TabList>
                <Tab value="0">Kelola Template SKM</Tab>
                <Tab value="1">Hasil Responden</Tab>
                <Tab value="2">Laporan</Tab>
            </TabList>
            <TabPanels>
                <TabPanel value="0">
                    <p class="m-0">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                        fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                        deserunt mollit anim id est laborum.
                    </p>
                </TabPanel>
                <TabPanel value="1">
                    <p class="m-0">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                        laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto
                        beatae vitae dicta sunt explicabo. Nemo enim
                        ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni
                        dolores eos qui ratione voluptatem sequi nesciunt. Consectetur, adipisci velit, sed quia non
                        numquam eius modi.
                    </p>
                </TabPanel>
                <TabPanel value="2">
                    <p class="m-0">
                        At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum
                        deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non
                        provident, similique sunt in culpa
                        qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum
                        facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio
                        cumque nihil impedit quo minus.
                    </p>
                </TabPanel>
            </TabPanels>
        </Tabs>
    </AdminLayout>
</template>
<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

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
</script>
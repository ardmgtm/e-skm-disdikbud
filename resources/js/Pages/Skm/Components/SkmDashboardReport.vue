<template>
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Dashboard Laporan SKM</h2>
            <Button label="Refresh" icon="pi pi-refresh" size="small" @click="fetchReport" :disabled="loading" />
        </div>
        <Divider />
        <Transition name="fade" mode="out-in">
            <div v-if="loading" class="text-center py-8">
                <ProgressSpinner />
            </div>
            <div v-else>
                <div class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <Card class="shadow-lg bg-primary text-white">
                            <template #content>
                                <div class="flex gap-2 items-center">
                                    <i class="pi pi-file-check text-4xl"></i>
                                    <div class="flex flex-col">
                                        <div class="text-lg">Skor IKM</div>
                                        <div class="text-4xl font-bold">{{ report.ikm ?? '-' }}</div>
                                    </div>
                                </div>
                            </template>
                        </Card>
                        <Card class="shadow-lg bg-green-600 text-white">
                            <template #content>
                                <div class="flex gap-2 items-center">
                                    <i class="pi pi-star text-4xl"></i>
                                    <div class="flex flex-col">
                                        <div class="text-lg">Predikat IKM</div>
                                        <div class="text-2xl font-bold">{{ report.ikm_predikat ?? '-' }}</div>
                                    </div>
                                </div>
                            </template>
                        </Card>
                        <Card class="shadow-lg bg-amber-600 text-white">
                            <template #content>
                                <div class="flex gap-2 items-center">
                                    <i class="pi pi-users text-4xl"></i>
                                    <div class="flex flex-col">
                                        <div class="text-lg">Jumlah Responden</div>
                                        <div class="text-4xl font-bold">{{ report.total_respondents }}</div>
                                    </div>
                                </div>
                            </template>
                        </Card>
                        <Card class="shadow-lg bg-red-600 text-white">
                            <template #content>
                                <div class="flex gap-2 items-center">
                                    <i class="pi pi-clock text-4xl"></i>
                                    <div class="flex flex-col">
                                        <div class="text-lg">Rata-rata Usia</div>
                                        <div class="text-4xl font-bold">{{ report.avg_age }}</div>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </div>
                    <!-- penutup flex flex-col gap-6, sudah ditutup di bawah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
                        <Card class="shadow-lg border-gray-200 border">
                            <template #title>Penilaian Indikator Layanan</template>
                            <template #content>
                                <div class="mb-6">
                                    <Chart type="bar" :data="indicatorChartData" :options="barChartOptions" :height="350" />
                                    <div class="mt-2 flex flex-wrap gap-2 text-xs justify-center">
                                        <span v-for="(item, i) in indicatorChartData.labels" :key="item"
                                            class="flex items-center gap-1">
                                            <span
                                                :style="{ background: indicatorChartData.datasets[0].backgroundColor[i], width: '14px', height: '14px', display: 'inline-block', borderRadius: '3px' }"></span>
                                            {{ item }}
                                        </span>
                                    </div>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border text-sm">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="px-2 py-1 border">Indikator</th>
                                                <th class="px-2 py-1 border">Rata-rata</th>
                                                <th class="px-2 py-1 border">Jumlah Jawaban</th>
                                                <th class="px-2 py-1 border">Skor</th>
                                                <th class="px-2 py-1 border">Predikat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="ind in report.indicator_avg || []" :key="ind.id">
                                                <td class="border px-2 py-1">{{ ind.name }}</td>
                                                <td class="border px-2 py-1 text-center">{{ ind.avg_value }}</td>
                                                <td class="border px-2 py-1 text-center">{{ ind.count }}</td>
                                                <td class="border px-2 py-1 text-center">{{ ind.score }}</td>
                                                <td class="border px-2 py-1 text-center">{{ ind.predikat }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                        </Card>
                        <Card class="shadow-lg border-gray-200 border">
                            <template #title>Rata-rata & Skor per Layanan</template>
                            <template #content>
                                <Chart type="bar" :data="serviceAvgChartData" :options="barChartOptions" :height="350" />
                                <div class="mt-2 flex flex-wrap gap-2 text-xs justify-center">
                                    <span v-for="(item, i) in serviceAvgChartData.labels" :key="item"
                                        class="flex items-center gap-1">
                                        <span
                                            :style="{ background: serviceAvgChartData.datasets[0].backgroundColor[i], width: '14px', height: '14px', display: 'inline-block', borderRadius: '3px' }"></span>
                                        {{ item }}
                                    </span>
                                </div>
                                <div class="overflow-x-auto mt-4">
                                    <table class="min-w-full border text-sm">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="px-2 py-1 border">Layanan</th>
                                                <th class="px-2 py-1 border">Rata-rata</th>
                                                <th class="px-2 py-1 border">Jumlah Responden</th>
                                                <th class="px-2 py-1 border">Skor</th>
                                                <th class="px-2 py-1 border">Predikat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="svc in report.service_avg || []" :key="svc.id">
                                                <td class="border px-2 py-1">{{ svc.name }}</td>
                                                <td class="border px-2 py-1 text-center">{{ svc.avg_value }}</td>
                                                <td class="border px-2 py-1 text-center">{{ svc.count }}</td>
                                                <td class="border px-2 py-1 text-center">{{ svc.score }}</td>
                                                <td class="border px-2 py-1 text-center">{{ svc.predikat }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </template>
                        </Card>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Card class="shadow-lg border-gray-200 border">
                            <template #title>Pendidikan</template>
                            <template #content>
                                <Chart type="bar" :data="educationChartData" :options="barChartOptions" :height="350" />
                                <div class="mt-2 flex flex-wrap gap-2 text-xs justify-center">
                                    <span v-for="(item, i) in educationChartData.labels" :key="item"
                                        class="flex items-center gap-1">
                                        <span
                                            :style="{ background: educationChartData.datasets[0].backgroundColor[i], width: '14px', height: '14px', display: 'inline-block', borderRadius: '3px' }"></span>
                                        {{ item }}
                                    </span>
                                </div>
                            </template>
                        </Card>
                        <Card class="shadow-lg border-gray-200 border">
                            <template #title>Pekerjaan</template>
                            <template #content>
                                <Chart type="bar" :data="occupationChartData" :options="barChartOptions" :height="350" />
                                <div class="mt-2 flex flex-wrap gap-2 text-xs justify-center">
                                    <span v-for="(item, i) in occupationChartData.labels" :key="item"
                                        class="flex items-center gap-1">
                                        <span
                                            :style="{ background: occupationChartData.datasets[0].backgroundColor[i], width: '14px', height: '14px', display: 'inline-block', borderRadius: '3px' }"></span>
                                        {{ item }}
                                    </span>
                                </div>
                            </template>
                        </Card>
                        <Card class="shadow-lg border-gray-200 border">
                            <template #title>Layanan/Service</template>
                            <template #content>
                                <Chart type="bar" :data="serviceChartData" :options="barChartOptions" :height="350" />
                                <div class="mt-2 flex flex-wrap gap-2 text-xs justify-center">
                                    <span v-for="(item, i) in serviceChartData.labels" :key="item"
                                        class="flex items-center gap-1">
                                        <span
                                            :style="{ background: serviceChartData.datasets[0].backgroundColor[i], width: '14px', height: '14px', display: 'inline-block', borderRadius: '3px' }"></span>
                                        {{ item }}
                                    </span>
                                </div>
                            </template>
                        </Card>
                        <!-- <Card class="shadow-lg border-gray-200 border">
                            <template #title>Rasio Jenis Kelamin</template>
                            <template #content>
                                <Chart type="pie" :data="genderChartData" :height="350" />
                            </template>
                        </Card> -->
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>
<script setup lang="ts">
function getColor(index: number) {
    const palette = [
        '#4e79a7', // blue
        '#76b7b2', // teal
        '#59a14f', // green
        '#edc949', // yellow
        '#f28e2b', // orange
        '#e15759', // red
        '#ff9da7', // pink
        '#af7aa1', // purple
        '#9c755f', // brown
        '#bab0ab', // gray
    ];
    return palette[index % palette.length];
}
import { ref, onMounted } from 'vue';
import Divider from 'primevue/divider';
import Card from 'primevue/card';
import Chart from 'primevue/chart';
import ProgressSpinner from 'primevue/progressspinner';
import axios from 'axios';

const props = defineProps<{ skmHeaderId: number | string }>();
const loading = ref(true);
type IndicatorAvg = {
    id: number | string;
    name: string;
    avg_value: number;
    count: number;
    score: number;
    predikat?: string;
};

type ServiceAvg = {
    id: number | string;
    name: string;
    avg_value: number;
    count: number;
    score: number;
    predikat?: string;
};
const report = ref<{
    total_respondents: number;
    avg_age: number;
    gender: { male: number; female: number };
    education: any;
    occupation: any;
    service: any[];
    indicator_avg: IndicatorAvg[];
    service_avg: ServiceAvg[];
    ikm?: number;
    ikm_predikat?: string;
}>({
    total_respondents: 0,
    avg_age: 0,
    gender: { male: 0, female: 0 },
    education: {},
    occupation: {},
    service: [],
    indicator_avg: [],
    service_avg: [],
    ikm: undefined,
    ikm_predikat: undefined,
});
const genderChartData = ref<{ labels: string[]; datasets: { data: number[]; backgroundColor: string[] }[] }>(
    {
        labels: ['Laki-laki', 'Perempuan'],
        datasets: [{ data: [0, 0], backgroundColor: ['#3498db', '#e74c3c'] }],
    }
);
const educationChartData = ref<{ labels: string[]; datasets: { data: number[]; backgroundColor: string[] }[] }>(
    {
        labels: [],
        datasets: [{ data: [], backgroundColor: [] }],
    }
);
const occupationChartData = ref<{ labels: string[]; datasets: { data: number[]; backgroundColor: string[] }[] }>(
    {
        labels: [],
        datasets: [{ data: [], backgroundColor: [] }],
    }
);

const indicatorChartData = ref<{ labels: string[]; datasets: { data: number[]; backgroundColor: string[] }[] }>(
    {
        labels: [],
        datasets: [{ data: [], backgroundColor: [] }],
    }
);
const serviceChartData = ref<{ labels: string[]; datasets: { data: number[]; backgroundColor: string[] }[] }>(
    {
        labels: [],
        datasets: [{ data: [], backgroundColor: [] }],
    }
);
// Chart rata-rata per layanan
const serviceAvgChartData = ref<{ labels: string[]; datasets: { data: number[]; backgroundColor: string[] }[] }>(
    {
        labels: [],
        datasets: [{ data: [], backgroundColor: [] }],
    }
);

const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        },
        title: {
            display: false
        }
    },
    scales: {
        x: {
            grid: {
                display: false
            },
            ticks: {
                display: false,
                color: '#888',
                font: { size: 13 }
            }
        },
        y: {
            beginAtZero: true,
            grid: {
                color: '#eee'
            },
            ticks: {
                color: '#888',
                font: { size: 13 }
            }
        }
    }
};

async function fetchReport() {
    loading.value = true;
    try {
        const res = await axios.get(route('skm.report_dashboard', props.skmHeaderId));
        report.value = res.data.data;
        genderChartData.value.datasets[0].data = [report.value.gender.male, report.value.gender.female];

        // Pendidikan
        const eduArr = Array.isArray(report.value.education) ? report.value.education : [];
        educationChartData.value.labels = eduArr.map(e => e.name);
        educationChartData.value.datasets = [{
            data: eduArr.map(e => e.count),
            backgroundColor: eduArr.map((_, i) => getColor(i)),
        }];

        // Pekerjaan
        const occArr = Array.isArray(report.value.occupation) ? report.value.occupation : [];
        occupationChartData.value.labels = occArr.map(o => o.name);
        occupationChartData.value.datasets = [{
            data: occArr.map(o => o.count),
            backgroundColor: occArr.map((_, i) => getColor(i)),
        }];

        // Layanan/Service
        const svcArr: { name: string; count: number }[] = Array.isArray(report.value.service) ? report.value.service : [];
        serviceChartData.value.labels = svcArr.map((s: { name: string; count: number }) => s.name);
        serviceChartData.value.datasets = [{
            data: svcArr.map((s: { name: string; count: number }) => s.count),
            backgroundColor: svcArr.map((_: any, i: number) => getColor(i)),
        }];

        // Indikator rata-rata
        const indArr: { name: string; avg_value: number; count: number }[] = Array.isArray(report.value.indicator_avg) ? report.value.indicator_avg : [];
        indicatorChartData.value.labels = indArr.map((i: { name: string }) => i.name);
        indicatorChartData.value.datasets = [{
            data: indArr.map((i: { avg_value: number }) => i.avg_value),
            backgroundColor: indArr.map((_, i) => getColor(i)),
        }];
        // Chart rata-rata per layanan
        const svcAvgArr: { name: string; avg_value: number }[] = Array.isArray(report.value.service_avg) ? report.value.service_avg : [];
        serviceAvgChartData.value.labels = svcAvgArr.map(s => s.name);
        serviceAvgChartData.value.datasets = [{
            data: svcAvgArr.map(s => s.avg_value),
            backgroundColor: svcAvgArr.map((_, i) => getColor(i)),
        }];
        // Service rata-rata dan skor
        // Tidak perlu chart, hanya tabel
    } catch (e) {
        // handle error
    } finally {
        loading.value = false;
    }
}

onMounted(fetchReport);
</script>

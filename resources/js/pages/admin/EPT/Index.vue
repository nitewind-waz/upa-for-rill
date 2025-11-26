<script setup>
import { ref, watch, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

// Import PrimeVue Components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast';
import InputNumber from 'primevue/inputnumber';
import Tag from 'primevue/tag';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import { useToast } from "primevue/usetoast";

// Inisialisasi Toast
const toast = useToast();

// Props
const props = defineProps({
    results: Object,
    mahasiswas: Array,
    filters: Object,
});

// --- STATE MANAGEMENT (YANG DIPERBAIKI) ---
// Kita pakai "?." (Optional Chaining) agar kalau data belum ada, tidak error
const lazyParams = ref({
    first: 0,
    rows: props.results?.per_page || 20,
    page: props.results?.current_page || 1,
    sortField: props.filters?.sortField || null,
    sortOrder: props.filters?.sortOrder || null,
    globalFilter: props.filters?.globalFilter || '',
    tahun: props.filters?.tahun || '',
    mahasiswa_id: props.filters?.mahasiswa_id || null,
});

const createDialog = ref(false);
const importDialog = ref(false);

const createForm = useForm({
    mahasiswa_id: null,
    tahun: new Date().getFullYear().toString(),
    listening: null,
    structure: null,
    reading: null,
    total_score: null,
    sertifikat_pdf: null,
});

const importForm = useForm({
    file: null,
});

// Gunakan ?. juga di sini untuk keamanan
const resultsData = computed(() => props.results?.data || []);

// --- LOGIKA UTAMA ---
const loadLazyData = (event) => {
    if (event) {
        lazyParams.value.first = event.first;
        lazyParams.value.rows = event.rows;
        lazyParams.value.page = event.page + 1;
        lazyParams.value.sortField = event.sortField;
        lazyParams.value.sortOrder = event.sortOrder;
    }

    const paramsToSend = {
        page: lazyParams.value.page,
        per_page: lazyParams.value.rows,
        sortField: lazyParams.value.sortField,
        sortOrder: lazyParams.value.sortOrder,
        globalFilter: lazyParams.value.globalFilter,
        tahun: lazyParams.value.tahun,
        mahasiswa_id: lazyParams.value.mahasiswa_id,
    };

    router.get('/admin/ept', paramsToSend, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

watch([
    () => lazyParams.value.globalFilter, 
    () => lazyParams.value.tahun, 
    () => lazyParams.value.mahasiswa_id
], () => {
    lazyParams.value.page = 1;
    lazyParams.value.first = 0; 
    loadLazyData();
});

// --- LOGIKA FORM ---
const openCreateDialog = () => { createDialog.value = true; };
const hideCreateDialog = () => { createDialog.value = false; createForm.reset(); createForm.clearErrors(); };

const openImportDialog = () => { importDialog.value = true; };
const hideImportDialog = () => { importDialog.value = false; importForm.reset(); importForm.clearErrors(); };

const submitCreateForm = () => {
    createForm.post('/admin/ept', {
        onSuccess: () => {
            hideCreateDialog();
            toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Data EPT ditambahkan.', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Gagal', detail: 'Periksa kembali inputan Anda.', life: 3000 });
        }
    });
};

const handleFileChange = (event) => {
    createForm.sertifikat_pdf = event.target.files[0];
};

const handleImportSelect = (event) => {
    importForm.file = event.files[0];
};

const submitImportForm = () => {
    importForm.post('/admin/ept/import', {
        onSuccess: () => {
            hideImportDialog();
            toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Data berhasil diimport.', life: 3000 });
        },
        onError: () => {
            toast.add({ severity: 'error', summary: 'Gagal', detail: 'Gagal import file.', life: 3000 });
        },
        onFinish: () => {
            importForm.processing = false;
        }
    });
};
</script>

<template>
    <AdminLayout title="Hasil EPT">
        <Toast position="top-right" />

        <div class="space-y-6">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                        Hasil EPT Mahasiswa
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Monitoring skor Listening, Structure, dan Reading.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button 
                        label="Import Excel" 
                        icon="pi pi-file-excel" 
                        severity="success" 
                        @click="openImportDialog" 
                        outlined
                    />
                    <Button 
                        label="Input Manual" 
                        icon="pi pi-plus" 
                        @click="openCreateDialog" 
                        severity="primary"
                    />
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5">
                        <IconField iconPosition="left" class="w-full">
                            <InputIcon class="pi pi-search text-slate-400" />
                            <InputText 
                                v-model="lazyParams.globalFilter" 
                                placeholder="Cari Nama atau NIM..." 
                                class="w-full !bg-white" 
                            />
                        </IconField>
                    </div>

                    <div class="md:col-span-3">
                        <InputText 
                            v-model="lazyParams.tahun" 
                            placeholder="Filter Tahun" 
                            class="w-full !bg-white" 
                        />
                    </div>

                    <div class="md:col-span-4">
                         <Dropdown 
                            v-model="lazyParams.mahasiswa_id" 
                            :options="props.mahasiswas" 
                            optionLabel="nama" 
                            optionValue="id" 
                            placeholder="Pilih Mahasiswa" 
                            showClear 
                            filter
                            class="w-full !bg-white"
                        />
                    </div>
                </div>

                <DataTable
                    :value="resultsData"
                    lazy
                    paginator
                    :first="lazyParams.first"
                    :rows="lazyParams.rows"
                    :totalRecords="props.results.total"
                    :loading="createForm.processing || importForm.processing"
                    @page="loadLazyData($event)"
                    @sort="loadLazyData($event)"
                    removableSort
                    stripedRows
                    showGridlines
                    class="p-datatable-sm"
                    tableStyle="min-width: 60rem"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown CurrentPageReport"
                    currentPageReportTemplate="Menampilkan {first} - {last} dari {totalRecords} data"
                    :rowsPerPageOptions="[10, 20, 50]"
                >
                    <template #empty> 
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                <i class="pi pi-database text-slate-300 text-2xl"></i>
                            </div>
                            <p class="text-slate-500 font-medium">Belum ada data EPT yang diupload.</p>
                        </div>
                    </template>

                    <Column field="mahasiswa.nim" header="NIM" sortable style="min-width: 140px">
                        <template #body="{ data }">
                            <div class="flex items-center gap-2">
                                <i class="pi pi-id-card text-slate-400 text-xs"></i>
                                <span class="font-mono text-slate-700 font-medium">{{ data.mahasiswa?.nim }}</span>
                            </div>
                        </template>
                    </Column>
                    
                    <Column field="mahasiswa.nama" header="Nama Mahasiswa" sortable style="min-width: 250px">
                        <template #body="{ data }">
                            <div class="font-semibold text-slate-900">{{ data.mahasiswa?.nama }}</div>
                            <div class="text-xs text-slate-500" v-if="data.mahasiswa?.prodi">
                                {{ data.mahasiswa.prodi }}
                            </div>
                        </template>
                    </Column>
                    
                    <Column field="tahun" header="Tahun" sortable class="text-center" style="width: 100px">
                        <template #body="{ data }">
                            <Tag :value="data.tahun" severity="secondary" class="font-mono" />
                        </template>
                    </Column>
                    
                    <Column field="listening" header="List." sortable style="width: 90px">
                         <template #body="{ data }">
                            <div class="text-center font-medium text-slate-600">{{ data.listening }}</div>
                        </template>
                    </Column>
                    <Column field="structure" header="Str." sortable style="width: 90px">
                         <template #body="{ data }">
                            <div class="text-center font-medium text-slate-600">{{ data.structure }}</div>
                        </template>
                    </Column>
                    <Column field="reading" header="Read." sortable style="width: 90px">
                         <template #body="{ data }">
                            <div class="text-center font-medium text-slate-600">{{ data.reading }}</div>
                        </template>
                    </Column>
                    
                    <Column field="total_score" header="Total" sortable style="width: 120px">
                        <template #body="{ data }">
                            <div class="text-center">
                                <Tag 
                                    :value="data.total_score" 
                                    :severity="data.total_score >= 450 ? 'success' : (data.total_score >= 400 ? 'warning' : 'danger')" 
                                    class="px-3 py-1 text-sm font-bold"
                                    rounded
                                />
                            </div>
                        </template>
                    </Column>

                    <Column header="File" style="width: 80px" alignFrozen="right" frozen>
                        <template #body="{ data }">
                            <div class="flex justify-center">
                                <a v-if="data.sertifikat_pdf" 
                                   :href="`/storage/${data.sertifikat_pdf}`" 
                                   target="_blank" 
                                   class="text-blue-600 hover:text-blue-800 transition-colors"
                                   v-tooltip.left="'Unduh PDF'"
                                >
                                    <i class="pi pi-download text-lg"></i>
                                </a>
                                <span v-else class="text-slate-300 cursor-not-allowed">
                                    <i class="pi pi-minus"></i>
                                </span>
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>

        <Dialog v-model:visible="createDialog" header="Input Data Manual" :style="{ width: '500px' }" modal :draggable="false" class="p-fluid">
            <div class="flex flex-col gap-5 pt-2">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Mahasiswa</label>
                        <Dropdown 
                            v-model="createForm.mahasiswa_id" 
                            :options="props.mahasiswas" 
                            optionLabel="nama" 
                            optionValue="id" 
                            placeholder="Pilih Mahasiswa" 
                            filter 
                            class="w-full" 
                            :invalid="!!createForm.errors.mahasiswa_id"
                        />
                        <small class="text-red-500" v-if="createForm.errors.mahasiswa_id">{{ createForm.errors.mahasiswa_id }}</small>
                    </div>
                    <div class="col-span-2">
                         <label class="mb-2 block text-sm font-medium text-slate-700">Tahun Tes</label>
                        <InputText v-model="createForm.tahun" placeholder="2024" :invalid="!!createForm.errors.tahun" />
                        <small class="text-red-500" v-if="createForm.errors.tahun">{{ createForm.errors.tahun }}</small>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Rincian Nilai</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Listening</label>
                            <InputNumber v-model="createForm.listening" :min="0" :max="677" placeholder="0" inputClass="text-center" :invalid="!!createForm.errors.listening" />
                        </div>
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Structure</label>
                            <InputNumber v-model="createForm.structure" :min="0" :max="677" placeholder="0" inputClass="text-center" :invalid="!!createForm.errors.structure" />
                        </div>
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Reading</label>
                            <InputNumber v-model="createForm.reading" :min="0" :max="677" placeholder="0" inputClass="text-center" :invalid="!!createForm.errors.reading" />
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Total Score (Konversi)</label>
                    <InputNumber v-model="createForm.total_score" :min="310" :max="677" class="w-full" :invalid="!!createForm.errors.total_score" />
                    <small class="text-slate-400 text-xs">Biasanya: ((L+S+R)*10)/3</small>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Upload Sertifikat (PDF)</label>
                    <input type="file" accept="application/pdf" @change="handleFileChange" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    <small class="text-red-500" v-if="createForm.errors.sertifikat_pdf">{{ createForm.errors.sertifikat_pdf }}</small>
                </div>
            </div>

            <template #footer>
                <Button label="Batal" text severity="secondary" @click="hideCreateDialog" />
                <Button label="Simpan Data" @click="submitCreateForm" :loading="createForm.processing" severity="primary" />
            </template>
        </Dialog>

        <Dialog v-model:visible="importDialog" header="Import Data Excel" :style="{ width: '400px' }" modal :draggable="false">
            <div class="flex flex-col items-center justify-center pt-4">
                <i class="pi pi-file-excel text-green-500 text-4xl mb-4"></i>
                <p class="text-center text-slate-600 mb-6 text-sm px-4">
                    Pastikan format Excel sesuai template. Kolom wajib: <strong>nim, nama, jurusan, prodi, kelas</strong>.
                </p>
                
                <FileUpload 
                    mode="basic" 
                    name="file" 
                    accept=".xlsx,.xls,.csv" 
                    :maxFileSize="5000000" 
                    chooseLabel="Pilih File Excel" 
                    class="w-full"
                    :auto="true" 
                    customUpload
                    @uploader="handleImportSelect"
                />
                
                <div v-if="importForm.file" class="mt-4 flex items-center gap-2 bg-green-50 text-green-700 px-3 py-2 rounded-lg text-sm w-full justify-center">
                    <i class="pi pi-check-circle"></i> 
                    <span class="font-medium truncate max-w-[200px]">{{ importForm.file.name }}</span>
                </div>
                
                <small class="text-red-500 mt-2" v-if="importForm.errors.file">{{ importForm.errors.file }}</small>
            </div>

            <template #footer>
                <Button label="Batal" text severity="secondary" @click="hideImportDialog" />
                <Button label="Mulai Import" @click="submitImportForm" :loading="importForm.processing" :disabled="!importForm.file" severity="success" />
            </template>
        </Dialog>

    </AdminLayout>
</template>

<style scoped>
/* Override header table agar warna soft & font tebal */
:deep(.p-datatable .p-datatable-thead > tr > th) {
    background-color: #f8fafc !important; /* bg-slate-50 */
    color: #475569 !important; /* text-slate-600 */
    font-weight: 700 !important;
    font-size: 0.80rem !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem;
}

/* Baris tabel */
:deep(.p-datatable .p-datatable-tbody > tr > td) {
    padding: 0.75rem 1rem;
    color: #334155; /* text-slate-700 */
    font-size: 0.875rem;
    border-bottom: 1px solid #f1f5f9;
}

/* Hover effect baris */
:deep(.p-datatable.p-datatable-hoverable-rows .p-datatable-tbody > tr:not(.p-highlight):hover) {
    background-color: #f8fafc !important;
}

/* Paginator rapi */
:deep(.p-paginator) {
    justify-content: end;
    border-top: 1px solid #e2e8f0;
}
</style>
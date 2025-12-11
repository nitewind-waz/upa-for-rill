<script setup>
import { ref, watch, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';

// PrimeVue Components
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

const toast = useToast();

const props = defineProps({
    results: Object,
    mahasiswas: Array,
    filters: Object,
});

// --- STATE ---
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

const formDialog = ref(false); // Ganti createDialog jadi formDialog biar umum
const deleteDialog = ref(false);
const importDialog = ref(false);
const isEditing = ref(false);
const itemToDelete = ref(null);

// Form Setup dengan _method untuk update file
const form = useForm({
    id: null,
    mahasiswa_id: null,
    tahun: new Date().getFullYear().toString(),
    listening: null,
    structure: null,
    reading: null,
    total_score: null,
    sertifikat_pdf: null,
    _method: 'POST', 
});

const importForm = useForm({
    file: null,
});

const resultsData = computed(() => props.results?.data || []);

// --- LOGIKA UTAMA (Load Data) ---
const loadLazyData = (event) => {
    // ... (Logika loadLazyData sama seperti sebelumnya)
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

// --- LOGIKA CRUD ---

// 1. Open Create
const openCreateDialog = () => { 
    isEditing.value = false;
    form.reset();
    form.clearErrors();
    form._method = 'POST';
    formDialog.value = true; 
};

// 2. Open Edit
const openEditDialog = (data) => {
    isEditing.value = true;
    form.clearErrors();
    
    // Isi form
    form.id = data.id;
    form.mahasiswa_id = data.mahasiswa_id; // Pastikan data.mahasiswa_id ada (bukan object mahasiswa)
    form.tahun = data.tahun;
    form.listening = data.listening;
    form.structure = data.structure;
    form.reading = data.reading;
    form.total_score = data.total_score;
    form.sertifikat_pdf = null; // Reset file input
    
    form._method = 'PUT'; // Ubah method jadi PUT
    formDialog.value = true;
};

const hideFormDialog = () => { formDialog.value = false; form.reset(); };

// 3. Submit Form (Create & Edit)
const submitForm = () => {
    if (isEditing.value) {
        // UPDATE: POST ke /admin/ept/{id} dengan _method: PUT
        form.post(`/admin/ept/${form.id}`, {
            onSuccess: () => {
                hideFormDialog();
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Data EPT diperbarui.', life: 3000 });
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Periksa inputan Anda.', life: 3000 });
            }
        });
    } else {
        // CREATE
        form.post('/admin/ept', {
            onSuccess: () => {
                hideFormDialog();
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Data EPT ditambahkan.', life: 3000 });
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Periksa inputan Anda.', life: 3000 });
            }
        });
    }
};

// 4. Delete Logic
const confirmDelete = (data) => {
    itemToDelete.value = data;
    deleteDialog.value = true;
};

const deleteEpt = () => {
    if (itemToDelete.value) {
        router.delete(`/admin/ept/${itemToDelete.value.id}`, {
            onSuccess: () => {
                deleteDialog.value = false;
                itemToDelete.value = null;
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Data dihapus.', life: 3000 });
            },
            onError: () => {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Gagal menghapus data.', life: 3000 });
            }
        });
    }
};

// --- IMPORT & DOWNLOAD ---
const openImportDialog = () => { importDialog.value = true; };
const hideImportDialog = () => { importDialog.value = false; importForm.reset(); importForm.clearErrors(); };

const downloadTemplate = () =>{
    window.location.href = '/admin/ept/template';
};

const handleFileChange = (event) => {
    form.sertifikat_pdf = event.target.files[0];
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

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Hasil EPT Mahasiswa</h1>
                    <p class="text-slate-500 mt-1">Monitoring skor Listening, Structure, dan Reading.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="/admin/ept/template" target="_blank">
                        <Button label="Template Excel" icon="pi pi-file-excel" severity="secondary" outlined size="small" class="!border-slate-300 !text-slate-600 hover:!bg-slate-50" />  
                    </a>
                    <Button label="Import Data" icon="pi pi-upload" size="small" class="!bg-emerald-600 !border-emerald-600 hover:!bg-emerald-700" @click="openImportDialog" />
                    <Button label="Input Manual" icon="pi pi-plus" size="small" class="!bg-blue-600 !border-blue-600 hover:!bg-blue-700" @click="openCreateDialog" />
                </div>
            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Filter Section sama seperti sebelumnya -->
                <div class="p-4 border-b border-slate-100 bg-slate-50/50 grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5">
                        <IconField iconPosition="left" class="w-full">
                            <InputIcon class="pi pi-search text-slate-400" />
                            <InputText v-model="lazyParams.globalFilter" placeholder="Cari Nama atau NIM..." class="w-full !bg-white !rounded-lg" />
                        </IconField>
                    </div>
                    <div class="md:col-span-3">
                        <InputText v-model="lazyParams.tahun" placeholder="Filter Tahun" class="w-full !bg-white !rounded-lg" />
                    </div>
                    <div class="md:col-span-4">
                         <Dropdown v-model="lazyParams.mahasiswa_id" :options="props.mahasiswas" optionLabel="nama" optionValue="id" placeholder="Pilih Mahasiswa" showClear filter class="w-full !bg-white !rounded-lg" />
                    </div>
                </div>

                <DataTable 
                    :value="resultsData" lazy paginator :first="lazyParams.first" :rows="lazyParams.rows"
                    :totalRecords="props.results.total" :loading="form.processing || importForm.processing"
                    @page="loadLazyData($event)" @sort="loadLazyData($event)"
                    removableSort stripedRows showGridlines class="p-datatable-sm" tableStyle="min-width: 70rem"
                    :rowsPerPageOptions="[10, 20, 50]"
                >
                    <template #empty> 
                        <div class="text-center py-12">
                            <p class="text-slate-500 font-medium">Belum ada data EPT yang diupload.</p>
                        </div>
                    </template>

                    <Column field="mahasiswa.nim" header="NIM" sortable style="min-width: 140px">
                        <template #body="{ data }">
                            <span class="font-mono text-slate-700 font-medium">{{ data.mahasiswa?.nim }}</span>
                        </template>
                    </Column>
                    
                    <Column field="mahasiswa.nama" header="Nama Mahasiswa" sortable style="min-width: 200px">
                        <template #body="{ data }">
                            <div class="font-semibold text-slate-900">{{ data.mahasiswa?.nama }}</div>
                        </template>
                    </Column>
                    
                    <Column field="tahun" header="Tahun" sortable class="text-center" style="width: 80px" />
                    
                    <Column field="listening" header="L" sortable style="width: 60px" class="text-center" />
                    <Column field="structure" header="S" sortable style="width: 60px" class="text-center" />
                    <Column field="reading" header="R" sortable style="width: 60px" class="text-center" />
                    
                    <Column field="total_score" header="Total" sortable style="width: 100px">
                        <template #body="{ data }">
                            <div class="text-center">
                                <Tag :value="data.total_score" :severity="data.total_score >= 450 ? 'success' : (data.total_score >= 400 ? 'warning' : 'danger')" rounded />
                            </div>
                        </template>
                    </Column>

                    <Column header="File" style="width: 60px" class="text-center">
                        <template #body="{ data }">
                            <a v-if="data.sertifikat_pdf" :href="`/storage/${data.sertifikat_pdf}`" target="_blank" class="text-blue-600 hover:text-blue-800">
                                <i class="pi pi-download"></i>
                            </a>
                            <span v-else class="text-slate-300"><i class="pi pi-minus"></i></span>
                        </template>
                    </Column>

                    <!-- KOLOM AKSI (NEW) -->
                    <Column header="Aksi" style="width: 100px" alignFrozen="right" frozen>
                        <template #body="{ data }">
                            <div class="flex justify-center gap-2">
                                <Button icon="pi pi-pencil" text rounded severity="info" size="small" @click="openEditDialog(data)" v-tooltip.top="'Edit'" />
                                <Button icon="pi pi-trash" text rounded severity="danger" size="small" @click="confirmDelete(data)" v-tooltip.top="'Hapus'" />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>

        <!-- DIALOG FORM (CREATE / EDIT) -->
        <Dialog v-model:visible="formDialog" :header="isEditing ? 'Edit Data EPT' : 'Input Data Manual'" :style="{ width: '500px' }" modal :draggable="false" class="p-fluid">
            <div class="flex flex-col gap-5 pt-2">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="mb-2 block text-sm font-medium text-slate-700">Mahasiswa</label>
                        <Dropdown v-model="form.mahasiswa_id" :options="props.mahasiswas" optionLabel="nama" optionValue="id" placeholder="Pilih Mahasiswa" filter class="w-full" :invalid="!!form.errors.mahasiswa_id" />
                        <small class="text-red-500" v-if="form.errors.mahasiswa_id">{{ form.errors.mahasiswa_id }}</small>
                    </div>
                    <div class="col-span-2">
                         <label class="mb-2 block text-sm font-medium text-slate-700">Tahun Tes</label>
                        <InputText v-model="form.tahun" placeholder="2024" :invalid="!!form.errors.tahun" />
                        <small class="text-red-500" v-if="form.errors.tahun">{{ form.errors.tahun }}</small>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Rincian Nilai</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Listening</label>
                            <InputNumber v-model="form.listening" :min="0" :max="677" placeholder="0" inputClass="text-center" :invalid="!!form.errors.listening" />
                        </div>
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Structure</label>
                            <InputNumber v-model="form.structure" :min="0" :max="677" placeholder="0" inputClass="text-center" :invalid="!!form.errors.structure" />
                        </div>
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Reading</label>
                            <InputNumber v-model="form.reading" :min="0" :max="677" placeholder="0" inputClass="text-center" :invalid="!!form.errors.reading" />
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Total Score</label>
                    <InputNumber v-model="form.total_score" :min="310" :max="677" class="w-full" :invalid="!!form.errors.total_score" />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Upload Sertifikat (PDF)</label>
                    <input type="file" accept="application/pdf" @change="handleFileChange" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    <small class="text-xs text-slate-400" v-if="isEditing">*Biarkan kosong jika tidak ingin mengubah file.</small>
                    <small class="text-red-500 block" v-if="form.errors.sertifikat_pdf">{{ form.errors.sertifikat_pdf }}</small>
                </div>
            </div>

            <template #footer>
                <Button label="Batal" text severity="secondary" @click="hideFormDialog" />
                <Button :label="isEditing ? 'Simpan Perubahan' : 'Simpan Data'" @click="submitForm" :loading="form.processing" class="!bg-blue-600 !border-blue-600" />
            </template>
        </Dialog>

        <!-- DIALOG IMPORT (Sama) -->
        <Dialog v-model:visible="importDialog" header="Import Data Excel" :style="{ width: '400px' }" modal :draggable="false">
            <div class="flex flex-col items-center justify-center pt-4">
                <i class="pi pi-file-excel text-green-500 text-4xl mb-4"></i>
                <p class="text-center text-slate-600 mb-6 text-sm px-4">Pastikan format Excel sesuai template.</p>
                <FileUpload mode="basic" name="file" accept=".xlsx,.xls,.csv" :maxFileSize="5000000" chooseLabel="Pilih File Excel" class="w-full !bg-emerald-600 !border-emerald-600" :auto="true" customUpload @uploader="handleImportSelect" />
                <div v-if="importForm.file" class="mt-4 flex items-center gap-2 bg-green-50 text-green-700 px-3 py-2 rounded-lg text-sm w-full justify-center">
                    <i class="pi pi-check-circle"></i> <span class="font-medium truncate max-w-[200px]">{{ importForm.file.name }}</span>
                </div>
                <small class="text-red-500 mt-2" v-if="importForm.errors.file">{{ importForm.errors.file }}</small>
            </div>
            <template #footer>
                <Button label="Batal" text severity="secondary" @click="hideImportDialog" />
                <Button label="Mulai Import" @click="submitImportForm" :loading="importForm.processing" :disabled="!importForm.file" class="!bg-emerald-600 !border-emerald-600" />
            </template>
        </Dialog>

        <!-- DIALOG DELETE (NEW) -->
        <Dialog v-model:visible="deleteDialog" header="Konfirmasi Hapus" :style="{ width: '400px' }" modal :draggable="false">
            <div class="flex items-start gap-4 py-2">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i class="pi pi-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Apakah Anda yakin ingin menghapus data EPT atas nama <span class="font-bold text-slate-900">{{ itemToDelete?.mahasiswa?.nama }}</span>?
                    </p>
                </div>
            </div>
            <template #footer>
                <Button label="Batal" text severity="secondary" @click="deleteDialog = false" />
                <Button label="Hapus" severity="danger" @click="deleteEpt" autofocus />
            </template>
        </Dialog>

    </AdminLayout>
</template>
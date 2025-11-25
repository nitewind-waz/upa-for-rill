<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Layout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

// PrimeVue Imports
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Textarea from 'primevue/textarea';
import Toast from 'primevue/toast';
import Tag from 'primevue/tag';
import ConfirmDialog from 'primevue/confirmdialog';
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
    jadwal: Array,
    jurusan: Array,
    prodi: Array, // Now a top-level prop
    kelas: Array,
    gedung: Array,
    ruang: Array,
    errors: Object
});

const page = usePage();
const toast = useToast();
const confirm = useConfirm();

// State untuk Modal & Form (using IDs)
const scheduleDialog = ref(false);
const isEditing = ref(false);
const submitted = ref(false);

const form = ref({
    id: null,
    jurusan_id: null,
    prodi_id: null,
    kelas_id: null,
    ruang_id: null, 
    gedung_id: null,
    tanggal: '',
    waktu_mulai: '',
    waktu_selesai: ''
});

// --- COMPUTED LOGIC ---

// Filter Prodi berdasarkan Jurusan ID
const filteredProdiList = computed(() => {
    if (!form.value.jurusan_id) return [];
    return props.prodi.filter(p => p.jurusan_id === form.value.jurusan_id);
});

// Filter Kelas berdasarkan Prodi ID
const filteredKelasList = computed(() => {
    if (!form.value.prodi_id) return [];
    return props.kelas.filter(k => k.prodi_id === form.value.prodi_id);
});


// --- HANDLERS ---

const openNew = () => {
    form.value = {
        id: null,
        jurusan_id: null,
        prodi_id: null,
        kelas_id: null,
        ruang_id: null, 
        gedung_id: null,
        tanggal: '',
        waktu_mulai: '',
        waktu_selesai: ''
    };
    submitted.value = false;
    isEditing.value = false;
    scheduleDialog.value = true;
};

const hideDialog = () => {
    scheduleDialog.value = false;
    submitted.value = false;
};

// Reset child dropdown saat parent berubah
const handleJurusanChange = () => {
    form.value.prodi_id = null;
    form.value.kelas_id = null;
};

const handleProdiChange = () => {
    form.value.kelas_id = null;
};

const saveSchedule = () => {
    submitted.value = true;

    // The validation is now primarily on the backend
    const payload = { ...form.value };

    // Rename ruang_id to tempat for the backend, as the column is likely 'tempat'
    // but the validation expects 'ruang_id'. Let's align with validation.
    // The controller validation expects: juruan_id, prodi_id, kelas_id, ruang_id, gedung_id
    // So the payload is correct as is.

    if (isEditing.value) {
        router.put(`/admin/jadwal/${form.value.id}`, payload, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal berhasil diperbarui', life: 3000 });
                hideDialog();
            },
            onError: (errors) => {
                let errorMessages = 'Terjadi kesalahan validasi: ' + Object.values(errors).join('; ');
                toast.add({ severity: 'error', summary: 'Gagal', detail: errorMessages, life: 5000 });
            }
        });
    } else {
        router.post('/admin/jadwal', payload, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal berhasil dibuat', life: 3000 });
                hideDialog();
            },
           onError: (errors) => {
                let errorMessages = 'Gagal membuat jadwal: ' + Object.values(errors).join('; ');
                toast.add({ severity: 'error', summary: 'Gagal', detail: errorMessages, life: 5000 });
            }
        });
    }
};

const editSchedule = (schedule) => {
    isEditing.value = true;
    // Map the nested objects to their IDs for the form
    form.value = { 
        id: schedule.id,
        jurusan_id: schedule.jurusan?.id || null,
        prodi_id: schedule.prodi?.id || null,
        kelas_id: schedule.kelas?.id || null,
        ruang_id: schedule.ruang?.id || null,
        gedung_id: schedule.gedung?.id || null,
        tanggal: schedule.tanggal ? new Date(schedule.tanggal).toISOString().split('T')[0] : '',
        waktu_mulai: schedule.waktu_mulai,
        waktu_selesai: schedule.waktu_selesai,
    };
    scheduleDialog.value = true;
};

const confirmDeleteSchedule = (schedule) => {
    confirm.require({
        message: 'Apakah Anda yakin ingin menghapus jadwal ini?',
        header: 'Konfirmasi Hapus',
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        accept: () => {
            router.delete(`/admin/jadwal/${schedule.id}`, {
                onSuccess: () => {
                    toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal dihapus', life: 3000 });
                }
            });
        }
    });
};

// --- HELPERS ---
const formatTanggalIndonesia = (dateString) => {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        const userTimezoneOffset = date.getTimezoneOffset() * 60000;
        const adjustedDate = new Date(date.getTime() + userTimezoneOffset);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return adjustedDate.toLocaleDateString('id-ID', options);
    } catch (e) {
        return dateString;
    }
};
</script>
...
// The rest of the file is template, which I will replace separately.


<template>
    <Layout>
        <Head title="Kelola Jadwal EPT" />
        <Toast position="top-right" />
        <ConfirmDialog />

        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Jadwal EPT</h1>
                    <p class="text-slate-500 mt-1">Atur jadwal pelaksanaan tes untuk Prodi dan Kelas.</p>
                </div>
                <Button label="Tambah Jadwal" icon="pi pi-plus" @click="openNew" severity="primary" />
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <DataTable 
                    :value="props.jadwal" 
                    stripedRows 
                    showGridlines 
                    paginator 
                    :rows="10" 
                    :rowsPerPageOptions="[5, 10, 20]"
                    class="p-datatable-sm"
                    tableStyle="min-width: 60rem"
                >
                    <template #empty>
                        <div class="text-center py-12">
                            <p class="text-slate-500">Belum ada data jadwal.</p>
                        </div>
                    </template>

                    <Column field="jurusan.nama_jurusan" header="Jurusan" sortable style="min-width: 200px">
                        <template #body="{ data }">
                            <span class="font-semibold text-slate-700">{{ data.jurusan?.nama_jurusan }}</span>
                        </template>
                    </Column>

                    <Column field="prodi.nama_prodi" header="Prodi & Kelas" sortable style="min-width: 200px">
                        <template #body="{ data }">
                            <div class="flex flex-col gap-1">
                                <span class="text-slate-700">{{ data.prodi?.nama_prodi }}</span>
                                <Tag :value="data.kelas?.nama_kelas" severity="info" class="w-fit" />
                            </div>
                        </template>
                    </Column>

                    <Column header="Lokasi" style="min-width: 150px">
                        <template #body="{ data }">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-800">{{ data.ruang?.nama }}</span>
                                <span class="text-xs text-slate-500">{{ data.gedung?.nama }}</span>
                            </div>
                        </template>
                    </Column>

                    <Column header="Waktu" style="min-width: 200px">
                        <template #body="{ data }">
                             <div class="flex flex-col text-sm">
                                <span class="font-medium text-slate-700">
                                    {{ formatTanggalIndonesia(data.tanggal) }}
                                </span>
                                <span class="text-slate-500">
                                    {{ data.waktu_mulai?.substring(0, 5) }} - {{ data.waktu_selesai?.substring(0, 5) }}
                                </span>
                            </div>
                        </template>
                    </Column>

                    <Column header="Aksi" :exportable="false" style="min-width: 100px" alignFrozen="right" frozen>
                        <template #body="slotProps">
                            <div class="flex gap-2">
                                <Button icon="pi pi-pencil" text rounded severity="warning" @click="editSchedule(slotProps.data)" />
                                <Button icon="pi pi-trash" text rounded severity="danger" @click="confirmDeleteSchedule(slotProps.data)" />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>

        <Dialog 
            v-model:visible="scheduleDialog" 
            :style="{ width: '600px' }" 
            :header="isEditing ? 'Edit Jadwal' : 'Tambah Jadwal Baru'" 
            :modal="true" 
            class="p-fluid"
        >
            <div class="flex flex-col gap-4 pt-2">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan</label>
                        <Dropdown 
                            v-model="form.jurusan_id" 
                            :options="props.jurusan" 
                            optionLabel="nama_jurusan" 
                            optionValue="id" 
                            placeholder="Pilih Jurusan" 
                            @change="handleJurusanChange"
                            :class="{'p-invalid': submitted && !form.jurusan_id}"
                        />
                        <small class="text-red-500" v-if="submitted && !form.jurusan_id">Jurusan wajib diisi.</small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Program Studi</label>
                        <Dropdown 
                            v-model="form.prodi_id" 
                            :options="filteredProdiList" 
                            optionLabel="nama_prodi" 
                            optionValue="id" 
                            placeholder="Pilih Prodi" 
                            :disabled="!form.jurusan_id"
                            @change="handleProdiChange"
                            :class="{'p-invalid': submitted && !form.prodi_id}"
                        />
                        <small class="text-red-500" v-if="submitted && !form.prodi_id">Prodi wajib diisi.</small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                        <Dropdown 
                            v-model="form.kelas_id" 
                            :options="filteredKelasList" 
                            optionLabel="nama_kelas" 
                            optionValue="id" 
                            placeholder="Pilih Kelas"
                            :disabled="!form.prodi_id"
                            :class="{'p-invalid': submitted && !form.kelas_id}"
                            editable 
                        />
                         <small class="text-slate-400 text-xs">Bisa ketik manual jika tidak ada di list.</small>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pelaksanaan</label>
                        <input 
                            type="date" 
                            v-model="form.tanggal" 
                            class="p-inputtext w-full"
                            :class="{'p-invalid': submitted && !form.tanggal}"
                        />
                        <small class="text-red-500" v-if="submitted && !form.tanggal">Tanggal wajib diisi.</small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                        <input type="time" v-model="form.waktu_mulai" class="p-inputtext w-full" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                        <input type="time" v-model="form.waktu_selesai" class="p-inputtext w-full" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Gedung</label>
                        <Dropdown 
                            v-model="form.gedung_id"
                            :options="props.gedung"
                            optionLabel="nama"
                            optionValue="id"
                            placeholder="Pilih Gedung"
                        />
                    </div>
                    <div>
                         <label class="block text-sm font-medium text-slate-700 mb-1">Ruangan</label>
                        <Dropdown 
                            v-model="form.ruang_id"
                            :options="props.ruang"
                            optionLabel="nama"
                            optionValue="id"
                            placeholder="Pilih Ruang"
                        />
                    </div>
                </div>
            </div>

            <template #footer>
                <Button label="Batal" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Simpan" icon="pi pi-check" @click="saveSchedule" severity="primary" />
            </template>
        </Dialog>

    </Layout>
</template>

<style scoped>
/* Override Table Header Styles */
:deep(.p-datatable .p-datatable-thead > tr > th) {
    background-color: #f8fafc;
    color: #475569;
    font-weight: 600;
    border-bottom: 1px solid #e2e8f0;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
    border-bottom: 1px solid #f1f5f9;
}

/* Dropdown Styling agar konsisten */
:deep(.p-dropdown) {
    width: 100%;
}
</style>
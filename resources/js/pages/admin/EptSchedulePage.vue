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
    kelas: Array,
    gedung: Array,
    ruang: Array,
    errors: Object
});

const page = usePage();
const toast = useToast();
const confirm = useConfirm();

// State untuk Modal & Form
const scheduleDialog = ref(false);
const isEditing = ref(false);
const submitted = ref(false);

const form = ref({
    id: null,
    jurusan: '',
    prodi: '',
    kelas: '',
    tempat: '', 
    gedung: '',
    tanggal: '',
    waktu_mulai: '',
    waktu_selesai: ''
});

// --- COMPUTED LOGIC ---

// Filter Prodi berdasarkan Jurusan
const filteredProdiList = computed(() => {
    if (!form.value.jurusan) return [];
    const selectedJurusan = props.jurusan.find(j => j.nama === form.value.jurusan);
    return selectedJurusan ? selectedJurusan.prodi : [];
});

// Filter Kelas berdasarkan Prodi
const filteredKelasList = computed(() => {
    if (!form.value.prodi) return [];
    
    return props.kelas
        .filter(k => k.prodi && k.prodi.nama === form.value.prodi)
        .map(k => ({ nama: k.nama_kelas }));
});


// --- HANDLERS ---

const openNew = () => {
    form.value = {
        id: null,
        jurusan: '',
        prodi: '',
        kelas: '',
        tempat: '',
        gedung: '',
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
    form.value.prodi = '';
    form.value.kelas = '';
};

const handleProdiChange = () => {
    form.value.kelas = '';
};

const saveSchedule = () => {
    submitted.value = true;

    // Validasi sederhana di frontend
    if (!form.value.jurusan || !form.value.prodi || !form.value.tanggal) {
        toast.add({ severity: 'error', summary: 'Gagal', detail: 'Harap isi semua field yang wajib diisi.', life: 3000 });
        return;
    }

    const payload = { ...form.value };

    if (isEditing.value) {
        router.put(`/admin/jadwal/${form.value.id}`, payload, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal berhasil diperbarui', life: 3000 });
                hideDialog();
            },
            onError: (errors) => {
                let errorMessages = 'Terjadi kesalahan: ';
                if (errors) {
                    errorMessages += Object.values(errors).join('; ');
                }
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
                let errorMessages = 'Gagal membuat jadwal. ';
                if (errors) {
                    errorMessages += Object.values(errors).join('; ');
                }
                toast.add({ severity: 'error', summary: 'Gagal', detail: errorMessages, life: 5000 });
            }
        });
    }
};

const editSchedule = (schedule) => {
    isEditing.value = true;
    // Clone object agar tidak reaktif langsung ke tabel sebelum save
    form.value = { 
        ...schedule,
        // Pastikan format tanggal YYYY-MM-DD untuk input type="date"
        tanggal: schedule.tanggal ? new Date(schedule.tanggal).toISOString().split('T')[0] : ''
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
        // Fix timezone offset issue simple way
        const userTimezoneOffset = date.getTimezoneOffset() * 60000;
        const adjustedDate = new Date(date.getTime() + userTimezoneOffset);
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return adjustedDate.toLocaleDateString('id-ID', options);
    } catch (e) {
        return dateString;
    }
};
</script>

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

                    <Column field="jurusan" header="Jurusan" sortable style="min-width: 200px">
                        <template #body="{ data }">
                            <span class="font-semibold text-slate-700">{{ data.jurusan }}</span>
                        </template>
                    </Column>

                    <Column field="prodi" header="Prodi & Kelas" sortable style="min-width: 200px">
                        <template #body="{ data }">
                            <div class="flex flex-col gap-1">
                                <span class="text-slate-700">{{ data.prodi }}</span>
                                <Tag :value="data.kelas" severity="info" class="w-fit" />
                            </div>
                        </template>
                    </Column>

                    <Column header="Lokasi" style="min-width: 150px">
                        <template #body="{ data }">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-800">{{ data.tempat }}</span>
                                <span class="text-xs text-slate-500">{{ data.gedung }}</span>
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
                            v-model="form.jurusan" 
                            :options="props.jurusan" 
                            optionLabel="nama" 
                            optionValue="nama" 
                            placeholder="Pilih Jurusan" 
                            @change="handleJurusanChange"
                            :class="{'p-invalid': submitted && !form.jurusan}"
                        />
                        <small class="text-red-500" v-if="submitted && !form.jurusan">Jurusan wajib diisi.</small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Program Studi</label>
                        <Dropdown 
                            v-model="form.prodi" 
                            :options="filteredProdiList" 
                            optionLabel="nama" 
                            optionValue="nama" 
                            placeholder="Pilih Prodi" 
                            :disabled="!form.jurusan"
                            @change="handleProdiChange"
                            :class="{'p-invalid': submitted && !form.prodi}"
                        />
                        <small class="text-red-500" v-if="submitted && !form.prodi">Prodi wajib diisi.</small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                        <Dropdown 
                            v-model="form.kelas" 
                            :options="filteredKelasList" 
                            optionLabel="nama" 
                            optionValue="nama" 
                            placeholder="Pilih Kelas"
                            :disabled="!form.prodi"
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
                            v-model="form.gedung"
                            :options="props.gedung"
                            optionLabel="nama_gedung"
                            optionValue="nama_gedung"
                            placeholder="Pilih Gedung"
                        />
                    </div>
                    <div>
                         <label class="block text-sm font-medium text-slate-700 mb-1">Ruangan</label>
                        <Dropdown 
                            v-model="form.tempat"
                            :options="props.ruang"
                            optionLabel="nama_ruang"
                            optionValue="nama_ruang"
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
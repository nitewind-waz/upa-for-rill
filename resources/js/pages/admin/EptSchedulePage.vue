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
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Toast from 'primevue/toast';
import Tag from 'primevue/tag';
import ConfirmDialog from 'primevue/confirmdialog';
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";

const props = defineProps({
    jadwal: Array, // Student schedules
    jadwalPublik: Array, // Public schedules (from JadwalPublik model)
    publik: Array, // List of public participants
    jurusan: Array,
    prodi: Array,
    kelas: Array,
    gedung: Array,
    ruang: Array,
    errors: Object
});

const page = usePage();
const toast = useToast();
const confirm = useConfirm();

// State untuk Modal & Form Mahasiswa (using IDs)
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

// State untuk Modal & Form Publik
const publicScheduleDialog = ref(false);
const isEditingPublic = ref(false);
const publicSubmitted = ref(false);
const publicForm = ref({
    id: null,
    publik_id: null,
    ruang_id: null,
    gedung_id: null,
    tanggal: '',
    waktu_mulai: '', // This will map to jam_mulai
    waktu_selesai: '' // This will map to jam_selesai
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


// --- HANDLERS MAHASISWA ---

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

    const payload = { ...form.value };

    if (isEditing.value) {
        router.put(`/admin/jadwal/${form.value.id}`, payload, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal berhasil diperbarui', life: 3000 });
                hideDialog();
            },
            onError: (errors) => {
                const errorMessages = 'Terjadi kesalahan validasi: ' + Object.values(errors).join('; ');
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
                const errorMessages = 'Gagal membuat jadwal: ' + Object.values(errors).join('; ');
                toast.add({ severity: 'error', summary: 'Gagal', detail: errorMessages, life: 5000 });
            }
        });
    }
};

const editSchedule = (schedule) => {
    isEditing.value = true;
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

// --- HANDLERS PUBLIK ---

const openNewPublic = () => {
    publicForm.value = {
        id: null,
        publik_id: null,
        ruang_id: null,
        gedung_id: null,
        tanggal: '',
        waktu_mulai: '',
        waktu_selesai: ''
    };
    publicSubmitted.value = false;
    isEditingPublic.value = false;
    publicScheduleDialog.value = true;
};

const hidePublicDialog = () => {
    publicScheduleDialog.value = false;
    publicSubmitted.value = false;
};

const savePublicSchedule = () => {
    publicSubmitted.value = true;

    // Minimal validation
    if (!publicForm.value.publik_id || !publicForm.value.tanggal || !publicForm.value.waktu_mulai || !publicForm.value.waktu_selesai) {
        toast.add({ severity: 'warn', summary: 'Perhatian', detail: 'Harap lengkapi semua bidang yang wajib diisi.', life: 3000 });
        return;
    }

    const payload = { ...publicForm.value };

    if (isEditingPublic.value) {
        router.put(`/admin/jadwal-publik/${publicForm.value.id}`, payload, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal publik berhasil diperbarui', life: 3000 });
                hidePublicDialog();
            },
            onError: (errors) => {
                const errorMessages = 'Gagal memperbarui: ' + Object.values(errors).join('; ');
                toast.add({ severity: 'error', summary: 'Gagal', detail: errorMessages, life: 5000 });
            }
        });
    } else {
        router.post('/admin/jadwal-publik', payload, {
            onSuccess: () => {
                toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal publik berhasil dibuat', life: 3000 });
                hidePublicDialog();
            },
            onError: (errors) => {
                const errorMessages = 'Gagal membuat: ' + Object.values(errors).join('; ');
                toast.add({ severity: 'error', summary: 'Gagal', detail: errorMessages, life: 5000 });
            }
        });
    }
};

const editPublicSchedule = (schedule) => {
    isEditingPublic.value = true;
    publicForm.value = {
        id: schedule.id,
        publik_id: schedule.publik?.id || null,
        ruang_id: schedule.ruang?.id || null,
        gedung_id: schedule.gedung?.id || null,
        tanggal: schedule.tanggal ? new Date(schedule.tanggal).toISOString().split('T')[0] : '',
        waktu_mulai: schedule.jam_mulai, // Note: maps to jam_mulai from backend
        waktu_selesai: schedule.jam_selesai, // Note: maps to jam_selesai from backend
    };
    publicScheduleDialog.value = true;
};

const confirmDeletePublicSchedule = (schedule) => {
    confirm.require({
        message: 'Apakah Anda yakin ingin menghapus jadwal publik ini?',
        header: 'Konfirmasi Hapus',
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        accept: () => {
            router.delete(`/admin/jadwal-publik/${schedule.id}`, {
                onSuccess: () => {
                    toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Jadwal publik dihapus', life: 3000 });
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


<template>
    <Layout>
        <Head title="Kelola Jadwal EPT" />
        <Toast position="top-right" />
        <ConfirmDialog />

        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Jadwal EPT</h1>
                    <p class="text-slate-500 mt-1">Atur jadwal pelaksanaan tes untuk Mahasiswa dan Publik.</p>
                </div>
                <div class="flex gap-2">
                    <Button label="Tambah Jadwal Mahasiswa" icon="pi pi-plus" @click="openNew" severity="primary" />
                    <Button label="Tambah Jadwal Publik" icon="pi pi-plus" @click="openNewPublic" severity="success" />
                </div>
            </div>

            <TabView>
                <TabPanel header="Jadwal Mahasiswa">
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
                                    <span class="font-semibold text-slate-700">{{ data.jurusan?.nama_jurusan || '-' }}</span>
                                </template>
                            </Column>

                            <Column field="prodi.nama_prodi" header="Prodi & Kelas" sortable style="min-width: 200px">
                                <template #body="{ data }">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-slate-700">{{ data.prodi?.nama_prodi || '-' }}</span>
                                        <Tag :value="data.kelas?.nama_kelas || '-'" severity="info" class="w-fit" />
                                    </div>
                                </template>
                            </Column>

                            <Column header="Lokasi" style="min-width: 150px">
                                <template #body="{ data }">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-800">{{ data.ruang?.nama || '-' }}</span>
                                        <span class="text-xs text-slate-500">{{ data.gedung?.nama || '-' }}</span>
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
                </TabPanel>
                
                <TabPanel header="Jadwal Publik">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <DataTable 
                            :value="props.jadwalPublik" 
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
                                    <p class="text-slate-500">Belum ada data jadwal publik.</p>
                                </div>
                            </template>

                            <Column header="Nama Peserta" sortable style="min-width: 200px">
                                <template #body="{ data }">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-slate-700">{{ data.publik?.nik || '-' }}</span>
                                        <span class="text-xs text-slate-500">{{ data.publik?.nama_lengkap || '-' }}</span>
                                    </div>
                                </template>
                            </Column>

                            <Column header="Lokasi" style="min-width: 150px">
                                <template #body="{ data }">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-800">{{ data.ruang?.nama || '-' }}</span>
                                        <span class="text-xs text-slate-500">{{ data.gedung?.nama || '-' }}</span>
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
                                            {{ data.jam_mulai?.substring(0, 5) }} - {{ data.jam_selesai?.substring(0, 5) }}
                                        </span>
                                    </div>
                                </template>
                            </Column>

                            <Column header="Aksi" :exportable="false" style="min-width: 100px" alignFrozen="right" frozen>
                                <template #body="slotProps">
                                    <div class="flex gap-2">
                                        <Button icon="pi pi-pencil" text rounded severity="warning" @click="editPublicSchedule(slotProps.data)" />
                                        <Button icon="pi pi-trash" text rounded severity="danger" @click="confirmDeletePublicSchedule(slotProps.data)" />
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </div>
                </TabPanel>
            </TabView>
        </div>

        <!-- Dialog Jadwal Mahasiswa -->
        <Dialog 
            v-model:visible="scheduleDialog" 
            :style="{ width: '600px' }" 
            :header="isEditing ? 'Edit Jadwal' : 'Tambah Jadwal Baru'" 
            :modal="true" 
            class="p-fluid"
            @hide="hideDialog"
        >
            <div class="flex flex-col gap-4 pt-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan <span class="text-red-500">*</span></label>
                        <Dropdown 
                            v-model="form.jurusan_id" 
                            :options="props.jurusan" 
                            optionLabel="nama_jurusan" 
                            optionValue="id" 
                            placeholder="Pilih Jurusan" 
                            showClear
                            @change="handleJurusanChange"
                            :class="{'p-invalid': submitted && !form.jurusan_id}"
                        />
                        <small class="text-red-500" v-if="submitted && !form.jurusan_id">Jurusan wajib diisi.</small>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Program Studi <span class="text-red-500">*</span></label>
                        <Dropdown 
                            v-model="form.prodi_id" 
                            :options="filteredProdiList" 
                            optionLabel="nama_prodi" 
                            optionValue="id" 
                            placeholder="Pilih Prodi" 
                            showClear
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
                            showClear
                            :disabled="!form.prodi_id"
                        />
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
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
                            showClear
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
                            showClear
                        />
                    </div>
                </div>
            </div>

            <template #footer>
                <Button label="Batal" icon="pi pi-times" text @click="hideDialog" />
                <Button label="Simpan" icon="pi pi-check" @click="saveSchedule" severity="primary" />
            </template>
        </Dialog>

        <!-- Dialog Jadwal Publik -->
        <Dialog 
            v-model:visible="publicScheduleDialog" 
            :style="{ width: '500px' }" 
            :header="isEditingPublic ? 'Edit Jadwal Publik' : 'Tambah Jadwal Publik'" 
            :modal="true" 
            class="p-fluid"
            @hide="hidePublicDialog"
        >
            <div class="flex flex-col gap-4 pt-2">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Peserta Publik <span class="text-red-500">*</span></label>
                        <Dropdown 
                            v-model="publicForm.publik_id" 
                            :options="props.publik" 
                            optionValue="id" 
                            placeholder="Pilih Peserta"
                            showClear
                            :class="{'p-invalid': publicSubmitted && !publicForm.publik_id}"
                        >
                            <template #option="slotProps">
                                <div class="flex items-center gap-2">
                                    <span>{{ slotProps.option.nik }} - {{ slotProps.option.nama_lengkap }}</span>
                                </div>
                            </template>
                            <template #value="slotProps">
                                <div v-if="slotProps.value" class="flex items-center gap-2">
                                    <span v-if="props.publik && props.publik.length > 0">
                                        {{ props.publik.find(p => p.id === slotProps.value)?.nik }} - {{ props.publik.find(p => p.id === slotProps.value)?.nama_lengkap }}
                                    </span>
                                    <span v-else>
                                        {{ slotProps.placeholder }}
                                    </span>
                                </div>
                                <span v-else>
                                    {{ slotProps.placeholder }}
                                </span>
                            </template>
                        </Dropdown>
                        <small class="text-red-500" v-if="publicSubmitted && !publicForm.publik_id">Peserta publik wajib diisi.</small>
                        <small v-if="!props.publik || props.publik.length === 0" class="text-yellow-600 block mt-1">
                            ⚠️ Tidak ada peserta publik tersedia. Silakan tambahkan peserta publik terlebih dahulu.
                        </small>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pelaksanaan <span class="text-red-500">*</span></label>
                        <input 
                            type="date" 
                            v-model="publicForm.tanggal" 
                            class="p-inputtext w-full"
                            :class="{'p-invalid': publicSubmitted && !publicForm.tanggal}"
                        />
                        <small class="text-red-500" v-if="publicSubmitted && !publicForm.tanggal">Tanggal wajib diisi.</small>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Mulai</label>
                            <input type="time" v-model="publicForm.waktu_mulai" class="p-inputtext w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Waktu Selesai</label>
                            <input type="time" v-model="publicForm.waktu_selesai" class="p-inputtext w-full" />
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Gedung</label>
                            <Dropdown 
                                v-model="publicForm.gedung_id"
                                :options="props.gedung"
                                optionLabel="nama"
                                optionValue="id"
                                placeholder="Pilih Gedung"
                                showClear
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Ruangan</label>
                            <Dropdown 
                                v-model="publicForm.ruang_id"
                                :options="props.ruang"
                                optionLabel="nama"
                                optionValue="id"
                                placeholder="Pilih Ruang"
                                showClear
                            />
                        </div>
                    </div>
                </div>
            </div>
            
            <template #footer>
                <Button label="Batal" icon="pi pi-times" text @click="hidePublicDialog" />
                <Button label="Simpan" icon="pi pi-check" @click="savePublicSchedule" severity="primary" />
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

/* Input invalid styling */
:deep(.p-invalid) {
    border-color: #ef4444 !important;
}
</style>
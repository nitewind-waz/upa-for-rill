<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast';
import Tag from 'primevue/tag';
import { useToast } from 'primevue/usetoast';

// Interface
interface Acara {
    id: number;
    judul: string;
    deskripsi_singkat: string;
    tanggal_acara: string;
    gambar: string | null;
}

const props = defineProps({
    acaras: {
        type: Array as () => Acara[],
        default: () => [],
    },
    errors: Object,
});

const toast = useToast();

// --- Reactive State ---
const isDialogVisible = ref(false);
const isDeleteDialogVisible = ref(false);
const isEditMode = ref(false);
const acaraToDelete = ref<Acara | null>(null);
const editingAcaraTitle = ref('');
const imagePreview = ref<string | null>(null);

// --- Filtering State ---
const filterJudul = ref('');
const filterTanggal = ref<Date | null>(null);

// --- Computed Properties ---
const filteredAcaras = computed(() => {
    let acaras = props.acaras;

    if (filterJudul.value) {
        acaras = acaras.filter(acara =>
            acara.judul.toLowerCase().includes(filterJudul.value.toLowerCase())
        );
    }

    if (filterTanggal.value) {
        const selectedDate = filterTanggal.value;
        const year = selectedDate.getFullYear();
        const month = (selectedDate.getMonth() + 1).toString().padStart(2, '0');
        const day = selectedDate.getDate().toString().padStart(2, '0');
        const formattedSelectedDate = `${year}-${month}-${day}`;

        acaras = acaras.filter(acara => {
            return acara.tanggal_acara === formattedSelectedDate;
        });
    }

    return acaras;
});

// Form helper
const form = useForm<{
    id: number | null;
    judul: string;
    deskripsi_singkat: string;
    tanggal_acara: Date | null;
    gambar: File | null;
}>({
    id: null,
    judul: '',
    deskripsi_singkat: '',
    tanggal_acara: null,
    gambar: null,
});

const deleteForm = useForm({});

// --- Functions ---
const getImageUrl = (gambar: string | null): string => {
    return gambar ? `/storage/acara/${gambar}` : 'https://via.placeholder.com/400x300?text=No+Image';
};

const formatDate = (dateString: string): string => {
    const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const clearFilters = () => {
    filterJudul.value = '';
    filterTanggal.value = null;
};

const openNew = () => {
    form.reset();
    imagePreview.value = null;
    isEditMode.value = false;
    isDialogVisible.value = true;
};

const editAcara = (acara: Acara) => {
    editingAcaraTitle.value = acara.judul;
    form.id = acara.id;
    form.judul = acara.judul;
    form.deskripsi_singkat = acara.deskripsi_singkat;
    form.tanggal_acara = new Date(acara.tanggal_acara);
    form.gambar = null;
    imagePreview.value = getImageUrl(acara.gambar);
    isEditMode.value = true;
    isDialogVisible.value = true;
};

const hideDialog = () => {
    isDialogVisible.value = false;
    form.reset();
    imagePreview.value = null;
};

const onFileSelect = (event: any) => {
    const file = event.files[0];
    if (file) {
        form.gambar = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const saveAcara = () => {
    const submitOptions = {
        onSuccess: () => {
            hideDialog();
            const detailMessage = isEditMode.value
                ? `Acara "${editingAcaraTitle.value}" berhasil diperbarui.`
                : 'Acara baru berhasil disimpan.';
            toast.add({ severity: 'success', summary: 'Sukses', detail: detailMessage, life: 3000 });
        },
        onError: (errors: any) => {
            if (Object.keys(errors).length === 0) {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Terjadi kesalahan pada server.', life: 3000 });
            }
            console.error('Form submission errors:', errors);
        },
    };

    if (isEditMode.value) {
        form.transform((data: any) => ({
            ...data,
            _method: 'put',
        })).post(`/admin/acara/${form.id}`, submitOptions);
    } else {
        form.post('/admin/acara', submitOptions);
    }
};

const confirmDeleteAcara = (acara: Acara) => {
    acaraToDelete.value = acara;
    isDeleteDialogVisible.value = true;
};

const proceedWithDelete = () => {
    isDeleteDialogVisible.value = false;
    if (acaraToDelete.value) {
        const deletedTitle = acaraToDelete.value.judul;
        deleteForm.delete('/admin/acara/' + acaraToDelete.value.id, {
            onSuccess: () => {
                acaraToDelete.value = null;
                toast.add({ severity: 'success', summary: 'Sukses', detail: `Acara "${deletedTitle}" berhasil dihapus.`, life: 3000 });
            },
            onError: (errors: any) => {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Gagal menghapus acara', life: 3000 });
                console.error('Delete errors:', errors);
            },
        });
    }
};
</script>

<template>
    <AdminLayout title="Manajemen Acara">
        <Head title="Manajemen Acara" />
        <Toast />

        <div class="space-y-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Manajemen Acara</h1>
                    <p class="text-slate-500">Kelola acara dan kegiatan</p>
                </div>
                <Button 
                    label="Tambah Acara" 
                    icon="pi pi-plus" 
                    @click="openNew" 
                    class="!bg-blue-600 !border-blue-600 hover:!bg-blue-700"
                />
            </div>

            <!-- Filter Bar -->
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
                <div class="flex flex-col md:flex-row gap-3">
                    <InputText 
                        v-model="filterJudul" 
                        placeholder="Cari berdasarkan judul..." 
                        class="flex-1"
                    />
                    <Calendar 
                        v-model="filterTanggal" 
                        placeholder="Filter tanggal..." 
                        :showIcon="true" 
                        dateFormat="yy-mm-dd"
                        class="md:w-64"
                    />
                    <Button 
                        label="Clear" 
                        icon="pi pi-times" 
                        severity="secondary"
                        outlined
                        @click="clearFilters" 
                    />
                </div>
            </div>

            <!-- Cards Grid -->
            <div v-if="filteredAcaras.length === 0" class="bg-white shadow rounded-xl p-12 text-center border border-dashed border-slate-300">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-3">
                    <i class="pi pi-calendar text-3xl text-slate-300"></i>
                </div>
                <p class="text-slate-500 font-medium">Belum ada acara.</p>
                <p class="text-slate-400 text-sm mt-1">Klik tombol "Tambah Acara" untuk memulai</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="acara in filteredAcaras" 
                    :key="acara.id"
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-all duration-300 group hover:-translate-y-1"
                >
                    <!-- Image -->
                    <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                        <img 
                            :src="getImageUrl(acara.gambar)" 
                            alt="Acara Image" 
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-60"></div>
                        
                        <div class="absolute top-3 left-3">
                            <Tag 
                                :value="formatDate(acara.tanggal_acara)" 
                                severity="success"
                                class="!text-xs shadow-sm font-bold"
                            >
                                <template #default>
                                    <i class="pi pi-calendar mr-1"></i>
                                    {{ formatDate(acara.tanggal_acara) }}
                                </template>
                            </Tag>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors min-h-[3.5rem]" :title="acara.judul">
                            {{ acara.judul }}
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-3 mb-4 flex-1 leading-relaxed">
                            {{ acara.deskripsi_singkat }}
                        </p>

                        <!-- Actions -->
                        <div class="pt-4 border-t border-slate-100 flex gap-2">
                            <Button 
                                label="Edit" 
                                icon="pi pi-pencil" 
                                severity="info" 
                                outlined 
                                size="small" 
                                class="flex-1 !text-xs !h-9"
                                @click="editAcara(acara)"
                            />
                            <Button 
                                icon="pi pi-trash" 
                                severity="danger" 
                                outlined 
                                size="small" 
                                class="!w-10 !h-9"
                                @click="confirmDeleteAcara(acara)"
                                v-tooltip.top="'Hapus'"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Dialog -->
        <Dialog 
            v-model:visible="isDialogVisible" 
            modal 
            :header="isEditMode ? 'Edit Acara' : 'Tambah Acara Baru'" 
            :style="{ width: '90vw', maxWidth: '600px' }"
            :draggable="false"
            :dismissableMask="true"
        >
            <div class="grid gap-5 py-2">
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-sm text-slate-700">Judul Acara</label>
                    <InputText 
                        v-model="form.judul" 
                        placeholder="Masukkan judul acara" 
                        :class="{'p-invalid': form.errors.judul}" 
                    />
                    <small class="text-red-500" v-if="form.errors.judul">{{ form.errors.judul }}</small>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-sm text-slate-700">Gambar</label>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-24 h-24 rounded-lg border border-slate-300 bg-slate-50 overflow-hidden flex-shrink-0 flex items-center justify-center relative">
                            <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                            <i v-else class="pi pi-image text-slate-400 text-2xl"></i>
                        </div>

                        <div class="flex-1">
                            <FileUpload 
                                mode="basic"
                                name="gambar" 
                                @select="onFileSelect" 
                                accept="image/*" 
                                :maxFileSize="2000000"
                                chooseLabel="Pilih Gambar"
                                class="w-full"
                            />
                            <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG. Max: 2MB.</p>
                            <small class="text-red-500" v-if="form.errors.gambar">{{ form.errors.gambar }}</small>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-sm text-slate-700">Tanggal Acara</label>
                    <Calendar 
                        v-model="form.tanggal_acara" 
                        dateFormat="yy-mm-dd" 
                        :showIcon="true"
                        placeholder="Pilih tanggal acara"
                        :class="{'p-invalid': form.errors.tanggal_acara}"
                    />
                    <small class="text-red-500" v-if="form.errors.tanggal_acara">{{ form.errors.tanggal_acara }}</small>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-sm text-slate-700">Deskripsi Singkat</label>
                    <Textarea 
                        v-model="form.deskripsi_singkat" 
                        rows="4" 
                        placeholder="Tulis deskripsi singkat acara..." 
                        :class="{'p-invalid': form.errors.deskripsi_singkat}" 
                    />
                    <small class="text-red-500" v-if="form.errors.deskripsi_singkat">{{ form.errors.deskripsi_singkat }}</small>
                </div>
            </div>

            <template #footer>
                <Button label="Batal" icon="pi pi-times" text severity="secondary" @click="hideDialog" />
                <Button label="Simpan" icon="pi pi-check" class="!bg-blue-600 !border-blue-600 hover:!bg-blue-700" @click="saveAcara" :loading="form.processing" />
            </template>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog 
            v-model:visible="isDeleteDialogVisible" 
            modal 
            header="Konfirmasi Hapus" 
            :style="{ width: '400px' }"
            :draggable="false"
        >
            <div class="flex items-start gap-4 py-2">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i class="pi pi-exclamation-triangle text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Apakah Anda yakin ingin menghapus acara <span class="font-bold text-slate-900">{{ acaraToDelete?.judul }}</span>?
                    </p>
                </div>
            </div>

            <template #footer>
                <div class="flex gap-2 justify-end mt-4">
                    <Button label="Batal" severity="secondary" text @click="isDeleteDialogVisible = false" />
                    <Button label="Hapus" severity="danger" @click="proceedWithDelete" :loading="deleteForm.processing" autofocus />
                </div>
            </template>
        </Dialog>
    </AdminLayout>
</template>
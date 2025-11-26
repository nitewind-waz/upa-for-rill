<script setup>
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

const props = defineProps({
    beritas: {
        type: Array,
        default: () => [],
    },
    errors: Object,
});

const toast = useToast();

// --- Reactive State ---
const isDialogVisible = ref(false);
const isDeleteDialogVisible = ref(false);
const isEditMode = ref(false);
const beritaToDelete = ref(null);
const editingBeritaTitle = ref('');
const imagePreview = ref(null);

// --- Filtering State ---
const filterJudul = ref('');
const filterTanggal = ref(null);

// --- Computed Properties ---
const filteredBeritas = computed(() => {
    let beritas = props.beritas;

    if (filterJudul.value) {
        beritas = beritas.filter(berita =>
            berita.judul.toLowerCase().includes(filterJudul.value.toLowerCase())
        );
    }

    if (filterTanggal.value) {
        const selectedDate = filterTanggal.value;
        const year = selectedDate.getFullYear();
        const month = (selectedDate.getMonth() + 1).toString().padStart(2, '0');
        const day = selectedDate.getDate().toString().padStart(2, '0');
        const formattedSelectedDate = `${year}-${month}-${day}`;

        beritas = beritas.filter(berita => {
            return berita.tanggal === formattedSelectedDate;
        });
    }

    return beritas;
});

// Form helper
const form = useForm({
    id: null,
    judul: '',
    deskripsi_singkat: '',
    tanggal: '',
    gambar: null,
});

const deleteForm = useForm({});

// --- Functions ---
const getImageUrl = (gambar) => {
    return gambar ? `/storage/berita/${gambar}` : 'https://via.placeholder.com/400x300?text=No+Image';
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
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

const editBerita = (berita) => {
    editingBeritaTitle.value = berita.judul;
    form.id = berita.id;
    form.judul = berita.judul;
    form.deskripsi_singkat = berita.deskripsi_singkat;
    form.tanggal = new Date(berita.tanggal);
    form.gambar = null;
    imagePreview.value = getImageUrl(berita.gambar);
    isEditMode.value = true;
    isDialogVisible.value = true;
};

const hideDialog = () => {
    isDialogVisible.value = false;
    form.reset();
    imagePreview.value = null;
};

const onFileSelect = (event) => {
    const file = event.files[0];
    if (file) {
        form.gambar = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const saveBerita = () => {
    const submitOptions = {
        onSuccess: () => {
            hideDialog();
            const detailMessage = isEditMode.value
                ? `Berita "${editingBeritaTitle.value}" berhasil diperbarui.`
                : 'Berita baru berhasil disimpan.';
            toast.add({ severity: 'success', summary: 'Sukses', detail: detailMessage, life: 3000 });
        },
        onError: (errors) => {
            if (Object.keys(errors).length === 0) {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Terjadi kesalahan pada server.', life: 3000 });
            }
            console.error('Form submission errors:', errors);
        },
    };

    if (isEditMode.value) {
        form.transform(data => ({
            ...data,
            _method: 'put',
        })).post(`/admin/berita/${form.id}`, submitOptions);
    } else {
        form.post('/admin/berita', submitOptions);
    }
};

const confirmDeleteBerita = (berita) => {
    beritaToDelete.value = berita;
    isDeleteDialogVisible.value = true;
};

const proceedWithDelete = () => {
    isDeleteDialogVisible.value = false;
    if (beritaToDelete.value) {
        const deletedTitle = beritaToDelete.value.judul;
        deleteForm.delete('/admin/berita/' + beritaToDelete.value.id, {
            onSuccess: () => {
                beritaToDelete.value = null;
                toast.add({ severity: 'success', summary: 'Sukses', detail: `Berita "${deletedTitle}" berhasil dihapus.`, life: 3000 });
            },
            onError: (errors) => {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Gagal menghapus berita', life: 3000 });
                console.error('Delete errors:', errors);
            },
        });
    }
};
</script>

<template>
    <AdminLayout title="Manajemen Berita">
        <Head title="Manajemen Berita" />
        <Toast />

        <div class="space-y-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Manajemen Berita</h1>
                    <p class="text-slate-500">Kelola berita dan pengumuman</p>
                </div>
                <Button 
                    label="Tambah Berita" 
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
            <div v-if="filteredBeritas.length === 0" class="bg-white shadow rounded-xl p-12 text-center border border-dashed border-slate-300">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-3">
                    <i class="pi pi-inbox text-3xl text-slate-300"></i>
                </div>
                <p class="text-slate-500 font-medium">Belum ada berita.</p>
                <p class="text-slate-400 text-sm mt-1">Klik tombol "Tambah Berita" untuk memulai</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="berita in filteredBeritas" 
                    :key="berita.id"
                    class="bg-white rounded-xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-all duration-300 group hover:-translate-y-1"
                >
                    <!-- Image -->
                    <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                        <img 
                            :src="getImageUrl(berita.gambar)" 
                            alt="Berita Image" 
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-60"></div>
                        
                        <div class="absolute top-3 left-3">
                            <Tag 
                                :value="formatDate(berita.tanggal)" 
                                severity="info"
                                class="!text-xs shadow-sm font-bold"
                            >
                                <template #default>
                                    <i class="pi pi-calendar mr-1"></i>
                                    {{ formatDate(berita.tanggal) }}
                                </template>
                            </Tag>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors min-h-[3.5rem]" :title="berita.judul">
                            {{ berita.judul }}
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-3 mb-4 flex-1 leading-relaxed">
                            {{ berita.deskripsi_singkat }}
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
                                @click="editBerita(berita)"
                            />
                            <Button 
                                icon="pi pi-trash" 
                                severity="danger" 
                                outlined 
                                size="small" 
                                class="!w-10 !h-9"
                                @click="confirmDeleteBerita(berita)"
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
            :header="isEditMode ? 'Edit Berita' : 'Tambah Berita Baru'" 
            :style="{ width: '90vw', maxWidth: '600px' }"
            :draggable="false"
            :dismissableMask="true"
        >
            <div class="grid gap-5 py-2">
                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-sm text-slate-700">Judul Berita</label>
                    <InputText 
                        v-model="form.judul" 
                        placeholder="Masukkan judul berita" 
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
                    <label class="font-semibold text-sm text-slate-700">Tanggal</label>
                    <Calendar 
                        v-model="form.tanggal" 
                        dateFormat="yy-mm-dd" 
                        :showIcon="true"
                        placeholder="Pilih tanggal"
                        :class="{'p-invalid': form.errors.tanggal}"
                    />
                    <small class="text-red-500" v-if="form.errors.tanggal">{{ form.errors.tanggal }}</small>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="font-semibold text-sm text-slate-700">Deskripsi Singkat</label>
                    <Textarea 
                        v-model="form.deskripsi_singkat" 
                        rows="4" 
                        placeholder="Tulis deskripsi singkat berita..." 
                        :class="{'p-invalid': form.errors.deskripsi_singkat}" 
                    />
                    <small class="text-red-500" v-if="form.errors.deskripsi_singkat">{{ form.errors.deskripsi_singkat }}</small>
                </div>
            </div>

            <template #footer>
                <Button label="Batal" icon="pi pi-times" text severity="secondary" @click="hideDialog" />
                <Button label="Simpan" icon="pi pi-check" class="!bg-blue-600 !border-blue-600 hover:!bg-blue-700" @click="saveBerita" :loading="form.processing" />
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
                        Apakah Anda yakin ingin menghapus berita <span class="font-bold text-slate-900">{{ beritaToDelete?.judul }}</span>?
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
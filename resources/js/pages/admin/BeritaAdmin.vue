<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import Paginator from 'primevue/paginator';
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

// Props definition for the component, receiving data from the backend.
const props = defineProps({
    beritas: {
        type: Array,
        default: () => [],
    },
    errors: Object,
});

// Initialize toast service for notifications
const toast = useToast();

// --- Reactive State ---
// Controls the visibility of the create/edit dialog
const isDialogVisible = ref(false);
// Controls the visibility of the delete confirmation dialog
const isDeleteDialogVisible = ref(false);
// Flag to determine if the dialog is in edit mode or create mode
const isEditMode = ref(false);
// Holds the 'berita' object selected for deletion
const beritaToDelete = ref(null);
// Holds the original title of the item being edited to prevent the dialog title from changing live
const editingBeritaTitle = ref('');

// --- Filtering State ---
const filterJudul = ref('');
const filterTanggal = ref(null);

// --- Pagination State ---
const currentPage = ref(1);
const itemsPerPage = 5;

// --- Computed Properties ---
const filteredBeritas = computed(() => {
    let beritas = props.beritas;

    // Filter by title
    if (filterJudul.value) {
        beritas = beritas.filter(berita =>
            berita.judul.toLowerCase().includes(filterJudul.value.toLowerCase())
        );
    }

    // Filter by date
    if (filterTanggal.value) {
        const selectedDate = filterTanggal.value;
        const year = selectedDate.getFullYear();
        const month = (selectedDate.getMonth() + 1).toString().padStart(2, '0');
        const day = selectedDate.getDate().toString().padStart(2, '0');
        const formattedSelectedDate = `${year}-${month}-${day}`;

        beritas = beritas.filter(berita => {
            // Assuming berita.tanggal is already in 'YYYY-MM-DD' format
            return berita.tanggal === formattedSelectedDate;
        });
    }

    return beritas;
});

const paginatedBeritas = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredBeritas.value.slice(start, end);
});

// --- Functions ---

/**
 * Handles the page change event from the paginator.
 * @param {object} event The paginator event.
 */
const onPage = (event) => {
    currentPage.value = event.page + 1;
};

// --- Functions ---

/**
 * Clears all active filters.
 */
const clearFilters = () => {
    filterJudul.value = '';
    filterTanggal.value = null;
};

// Form helper for creating and editing news items.
// Handles form state, validation, and submission.
const form = useForm({
    id: null,
    judul: '',
    deskripsi_singkat: '',
    tanggal: '',
    gambar: null,
});

// A separate form helper for the delete action to manage its loading state independently.
const deleteForm = useForm({});


// --- Functions ---

/**
 * Opens the dialog in 'create' mode.
 */
const openNew = () => {
    form.reset();
    isEditMode.value = false;
    isDialogVisible.value = true;
};

/**
 * Opens the dialog in 'edit' mode and populates the form with existing data.
 * @param {object} berita The news item to edit.
 */
const editBerita = (berita) => {
    editingBeritaTitle.value = berita.judul; // Store original title for dialog header
    form.id = berita.id;
    form.judul = berita.judul;
    form.deskripsi_singkat = berita.deskripsi_singkat;
    form.tanggal = new Date(berita.tanggal);
    form.gambar = null; // Reset gambar on edit to ensure new file is uploaded if desired
    isEditMode.value = true;
    isDialogVisible.value = true;
};

/**
 * Hides the create/edit dialog and resets the form.
 */
const hideDialog = () => {
    isDialogVisible.value = false;
    form.reset();
};

/**
 * Handles the file upload event and assigns the file to the form.
 * @param {object} event The file upload event.
 */
const onUpload = (event) => {
    form.gambar = event.files[0];
};

/**
 * Submits the form to either create or update a news item.
 */
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
            // Only show a toast for non-validation errors (e.g., server errors).
            // Validation errors are automatically displayed beneath their respective fields.
            if (Object.keys(errors).length === 0) {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Terjadi kesalahan pada server.', life: 3000 });
            }
            console.error('Form submission errors:', errors);
        },
    };

    if (isEditMode.value) {
        // For updates, we send a POST request but spoof it as PUT to support multipart/form-data (file uploads).
        form.transform(data => ({
            ...data,
            _method: 'put',
        })).post(`/admin/berita/${form.id}`, submitOptions);
    } else {
        // For creation, a standard POST request is used.
        form.post('/admin/berita', submitOptions);
    }
};

/**
 * Shows the delete confirmation dialog.
 * @param {object} berita The news item to be deleted.
 */
const confirmDeleteBerita = (berita) => {
    beritaToDelete.value = berita;
    isDeleteDialogVisible.value = true;
};

/**
 * Proceeds with the deletion of the selected news item.
 */
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
    <AdminLayout>
        <Toast />
        <div class="card p-6">
            <!-- JUDUL PAGE -->
            <h1 class="text-3xl font-extrabold text-gray-800 mb-6">
                Manajemen Berita
            </h1>

            <!-- FILTER BAR -->
            <div class="flex items-center gap-4 mb-8">
                <InputText v-model="filterJudul" placeholder="Filter berdasarkan judul..." class="flex-1 p-3 rounded-xl shadow-sm" />
                <Calendar v-model="filterTanggal" placeholder="Filter berdasarkan tanggal..." :showIcon="true" dateFormat="yy-mm-dd" class="p-3 rounded-xl shadow-sm" />
                <Button label="Clear" icon="pi pi-times" class="p-button-secondary" @click="clearFilters" />
                <Button label="Tambah Berita" icon="pi pi-plus" class="p-button-rounded p-button-primary" @click="openNew" />
            </div>

            <!-- LIST CARD STYLE -->
            <div class="space-y-6">
                <div v-if="filteredBeritas.length === 0" class="text-center text-gray-500 text-lg py-8">
                    Berita tidak ditemukan.
                </div>
                <div
                    v-for="item in paginatedBeritas"
                    :key="item.id"
                    class="p-6 rounded-3xl bg-gradient-to-r from-blue-100 to-teal-100 shadow-lg flex items-center justify-between"
                >
                    <!-- LEFT AREA -->
                    <div class="flex items-center gap-6">
                        <!-- Thumbnail -->
                        <img
                            v-if="item.gambar"
                            :src="`/storage/berita/${item.gambar}`"
                            class="w-24 h-24 rounded-2xl shadow-md object-cover"
                        />

                        <!-- Text Content -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">{{ item.judul }}</h2>
                            <p class="text-gray-600 text-sm mt-2 max-w-md">
                                {{ item.deskripsi_singkat }}
                            </p>
                            <p class="text-gray-500 text-xs mt-1">
                                Diposting pada: <b>{{ item.tanggal }}</b>
                            </p>
                        </div>
                    </div>

                    <!-- RIGHT ACTIONS -->
                    <div class="flex gap-3">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success" @click="editBerita(item)" />
                        <Button icon="pi pi-trash" class="p-button-rounded p-button-danger" @click="confirmDeleteBerita(item)" />
                    </div>
                </div>
            </div>

            <!-- PAGINATOR -->
            <Paginator
                :rows="itemsPerPage"
                :totalRecords="filteredBeritas.length"
                @page="onPage"
                class="mt-8"
            ></Paginator>
        </div>

        <!-- Create/Edit Dialog -->
        <Dialog v-model:visible="isDialogVisible" :style="{width: '450px'}" :header="isEditMode ? 'Edit Berita: ' + editingBeritaTitle : 'Berita Baru'" :modal="true">
            <div class="p-fluid grid formgrid">
                <div class="field col-12">
                    <label for="judul" class="block mb-2">Judul</label>
                    <InputText id="judul" v-model.trim="form.judul" required="true" autofocus :class="{'p-invalid': errors.judul}" />
                    <small class="p-error block text-red-500" v-if="errors.judul">{{ errors.judul }}</small>
                </div>
                <div class="field col-12">
                    <label for="deskripsi_singkat" class="block mb-2">Deskripsi Singkat</label>
                    <Textarea id="deskripsi_singkat" v-model="form.deskripsi_singkat" required="true" rows="3" cols="20" :class="{'p-invalid': errors.deskripsi_singkat}"/>
                    <small class="p-error block text-red-500" v-if="errors.deskripsi_singkat">{{ errors.deskripsi_singkat }}</small>
                </div>
                <div class="field col-12">
                    <label for="tanggal" class="block mb-2">Tanggal</label>
                    <Calendar id="tanggal" v-model="form.tanggal" dateFormat="yy-mm-dd" required="true" :class="{'p-invalid': errors.tanggal}"/>
                    <small class="p-error block text-red-500" v-if="errors.tanggal">{{ errors.tanggal }}</small>
                </div>
                <div class="field col-12">
                    <label for="gambar" class="block mb-2">Gambar</label>
                    <FileUpload name="gambar" @select="onUpload" :multiple="false" accept="image/*" :maxFileSize="2000000" customUpload>
                        <template #empty>
                            <p>Drag and drop files to here to upload.</p>
                        </template>
                    </FileUpload>
                    <small class="p-error block text-red-500" v-if="errors.gambar">{{ errors.gambar }}</small>
                </div>
            </div>
            <template #footer>
                <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog"/>
                <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveBerita" :loading="form.processing" />
            </template>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:visible="isDeleteDialogVisible" :style="{width: '450px'}" header="Confirm" :modal="true">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span v-if="beritaToDelete">Apakah kamu yakin ingin menghapus berita: <b>{{beritaToDelete.judul}}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" class="p-button-text" @click="isDeleteDialogVisible = false"/>
                <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="proceedWithDelete" :loading="deleteForm.processing" />
            </template>
        </Dialog>
    </AdminLayout>
</template>
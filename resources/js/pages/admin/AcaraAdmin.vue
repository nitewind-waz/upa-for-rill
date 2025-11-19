<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

// Props definition for the component, receiving data from the backend.
defineProps({
    acaras: {
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
// Holds the 'acara' object selected for deletion
const acaraToDelete = ref(null);
// Holds the original title of the item being edited to prevent the dialog title from changing live
const editingAcaraTitle = ref('');

// Form helper for creating and editing news items.
// Handles form state, validation, and submission.
const form = useForm({
    id: null,
    judul: '',
    deskripsi_singkat: '',
    tanggal_acara: '',
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
 * @param {object} acara The news item to edit.
 */
const editAcara = (acara) => {
    editingAcaraTitle.value = acara.judul; // Store original title for dialog header
    form.id = acara.id;
    form.judul = acara.judul;
    form.deskripsi_singkat = acara.deskripsi_singkat;
    form.tanggal_acara = new Date(acara.tanggal_acara);
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
const saveAcara = () => {
    const submitOptions = {
        onSuccess: () => {
            hideDialog();
            const detailMessage = isEditMode.value
                ? `Acara "${editingAcaraTitle.value}" berhasil diperbarui.`
                : 'Acara baru berhasil disimpan.';
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
        })).post(`/admin/acara/${form.id}`, submitOptions);
    } else {
        // For creation, a standard POST request is used.
        form.post('/admin/acara', submitOptions);
    }
};

/**
 * Shows the delete confirmation dialog.
 * @param {object} acara The news item to be deleted.
 */
const confirmDeleteAcara = (acara) => {
    acaraToDelete.value = acara;
    isDeleteDialogVisible.value = true;
};

/**
 * Proceeds with the deletion of the selected news item.
 */
const proceedWithDelete = () => {
    isDeleteDialogVisible.value = false;
    if (acaraToDelete.value) {
        const deletedTitle = acaraToDelete.value.judul;
        deleteForm.delete('/admin/acara/' + acaraToDelete.value.id, {
            onSuccess: () => {
                acaraToDelete.value = null;
                toast.add({ severity: 'success', summary: 'Sukses', detail: `Acara "${deletedTitle}" berhasil dihapus.`, life: 3000 });
            },
            onError: (errors) => {
                toast.add({ severity: 'error', summary: 'Gagal', detail: 'Gagal menghapus acara', life: 3000 });
                console.error('Delete errors:', errors);
            },
        });
    }
};
</script>

<template>
    <AdminLayout>
        <Toast />
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Kelola Acara</h1>
                <Button label="Tambah Acara" icon="pi pi-plus" @click="openNew" />
            </div>

            <DataTable :value="acaras" :paginator="true" :rows="5" responsiveLayout="scroll" showGridlines stripedRows rowHover
                       tableClass="border-separate border-spacing-0 border-2 border-slate-400">
                <Column field="judul" header="Judul" :sortable="true">
                    <template #body="slotProps">
                        <div class="flex justify-center items-center h-full">{{ slotProps.data.judul }}</div>
                    </template>
                </Column>
                <Column header="Gambar">
                    <template #body="slotProps">
                        <img v-if="slotProps.data.gambar" :src="`/storage/acara/${slotProps.data.gambar}`" :alt="slotProps.data.judul" class="w-32 h-auto block mx-auto" />
                    </template>
                </Column>
                <Column field="deskripsi_singkat" header="Deskripsi Singkat">
                    <template #body="slotProps">
                        <div class="flex justify-center items-center h-full">{{ slotProps.data.deskripsi_singkat }}</div>
                    </template>
                </Column>
                <Column field="tanggal_acara" header="Tanggal Acara" :sortable="true">
                    <template #body="slotProps">
                        <div class="flex justify-center items-center h-full">{{ slotProps.data.tanggal_acara }}</div>
                    </template>
                </Column>
                <Column header="Actions">
                    <template #body="slotProps">
                        <div class="flex justify-center items-center">
                            <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="editAcara(slotProps.data)" />
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-danger" @click="confirmDeleteAcara(slotProps.data)" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Create/Edit Dialog -->
        <Dialog v-model:visible="isDialogVisible" :style="{width: '450px'}" :header="isEditMode ? 'Edit Acara: ' + editingAcaraTitle : 'Acara Baru'" :modal="true">
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
                    <label for="tanggal_acara" class="block mb-2">Tanggal Acara</label>
                    <Calendar id="tanggal_acara" v-model="form.tanggal_acara" dateFormat="yy-mm-dd" required="true" :class="{'p-invalid': errors.tanggal_acara}"/>
                    <small class="p-error block text-red-500" v-if="errors.tanggal_acara">{{ errors.tanggal_acara }}</small>
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
                <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveAcara" :loading="form.processing" />
            </template>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:visible="isDeleteDialogVisible" :style="{width: '450px'}" header="Confirm" :modal="true">
            <div class="flex items-center">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span v-if="acaraToDelete">Apakah kamu yakin ingin menghapus acara: <b>{{acaraToDelete.judul}}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" class="p-button-text" @click="isDeleteDialogVisible = false"/>
                <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="proceedWithDelete" :loading="deleteForm.processing" />
            </template>
        </Dialog>
    </AdminLayout>
</template>
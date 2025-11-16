<script setup>
import { ref, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Calendar from 'primevue/calendar';
import FileUpload from 'primevue/fileupload';
import Toast from 'primevue/toast'; // Import Toast component
import { useToast } from 'primevue/usetoast'; // Import useToast composable

const props = defineProps({
    beritas: {
        type: Array,
        default: () => [],
    },
    errors: Object,
});

onMounted(() => {
    console.log('BeritaAdmin component mounted. Initial beritas prop:', props.beritas);
});

const toast = useToast(); // Initialize useToast

const isDialogVisible = ref(false);
const isDeleteDialogVisible = ref(false);
const isEditMode = ref(false);
const selectedBerita = ref(null);
const beritaToDelete = ref(null);

const form = useForm({
    id: null,
    judul: '',
    deskripsi_singkat: '',
    tanggal: '',
    gambar: null,
});

const openNew = () => {
    form.reset();
    isEditMode.value = false;
    isDialogVisible.value = true;
};

const editBerita = (berita) => {
    selectedBerita.value = berita;
    form.id = berita.id;
    form.judul = berita.judul;
    form.deskripsi_singkat = berita.deskripsi_singkat;
    form.tanggal = new Date(berita.tanggal);
    isEditMode.value = true;
    isDialogVisible.value = true;
};

const hideDialog = () => {
    isDialogVisible.value = false;
    form.reset();
};

const onUpload = (event) => {
    form.gambar = event.files[0];
};

const saveBerita = () => {
    const submitOptions = {
        onSuccess: () => {
            hideDialog();
            toast.add({ severity: 'success', summary: 'Success', detail: 'Berita saved successfully', life: 3000 });
        },
        onError: (errors) => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to save berita', life: 3000 });
            console.error('Form submission errors:', errors);
        },
        forceFormData: true,
    };

    if (isEditMode.value) {
        router.post('/admin/berita/' + form.id, {
            _method: 'put',
            judul: form.judul,
            deskripsi_singkat: form.deskripsi_singkat,
            tanggal: form.tanggal,
            gambar: form.gambar,
        }, submitOptions);
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
        router.delete('/admin/berita/' + beritaToDelete.value.id, {
            onSuccess: () => { // Use onSuccess for delete as well
                beritaToDelete.value = null;
                toast.add({ severity: 'success', summary: 'Success', detail: 'Berita deleted successfully', life: 3000 });
            },
            onError: (errors) => {
                toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to delete berita', life: 3000 });
                console.error('Delete errors:', errors);
            },
        });
    }
};

</script>

<template>
    <AdminLayout>
        <Toast /> <!-- Add Toast component here -->
        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Kelola Berita</h1>
                <Button label="Tambah Berita" icon="pi pi-plus" @click="openNew" />
            </div>

            <DataTable :value="beritas" :paginator="true" :rows="10" responsiveLayout="scroll">
                <Column field="judul" header="Judul" :sortable="true"></Column>
                <Column header="Gambar">
                    <template #body="slotProps">
                        <img v-if="slotProps.data.gambar" :src="`/storage/berita/${slotProps.data.gambar}`" :alt="slotProps.data.judul" class="w-32 h-auto" />
                    </template>
                </Column>
                <Column field="deskripsi_singkat" header="Deskripsi Singkat"></Column>
                <Column field="tanggal" header="Tanggal" :sortable="true"></Column>
                <Column header="Actions">
                    <template #body="slotProps">
                        <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="editBerita(slotProps.data)" />
                        <Button icon="pi pi-trash" class="p-button-rounded p-button-danger" @click="confirmDeleteBerita(slotProps.data)" />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Create/Edit Dialog -->
        <Dialog v-model:visible="isDialogVisible" :style="{width: '450px'}" :header="isEditMode ? 'Edit Berita' : 'Berita Baru'" :modal="true">
            <div class="p-fluid grid formgrid">
                <div class="field col-12">
                    <label for="judul" class="block mb-2">Judul</label>
                    <InputText id="judul" v-model.trim="form.judul" required="true" autofocus :class="{'p-invalid': errors.judul}" />
                    <small class="p-error" v-if="errors.judul">{{ errors.judul }}</small>
                </div>
                <div class="field col-12">
                    <label for="deskripsi_singkat" class="block mb-2">Deskripsi Singkat</label>
                    <Textarea id="deskripsi_singkat" v-model="form.deskripsi_singkat" required="true" rows="3" cols="20" :class="{'p-invalid': errors.deskripsi_singkat}"/>
                    <small class="p-error" v-if="errors.deskripsi_singkat">{{ errors.deskripsi_singkat }}</small>
                </div>
                <div class="field col-12">
                    <label for="tanggal" class="block mb-2">Tanggal</label>
                    <Calendar id="tanggal" v-model="form.tanggal" dateFormat="yy-mm-dd" required="true" :class="{'p-invalid': errors.tanggal}"/>
                    <small class="p-error" v-if="errors.tanggal">{{ errors.tanggal }}</small>
                </div>
                <div class="field col-12">
                    <label for="gambar" class="block mb-2">Gambar</label>
                    <FileUpload name="gambar" @select="onUpload" :multiple="false" accept="image/*" :maxFileSize="2000000" customUpload>
                        <template #empty>
                            <p>Drag and drop files to here to upload.</p>
                        </template>
                    </FileUpload>
                    <small class="p-error" v-if="errors.gambar">{{ errors.gambar }}</small>
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
                <span v-if="beritaToDelete">Are you sure you want to delete <b>{{beritaToDelete.judul}}</b>?</span>
            </div>
            <template #footer>
                <Button label="No" icon="pi pi-times" class="p-button-text" @click="isDeleteDialogVisible = false"/>
                <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="proceedWithDelete" :loading="form.processing" />
            </template>
        </Dialog>
    </AdminLayout>
</template>

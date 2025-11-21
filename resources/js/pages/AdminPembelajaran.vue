<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

import AdminLayout from "@/layouts/AdminLayout.vue";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import FileUpload from "primevue/fileupload";
import Toast from "primevue/toast";

const props = defineProps({
    materialPembelajarans: {
        type: Array,
        default: () => [],
    },
});

// Toast
const toast = useToast();

// Dialog
const showDialog = ref(false);
const showDeleteDialog = ref(false);

// Form
const form = useForm({
    id: null,
    judul: "",
    deskripsi_singkat: "",
    link_pdf: null, // This will hold the file object
    link_video: "",
});

// For binding inside dialog
const material = ref({
    id: null,
    judul: "",
    deskripsi_singkat: "",
    link_pdf_url: null, // To display existing PDF link
    link_video: "",
});

// Open NEW dialog
const openNew = () => {
    form.reset();
    material.value = {
        id: null,
        judul: "",
        deskripsi_singkat: "",
        link_pdf_url: null,
        link_video: "",
    };
    showDialog.value = true;
};

// Close
const closeDialog = () => {
    showDialog.value = false;
};

// Handle FILE upload
const onFileSelect = (event: any) => {
    const file = event.files[0];
    if (file) {
        form.link_pdf = file;
    }
};

// Save NEW or UPDATE
const saveMaterial = () => {
    form.id = material.value.id;
    form.judul = material.value.judul;
    form.deskripsi_singkat = material.value.deskripsi_singkat;
    form.link_video = material.value.link_video;

    // Client-side validation for required fields
    if (!form.judul || !form.deskripsi_singkat) {
        toast.add({ severity: "error", summary: "Error", detail: "Isi semua field wajib!", life: 2000 });
        return;
    }

    if (form.id) {
        // Update: Inertia requires POST for multipart forms, so we use _method: "PUT"
        form.transform((data) => ({ ...data, _method: "PUT" }))
            .post(route("admin.pembelajaran.update", form.id), {
                forceFormData: true, // Important for file uploads
                onSuccess: () => {
                    toast.add({ severity: "success", summary: "Berhasil", detail: "Materi diperbarui!", life: 3000 });
                    closeDialog();
                },
                onError: (errors) => {
                    console.error(errors);
                    toast.add({ severity: "error", summary: "Error", detail: "Gagal memperbarui: " + (errors.link_pdf || errors.link_video || errors.judul || errors.deskripsi_singkat || 'Periksa kembali isian Anda'), life: 3000 });
                },
            });
    } else {
        // Create
        form.post(route("admin.pembelajaran.store"), {
            forceFormData: true, // Important for file uploads
            onSuccess: () => {
                toast.add({ severity: "success", summary: "Berhasil", detail: "Materi berhasil ditambahkan!", life: 3000 });
                closeDialog();
            },
            onError: (errors) => {
                console.error(errors);
                toast.add({ severity: "error", summary: "Error", detail: "Gagal membuat: " + (errors.link_pdf || errors.link_video || errors.judul || errors.deskripsi_singkat || 'Periksa kembali isian Anda'), life: 3000 });
            },
        });
    }
};

// Edit data
const editMaterial = (item: any) => {
    material.value = { ...item };
    form.reset(); // Reset form state before populating
    showDialog.value = true;
};

// Delete confirmation
const confirmDelete = (item: any) => {
    material.value = item;
    showDeleteDialog.value = true;
};

// Execute DELETE
const deleteMaterial = () => {
    // We can use the simpler delete helper here as no file is being sent
    useForm({}).delete(route("admin.pembelajaran.destroy", material.value.id), {
        onSuccess: () => {
            toast.add({ severity: "success", summary: "Dihapus", detail: "Materi berhasil dihapus", life: 3000 });
            showDeleteDialog.value = false;
        },
        onError: (errors) => {
            console.error(errors);
            toast.add({ severity: "error", summary: "Error", detail: "Gagal menghapus materi pembelajaran", life: 3000 });
        },
    });
};
</script>

<template>
<AdminLayout>
    <Toast />

    <div class="p-6">
        <!-- Title -->
        <div class="bg-white shadow rounded-xl p-5 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-black">📘 Manajemen Material Pembelajaran</h1>
                <Button label="Tambah Materi" icon="pi pi-plus" @click="openNew" />
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-xl p-4">
            <DataTable :value="materialPembelajarans" responsiveLayout="scroll">
                <Column field="judul" header="Judul" sortable></Column>
                <Column field="deskripsi_singkat" header="Deskripsi"></Column>

                <Column header="File PDF/PPT">
                    <template #body="{ data }">
                        <a v-if="data.link_pdf_url" :href="data.link_pdf_url" target="_blank"
                           class="px-2 py-1 bg-blue-100 text-blue-600 rounded shadow hover:bg-blue-200">
                            Lihat File
                        </a>
                        <span v-else class="text-gray-400">Tidak ada</span>
                    </template>
                </Column>

                <Column header="Video">
                    <template #body="{ data }">
                        <a v-if="data.link_video" :href="data.link_video" target="_blank"
                           class="text-blue-500 underline">
                            Buka YouTube
                        </a>
                        <span v-else class="text-gray-400">Tidak ada</span>
                    </template>
                </Column>

                <Column header="Aksi" style="width: 150px;">
                    <template #body="{ data }">
                        <Button icon="pi pi-pencil" class="mr-2" @click="editMaterial(data)" />
                        <Button icon="pi pi-trash" severity="danger" @click="confirmDelete(data)" />
                    </template>
                </Column>
            </DataTable>
        </div>

        <!-- Dialog Tambah/Edit -->
        <Dialog 
            v-model:visible="showDialog" 
            modal 
            header="Form Material Pembelajaran" 
            class="p-fluid"
            :style="{ width: '500px' }"
        >

            <div class="flex flex-col gap-4">

                <!-- Judul -->
                <div>
                    <label class="block mb-1 font-medium text-black">Judul</label>
                    <InputText 
                        v-model="material.judul" 
                        placeholder="Masukkan judul materi" 
                        class="w-full"
                    />
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block mb-1 font-medium text-black">Deskripsi Singkat</label>
                    <Textarea 
                        rows="4" 
                        v-model="material.deskripsi_singkat" 
                        placeholder="Deskripsi..." 
                        class="w-full"
                    />
                </div>

                <!-- Upload File -->
                <div>
                    <label class="block mb-1 font-medium text-black">Upload File (PDF/PPT)</label>
                    <FileUpload 
                        mode="basic" 
                        accept=".pdf,.ppt,.pptx" 
                        @select="onFileSelect" 
                        class="w-full"
                    />

                    <p v-if="material.link_pdf_url" class="mt-2 text-black">
                        File saat ini:
                        <a 
                            :href="material.link_pdf_url" 
                            target="_blank" 
                            class="text-blue-600 underline"
                        >
                            Lihat File
                        </a>
                    </p>
                </div>

                <!-- YouTube Link -->
                <div>
                    <label class="block mb-1 font-medium text-black">Link Video YouTube</label>
                    <InputText 
                        v-model="material.link_video" 
                        placeholder="https://youtube.com/..." 
                        class="w-full"
                    />
                </div>

            </div>

            <!-- Footer -->
            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Batal" icon="pi pi-times" @click="closeDialog" />
                    <Button label="Simpan" icon="pi pi-check" @click="saveMaterial" :loading="form.processing" />
                </div>
            </template>

        </Dialog>


        <!-- Delete Dialog -->
        <Dialog v-model:visible="showDeleteDialog" modal header="Konfirmasi Hapus" :style="{ width: '380px' }">
            <p class="text-black">Yakin ingin menghapus <b>{{ material.judul }}</b>?</p> <!-- Added text-black -->

            <template #footer>
                <Button label="Batal" icon="pi pi-times" @click="showDeleteDialog = false" />
                <Button label="Hapus" icon="pi pi-trash" severity="danger" @click="deleteMaterial" />
            </template>
        </Dialog>
    </div>
</AdminLayout>
</template>

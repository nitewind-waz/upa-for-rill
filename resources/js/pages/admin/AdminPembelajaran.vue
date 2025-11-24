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
import InputError from "@/components/InputError.vue";

defineProps({
    materialPembelajarans: {
        type: Array,
        default: () => [],
    },
});

const toast = useToast();

const showDialog = ref(false);
const showDeleteDialog = ref(false);

const form = useForm({
    id: null,
    judul: "",
    deskripsi_singkat: "",
    link_pdf: null,
    link_video: "",
    remove_pdf: false, 
});

const material = ref({
    id: null,
    judul: "",
    deskripsi_singkat: "",
    link_pdf_url: null, 
    link_video: "",
});

const openNew = () => {
    form.reset();
    form.remove_pdf = false;

    material.value = {
        id: null,
        judul: "",
        deskripsi_singkat: "",
        link_pdf_url: null,
        link_video: "",
    };
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
};

const onFileSelect = (event: any) => {
    const file = event.files[0];
    if (file) {
        form.link_pdf = file;
    }
};

const saveMaterial = () => {
    form.clearErrors();

    form.id = material.value.id;
    form.judul = material.value.judul;
    form.deskripsi_singkat = material.value.deskripsi_singkat;
    form.link_video = material.value.link_video;

    // Basic client-side validation
    if (!form.judul || !form.deskripsi_singkat) {
        form.errors.judul = form.errors.judul || "Judul wajib diisi.";
        form.errors.deskripsi_singkat = form.errors.deskripsi_singkat || "Deskripsi Singkat wajib diisi.";
        return;
    }

    if (!form.link_pdf && !form.link_video && !material.value.link_pdf_url) {
        form.errors.link_pdf = "Harap unggah file atau masukkan tautan video.";
        form.errors.link_video = "Harap unggah file atau masukkan tautan video.";
        return;
    }

    if (form.id) {
        // Use POST for updates to support file uploads, as defined in web.php
        form.post('/admin/pembelajaran/' + form.id, {
                forceFormData: true, 
                onSuccess: () => {
                    toast.add({ severity: "success", summary: "Berhasil", detail: "Materi diperbarui!", life: 3000 });
                    closeDialog();
                    form.reset(); 
                },
                onError: (errors) => {
                    console.error(errors);
                    if (Object.keys(errors).length === 0) {
                        toast.add({ severity: "error", summary: "Error", detail: "Gagal memperbarui materi. Periksa kembali isian Anda.", life: 3000 });
                    }
                },
            });
    } else {
        form.post('/admin/pembelajaran', {
            forceFormData: true, 
            onSuccess: () => {
                toast.add({ severity: "success", summary: "Berhasil", detail: "Materi berhasil ditambahkan!", life: 3000 });
                closeDialog();
                form.reset();
            },
            onError: (errors) => {
                console.error(errors);
                if (Object.keys(errors).length === 0) {
                    toast.add({ severity: "error", summary: "Error", detail: "Gagal membuat materi. Periksa kembali isian Anda.", life: 3000 });
                }
            },
        });
    }
};

const editMaterial = (item: any) => {
    material.value = { ...item };
    form.reset(); 
    form.remove_pdf = false; 
    showDialog.value = true;
};

const confirmDelete = (item: any) => {
    material.value = item;
    showDeleteDialog.value = true;
};

const deleteMaterial = () => {
    useForm({}).delete('/admin/pembelajaran/' + material.value.id, {
        onSuccess: () => {
            toast.add({ severity: "success", summary: "Dihapus", detail: "Materi berhasil dihapus", life: 3000 });
            showDeleteDialog.value = false;
        },
        onError: (errors) => {
            console.error(errors);
            toast.add({ severity: "error", summary: "Error", detail: errors.error || "Gagal menghapus materi pembelajaran.", life: 3000 });
        },
    });
};

const requestRemovePdf = () => {
    form.remove_pdf = true;
    material.value.link_pdf_url = null; 
    toast.add({ severity: "info", summary: "Info", detail: "File akan dihapus saat disimpan.", life: 3000 });
};
</script>

<template>
<AdminLayout>
    <Toast />

    <div class="p-6">
        <div class="bg-white shadow rounded-xl p-5 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold text-black">📘 Manajemen Material Pembelajaran</h1>
                <Button label="Tambah Materi" icon="pi pi-plus" @click="openNew" />
            </div>
        </div>

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

        <Dialog 
            v-model:visible="showDialog" 
            modal 
            header="Form Material Pembelajaran" 
            class="p-fluid"
            :style="{ width: '500px' }"
        >

            <div class="flex flex-col gap-4">

                <div>
                    <label class="block mb-1 font-medium text-black">Judul</label>
                    <InputText 
                        v-model="material.judul" 
                        placeholder="Masukkan judul materi" 
                        class="w-full"
                        :class="{ 'p-invalid': form.errors.judul }"
                    />
                    <InputError :message="form.errors.judul" />
                </div>

                <div>
                    <label class="block mb-1 font-medium text-black">Deskripsi Singkat</label>
                    <Textarea 
                        rows="4" 
                        v-model="material.deskripsi_singkat" 
                        placeholder="Deskripsi..." 
                        class="w-full"
                        :class="{ 'p-invalid': form.errors.deskripsi_singkat }"
                    />
                    <InputError :message="form.errors.deskripsi_singkat" />
                </div>

                <div>
                    <label class="block mb-1 font-medium text-black">Upload File (PDF/PPT)</label>
                    <FileUpload 
                        mode="basic" 
                        accept=".pdf,.ppt,.pptx" 
                        @select="onFileSelect" 
                        class="w-full"
                        :class="{ 'p-invalid': form.errors.link_pdf }"
                    />
                    <InputError :message="form.errors.link_pdf" />
                    <div v-if="material.link_pdf_url" class="mt-2 text-black flex items-center gap-3">
                        File saat ini:
                        <a 
                            :href="material.link_pdf_url" 
                            target="_blank" 
                            class="text-blue-600 underline"
                        >
                            Lihat File
                        </a>
                        <Button 
                            icon="pi pi-times" 
                            severity="danger"
                            rounded
                            text
                            aria-label="Hapus File"
                            v-tooltip.top="'Hapus file yang sudah ada'"
                            @click="requestRemovePdf" 
                        />
                    </div>
                </div>

                <div>
                    <label class="block mb-1 font-medium text-black">Link Video YouTube</label>
                    <InputText 
                        v-model="material.link_video" 
                        placeholder="https://youtube.com/..." 
                        class="w-full"
                        :class="{ 'p-invalid': form.errors.link_video }"
                    />
                    <InputError :message="form.errors.link_video" />
                </div>

            </div>

            <template #footer>
                <div class="flex justify-end gap-2">
                    <Button label="Batal" icon="pi pi-times" @click="closeDialog" />
                    <Button label="Simpan" icon="pi pi-check" @click="saveMaterial" :loading="form.processing" />
                </div>
            </template>

        </Dialog>


        <Dialog v-model:visible="showDeleteDialog" modal header="Konfirmasi Hapus" :style="{ width: '380px' }">
            <p class="text-black">Yakin ingin menghapus <b>{{ material.judul }}</b>?</p>

            <template #footer>
                <Button label="Batal" icon="pi pi-times" @click="showDeleteDialog = false" />
                <Button label="Hapus" icon="pi pi-trash" severity="danger" @click="deleteMaterial" />
            </template>
        </Dialog>
    </div>
</AdminLayout>
</template>

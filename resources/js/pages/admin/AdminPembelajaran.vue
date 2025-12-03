<script setup lang="ts">
import { computed, ref } from "vue";
import { useForm, Head } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

import AdminLayout from "@/layouts/AdminLayout.vue";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import FileUpload, { FileUploadSelectEvent } from "primevue/fileupload";
import Toast from "primevue/toast";
import InputError from "@/components/InputError.vue";

interface Material {
    id: number | null;
    judul: string;
    deskripsi_singkat: string;
    link_pdf: string | null;
    link_pdf_url?: string | null;
    link_video: string | null;
    file_type?: 'pdf' | 'ppt' | 'pptx' | null;
}

const props = defineProps<{
    materialPembelajarans: Material[]
}>();

const toast = useToast();

const showDialog = ref(false);
const showDeleteDialog = ref(false);
const activeFilter = ref<'All' | 'Video' | 'PDF' | 'PPT'>('All');

const getYoutubeThumbnailUrl = (videoUrl: string | null) => {
    if (!videoUrl) return '';
    const videoIdMatch = videoUrl.match(/(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})(?:\S+)?/);
    return videoIdMatch && videoIdMatch[1] ? `https://img.youtube.com/vi/${videoIdMatch[1]}/hqdefault.jpg` : '';
};

const getFileType = (url: string | null | undefined): 'pdf' | 'ppt' | 'pptx' | null => {
    if (!url) return null;
    const extension = url.split('.').pop()?.toLowerCase();
    if (extension === 'pdf') return 'pdf';
    if (extension === 'ppt' || extension === 'pptx') return 'ppt'; 
    return null;
};

const filteredMaterials = computed(() => {
    const allMaterials = props.materialPembelajarans || [];
    if (activeFilter.value === 'All') {
        return allMaterials;
    }
    if (activeFilter.value === 'Video') {
        return allMaterials.filter(m => m.link_video);
    }
    if (activeFilter.value === 'PDF') {
        return allMaterials.filter(m => getFileType(m.link_pdf_url) === 'pdf');
    }
    if (activeFilter.value === 'PPT') {
        return allMaterials.filter(m => getFileType(m.link_pdf_url) === 'ppt');
    }
    return [];
});


const form = useForm({
    id: null as number | null,
    judul: "",
    deskripsi_singkat: "",
    link_pdf: null as File | null,
    link_video: "",
    remove_pdf: false,
    _method: 'POST' as 'POST' | 'PUT',
});

const material = ref<Material>({
    id: null,
    judul: "",
    deskripsi_singkat: "",
    link_pdf: null,
    link_pdf_url: null,
    link_video: "",
});

const openNew = () => {
    form.reset();
    form.clearErrors();
    form.remove_pdf = false;
    form._method = 'POST';
    material.value = {
        id: null,
        judul: "",
        deskripsi_singkat: "",
        link_pdf: null,
        link_pdf_url: null,
        link_video: "",
    };
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
};

const onFileSelect = (event: FileUploadSelectEvent) => {
    const file = event.files[0];
    if (!file) return;

    const maxSize = 10 * 1024 * 1024;

    if (file.size > maxSize){
        toast.add({
            severity: "error",
            summary: "File terlalu besar!",
            detail: "Maksimal ukuran PPT/PDF adalah 10 MB.",
            life: 3000
        })

        form.link_pdf = null;
        return;
    }

    form.link_pdf = file;
};

const saveMaterial = () => {
    form.clearErrors();

    // Sync form data from the reactive material ref
    form.id = material.value.id;
    form.judul = material.value.judul;
    form.deskripsi_singkat = material.value.deskripsi_singkat;
    form.link_video = material.value.link_video ?? "";

    // Basic frontend validation
    if (!form.judul) form.setError('judul', "Judul wajib diisi.");
    if (!form.deskripsi_singkat) form.setError('deskripsi_singkat', "Deskripsi Singkat wajib diisi.");
    
    // At least one media type must be present
    if (!form.link_pdf && !form.link_video && !material.value.link_pdf_url) {
        form.setError('link_pdf', "Harap unggah file atau masukkan tautan video.");
        form.setError('link_video', "Harap unggah file atau masukkan tautan video.");
    }
    
    if (form.hasErrors) {
        toast.add({ severity: "error", summary: "Validasi Gagal", detail: "Silakan periksa kembali isian Anda.", life: 3000 });
        return;
    }

    const url = form.id ? `/admin/pembelajaran/${form.id}` : '/admin/pembelajaran';
    if(form.id) {
        form._method = 'PUT';
    }

    form.post(url, {
        forceFormData: true,
        onSuccess: () => {
            const message = form.id ? "Materi berhasil diperbarui!" : "Materi berhasil ditambahkan!";
            toast.add({ severity: "success", summary: "Berhasil", detail: message, life: 3000 });
            closeDialog();
            form.reset();
        },
        onError: (errors) => {
            console.error("Save error:", errors);
            toast.add({ severity: "error", summary: "Gagal Menyimpan", detail: "Terjadi kesalahan saat menyimpan data. Periksa konsol untuk detail.", life: 3000 });
        },
    });
};


const editMaterial = (item: Material) => {
    form.reset();
    form.clearErrors();
    form.remove_pdf = false;
    form.id = item.id;
    form._method = 'PUT'; // Set method for update
    material.value = { ...item };
    showDialog.value = true;
};

const confirmDelete = (item: Material) => {
    material.value = item;
    showDeleteDialog.value = true;
};

const deleteMaterial = () => {
    if (!material.value.id) return;

    const deleteForm = useForm({});
    deleteForm.delete('/admin/pembelajaran/' + material.value.id, {
        onSuccess: () => {
            toast.add({ severity: "success", summary: "Dihapus", detail: "Materi berhasil dihapus.", life: 3000 });
            showDeleteDialog.value = false;
        },
        onError: (errors) => {
            console.error(errors);
            toast.add({ severity: "error", summary: "Error", detail: "Gagal menghapus materi.", life: 3000 });
        },
    });
};

const requestRemovePdf = () => {
    form.remove_pdf = true;
    if(material.value) {
        material.value.link_pdf_url = null;
    }
    
};
</script>

<template>
<AdminLayout>
    <Head title="Manajemen Pembelajaran" />
    <Toast />

    <div class="p-6 md:p-8">
        <!-- Header -->
        <div class="bg-white shadow-sm border border-slate-100 rounded-2xl p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Manajemen Materi</h1>
                    <p class="text-slate-500 mt-1">Kelola semua materi pembelajaran untuk mahasiswa.</p>
                </div>
                <Button label="Tambah Materi" icon="pi pi-plus" @click="openNew" severity="success" class="w-full md:w-auto" />
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 flex flex-wrap justify-center gap-2">
            <Button
                @click="activeFilter = 'All'"
                :outlined="activeFilter !== 'All'"
                label="Semua"
                icon="pi pi-clone"
                class="transition-all"
            />
            <Button
                @click="activeFilter = 'Video'"
                :outlined="activeFilter !== 'Video'"
                severity="danger"
                label="Video"
                icon="pi pi-youtube"
                class="transition-all"
            />
            <Button
                @click="activeFilter = 'PDF'"
                :outlined="activeFilter !== 'PDF'"
                severity="info"
                label="PDF"
                icon="pi pi-file-pdf"
                class="transition-all"
            />
             <Button
                @click="activeFilter = 'PPT'"
                :outlined="activeFilter !== 'PPT'"
                severity="warning"
                label="PowerPoint"
                icon="pi pi-file"
                class="transition-all"
            />
        </div>

        <!-- Empty State -->
        <div v-if="filteredMaterials.length === 0" class="bg-white shadow-sm rounded-2xl p-12 text-center border border-slate-100">
            <i class="pi pi-inbox text-6xl text-slate-300 mb-4"></i>
            <h3 class="text-slate-700 text-lg font-semibold">Belum Ada Materi</h3>
            <p class="text-slate-500 text-sm mt-1">Tidak ada materi yang cocok dengan filter <span class="font-bold">{{ activeFilter }}</span>.</p>
        </div>

        <!-- Materials Grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div
                v-for="item in filteredMaterials.filter(m => m.id !== null)"
                :key="item.id!"
                class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-slate-100 flex flex-col group"
            >
                <!-- Card Header (Preview) -->
                <div class="h-48 relative overflow-hidden">
                    <!-- YouTube Video Preview -->
                    <template v-if="item.link_video">
                         <img
                            :src="getYoutubeThumbnailUrl(item.link_video)"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            alt="Video thumbnail"
                        />
                        <div class="absolute inset-0 bg-black/30"></div>
                        <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-lg flex items-center gap-1.5">
                            <i class="pi pi-youtube"></i> VIDEO
                        </div>
                    </template>
                    <!-- PDF Preview -->
                    <template v-else-if="getFileType(item.link_pdf_url) === 'pdf'">
                        <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center p-4">
                            <i class="pi pi-file-pdf text-7xl text-red-500/50 opacity-60"></i>
                        </div>
                        <div class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-lg flex items-center gap-1.5">
                            <i class="pi pi-file-pdf"></i> PDF
                        </div>
                    </template>
                    <!-- PPT Preview -->
                     <template v-else-if="getFileType(item.link_pdf_url) === 'ppt'">
                        <div class="w-full h-full bg-gradient-to-br from-orange-50 to-yellow-100 flex items-center justify-center p-4">
                           <i class="pi pi-file text-7xl text-orange-500/50 opacity-60"></i>
                        </div>
                        <div class="absolute top-3 right-3 bg-orange-600 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-lg flex items-center gap-1.5">
                            <i class="pi pi-file"></i> PPT
                        </div>
                    </template>
                     <!-- Fallback Preview -->
                    <template v-else>
                         <div class="w-full h-full bg-slate-100 flex items-center justify-center p-4">
                            <i class="pi pi-book text-7xl text-slate-300"></i>
                        </div>
                         <div class="absolute top-3 right-3 bg-slate-500 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-lg">
                            MATERI
                        </div>
                    </template>
                </div>

                <!-- Card Content -->
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-slate-800 text-lg mb-2 line-clamp-2 leading-snug flex-1">
                        {{ item.judul }}
                    </h3>
                    <p class="text-sm text-slate-600 mb-4 line-clamp-3 leading-relaxed">
                        {{ item.deskripsi_singkat }}
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 mt-auto pt-4 border-t border-slate-100">
                        <Button
                            icon="pi pi-pencil"
                            severity="warning"
                            outlined
                            size="small"
                            class="flex-1"
                            @click="editMaterial(item)"
                            label="Edit"
                        />
                        <Button
                            icon="pi pi-trash"
                            severity="danger"
                            outlined
                            size="small"
                            class="flex-1"
                            @click="confirmDelete(item)"
                            label="Hapus"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog Form -->
        <Dialog
            v-model:visible="showDialog"
            modal
            :header="form.id ? 'Edit Materi' : 'Tambah Materi Baru'"
            class="p-fluid"
            :style="{ width: '50rem' }"
            :breakpoints="{ '960px': '75vw', '640px': '90vw' }"
        >
            <div class="flex flex-col gap-5 pt-2">
                <div>
                    <label for="judul" class="block mb-2 font-semibold text-slate-700">Judul Materi</label>
                    <InputText
                        id="judul"
                        v-model.trim="material.judul"
                        placeholder="Contoh: Pengenalan TOEFL"
                        class="w-full"
                        :class="{ 'p-invalid': form.errors.judul }"
                    />
                    <InputError :message="form.errors.judul" class="mt-1" />
                </div>

                <div>
                    <label for="deskripsi" class="block mb-2 font-semibold text-slate-700">Deskripsi Singkat</label>
                    <Textarea
                        id="deskripsi"
                        rows="4"
                        v-model="material.deskripsi_singkat"
                        placeholder="Jelaskan secara singkat isi dari materi ini..."
                        class="w-full"
                        :class="{ 'p-invalid': form.errors.deskripsi_singkat }"
                    />
                    <InputError :message="form.errors.deskripsi_singkat" class="mt-1" />
                </div>

                <div class="border-t border-slate-200 my-2"></div>
                
                 <p class="text-sm text-center text-slate-500 -mt-2 mb-2">Pilih salah satu: unggah file ATAU masukkan link video.</p>

                <div>
                    <label class="block mb-2 font-semibold text-slate-700">Upload File (PDF/PPT/PPTX)</label>
                    <FileUpload
                        mode="basic"
                        name="link_pdf"
                        :auto="true"
                        customUpload
                        @select="onFileSelect"
                        :chooseLabel="form.link_pdf ? form.link_pdf.name : 'Pilih File'"
                        class="w-full"
                        :class="{ 'p-invalid': form.errors.link_pdf }"
                        :disabled="!!material.link_video"
                    />
                    <InputError :message="form.errors.link_pdf" class="mt-1"/>
                    <div v-if="!form.link_pdf && material.link_pdf_url" class="mt-3 text-slate-600 flex items-center justify-between p-3 bg-slate-50 rounded-lg border">
                        <div class="flex items-center gap-3">
                           <i class="pi pi-check-circle text-emerald-500"></i>
                           <span>File saat ini: <a :href="material.link_pdf_url" target="_blank" class="text-blue-600 font-medium underline">Lihat File</a></span>
                        </div>
                        <Button
                            icon="pi pi-times"
                            severity="danger"
                            rounded text
                            v-tooltip.top="'Hapus file saat ini'"
                            @click="requestRemovePdf"
                        />
                    </div>
                    <small>Maksimal ukuran PPT/PDF/PPTX adalah 10 MB.</small>
                </div>

                <div class="text-center text-slate-400 font-bold">ATAU</div>

                <div>
                    <label for="link_video" class="block mb-2 font-semibold text-slate-700">Link Video YouTube</label>
                    <InputText
                        id="link_video"
                        v-model="material.link_video"
                        placeholder="Contoh: https://www.youtube.com/watch?v=..."
                        class="w-full"
                        :class="{ 'p-invalid': form.errors.link_video }"
                        :disabled="!!form.link_pdf || !!material.link_pdf_url"
                    />
                    <InputError :message="form.errors.link_video" class="mt-1" />
                </div>
            </div>

            <template #footer>
                <div class="flex justify-end gap-2 mt-6">
                    <Button label="Batal" severity="secondary" outlined icon="pi pi-times" @click="closeDialog" />
                    <Button :label="form.id ? 'Simpan Perubahan' : 'Simpan Materi'" icon="pi pi-check" @click="saveMaterial" :loading="form.processing" />
                </div>
            </template>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:visible="showDeleteDialog" modal header="Konfirmasi Hapus" :style="{ width: '25rem' }">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 flex-shrink-0 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="pi pi-exclamation-triangle text-2xl"></i>
                </div>
                <div>
                     <h3 class="font-bold text-lg text-slate-800">Anda yakin?</h3>
                    <p class="text-slate-600 mt-1">Anda akan menghapus materi <b>"{{ material.judul }}"</b>. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <template #footer>
                <Button label="Batal" severity="secondary" outlined @click="showDeleteDialog = false" class="w-full sm:w-auto" />
                <Button label="Ya, Hapus Saja" severity="danger" @click="deleteMaterial" class="w-full sm:w-auto" />
            </template>
        </Dialog>
    </div>
</AdminLayout>
</template>
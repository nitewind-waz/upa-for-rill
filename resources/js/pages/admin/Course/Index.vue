<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

// PrimeVue Components
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';

// Interface Data
interface Course {
  id: number;
  judul: string;
  images: string | null;
  deskripsi: string;
  ketentuan: string;
  sistem_pembelajaran: string;
  jadwal: string;
  jenis: string;
}

const props = defineProps<{
  courses: Course[];
}>();

// --- STATE ---
const showFormModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const imagePreview = ref<string | null>(null);
const courseToDelete = ref<Course | null>(null);

// --- OPTIONS (SUDAH DISESUAIKAN) ---
// Hanya Gratis dan Berbayar agar tidak kena error constraint database
const jenisOptions = ['Gratis', 'Berbayar']; 
const sistemOptions = ['Daring', 'Luring', 'Hybrid'];

// --- FORM SETUP ---
const form = useForm({
  id: null as number | null,
  judul: '',
  deskripsi: '',
  ketentuan: '',
  jadwal: '',
  jenis: '',
  sistem_pembelajaran: '',
  images: null as File | null,
  _method: 'POST', // Default method
});

// --- METHODS ---

const getImageUrl = (path: string | null) => {
  return path ? `/storage/${path}` : 'https://via.placeholder.com/400x300?text=No+Image';
};

const onFileSelect = (event: Event) => {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (file) {
    form.images = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

// 1. BUKA MODAL CREATE
const openCreate = () => {
  isEditing.value = false;
  form.reset(); 
  form.clearErrors();
  
  // Pastikan method balikan ke POST untuk create
  form._method = 'POST'; 
  
  imagePreview.value = null;
  showFormModal.value = true;
};

// 2. BUKA MODAL EDIT
const openEdit = (course: Course) => {
  isEditing.value = true;
  form.clearErrors();
  
  // Isi data form
  form.id = course.id;
  form.judul = course.judul;
  form.deskripsi = course.deskripsi;
  form.ketentuan = course.ketentuan;
  form.jadwal = course.jadwal;
  form.jenis = course.jenis;
  form.sistem_pembelajaran = course.sistem_pembelajaran;
  form.images = null; // Reset input file

  // KUNCI UPDATE GAMBAR: Ubah method jadi PUT di payload
  form._method = 'PUT'; 

  imagePreview.value = getImageUrl(course.images);
  showFormModal.value = true;
};

// 3. SUBMIT FORM (MANUAL URL & METHOD SPOOFING)
const submitForm = () => {
  if (isEditing.value && form.id) {
    // UPDATE: POST ke /admin/course/{id} dengan _method: 'PUT'
    form.post(`/admin/course/${form.id}`, {
      onSuccess: () => {
        showFormModal.value = false;
        form.reset();
      },
    });
  } else {
    // CREATE: POST ke /admin/course
    form.post('/admin/course', {
      onSuccess: () => {
        showFormModal.value = false;
        form.reset();
      },
    });
  }
};

// 4. DELETE
const confirmDelete = (course: Course) => {
  courseToDelete.value = course;
  showDeleteModal.value = true;
};

const deleteCourse = () => {
  if (!courseToDelete.value) return;
  
  router.delete(`/admin/course/${courseToDelete.value.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false;
      courseToDelete.value = null;
    },
  });
};

// Helper Warna Tag
const getSeverity = (val: string) => {
    if (val === 'Gratis') return 'success';
    if (val === 'Berbayar') return 'info';
    if (val === 'Luring') return 'warn';
    if (val === 'Daring') return 'help';
    return 'secondary';
}
</script>

<template>
  <AdminLayout title="Manajemen Kursus">
    <Head title="Manajemen Kursus" />

    <div class="space-y-8">
      
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-slate-800">Daftar Kursus</h1>
          <p class="text-slate-500">Kelola program kursus (Hanya Gratis & Berbayar).</p>
        </div>
        <Button 
          label="Tambah Kursus" 
          icon="pi pi-plus" 
          @click="openCreate" 
          class="!bg-blue-600 !border-blue-600 hover:!bg-blue-700"
        />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <div 
          v-for="course in props.courses" 
          :key="course.id"
          class="bg-white rounded-xl border border-slate-200 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-all duration-300 group hover:-translate-y-1"
        >
          <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
             <img 
              :src="getImageUrl(course.images)" 
              alt="Course Image" 
              class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent opacity-60"></div>
            
            <div class="absolute top-3 left-3 flex flex-wrap gap-2">
               <Tag :value="course.jenis" :severity="getSeverity(course.jenis)" class="!text-xs shadow-sm font-bold" />
               <Tag :value="course.sistem_pembelajaran" :severity="getSeverity(course.sistem_pembelajaran)" class="!text-xs shadow-sm font-bold" />
            </div>
          </div>

          <div class="p-5 flex flex-col flex-1">
            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors" :title="course.judul">
              {{ course.judul }}
            </h3>
            <p class="text-slate-500 text-sm line-clamp-2 mb-4 flex-1 leading-relaxed">
              {{ course.deskripsi }}
            </p>

            <div class="flex items-center gap-2 text-xs text-slate-500 mb-4 bg-slate-50 p-2 rounded-lg border border-slate-100">
               <i class="pi pi-calendar text-blue-500"></i>
               <span class="font-medium truncate">{{ course.jadwal }}</span>
            </div>

            <div class="pt-4 border-t border-slate-100 flex gap-2">
              <Button 
                label="Edit" 
                icon="pi pi-pencil" 
                severity="info" 
                outlined 
                size="small" 
                class="flex-1 !text-xs !h-9"
                @click="openEdit(course)"
              />
              <Button 
                icon="pi pi-trash" 
                severity="danger" 
                outlined 
                size="small" 
                class="!w-10 !h-9"
                @click="confirmDelete(course)"
                v-tooltip.top="'Hapus'"
              />
            </div>
          </div>
        </div>

        <div v-if="props.courses.length === 0" class="col-span-full py-16 text-center bg-white rounded-xl border border-dashed border-slate-300">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-3">
              <i class="pi pi-images text-3xl text-slate-300"></i>
            </div>
            <p class="text-slate-500 font-medium">Belum ada data kursus.</p>
        </div>

      </div>
    </div>

    <Dialog 
      v-model:visible="showFormModal" 
      modal 
      :header="isEditing ? 'Edit Kursus' : 'Tambah Kursus Baru'" 
      :style="{ width: '90vw', maxWidth: '600px' }"
      :draggable="false"
      :dismissableMask="true"
    >
      <div class="grid gap-5 py-2">
        
        <div class="flex flex-col gap-2">
          <label class="font-semibold text-sm text-slate-700">Judul Kursus</label>
          <InputText v-model="form.judul" placeholder="Contoh: General English Level 1" :class="{'p-invalid': form.errors.judul}" />
          <small class="text-red-500" v-if="form.errors.judul">{{ form.errors.judul }}</small>
        </div>

        <div class="flex flex-col gap-2">
          <label class="font-semibold text-sm text-slate-700">Gambar Cover</label>
          
          <div class="flex items-start gap-4">
            <div class="w-24 h-24 rounded-lg border border-slate-300 bg-slate-50 overflow-hidden flex-shrink-0 flex items-center justify-center relative">
               <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
               <i v-else class="pi pi-image text-slate-400 text-2xl"></i>
            </div>

            <div class="flex-1">
               <input 
                type="file" 
                @change="onFileSelect" 
                accept="image/*"
                class="block w-full text-sm text-slate-500
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-blue-50 file:text-blue-700
                  hover:file:bg-blue-100
                  cursor-pointer
                "
              />
              <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG. Max: 2MB.</p>
              <small class="text-red-500" v-if="form.errors.images">{{ form.errors.images }}</small>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
           <div class="flex flex-col gap-2">
              <label class="font-semibold text-sm text-slate-700">Jenis</label>
              <Dropdown v-model="form.jenis" :options="jenisOptions" placeholder="Pilih Jenis" class="w-full" :class="{'p-invalid': form.errors.jenis}" />
              <small class="text-red-500" v-if="form.errors.jenis">{{ form.errors.jenis }}</small>
           </div>
           <div class="flex flex-col gap-2">
              <label class="font-semibold text-sm text-slate-700">Sistem Pembelajaran</label>
              <Dropdown v-model="form.sistem_pembelajaran" :options="sistemOptions" placeholder="Pilih Sistem" class="w-full" :class="{'p-invalid': form.errors.sistem_pembelajaran}" />
              <small class="text-red-500" v-if="form.errors.sistem_pembelajaran">{{ form.errors.sistem_pembelajaran }}</small>
           </div>
        </div>

        <div class="flex flex-col gap-2">
          <label class="font-semibold text-sm text-slate-700">Jadwal</label>
          <InputText v-model="form.jadwal" placeholder="Contoh: Senin & Rabu, 16.00 WIB" :class="{'p-invalid': form.errors.jadwal}" />
          <small class="text-red-500" v-if="form.errors.jadwal">{{ form.errors.jadwal }}</small>
        </div>

        <div class="flex flex-col gap-2">
          <label class="font-semibold text-sm text-slate-700">Deskripsi Singkat</label>
          <Textarea v-model="form.deskripsi" rows="3" placeholder="Penjelasan singkat tentang kursus..." :class="{'p-invalid': form.errors.deskripsi}" />
          <small class="text-red-500" v-if="form.errors.deskripsi">{{ form.errors.deskripsi }}</small>
        </div>

         <div class="flex flex-col gap-2">
          <label class="font-semibold text-sm text-slate-700">Ketentuan / Syarat</label>
          <Textarea v-model="form.ketentuan" rows="3" placeholder="- Mahasiswa aktif..." :class="{'p-invalid': form.errors.ketentuan}" />
          <small class="text-red-500" v-if="form.errors.ketentuan">{{ form.errors.ketentuan }}</small>
        </div>

      </div>

      <template #footer>
        <Button label="Batal" icon="pi pi-times" text severity="secondary" @click="showFormModal = false" />
        <Button label="Simpan" icon="pi pi-check" class="!bg-blue-600 !border-blue-600 hover:!bg-blue-700" @click="submitForm" :loading="form.processing" />
      </template>
    </Dialog>

    <Dialog 
      v-model:visible="showDeleteModal" 
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
            Apakah Anda yakin ingin menghapus kursus <span class="font-bold text-slate-900">{{ courseToDelete?.judul }}</span>?
          </p>
        </div>
      </div>

      <template #footer>
        <div class="flex gap-2 justify-end mt-4">
          <Button label="Batal" severity="secondary" text @click="showDeleteModal = false" />
          <Button label="Hapus" severity="danger" @click="deleteCourse" autofocus />
        </div>
      </template>
    </Dialog>

  </AdminLayout>
</template>
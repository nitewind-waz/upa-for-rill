<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import AdminLayout from '@/layouts/AdminLayout.vue';

// Komponen PrimeVue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button'; // Kita pakai Button native PrimeVue
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';

interface Course {
  id: number;
  judul: string;
  deskripsi: string;
  ketentuan: string;
  sistem_pembelajaran: 'Daring' | 'Luring' | 'Hybrid';
  jadwal: string;
  jenis: 'Berbayar' | 'Gratis';
}

const props = defineProps<{
  courses: Course[];
}>();

const showDeleteModal = ref(false);
const courseToDelete = ref<Course | null>(null);
const filters = ref({}); // Untuk fitur search nanti jika dibutuhkan

const openAddModal = () => router.get('/admin/course/create');
const editCourse = (c: Course) => router.get(`/admin/course/${c.id}/edit`);

const confirmDeleteCourse = (c: Course) => {
  courseToDelete.value = c;
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

// Helper untuk mapping severity ke komponen Tag PrimeVue
const getJenisSeverity = (jenis: string) => {
  const map: Record<string, string> = {
    'Umum': 'info',
    'Privat': 'warn',
    'Korporat': 'success',
    'Berbayar': 'secondary',
    'Gratis': 'success'
  };
  return map[jenis] || 'secondary';
};

const getSistemSeverity = (sistem: string) => {
  const map: Record<string, string> = {
    'Daring': 'info',    // Biru
    'Luring': 'warn',    // Orange
    'Hybrid': 'contrast' // Gelap/Ungu
  };
  return map[sistem] || 'secondary';
};
</script>

<template>
  <AdminLayout title="Manajemen Kursus">
    <Head title="Manajemen Kursus" />

    <div class="space-y-6">

      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            Manajemen Kursus
          </h1>
          <p class="text-slate-500 mt-1">
            Kelola daftar kursus, jadwal, dan sistem pembelajaran.
          </p>
        </div>

        <div class="flex gap-4">
          <div class="px-5 py-2 rounded-lg bg-slate-50 border border-slate-200 text-center min-w-[100px]">
            <div class="text-xs uppercase tracking-wider text-slate-500 font-semibold mb-1">Total</div>
            <div class="text-xl font-bold text-slate-900">{{ props.courses.length }}</div>
          </div>
          <div class="px-5 py-2 rounded-lg bg-blue-50 border border-blue-100 text-center min-w-[100px]">
            <div class="text-xs uppercase tracking-wider text-blue-600 font-semibold mb-1">Aktif</div>
            <div class="text-xl font-bold text-blue-700">{{ props.courses.length }}</div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
          <IconField iconPosition="left">
            <InputIcon class="pi pi-search" />
            <InputText placeholder="Cari kursus..." class="w-full sm:w-64" size="small" />
          </IconField>

          <Button 
            label="Tambah Kursus" 
            icon="pi pi-plus" 
            @click="openAddModal"
            size="small"
          />
        </div>

        <DataTable
          :value="props.courses"
          paginator
          :rows="10"
          :rowsPerPageOptions="[5, 10, 20]"
          tableStyle="min-width: 50rem"
          
          class="p-datatable-sm"
          
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown CurrentPageReport"
          currentPageReportTemplate="Menampilkan {first} sampai {last} dari {totalRecords} kursus"
        >

          <Column field="judul" header="Informasi Kursus" sortable style="min-width: 250px">
            <template #body="{ data }">
              <div class="flex items-start gap-3 py-1">
                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0 mt-1">
                  <i class="pi pi-book"></i>
                </div>
                <div>
                  <div class="font-semibold text-slate-900 text-base hover:text-blue-600 transition-colors cursor-pointer" @click="editCourse(data)">
                    {{ data.judul }}
                  </div>
                  <div class="text-xs text-slate-500 mt-1 font-mono">ID: #{{ data.id }}</div>
                </div>
              </div>
            </template>
          </Column>

          <Column field="jenis" header="Jenis" sortable style="min-width: 120px">
            <template #body="{ data }">
              <Tag :value="data.jenis" :severity="getJenisSeverity(data.jenis)" rounded />
            </template>
          </Column>

          <Column field="sistem_pembelajaran" header="Sistem" sortable style="min-width: 120px">
            <template #body="{ data }">
              <Tag :value="data.sistem_pembelajaran" :severity="getSistemSeverity(data.sistem_pembelajaran)" icon="pi pi-wifi" />
            </template>
          </Column>

          <Column field="jadwal" header="Jadwal" sortable style="min-width: 180px">
            <template #body="{ data }">
              <div class="flex items-center gap-2 text-slate-600 text-sm">
                <i class="pi pi-calendar text-slate-400"></i>
                <span>{{ data.jadwal }}</span>
              </div>
            </template>
          </Column>

          <Column header="Status" style="min-width: 100px">
            <template #body>
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-sm text-slate-600">Aktif</span>
              </div>
            </template>
          </Column>

          <Column header="Aksi" :exportable="false" style="min-width: 120px" alignFrozen="right" frozen>
            <template #body="{ data }">
              <div class="flex gap-1">
                <Button 
                  icon="pi pi-pencil" 
                  text 
                  rounded 
                  severity="secondary" 
                  @click="editCourse(data)" 
                  v-tooltip.top="'Edit'"
                  class="!w-9 !h-9"
                />
                <Button 
                  icon="pi pi-trash" 
                  text 
                  rounded 
                  severity="danger" 
                  @click="confirmDeleteCourse(data)" 
                  v-tooltip.top="'Hapus'"
                  class="!w-9 !h-9 hover:bg-red-50"
                />
              </div>
            </template>
          </Column>

          <template #empty>
            <div class="text-center py-12">
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                <i class="pi pi-inbox text-slate-300 text-2xl"></i>
              </div>
              <p class="text-slate-500 font-medium">Belum ada data kursus.</p>
            </div>
          </template>

        </DataTable>
      </div>

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
              Data yang dihapus tidak dapat dikembalikan.
            </p>
          </div>
        </div>

        <template #footer>
          <div class="flex gap-2 justify-end mt-4">
            <Button 
              label="Batal" 
              severity="secondary" 
              text
              @click="showDeleteModal = false" 
            />
            <Button 
              label="Hapus" 
              severity="danger" 
              @click="deleteCourse" 
              autofocus
            />
          </div>
        </template>
      </Dialog>

    </div>
  </AdminLayout>
</template>

<style scoped>
/* Kita tidak lagi menggunakan Custom CSS berat (.modern-datatable).
   Kita mengandalkan styling bawaan PrimeVue + Tailwind Utility
   agar konsisten dengan Theme Preset "Blue" yang kita buat.
*/

/* Sedikit tweak untuk font table header agar lebih rapi */
:deep(.p-datatable .p-datatable-thead > tr > th) {
  background: #f8fafc; /* slate-50 */
  color: #64748b;      /* slate-500 */
  font-weight: 600;
  font-size: 0.875rem;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
  color: #334155;      /* slate-700 */
  font-size: 0.875rem;
}

:deep(.p-paginator) {
  border-top: 1px solid #e2e8f0;
}
</style>
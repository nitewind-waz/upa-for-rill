<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import AdminLayout from '@/layouts/AdminLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dialog from 'primevue/dialog';

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

const getJenisConfig = (jenis: string) => {
  const configs = {
    Umum: { severity: 'info', icon: 'pi-users', color: 'from-blue-500 to-blue-600' },
    Privat: { severity: 'warning', icon: 'pi-user', color: 'from-amber-500 to-orange-600' },
    Korporat: { severity: 'success', icon: 'pi-building', color: 'from-emerald-500 to-teal-600' },
  };
  return configs[jenis] || { severity: 'secondary', icon: 'pi-circle', color: 'from-gray-500 to-gray-600' };
};

const getSistemConfig = (sistem: string) => {
  const configs = {
    Daring: { severity: 'info', icon: 'pi-wifi', color: 'bg-blue-500' },
    Luring: { severity: 'warning', icon: 'pi-map-marker', color: 'bg-orange-500' },
    Hybrid: { severity: 'contrast', icon: 'pi-sitemap', color: 'bg-purple-500' },
  };
  return configs[sistem] || { severity: 'secondary', icon: 'pi-circle', color: 'bg-gray-500' };
};
</script>



<template>
  <AdminLayout title="Manajemen Kursus">
    <Head title="Manajemen Kursus" />

    <div class="p-6 space-y-6">

      <!-- HEADER WITH STATS -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
              <i class="pi pi-book text-white text-lg"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">
              Manajemen Kursus
            </h1>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="flex gap-3">
          <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-4 py-3 rounded-xl border border-blue-200">
            <div class="text-xs text-blue-600 font-medium">Total Kursus</div>
            <div class="text-2xl font-bold text-blue-700">{{ props.courses.length }}</div>
          </div>
          <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 px-4 py-3 rounded-xl border border-emerald-200">
            <div class="text-xs text-emerald-600 font-medium">Aktif</div>
            <div class="text-2xl font-bold text-emerald-700">{{ props.courses.length }}</div>
          </div>
        </div>
      </div>

      <!-- TABLE CARD -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

        <DataTable
          :value="props.courses"
          paginator
          :rows="10"
          :rowsPerPageOptions="[5, 10, 20]"
          class="modern-datatable"
        >

          <!-- JUDUL -->
          <Column field="judul" header="Judul Kursus" sortable style="min-width: 250px">
            <template #body="{ data }">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 
                           flex items-center justify-center border-2 border-indigo-200">
                  <i class="pi pi-book text-indigo-600 text-lg"></i>
                </div>
                <div>
                  <div class="font-semibold text-gray-900">{{ data.judul }}</div>
                  <div class="text-xs text-gray-500 mt-0.5">ID: #{{ data.id }}</div>
                </div>
              </div>
            </template>
          </Column>

          <!-- JENIS -->
          <Column field="jenis" header="Jenis" sortable style="min-width: 150px">
            <template #body="{ data }">
              <div class="flex items-center gap-2">
                <div :class="['w-8 h-8 rounded-lg bg-gradient-to-br flex items-center justify-center text-white shadow-md', 
                              getJenisConfig(data.jenis).color]">
                  <i :class="['text-sm', getJenisConfig(data.jenis).icon]"></i>
                </div>
                <span class="font-medium text-gray-700">{{ data.jenis }}</span>
              </div>
            </template>
          </Column>

          <!-- SISTEM -->
          <Column field="sistem_pembelajaran" header="Sistem" sortable style="min-width: 150px">
            <template #body="{ data }">
              <div class="flex items-center gap-2">
                <div :class="['w-8 h-8 rounded-lg flex items-center justify-center text-white shadow-md', 
                              getSistemConfig(data.sistem_pembelajaran).color]">
                  <i :class="['text-sm', getSistemConfig(data.sistem_pembelajaran).icon]"></i>
                </div>
                <span class="font-medium text-gray-700">{{ data.sistem_pembelajaran }}</span>
              </div>
            </template>
          </Column>

          <!-- JADWAL -->
          <Column field="jadwal" header="Jadwal" sortable style="min-width: 200px">
            <template #body="{ data }">
              <div class="flex items-center gap-2 text-gray-600">
                <i class="pi pi-calendar text-indigo-500"></i>
                <span class="text-sm">{{ data.jadwal }}</span>
              </div>
            </template>
          </Column>

          <!-- STATUS -->
          <Column header="Status" style="min-width: 120px">
            <template #body>
              <div class="flex items-center gap-2">
                <span class="flex h-2 w-2">
                  <span class="animate-ping absolute h-2 w-2 rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-sm font-medium text-emerald-600">Aktif</span>
              </div>
            </template>
          </Column>

          <!-- AKSI -->
          <Column header="Aksi" :exportable="false" style="min-width: 140px">
            <template #body="{ data }">
              <div class="flex gap-2">
                <button
                  @click="editCourse(data)"
                  class="group relative w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 
                         hover:from-blue-600 hover:to-blue-700 text-white
                         flex items-center justify-center transition-all duration-200 
                         shadow-md hover:shadow-lg hover:scale-105"
                  title="Edit"
                >
                  <i class="pi pi-pencil text-sm"></i>
                </button>
                <button
                  @click="confirmDeleteCourse(data)"
                  class="group relative w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-red-600 
                         hover:from-red-600 hover:to-red-700 text-white
                         flex items-center justify-center transition-all duration-200 
                         shadow-md hover:shadow-lg hover:scale-105"
                  title="Hapus"
                >
                  <i class="pi pi-trash text-sm"></i>
                </button>
              </div>
            </template>
          </Column>

          <template #empty>
            <div class="text-center py-12">
              <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 
                         flex items-center justify-center">
                <i class="pi pi-inbox text-gray-400 text-3xl"></i>
              </div>
              <p class="text-gray-500 font-medium mb-1">Belum ada data kursus</p>
              <p class="text-gray-400 text-sm">Klik tombol di bawah untuk menambah kursus baru</p>
            </div>
          </template>

        </DataTable>

      </div>

      <!-- TOMBOL TAMBAH -->
      <div class="flex justify-center">
        <button
          @click="openAddModal"
          class="group relative px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 
                 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-xl 
                 shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105
                 flex items-center gap-3"
        >
          <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center">
            <i class="pi pi-plus text-sm"></i>
          </div>
          <span>Tambah Kursus Baru</span>
          <i class="pi pi-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
        </button>
      </div>

      <!-- MODAL -->
      <Dialog
        v-model:visible="showDeleteModal"
        modal
        header="Konfirmasi Hapus"
        :style="{ width: '28rem' }"
        class="rounded-2xl"
      >
        <div class="flex items-start gap-4 p-2">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-100 to-red-200 
                     flex items-center justify-center flex-shrink-0 border-2 border-red-300">
            <i class="pi pi-exclamation-triangle text-red-600 text-xl"></i>
          </div>
          <div>
            <h3 class="font-bold text-gray-900 mb-1.5">Hapus Kursus</h3>
            <p class="text-gray-600 text-sm leading-relaxed" v-if="courseToDelete">
              Apakah Anda yakin ingin menghapus kursus 
              <span class="font-semibold text-gray-900">{{ courseToDelete.judul }}</span>? 
              <br/>
              <span class="text-red-600 font-medium">Tindakan ini tidak dapat dibatalkan.</span>
            </p>
          </div>
        </div>

        <template #footer>
          <div class="flex gap-3 justify-end">
            <Button 
              label="Batal" 
              text 
              class="px-5 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-all font-medium" 
              @click="showDeleteModal = false" 
            />
            <Button 
              label="Hapus Kursus" 
              icon="pi pi-trash"
              class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 
                     text-white border-0 rounded-lg shadow-md hover:shadow-lg transition-all font-medium" 
              @click="deleteCourse" 
            />
          </div>
        </template>

      </Dialog>

    </div>
  </AdminLayout>
</template>



<style>
/* Modern DataTable Styling */
.modern-datatable .p-datatable-table {
  border-spacing: 0;
}

.modern-datatable .p-datatable-thead > tr > th {
  background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
  color: #475569;
  font-weight: 700;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 1rem 1.25rem;
  border: none;
  border-bottom: 2px solid #e2e8f0;
}

.modern-datatable .p-datatable-tbody > tr {
  background: white;
  transition: all 0.2s ease;
}

.modern-datatable .p-datatable-tbody > tr:hover {
  background: linear-gradient(to right, #f8fafc, #ffffff);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.modern-datatable .p-datatable-tbody > tr > td {
  padding: 1rem 1.25rem;
  border: none;
  border-bottom: 1px solid #f1f5f9;
}

.modern-datatable .p-datatable-tbody > tr:last-child > td {
  border-bottom: none;
}

.modern-datatable .p-paginator {
  background: linear-gradient(to bottom, #ffffff, #f8fafc);
  border: none;
  padding: 1rem 1.25rem;
  border-top: 2px solid #e2e8f0;
}

.modern-datatable .p-paginator .p-paginator-pages .p-paginator-page {
  min-width: 2.5rem;
  height: 2.5rem;
  border-radius: 0.5rem;
  margin: 0 0.25rem;
  font-weight: 600;
  transition: all 0.2s ease;
}

.modern-datatable .p-paginator .p-paginator-pages .p-paginator-page:hover {
  background: #f1f5f9;
}

.modern-datatable .p-paginator .p-paginator-pages .p-paginator-page.p-highlight {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  border-color: #3b82f6;
  color: white;
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.modern-datatable .p-datatable-tbody > tr {
  animation: fadeIn 0.3s ease-out;
}
</style>
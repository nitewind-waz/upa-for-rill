<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue';

import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
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
  jenis: 'Umum' | 'Privat' | 'Korporat';
}

// menerima props dari Laravel
const props = defineProps<{
  courses: Course[];
}>();

// state
const showDeleteModal = ref(false);
const courseToDelete = ref<Course | null>(null);

// functions
const openAddModal = () => {
  router.get('/kursus/create');
};

const editCourse = (course: Course) => {
  router.get(`/kursus/${course.id}/edit`);
};

const confirmDeleteCourse = (course: Course) => {
  courseToDelete.value = course;
  showDeleteModal.value = true;
};

const deleteCourse = () => {
  if (courseToDelete.value) {
    router.delete(`/kursus/${courseToDelete.value.id}`, {
      onSuccess: () => {
        showDeleteModal.value = false;
        courseToDelete.value = null;
      },
      onError: () => {
        console.error('Gagal menghapus data');
      },
      preserveScroll: true,
    });
  }
};

// severity color
const getJenisSeverity = (jenis: string) => {
  switch (jenis) {
    case 'Umum': return 'info';
    case 'Privat': return 'warning';
    case 'Korporat': return 'success';
    default: return 'secondary';
  }
};

const getSistemSeverity = (sistem: string) => {
  switch (sistem) {
    case 'Daring': return 'info';
    case 'Luring': return 'warning';
    case 'Hybrid': return 'contrast';
    default: return 'secondary';
  }
};

const rowClassFn = () => {
  return 'hover:bg-gray-50';
};
</script>


<template>
  <AdminLayout title="Manajemen Kursus">
    <Head title="Manajemen Kursus" />

    <div class="p-6 md:p-8 min-h-full space-y-8">

      <!-- HEADER -->
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
            Manajemen Kursus
          </h1>
          <p class="text-gray-500 mt-1 text-sm">
            Kelola daftar kursus, jadwal, dan jenis pembelajaran.
          </p>
        </div>

        <Button 
          label="Tambah Kursus" 
          icon="pi pi-plus" 
          class="bg-yellow-500 border-yellow-500 text-white hover:bg-yellow-600 
                 px-4 py-2 rounded-lg shadow-sm"
          @click="openAddModal" 
        />
      </div>

      <!-- TABLE CARD -->
      <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden">

        <!-- TABEL -->
        <DataTable 
          :value="props.courses"
          paginator 
          :rows="10" 
          :rowsPerPageOptions="[5, 10, 20]"
          tableClass="w-full text-sm"
          headerClass="bg-gray-50 text-gray-600 font-semibold border-b border-gray-200"
          :rowClass="() => 'hover:bg-gray-50'"

          paginatorClass="border-t border-gray-200 bg-white py-3"
          class="modern-table"
        >

          <!-- Judul -->
          <Column field="judul" header="Judul Kursus" sortable style="width: 28%">
            <template #body="{ data }">
              <span class="font-semibold text-gray-800">{{ data.judul }}</span>
            </template>
          </Column>

          <!-- Jenis -->
          <Column field="jenis" header="Jenis" sortable style="width: 15%">
            <template #body="{ data }">
              <Tag 
                :value="data.jenis" 
                :severity="getJenisSeverity(data.jenis)" 
                class="px-2 py-1 text-xs"
                rounded
              />
            </template>
          </Column>

          <!-- Sistem Pembelajaran -->
          <Column field="sistem_pembelajaran" header="Sistem" sortable style="width: 15%">
            <template #body="{ data }">
              <Tag 
                :value="data.sistem_pembelajaran" 
                :severity="getSistemSeverity(data.sistem_pembelajaran)" 
                class="px-2 py-1 text-xs"
                rounded
              />
            </template>
          </Column>

          <!-- Jadwal -->
          <Column field="jadwal" header="Jadwal" sortable style="width: 25%">
            <template #body="{ data }">
              <span class="text-gray-700">{{ data.jadwal }}</span>
            </template>
          </Column>

          <!-- Aksi -->
          <Column header="Aksi" style="width: 18%" :exportable="false">
            <template #body="{ data }">
              <div class="flex gap-2">
                <Button 
                  icon="pi pi-pencil" 
                  class="bg-blue-600 border-blue-600 text-white hover:bg-blue-700 
                         rounded-lg p-2 shadow-sm"
                  @click="editCourse(data)" 
                />
                <Button 
                  icon="pi pi-trash" 
                  class="bg-red-500 border-red-500 text-white hover:bg-red-600 
                         rounded-lg p-2 shadow-sm"
                  @click="confirmDeleteCourse(data)" 
                />
              </div>
            </template>
          </Column>

          <template #empty>
            <div class="text-center p-6 text-gray-500 text-sm">
              Belum ada data kursus yang ditambahkan.
            </div>
          </template>
        </DataTable>
      </div>

      <!-- MODAL -->
      <Dialog 
        v-model:visible="showDeleteModal" 
        modal 
        header="Konfirmasi Hapus" 
        :style="{ width: '25rem' }"
        class="rounded-xl"
      >
        <div class="flex items-center gap-3">
          <i class="pi pi-exclamation-triangle text-yellow-500 text-3xl"></i>
          <p v-if="courseToDelete">
            Apakah Anda yakin ingin menghapus kursus 
            <strong>"{{ courseToDelete.judul }}"</strong>?
          </p>
        </div>

        <p class="mt-2 text-sm text-gray-600">
          Tindakan ini tidak dapat dibatalkan.
        </p>

        <template #footer>
          <Button 
            label="Batal" 
            icon="pi pi-times" 
            @click="showDeleteModal = false" 
            severity="secondary"
            text
            class="px-3"
          />
          <Button 
            label="Hapus" 
            icon="pi pi-trash" 
            @click="deleteCourse" 
            severity="danger" 
            class="px-3"
          />
        </template>
      </Dialog>

    </div>
  </AdminLayout>
</template>


<style scoped>

</style>


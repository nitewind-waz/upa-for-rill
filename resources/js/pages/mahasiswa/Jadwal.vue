<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Card from 'primevue/card';
import Chip from 'primevue/chip';

const selectedUserType = ref('');
const selectedJurusan = ref('');
const selectedProdi = ref('');

const jurusanList = ref([
  {
    nama: 'Teknik Informatika dan Komputer',
    prodi: [
      { nama: 'D4 Teknik Informatika' },
      { nama: 'D3 Teknik Informatika' },
      { nama: 'D4 Teknik Komputer' },
    ],
  },
  {
    nama: 'Administrasi Niaga',
    prodi: [{ nama: 'D4 Administrasi Bisnis' }],
  },
]);

const jadwalMahasiswa = ref([
  {
    id: 1,
    prodi: 'D4 Teknik Informatika',
    kelas: '2A-D4',
    tempat: '402',
    gedung: 'Gedung H',
    tanggal: 'Sabtu, 20 September 2025',
    waktu_mulai: '09:00',
    waktu_selesai: '12:00',
  },
  {
    id: 2,
    prodi: 'D4 Teknik Informatika',
    kelas: '2B-D4',
    tempat: '404',
    gedung: 'Gedung H',
    tanggal: 'Sabtu, 20 September 2025',
    waktu_mulai: '09:00',
    waktu_selesai: '12:00',
  },
  {
    id: 3,
    prodi: 'D3 Teknik Informatika',
    kelas: '2A-D3',
    tempat: '402',
    gedung: 'Gedung H',
    tanggal: 'Sabtu, 20 September 2025',
    waktu_mulai: '13:00',
    waktu_selesai: '15:00',
  },
]);

const jadwalPublik = ref([
  {
    id: 1,
    prodi: 'Publik Batch 1',
    kelas: '-',
    tempat: 'Lab Bahasa 1',
    gedung: 'Gedung UPA',
    tanggal: 'Minggu, 25 September 2025',
    waktu_mulai: '09:00',
    waktu_selesai: '11:00',
  },
  {
    id: 2,
    prodi: 'Publik Batch 2',
    kelas: '-',
    tempat: 'Lab Bahasa 2',
    gedung: 'Gedung UPA',
    tanggal: 'Sabtu, 18 Oktober 2025',
    waktu_mulai: '13:00',
    waktu_selesai: '15:00',
  },
]);

const getProdiByJurusan = (jurusanNama) => {
  const jurusan = jurusanList.value.find((j) => j.nama === jurusanNama);
  return jurusan ? jurusan.prodi : [];
};

const filteredJadwal = computed(() => {
  if (selectedUserType.value === 'publik') {
    return jadwalPublik.value;
  } else if (selectedUserType.value === 'mahasiswa') {
    if (selectedProdi.value) {
      return jadwalMahasiswa.value.filter(
        (j) => j.prodi === selectedProdi.value
      );
    }
  }
  return [];
});
</script>

<template>
  <Head title="Jadwal EPT - UPA Bahasa POLBAN" />

  <Layout>
    <!-- Header -->
    <section class="bg-[#0459A0] text-white py-10 text-center w-screen relative left-1/2 right-1/2 -mx-[50vw]">
        <h1 class="text-2xl md:text-3xl font-bold">Jadwal EPT</h1>
        <p class="mt-1 text-sm md:text-base opacity-90">
            Pilih jadwal sesuai kategori
        </p>
    </section>

<section class="py-10">
  <div class="container mx-auto px-4">
    <div
      class="bg-gray-50 rounded-xl shadow-sm p-6 flex flex-wrap gap-6 items-end justify-start"
    >
      <div class="flex flex-col w-48">
        <label
          class="font-semibold mb-1 bg-[#1E3A8A] text-white px-3 py-1 rounded-t-md"
        >
          Pengguna
        </label>
        <select
          v-model="selectedUserType"
          class="border rounded-b-md p-2 bg-white"
        >
          <option value="">-- Pilih --</option>
          <option value="mahasiswa">Mahasiswa</option>
          <option value="publik">Publik</option>
        </select>
      </div>

      <!-- Jurusan -->
      <div
        v-if="selectedUserType === 'mahasiswa'"
        class="flex flex-col w-64"
      >
        <label
          class="font-semibold mb-1 bg-[#1E3A8A] text-white px-3 py-1 rounded-t-md"
        >
          Jurusan
        </label>
        <select
          v-model="selectedJurusan"
          class="border rounded-b-md p-2 bg-white"
        >
          <option value="">-- Pilih Jurusan --</option>
          <option
            v-for="j in jurusanList"
            :key="j.nama"
            :value="j.nama"
          >
            {{ j.nama }}
          </option>
        </select>
      </div>

      <!-- Program Studi -->
      <div
        v-if="selectedJurusan && selectedUserType === 'mahasiswa'"
        class="flex flex-col w-64"
      >
        <label
          class="font-semibold mb-1 bg-[#1E3A8A] text-white px-3 py-1 rounded-t-md"
        >
          Program Studi
        </label>
        <select
          v-model="selectedProdi"
          class="border rounded-b-md p-2 bg-white"
        >
          <option value="">-- Pilih Prodi --</option>
          <option
            v-for="p in getProdiByJurusan(selectedJurusan)"
            :key="p.nama"
            :value="p.nama"
          >
            {{ p.nama }}
          </option>
        </select>
      </div>
    </div>
  </div>
</section>

    <!-- TABEL HASIL -->
    <section v-if="filteredJadwal.length" class="pb-16 bg-gray-50">
      <div class="container mx-auto px-4">
        <Card>
          <template #content>
            <DataTable :value="filteredJadwal" stripedRows responsiveLayout="scroll">
              <Column field="prodi" header="Prodi" sortable />
              <Column field="kelas" header="Kelas" sortable />
              <Column field="tempat" header="Tempat" sortable />
              <Column field="gedung" header="Gedung" sortable />
              <Column field="tanggal" header="Tanggal" sortable />
              <Column field="waktu_mulai" header="Waktu Mulai" sortable />
              <Column field="waktu_selesai" header="Waktu Selesai" sortable />
            </DataTable>
          </template>
        </Card>
      </div>
    </section>

    <section
      v-else
      class="py-12 bg-gray-50 text-center text-gray-500 italic"
    >
      <p>Silakan pilih opsi lain di atas untuk melihat jadwal EPT.</p>
    </section>
  </Layout>
</template>

<style scoped>
:deep(.p-datatable .p-datatable-thead > tr > th) {
  background-color: #1e3a8a;   
  color: white;
}
select {
  cursor: pointer;
}

select:hover {
  border-color: #1e3a8a;
}
</style>

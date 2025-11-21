<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Card from 'primevue/card';
import Chip from 'primevue/chip';

const props = defineProps({
    jadwal: {
        type: Array,
        default: () => [],
    },
    jurusan: {
        type: Array,
        default: () => [],
    },
    prodi: {
        type: Array,
        default: () => [],
    },
});

const selectedUserType = ref('');
const selectedJurusan = ref(null); 
const selectedProdi = ref(null); 


const jadwalMahasiswaComputed = computed(() => {
    return props.jadwal.filter(j => j.prodi_id !== null && j.kelas_id !== null);
});


const jadwalPublikComputed = computed(() => {
    return props.jadwal.filter(j => j.prodi_id === null && j.kelas_id === null);
});


const prodiList = computed(() => {
    if (!selectedJurusan.value) {
        return [];
    }
    return props.prodi.filter(p => p.jurusan_id === selectedJurusan.value);
});


const filteredJadwal = computed(() => {
  if (selectedUserType.value === 'publik') {
    return jadwalPublikComputed.value;
  } else if (selectedUserType.value === 'mahasiswa') {
    if (selectedProdi.value) {
      return jadwalMahasiswaComputed.value.filter(
        (j) => j.prodi_id === selectedProdi.value
      );
    }
  }
  return [];
});

let polling = null;

onMounted(() => {
  polling = setInterval(() => {
    router.reload({ only: ['jadwal'] });
  }, 20000); // refresh data dalam 20 detik
});

onUnmounted(() => {
  clearInterval(polling);
});
</script>

<template>
  <Head title="Jadwal EPT - UPA Bahasa POLBAN" />

  <Layout>
    <!-- Header -->
    <section class="bg-gradient-to-r from-blue-900 to-blue-800 py-14">
      <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-white mb-2">Jadwal EPT</h1>
        <p class="text-gray-200 text-lg">
          Pilih kategori untuk menampilkan jadwal EPT
        </p>
      </div>
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
          @change="selectedProdi = null"
        >
          <option :value="null">-- Pilih Jurusan --</option>
          <option
            v-for="j in props.jurusan"
            :key="j.id"
            :value="j.id"
          >
            {{ j.nama_jurusan }}
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
          <option :value="null">-- Pilih Prodi --</option>
          <option
            v-for="p in prodiList"
            :key="p.id"
            :value="p.id"
          >
            {{ p.nama_prodi }}
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
              <Column header="Prodi" sortable>
                  <template #body="slotProps">
                      {{ slotProps.data.prodi?.nama_prodi || '-' }}
                  </template>
              </Column>
              <Column header="Kelas" sortable>
                  <template #body="slotProps">
                      {{ slotProps.data.kelas?.nama_kelas || '-' }}
                  </template>
              </Column>
              <Column header="Tempat" sortable>
                  <template #body="slotProps">
                      {{ slotProps.data.ruang?.nama || '-' }}
                  </template>
              </Column>
              <Column header="Gedung" sortable>
                  <template #body="slotProps">
                      {{ slotProps.data.gedung?.nama || '-' }}
                  </template>
              </Column>
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

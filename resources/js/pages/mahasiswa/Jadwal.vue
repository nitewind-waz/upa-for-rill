<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// PrimeVue Components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';
import Button from 'primevue/button';

// --- DEFINISI PROPS (Data dari Controller Laravel) ---
const props = defineProps({
    // Struktur: [{ nama: 'Jurusan A', prodi: [{ nama: 'Prodi A' }] }]
    jurusanList: {
        type: Array,
        default: () => []
    },
    // Data Jadwal Mahasiswa (Master Data)
    jadwalMahasiswa: {
        type: Array,
        default: () => []
    },
    // Data Jadwal Publik (Master Data)
    jadwalPublik: {
        type: Array,
        default: () => []
    }
});

// Static Options
const userTypes = [
    { label: 'Mahasiswa Polban', value: 'mahasiswa' },
    { label: 'Peserta Umum / Publik', value: 'publik' }
];

// State Filter
const selectedUserType = ref(null);
const selectedJurusan = ref(null);
const selectedProdi = ref(null);

// Logic Helper
const getProdiByJurusan = (jurusanNama) => {
  // Mengambil dari props.jurusanList
  const jurusan = props.jurusanList.find((j) => j.nama === jurusanNama);
  return jurusan ? jurusan.prodi : [];
};

// Computed Filter Logic
const filteredJadwal = computed(() => {
  // 1. Jika Publik, ambil dari props.jadwalPublik
  if (selectedUserType.value === 'publik') {
    return props.jadwalPublik;
  } 
  // 2. Jika Mahasiswa, filter dari props.jadwalMahasiswa berdasarkan Prodi
  else if (selectedUserType.value === 'mahasiswa') {
    if (selectedProdi.value) {
      return props.jadwalMahasiswa.filter(
        (j) => j.prodi === selectedProdi.value
      );
    }
  }
  return [];
});

const resetFilter = () => {
    selectedUserType.value = null;
    selectedJurusan.value = null;
    selectedProdi.value = null;
}
</script>

<template>
  <Head title="Jadwal EPT - UPA Bahasa POLBAN" />

  <Layout>
    <div class="min-h-screen bg-slate-50 py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">
                Jadwal Tes EPT
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-slate-500 sm:mt-4">
                Cek jadwal dan lokasi tes English Proficiency Test UPA Bahasa.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <i class="pi pi-filter text-blue-600"></i> Filter Pencarian
                </h3>
                <Button 
                    v-if="selectedUserType" 
                    label="Reset Filter" 
                    icon="pi pi-refresh" 
                    text 
                    size="small" 
                    @click="resetFilter"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-slate-600">Kategori Peserta</label>
                    <Dropdown 
                        v-model="selectedUserType" 
                        :options="userTypes" 
                        optionLabel="label" 
                        optionValue="value" 
                        placeholder="Pilih Kategori" 
                        class="w-full"
                        showClear
                    />
                </div>

                <div class="flex flex-col gap-2" v-if="selectedUserType === 'mahasiswa'">
                    <label class="text-sm font-medium text-slate-600">Jurusan</label>
                    <Dropdown 
                        v-model="selectedJurusan" 
                        :options="props.jurusanList" 
                        optionLabel="nama" 
                        optionValue="nama" 
                        placeholder="Pilih Jurusan" 
                        class="w-full"
                        filter
                        :disabled="!selectedUserType"
                    />
                </div>

                <div class="flex flex-col gap-2" v-if="selectedUserType === 'mahasiswa'">
                    <label class="text-sm font-medium text-slate-600">Program Studi</label>
                    <Dropdown 
                        v-model="selectedProdi" 
                        :options="getProdiByJurusan(selectedJurusan)" 
                        optionLabel="nama" 
                        optionValue="nama" 
                        placeholder="Pilih Prodi" 
                        class="w-full"
                        :disabled="!selectedJurusan"
                        emptyMessage="Pilih Jurusan terlebih dahulu"
                    />
                </div>
                
                <div class="hidden md:block col-span-2" v-if="selectedUserType === 'publik'">
                    <div class="h-full flex items-center text-slate-400 text-sm italic bg-slate-50 rounded-lg px-4 border border-dashed border-slate-200">
                        Jadwal publik ditampilkan untuk semua peserta umum.
                    </div>
                </div>
            </div>
        </div>

        <transition name="fade" mode="out-in">
            
            <div v-if="filteredJadwal.length" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                     <div>
                        <h3 class="text-lg font-bold text-slate-800">Hasil Pencarian</h3>
                        <p class="text-sm text-slate-500">Menampilkan {{ filteredJadwal.length }} jadwal tersedia.</p>
                     </div>
                </div>

                <DataTable :value="filteredJadwal" stripedRows showGridlines class="p-datatable-sm">
                    
                    <Column field="prodi" header="Program Studi / Batch" style="min-width: 200px">
                        <template #body="{ data }">
                            <span class="font-semibold text-slate-700">{{ data.prodi }}</span>
                        </template>
                    </Column>
                    
                    <Column field="kelas" header="Kelas" style="min-width: 100px">
                        <template #body="{ data }">
                            <Tag :value="data.kelas" severity="info" class="font-mono" rounded />
                        </template>
                    </Column>
                    
                    <Column header="Lokasi" style="min-width: 200px">
                        <template #body="{ data }">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-700">{{ data.tempat }}</span>
                                <span class="text-xs text-slate-500">{{ data.gedung }}</span>
                            </div>
                        </template>
                    </Column>
                    
                    <Column header="Waktu Pelaksanaan" style="min-width: 220px">
                        <template #body="{ data }">
                             <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    <i class="pi pi-calendar text-slate-400"></i>
                                    <span>{{ data.tanggal }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-slate-700">
                                    <i class="pi pi-clock text-slate-400"></i>
                                    <span>{{ data.waktu }}</span>
                                </div>
                            </div>
                        </template>
                    </Column>

                     <Column field="status" header="Status" style="min-width: 100px">
                        <template #body="{ data }">
                            <Tag 
                                :value="data.status" 
                                :severity="data.status === 'Dibuka' ? 'success' : (data.status === 'Penuh' ? 'danger' : 'warning')" 
                            />
                        </template>
                    </Column>

                </DataTable>
            </div>

            <div v-else-if="!selectedUserType" class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-4">
                    <i class="pi pi-search text-blue-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900">Mulai Pencarian</h3>
                <p class="text-slate-500 max-w-sm mx-auto mt-2">Silakan pilih <strong>Kategori Peserta</strong> di atas untuk melihat jadwal yang tersedia.</p>
            </div>

            <div v-else class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 mb-4">
                    <i class="pi pi-calendar-times text-slate-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-900">Jadwal Tidak Ditemukan</h3>
                <p class="text-slate-500 max-w-sm mx-auto mt-2">Belum ada jadwal EPT untuk pilihan jurusan/prodi tersebut saat ini.</p>
            </div>

        </transition>

      </div>
    </div>
  </Layout>
</template>

<style scoped>
/* Custom PrimeVue overrides for Clean Look */
:deep(.p-dropdown) {
    border-radius: 0.5rem;
    border-color: #e2e8f0;
}
:deep(.p-dropdown:not(.p-disabled):hover) {
    border-color: #3b82f6;
}
:deep(.p-dropdown:not(.p-disabled).p-focus) {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

/* Table Header Styling */
:deep(.p-datatable .p-datatable-thead > tr > th) {
    background-color: #f8fafc;
    color: #64748b;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
    padding: 1rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}

/* Transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
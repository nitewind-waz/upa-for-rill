<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

// PrimeVue
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';

const props = defineProps({
    jadwalMahasiswa: {
        type: Array,
        default: () => []
    },
    jadwalPublik: {
        type: Array,
        default: () => []
    },
    jurusan: Array,
    prodi: Array,
});

// Tambahkan ini untuk debug
console.log('Jadwal Mahasiswa:', props.jadwalMahasiswa);
console.log('Jadwal Publik:', props.jadwalPublik);
console.log('Jurusan:', props.jurusan);
console.log('Prodi:', props.prodi);

// Static Options for User Type
const userTypes = [
    { label: 'Mahasiswa Polban', value: 'mahasiswa', icon: 'pi pi-id-card' },
    { label: 'Peserta Umum / Publik', value: 'publik', icon: 'pi pi-users' }
];

// ======================
// FILTER STATES
// ======================
const selectedUserType = ref(null); // Re-added
const selectedJurusanId = ref(null);
const selectedProdiId = ref(null);
const globalFilter = ref('');

// Reset Prodi & Jurusan ketika User Type berubah
watch(selectedUserType, () => {
    selectedJurusanId.value = null;
    selectedProdiId.value = null;
});

// Reset Prodi ketika Jurusan berubah
watch(selectedJurusanId, () => {
    selectedProdiId.value = null;
});


// ======================
// DROPDOWN PRODI TERGANTUNG JURUSAN
// ======================
const prodiOptions = computed(() => {
    if (!selectedJurusanId.value) return [];
    return props.prodi.filter(p => p.jurusan_id === selectedJurusanId.value);
});

// ======================
// FILTER UTAMA
// ======================
const filteredJadwal = computed(() => {
  let baseData = [];
  if (selectedUserType.value === 'mahasiswa') {
    baseData = Array.isArray(props.jadwalMahasiswa) ? props.jadwalMahasiswa : [];
  } else if (selectedUserType.value === 'publik') {
    baseData = Array.isArray(props.jadwalPublik) ? props.jadwalPublik : [];
  } else {
    return []; // No user type selected, no data to show
  }

  let data = baseData;
  
  // Apply Jurusan/Prodi filter only for Mahasiswa type
  if (selectedUserType.value === 'mahasiswa') {
      if (selectedProdiId.value) {
        data = data.filter(j => j.prodi_id === selectedProdiId.value);
      } else if (selectedJurusanId.value) {
        const prodiInJurusan = prodiOptions.value.map(p => p.id);
        data = data.filter(j => prodiInJurusan.includes(j.prodi_id));
      }
  }

  if (globalFilter.value) {
    const query = globalFilter.value.toLowerCase();
    
    return data.filter(item => {
      const jurusanName = item.jurusan?.nama_jurusan?.toLowerCase() || '';
      const prodiName = item.prodi?.nama_prodi?.toLowerCase() || '';
      const kelasName = item.kelas?.nama_kelas?.toLowerCase() || '';
      const ruangName = item.ruang?.nama?.toLowerCase() || '';
      const gedungName = item.gedung?.nama?.toLowerCase() || '';

      return (
        jurusanName.includes(query) ||
        prodiName.includes(query) ||
        kelasName.includes(query) ||
        ruangName.includes(query) ||
        gedungName.includes(query) ||
        item.tanggal.toLowerCase().includes(query)
      );
    });
  }

  return data;
});

// ======================
// RESET FILTER
// ======================
const resetFilter = () => {
    selectedUserType.value = null;
    selectedJurusanId.value = null;
    selectedProdiId.value = null;
    globalFilter.value = "";
};

// ======================
// STATUS (Dibuka / Selesai)
// ======================
const getStatusSeverity = (tanggal, waktuSelesai) => {
    if (!tanggal || !waktuSelesai) {
        return { label: "Tidak Valid", severity: "danger" };
    }

    const scheduleDateTime = new Date(`${tanggal}T${waktuSelesai}`);
    const now = new Date();

    if (scheduleDateTime < now) {
        return { label: 'Selesai', severity: 'secondary' };
    }

    return { label: 'Dibuka', severity: 'success' };
};
</script>

<template>
  <Head title="Jadwal EPT" />

  <Layout>
    <div class="min-h-screen flex flex-col bg-slate-50">

        <section class="relative w-full h-[350px] flex items-center justify-center overflow-hidden shadow-md">
            <div 
                class="absolute inset-0 w-full h-full bg-cover bg-center z-0 animate-[pulse_15s_ease-in-out_infinite]"
                style="background-image: url('/banner-upa.jpeg');" 
            ></div>
            <div class="absolute inset-0 bg-slate-900/50 z-10"></div>

            <div class="relative z-30 container mx-auto px-6 text-center pt-8">
                <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight drop-shadow-lg mb-3">
                    Jadwal Tes EPT
                </h1>
                <p class="text-white/90 text-lg max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
                    Informasi jadwal pelaksanaan tes kemampuan bahasa Inggris bagi mahasiswa dan peserta umum.
                </p>
            </div>
        </section>

        <section class="relative z-30 -mt-12 pb-10">
            <div class="container mx-auto px-6">
                
                <div class="bg-blue-50 rounded-2xl shadow-xl border border-blue-100 p-6 md:p-8">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                             <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="pi pi-filter text-blue-600 bg-blue-100/50 p-2 rounded-lg"></i> 
                                Filter Pencarian
                             </h3>
                             <p class="text-sm text-slate-500 mt-1">Pilih kategori peserta untuk melihat jadwal yang sesuai.</p>
                        </div>
                        <Button 
                            v-if="selectedUserType || selectedJurusanId || selectedProdiId || globalFilter" 
                            label="Reset Filter" 
                            icon="pi pi-refresh" 
                            severity="secondary" 
                            text 
                            size="small" 
                            @click="resetFilter"
                            class="!text-slate-500 hover:!text-red-500"
                        />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori Peserta</label>
                            <Dropdown 
                                v-model="selectedUserType" 
                                :options="userTypes" 
                                optionLabel="label" 
                                optionValue="value" 
                                placeholder="Pilih Kategori..." 
                                class="w-full p-inputtext-sm custom-dropdown"
                                showClear
                            >
                                <template #option="slotProps">
                                    <div class="flex items-center gap-2">
                                        <i :class="slotProps.option.icon"></i>
                                        <div>{{ slotProps.option.label }}</div>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <div class="flex flex-col gap-2" :class="{ 'opacity-50 pointer-events-none': selectedUserType !== 'mahasiswa' }">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jurusan</label>
                            <Dropdown 
                                v-model="selectedJurusanId" 
                                :options="props.jurusan" 
                                optionLabel="nama_jurusan" 
                                optionValue="id" 
                                placeholder="Pilih Jurusan..." 
                                class="w-full p-inputtext-sm custom-dropdown"
                                showClear
                                :disabled="selectedUserType !== 'mahasiswa'"
                            />
                        </div>

                        <div class="flex flex-col gap-2" :class="{ 'opacity-50 pointer-events-none': !selectedJurusanId || selectedUserType !== 'mahasiswa' }">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Program Studi</label>
                            <Dropdown 
                                v-model="selectedProdiId" 
                                :options="prodiOptions" 
                                optionLabel="nama_prodi" 
                                optionValue="id" 
                                placeholder="Pilih Prodi..." 
                                class="w-full p-inputtext-sm custom-dropdown"
                                :disabled="!selectedJurusanId || selectedUserType !== 'mahasiswa'"
                                showClear
                                emptyMessage="Pilih Jurusan dahulu"
                            />
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section class="pb-20 min-h-[400px]">
            <div class="container mx-auto px-6">
                
                <transition name="fade" mode="out-in">
                    
                    <div v-if="filteredJadwal.length > 0" class="bg-blue-50 rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
                        
                        <div class="p-5 border-b border-blue-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-blue-50">
                             <div class="flex items-center gap-3">
                                <div class="bg-green-100 p-2 rounded-lg text-green-600">
                                    <i class="pi pi-calendar-check text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">Jadwal Tersedia</h3>
                                    <p class="text-xs text-slate-500">{{ filteredJadwal.length }} sesi ditemukan</p>
                                </div>
                             </div>

                             <IconField iconPosition="left">
                                <InputIcon class="pi pi-search text-slate-400" />
                                <InputText v-model="globalFilter" placeholder="Cari data..." size="small" class="!pl-10 w-full md:w-64 !bg-white" />
                            </IconField>
                        </div>

                        <DataTable 
                            :value="filteredJadwal" 
                            stripedRows 
                            paginator 
                            :rows="10"
                            :rowsPerPageOptions="[10, 20, 50]"
                            class="p-datatable-sm custom-table"
                            columnResizeMode="fit"
                        >
                            <Column header="Program" sortable field="prodi.nama_prodi" style="min-width: 200px">
                                <template #body="{ data }">
                                    <div class="font-bold text-slate-700">{{ data.prodi?.nama_prodi }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ data.jurusan?.nama_jurusan }}</div>
                                </template>
                            </Column>
                            <Column header="Kelas" sortable field="kelas.nama_kelas" style="min-width: 100px">
                                <template #body="{ data }">
                                    <Tag :value="data.kelas?.nama_kelas" severity="secondary" class="font-mono !text-slate-600 bg-slate-100 border border-slate-200" rounded v-if="data.kelas_id" />
                                    <Tag value="UMUM" severity="info" class="font-mono !text-slate-600 bg-slate-100 border border-slate-200" rounded v-else />
                                </template>
                            </Column>
                            <Column header="Waktu & Tanggal" sortable field="tanggal" style="min-width: 220px">
                                <template #body="{ data }">
                                     <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-2 text-sm font-medium text-slate-700">
                                            <i class="pi pi-calendar text-blue-500"></i>
                                            <span>{{ data.tanggal }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-slate-500">
                                            <i class="pi pi-clock"></i>
                                            <span>{{ data.waktu_mulai?.substring(0,5) }} - {{ data.waktu_selesai?.substring(0,5) }} WIB</span>
                                        </div>
                                    </div>
                                </template>
                            </Column>
                            <Column header="Lokasi Tes" style="min-width: 200px">
                                <template #body="{ data }">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-slate-700">{{ data.ruang?.nama }}</span>
                                        <span class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                            <i class="pi pi-map-marker text-[10px]"></i> {{ data.gedung?.nama }}
                                        </span>
                                    </div>
                                </template>
                            </Column>
                             <Column field="status" header="Status" sortable style="min-width: 120px">
                                <template #body="{ data }">
                                    <Tag 
                                        :value="getStatusSeverity(data.tanggal, data.waktu_selesai).label" 
                                        :severity="getStatusSeverity(data.tanggal, data.waktu_selesai).severity" 
                                        class="!text-xs !font-bold shadow-sm"
                                    />
                                </template>
                            </Column>
                        </DataTable>
                    </div>

                    <div v-else-if="!selectedUserType" class="text-center py-16 bg-blue-50 rounded-2xl border-2 border-dashed border-blue-200">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100/50 mb-4 animate-bounce-slow">
                            <i class="pi pi-search text-blue-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Mulai Pencarian Jadwal</h3>
                        <p class="text-slate-500 max-w-sm mx-auto mt-2">Silakan pilih <strong>Kategori Peserta</strong> pada filter di atas untuk menampilkan jadwal yang tersedia.</p>
                    </div>

                    <div v-else class="text-center py-16 bg-blue-50 rounded-2xl border border-blue-100 shadow-sm">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100/50 mb-4">
                            <i class="pi pi-folder-open text-slate-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Jadwal Tidak Ditemukan</h3>
                        <p class="text-slate-500 max-w-md mx-auto mt-2">Maaf, belum ada jadwal EPT yang tersedia untuk kriteria pencarian Anda saat ini.</p>
                        <Button v-if="selectedUserType || selectedJurusanId || selectedProdiId || globalFilter" label="Reset Pencarian" text class="mt-4" @click="resetFilter" />
                    </div>

                </transition>

            </div>
        </section>

    </div>
  </Layout>
</template>

<style scoped>
/* Override PrimeVue Styles for Modern Look */

/* Dropdown (Input tetap putih agar kontras) */
:deep(.custom-dropdown) {
    border-radius: 0.75rem;
    border-color: #e2e8f0;
    background-color: #ffffff; /* Tetap putih */
    box-shadow: none;
    transition: all 0.2s;
}
:deep(.custom-dropdown:not(.p-disabled):hover) {
    border-color: #3b82f6;
}
:deep(.custom-dropdown.p-focus) {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* DataTable Styling dengan nuansa biru */
:deep(.custom-table .p-datatable-thead > tr > th) {
    background-color: #eff6ff; /* blue-50 untuk header tabel */
    color: #1e40af; /* blue-800 */
    font-weight: 700;
    font-size: 0.80rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1.25rem 1rem;
    border-bottom: 1px solid #dbeafe; /* blue-100 */
}

:deep(.custom-table .p-datatable-tbody > tr > td) {
    padding: 1.25rem 1rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.9rem;
    background-color: #ffffff; /* Baris tabel tetap putih */
}
/* Baris genap (striped) diberi warna biru sangat muda */
:deep(.custom-table .p-datatable-tbody > tr:nth-child(even)) {
    background-color: #f8fafc !important; 
}

:deep(.custom-table .p-datatable-tbody > tr:hover) {
    background-color: #eff6ff !important; /* hover jadi blue-50 */
}

/* Paginator */
:deep(.p-paginator) {
    border-top: 1px solid #dbeafe; /* blue-100 */
    padding: 1rem;
    background-color: #eff6ff; /* blue-50 */
}

/* Animasi Tambahan */
.animate-bounce-slow {
    animation: bounce 3s infinite;
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(-5%);
    animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
  }
  50% {
    transform: translateY(0);
    animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
  }
}

/* Transition Fade */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Card from 'primevue/card';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Chip from 'primevue/chip';

const searchQuery = ref('');

const jadwalReguler = ref([
  {
    id: 1,
    kelas: 'Bahasa Inggris Basic',
    hari: 'Senin & Rabu',
    waktu: '08:00 - 10:00',
    ruangan: 'Lab Bahasa 1',
    pengajar: 'Dr. Sarah Johnson',
    kuota: 25,
    terisi: 18,
    status: 'available'
  },
  {
    id: 2,
    kelas: 'Bahasa Inggris Intermediate',
    hari: 'Selasa & Kamis',
    waktu: '10:00 - 12:00',
    ruangan: 'Lab Bahasa 2',
    pengajar: 'Prof. Michael Chen',
    kuota: 20,
    terisi: 20,
    status: 'full'
  },
  {
    id: 3,
    kelas: 'Bahasa Inggris Advanced',
    hari: 'Rabu & Jumat',
    waktu: '13:00 - 15:00',
    ruangan: 'Lab Bahasa 3',
    pengajar: 'Dr. Lisa Anderson',
    kuota: 20,
    terisi: 15,
    status: 'available'
  },
  {
    id: 4,
    kelas: 'Business English',
    hari: 'Senin & Kamis',
    waktu: '15:00 - 17:00',
    ruangan: 'Lab Bahasa 1',
    pengajar: 'Mr. David Wilson',
    kuota: 15,
    terisi: 12,
    status: 'available'
  },
  {
    id: 5,
    kelas: 'TOEFL Preparation',
    hari: 'Selasa & Jumat',
    waktu: '08:00 - 10:00',
    ruangan: 'Lab Komputer 1',
    pengajar: 'Mrs. Emily Brown',
    kuota: 30,
    terisi: 28,
    status: 'limited'
  }
]);

const jadwalEPT = ref([
  {
    id: 1,
    sesi: 'EPT Batch 1/2025',
    tanggal: '15 Januari 2025',
    waktu: '08:00 - 11:00',
    ruangan: 'Aula Utama',
    kuota: 100,
    terisi: 85,
    status: 'available'
  },
  {
    id: 2,
    sesi: 'EPT Batch 2/2025',
    tanggal: '20 Februari 2025',
    waktu: '08:00 - 11:00',
    ruangan: 'Aula Utama',
    kuota: 100,
    terisi: 45,
    status: 'available'
  },
  {
    id: 3,
    sesi: 'EPT Batch 3/2025',
    tanggal: '15 Maret 2025',
    waktu: '08:00 - 11:00',
    ruangan: 'Aula Utama',
    kuota: 100,
    terisi: 100,
    status: 'full'
  }
]);

const getStatusSeverity = (status) => {
  switch (status) {
    case 'available':
      return 'success';
    case 'limited':
      return 'warn';
    case 'full':
      return 'danger';
    default:
      return 'info';
  }
};

const getStatusLabel = (status) => {
  switch (status) {
    case 'available':
      return 'Tersedia';
    case 'limited':
      return 'Terbatas';
    case 'full':
      return 'Penuh';
    default:
      return 'Unknown';
  }
};
</script>

<template>
  <Head title="Jadwal - UPA Bahasa POLBAN" />
  
  <Layout>
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-900 to-blue-800 py-16">
      <div class="container mx-auto px-4">
        <div class="max-w-3xl">
          <Chip label="Jadwal Kursus" class="mb-4 bg-orange-500 text-white" />
          <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
            Jadwal Kursus & Ujian
          </h1>
          <p class="text-xl text-gray-200">
            Temukan jadwal kursus dan ujian yang sesuai dengan waktu Anda
          </p>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4">
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
          <Card class="bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <template #content>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm opacity-90 mb-1">Total Kelas</p>
                  <p class="text-3xl font-bold">12</p>
                </div>
                <i class="pi pi-book text-4xl opacity-50"></i>
              </div>
            </template>
          </Card>

          <Card class="bg-gradient-to-br from-green-500 to-green-600 text-white">
            <template #content>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm opacity-90 mb-1">Kelas Tersedia</p>
                  <p class="text-3xl font-bold">8</p>
                </div>
                <i class="pi pi-check-circle text-4xl opacity-50"></i>
              </div>
            </template>
          </Card>

          <Card class="bg-gradient-to-br from-orange-500 to-orange-600 text-white">
            <template #content>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm opacity-90 mb-1">EPT Mendatang</p>
                  <p class="text-3xl font-bold">2</p>
                </div>
                <i class="pi pi-calendar text-4xl opacity-50"></i>
              </div>
            </template>
          </Card>

          <Card class="bg-gradient-to-br from-purple-500 to-purple-600 text-white">
            <template #content>
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm opacity-90 mb-1">Peserta Aktif</p>
                  <p class="text-3xl font-bold">245</p>
                </div>
                <i class="pi pi-users text-4xl opacity-50"></i>
              </div>
            </template>
          </Card>
        </div>

        <!-- Jadwal Tables -->
        <Card>
          <template #content>
            <TabView>
              <!-- Tab Kursus Reguler -->
              <TabPanel>
                <template #header>
                  <div class="flex items-center gap-2">
                    <i class="pi pi-book"></i>
                    <span>Kursus Reguler</span>
                  </div>
                </template>

                <div class="mb-4">
                  <IconField iconPosition="left">
                    <InputIcon>
                      <i class="pi pi-search" />
                    </InputIcon>
                    <InputText 
                      v-model="searchQuery" 
                      placeholder="Cari kelas..." 
                      class="w-full md:w-96"
                    />
                  </IconField>
                </div>

                <DataTable 
                  :value="jadwalReguler" 
                  paginator 
                  :rows="5"
                  :rowsPerPageOptions="[5, 10, 20]"
                  responsiveLayout="scroll"
                  stripedRows
                  :globalFilterFields="['kelas', 'pengajar', 'hari']"
                  :globalFilter="searchQuery"
                >
                  <Column field="kelas" header="Nama Kelas" sortable>
                    <template #body="slotProps">
                      <div class="font-semibold text-gray-900">
                        {{ slotProps.data.kelas }}
                      </div>
                    </template>
                  </Column>
                  <Column field="hari" header="Hari" sortable></Column>
                  <Column field="waktu" header="Waktu" sortable></Column>
                  <Column field="ruangan" header="Ruangan" sortable></Column>
                  <Column field="pengajar" header="Pengajar" sortable></Column>
                  <Column header="Kuota">
                    <template #body="slotProps">
                      <div class="text-center">
                        <span class="font-semibold">
                          {{ slotProps.data.terisi }}/{{ slotProps.data.kuota }}
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                          <div 
                            class="h-2 rounded-full transition-all"
                            :class="{
                              'bg-green-500': slotProps.data.status === 'available',
                              'bg-orange-500': slotProps.data.status === 'limited',
                              'bg-red-500': slotProps.data.status === 'full'
                            }"
                            :style="{ width: (slotProps.data.terisi / slotProps.data.kuota * 100) + '%' }"
                          ></div>
                        </div>
                      </div>
                    </template>
                  </Column>
                  <Column header="Status">
                    <template #body="slotProps">
                      <Tag 
                        :value="getStatusLabel(slotProps.data.status)" 
                        :severity="getStatusSeverity(slotProps.data.status)"
                      />
                    </template>
                  </Column>
                  <Column header="Aksi">
                    <template #body="slotProps">
                      <Button 
                        :label="slotProps.data.status === 'full' ? 'Penuh' : 'Daftar'" 
                        :disabled="slotProps.data.status === 'full'"
                        icon="pi pi-user-plus"
                        size="small"
                        :severity="slotProps.data.status === 'full' ? 'secondary' : 'primary'"
                      />
                    </template>
                  </Column>
                </DataTable>
              </TabPanel>

              <!-- Tab EPT -->
              <TabPanel>
                <template #header>
                  <div class="flex items-center gap-2">
                    <i class="pi pi-file-check"></i>
                    <span>Jadwal EPT</span>
                  </div>
                </template>

                <DataTable 
                  :value="jadwalEPT" 
                  responsiveLayout="scroll"
                  stripedRows
                >
                  <Column field="sesi" header="Sesi" sortable>
                    <template #body="slotProps">
                      <div class="font-semibold text-gray-900">
                        {{ slotProps.data.sesi }}
                      </div>
                    </template>
                  </Column>
                  <Column field="tanggal" header="Tanggal" sortable></Column>
                  <Column field="waktu" header="Waktu" sortable></Column>
                  <Column field="ruangan" header="Ruangan" sortable></Column>
                  <Column header="Kuota">
                    <template #body="slotProps">
                      <div class="text-center">
                        <span class="font-semibold">
                          {{ slotProps.data.terisi }}/{{ slotProps.data.kuota }}
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                          <div 
                            class="h-2 rounded-full transition-all"
                            :class="{
                              'bg-green-500': slotProps.data.status === 'available',
                              'bg-red-500': slotProps.data.status === 'full'
                            }"
                            :style="{ width: (slotProps.data.terisi / slotProps.data.kuota * 100) + '%' }"
                          ></div>
                        </div>
                      </div>
                    </template>
                  </Column>
                  <Column header="Status">
                    <template #body="slotProps">
                      <Tag 
                        :value="getStatusLabel(slotProps.data.status)" 
                        :severity="getStatusSeverity(slotProps.data.status)"
                      />
                    </template>
                  </Column>
                  <Column header="Aksi">
                    <template #body="slotProps">
                      <Button 
                        :label="slotProps.data.status === 'full' ? 'Penuh' : 'Daftar EPT'" 
                        :disabled="slotProps.data.status === 'full'"
                        icon="pi pi-user-plus"
                        size="small"
                        :severity="slotProps.data.status === 'full' ? 'secondary' : 'success'"
                      />
                    </template>
                  </Column>
                </DataTable>
              </TabPanel>
            </TabView>
          </template>
        </Card>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
          <Card>
            <template #header>
              <div class="p-6 pb-0">
                <div class="flex items-center gap-3">
                  <div class="bg-blue-100 p-3 rounded-full">
                    <i class="pi pi-info-circle text-2xl text-blue-600"></i>
                  </div>
                  <h3 class="text-xl font-semibold">Informasi Pendaftaran</h3>
                </div>
              </div>
            </template>
            <template #content>
              <ul class="space-y-3">
                <li class="flex items-start gap-2">
                  <i class="pi pi-check text-green-600 mt-1"></i>
                  <span>Pendaftaran dibuka H-7 sebelum kelas dimulai</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="pi pi-check text-green-600 mt-1"></i>
                  <span>Pembayaran dapat dilakukan via transfer atau tunai</span>
                </li>
                <li class="flex items-start gap-2">
                  <i class="pi pi-check text-green-600 mt-1"></i>
                  <span>Konfirmasi pendaftaran via email maksimal 2x24 jam</span>
                </li>
              </ul>
            </template>
          </Card>

          <Card>
            <template #header>
              <div class="p-6 pb-0">
                <div class="flex items-center gap-3">
                  <div class="bg-orange-100 p-3 rounded-full">
                    <i class="pi pi-phone text-2xl text-orange-600"></i>
                  </div>
                  <h3 class="text-xl font-semibold">Butuh Bantuan?</h3>
                </div>
              </div>
            </template>
            <template #content>
              <p class="text-gray-600 mb-4">
                Hubungi kami untuk informasi lebih lanjut mengenai jadwal dan pendaftaran
              </p>
              <div class="flex flex-col gap-2">
                <Button 
                  label="WhatsApp" 
                  icon="pi pi-whatsapp" 
                  severity="success"
                  outlined
                />
                <Button 
                  label="Email" 
                  icon="pi pi-envelope" 
                  severity="primary"
                  outlined
                />
              </div>
            </template>
          </Card>
        </div>
      </div>
    </section>
  </Layout>
</template>

<style scoped>
:deep(.p-datatable .p-datatable-thead > tr > th) {
  background-color: #1e3a8a;
  color: white;
}

:deep(.p-tabview .p-tabview-nav li.p-highlight .p-tabview-nav-link) {
  color: #f97316;
  border-color: #f97316;
}
</style>
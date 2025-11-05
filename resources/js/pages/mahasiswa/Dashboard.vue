<script setup>
import Layout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Chip from 'primevue/chip';
import Timeline from 'primevue/timeline';
import { ref } from 'vue';

const courses = ref([
  {
    icon: 'pi pi-book',
    title: 'Bahasa Inggris',
    description: 'Program kursus bahasa Inggris untuk berbagai tingkat kemampuan dari beginner hingga advanced',
    level: 'Semua Level',
    color: 'bg-blue-100 text-blue-600'
  },
  {
    icon: 'pi pi-calendar',
    title: 'EPT Preparation',
    description: 'Persiapan ujian English Proficiency Test dengan metode terbaik dan pengajar berpengalaman',
    level: 'Intermediate',
    color: 'bg-green-100 text-green-600'
  },
  {
    icon: 'pi pi-users',
    title: 'Kelas Reguler',
    description: 'Kelas reguler dengan pengajar berpengalaman dan bersertifikat internasional',
    level: 'Beginner',
    color: 'bg-purple-100 text-purple-600'
  }
]);

const timelineEvents = ref([
  {
    status: 'Pendaftaran',
    icon: 'pi pi-user-plus',
    color: '#3B82F6',
    description: 'Daftar dan pilih program kursus yang sesuai'
  },
  {
    status: 'Placement Test',
    icon: 'pi pi-file-edit',
    color: '#10B981',
    description: 'Ikuti tes penempatan untuk menentukan level Anda'
  },
  {
    status: 'Pembelajaran',
    icon: 'pi pi-book',
    color: '#F59E0B',
    description: 'Mulai belajar dengan metode interaktif dan menyenangkan'
  },
  {
    status: 'Sertifikasi',
    icon: 'pi pi-certificate',
    color: '#EF4444',
    description: 'Dapatkan sertifikat setelah menyelesaikan program'
  }
]);

const stats = ref([
  { value: '500+', label: 'Siswa Aktif', icon: 'pi pi-users' },
  { value: '20+', label: 'Pengajar', icon: 'pi pi-user' },
  { value: '15+', label: 'Program Kursus', icon: 'pi pi-book' },
  { value: '95%', label: 'Tingkat Kepuasan', icon: 'pi pi-heart' }
]);
</script>

<template>
  <Head title="Home - UPA Bahasa POLBAN" />
  
  <Layout 
    :show-hero="true"
    hero-title="Tingkatkan Kemampuan Bahasa Anda"
    hero-subtitle="Bergabunglah dengan program kursus bahasa berkualitas di UPA Bahasa POLBAN"
  >
    <!-- Stats Section -->
    <section class="py-12 bg-white border-b">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div 
            v-for="stat in stats" 
            :key="stat.label"
            class="text-center"
          >
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-3">
              <i :class="stat.icon + ' text-3xl text-blue-600'"></i>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ stat.value }}</h3>
            <p class="text-gray-600">{{ stat.label }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Courses Section -->
    <section class="py-16 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <Chip label="Program Kami" class="mb-4 bg-orange-500 text-white" />
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Program Kursus Unggulan</h2>
          <p class="text-gray-600 max-w-2xl mx-auto text-lg">
            Berbagai program kursus bahasa yang disesuaikan dengan kebutuhan dan level kemampuan Anda
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Card v-for="course in courses" :key="course.title" class="hover:shadow-xl transition-shadow">
            <template #header>
              <div class="p-6 pb-0">
                <div :class="course.color + ' w-16 h-16 rounded-full flex items-center justify-center mb-4'">
                  <i :class="course.icon + ' text-3xl'"></i>
                </div>
              </div>
            </template>
            <template #title>
              {{ course.title }}
            </template>
            <template #subtitle>
              <Chip :label="course.level" severity="info" size="small" />
            </template>
            <template #content>
              <p class="text-gray-600">{{ course.description }}</p>
            </template>
            <template #footer>
              <Button 
                label="Baca Selengkapnya" 
                icon="pi pi-arrow-right" 
                iconPos="right"
                text
                severity="warning"
                class="w-full"
              />
            </template>
          </Card>
        </div>

        <div class="text-center mt-10">
          <Button 
            label="Lihat Semua Program" 
            icon="pi pi-book"
            severity="primary"
            size="large"
            raised
          />
        </div>
      </div>
    </section>

    <!-- Process Timeline Section -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <Chip label="Proses Pendaftaran" class="mb-4 bg-blue-600 text-white" />
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Cara Bergabung</h2>
          <p class="text-gray-600 max-w-2xl mx-auto text-lg">
            Mudah dan cepat! Ikuti 4 langkah sederhana untuk memulai perjalanan belajar Anda
          </p>
        </div>

        <div class="max-w-4xl mx-auto">
          <Timeline :value="timelineEvents" align="alternate" class="custom-timeline">
            <template #marker="slotProps">
              <div 
                class="flex w-12 h-12 items-center justify-center text-white rounded-full shadow-md"
                :style="{ backgroundColor: slotProps.item.color }"
              >
                <i :class="slotProps.item.icon"></i>
              </div>
            </template>
            <template #content="slotProps">
              <Card>
                <template #title>
                  <span class="text-lg">{{ slotProps.item.status }}</span>
                </template>
                <template #content>
                  <p class="text-gray-600">{{ slotProps.item.description }}</p>
                </template>
              </Card>
            </template>
          </Timeline>
        </div>
      </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-16 bg-gradient-to-br from-blue-50 to-orange-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-12">
          <Chip label="Testimoni" class="mb-4 bg-orange-500 text-white" />
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Apa Kata Mereka?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
          <Card class="hover:shadow-xl transition-shadow">
            <template #content>
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    A
                  </div>
                </div>
                <div>
                  <div class="flex gap-1 mb-2">
                    <i class="pi pi-star-fill text-yellow-400"></i>
                    <i class="pi pi-star-fill text-yellow-400"></i>
                    <i class="pi pi-star-fill text-yellow-400"></i>
                    <i class="pi pi-star-fill text-yellow-400"></i>
                    <i class="pi pi-star-fill text-yellow-400"></i>
                  </div>
                  <p class="text-gray-700 mb-3">
                    "Metode pembelajaran yang interaktif dan pengajar yang sangat membantu membuat saya lebih percaya diri berbahasa Inggris!"
                  </p>
                  <p class="font-semibold text-gray-900">Ahmad Fauzi</p>
                  <p class="text-sm text-gray-600">Mahasiswa Teknik Informatika</p>
                </div>
              </div>
            </template>
          </Card>

          <Card class="hover:shadow-xl transition-shadow">
            <template #content>
              <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                  <div class="w-12 h-12 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                    S
                  </div>
                </div>
                <div>
                  <div class="flex gap-1 mb-2">
                    <i class="pi pi-star-fill text-yellow-400"></i>
                    <i class="pi pi-star-fill text-yellow-400"></i>
                    <i class="pi pi-star-fill text-yellow-400"></i>
                    <i class="pi pi-star-fill text-yellow-400"></i>
                    <i class="pi pi-star-fill text-yellow-400"></i>
                  </div>
                  <p class="text-gray-700 mb-3">
                    "Program EPT preparation sangat membantu saya lulus dengan skor tinggi. Materinya lengkap dan terstruktur dengan baik."
                  </p>
                  <p class="font-semibold text-gray-900">Siti Nurhaliza</p>
                  <p class="text-sm text-gray-600">Mahasiswa Teknik Sipil</p>
                </div>
              </div>
            </template>
          </Card>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-blue-900 to-blue-800">
      <div class="container mx-auto px-4 text-center">
        <div class="max-w-3xl mx-auto">
          <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Siap Meningkatkan Kemampuan Bahasa Anda?
          </h2>
          <p class="text-gray-200 mb-8 text-lg">
            Daftar sekarang dan mulai perjalanan pembelajaran bahasa Anda bersama kami. Dapatkan akses ke materi berkualitas dan pengajar berpengalaman!
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Button 
              label="Daftar Sekarang" 
              icon="pi pi-user-plus"
              severity="warning"
              size="large"
              raised
              class="px-8"
            />
            <Button 
              label="Hubungi Kami" 
              icon="pi pi-phone"
              severity="secondary"
              size="large"
              outlined
              class="px-8"
            />
          </div>
        </div>
      </div>
    </section>
  </Layout>
</template>

<style scoped>
:deep(.p-timeline-event-content) {
  padding: 0 1rem;
}

:deep(.custom-timeline .p-timeline-event-opposite) {
  flex: 0;
}
</style>
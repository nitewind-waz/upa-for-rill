<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Menubar from 'primevue/menubar';
import Button from 'primevue/button';
import Sidebar from 'primevue/sidebar';

const props = defineProps({
  title: String,
  showHero: {
    type: Boolean,
    default: false
  },
  heroTitle: {
    type: String,
    default: 'Tingkatkan Kemampuan Bahasa Anda'
  },
  heroSubtitle: {
    type: String,
    default: 'Bergabunglah dengan program kursus bahasa berkualitas di UPA Bahasa POLBAN'
  }
});

const visibleSidebar = ref(false);
const currentRoute = ref(window.location.pathname);

const menuItems = ref([
  {
    label: 'Home',
    icon: 'pi pi-home',
    route: '/',
    command: () => navigateTo('/')
  },
  {
    label: 'Kursus',
    icon: 'pi pi-book',
    route: '/kursus',
    command: () => navigateTo('/kursus')
  },
  {
    label: 'Jadwal',
    icon: 'pi pi-calendar',
    route: '/jadwal',
    command: () => navigateTo('/jadwal')
  },
  {
    label: 'Pembelajaran',
    icon: 'pi pi-graduation-cap',
    route: '/pembelajaran',
    command: () => navigateTo('/pembelajaran')
  },
  {
    label: 'Berita',
    icon: 'pi pi-newspaper',
    route: '/berita',
    command: () => navigateTo('/berita')
  },
  {
    label: 'Hasil EPT',
    icon: 'pi pi-file-check',
    route: '/hasil-ept',
    command: () => navigateTo('/hasil')
  }
]);

const navigateTo = (path) => {
  currentRoute.value = path;
};
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <!-- Desktop Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 hidden md:block">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
          <!-- Logo -->
          <Link href="/" class="flex flex-col">
            <span class="text-2xl font-bold text-blue-900">UPA Bahasa</span>
            <span class="text-sm text-orange-500 font-semibold">POLBAN</span>
          </Link>

          <!-- Navigation Menu -->
          <div class="flex items-center space-x-1">
            <Link 
              v-for="item in menuItems" 
              :key="item.route"
              :href="item.route" 
              class="px-4 py-2 text-gray-700 hover:text-blue-900 font-medium rounded-lg hover:bg-blue-50 transition-all"
              :class="{ 'bg-orange-500 text-white hover:bg-orange-600 hover:text-white': currentRoute === item.route }"
            >
              <i :class="item.icon + ' mr-2'"></i>
              {{ item.label }}
            </Link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Mobile Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 md:hidden">
      <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
          <Link href="/" class="flex flex-col">
            <span class="text-xl font-bold text-blue-900">UPA Bahasa</span>
            <span class="text-xs text-orange-500 font-semibold">POLBAN</span>
          </Link>
          
          <Button 
            icon="pi pi-bars" 
            @click="visibleSidebar = true"
            text
            severity="secondary"
            size="large"
          />
        </div>
      </div>
    </nav>

    <!-- Mobile Sidebar -->
    <Sidebar 
      v-model:visible="visibleSidebar" 
      position="right"
      class="w-80"
    >
      <template #header>
        <div class="flex flex-col">
          <span class="text-2xl font-bold text-blue-900">UPA Bahasa</span>
          <span class="text-sm text-orange-500 font-semibold">POLBAN</span>
        </div>
      </template>
      
      <div class="flex flex-col gap-2">
        <Link 
          v-for="item in menuItems" 
          :key="item.route"
          :href="item.route" 
          class="p-3 text-gray-700 hover:text-blue-900 font-medium rounded-lg hover:bg-blue-50 transition-all flex items-center"
          :class="{ 'bg-orange-500 text-white hover:bg-orange-600 hover:text-white': currentRoute === item.route }"
          @click="visibleSidebar = false"
        >
          <i :class="item.icon + ' mr-3 text-lg'"></i>
          {{ item.label }}
        </Link>
      </div>
    </Sidebar>

    <!-- Hero Section (Optional) -->
 

    <!-- Main Content -->
    <main class="flex-grow">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-12">
      <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Company Info -->
          <div>
            <h3 class="text-2xl font-bold text-orange-500 mb-4">UPA Bahasa POLBAN</h3>
            <p class="text-gray-300 leading-relaxed">
              Unit Pelaksana Akademik Bahasa yang berkomitmen meningkatkan kemampuan berbahasa mahasiswa.
            </p>
          </div>

          <!-- Menu -->
          <div>
            <h4 class="text-lg font-semibold mb-4">Menu</h4>
            <ul class="space-y-2">
              <li v-for="item in menuItems" :key="item.route">
                <Link 
                  :href="item.route" 
                  class="text-gray-300 hover:text-orange-500 transition-colors flex items-center"
                >
                  <i :class="item.icon + ' mr-2'"></i>
                  {{ item.label }}
                </Link>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div>
            <h4 class="text-lg font-semibold mb-4">Kontak</h4>
            <ul class="space-y-3">
              <li class="flex items-start">
                <i class="pi pi-map-marker mt-1 mr-3 text-orange-500"></i>
                <span class="text-gray-300">
                  Jl. Gegerkalong Hilir, Ciwaruga, Bandung
                </span>
              </li>
              <li class="flex items-center">
                <i class="pi pi-phone mr-3 text-orange-500"></i>
                <span class="text-gray-300">+62 821 1234 5678</span>
              </li>
              <li class="flex items-center">
                <i class="pi pi-envelope mr-3 text-orange-500"></i>
                <span class="text-gray-300">upabahasa@polban.ac.id</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-blue-800 mt-8 pt-6 text-center">
          <p class="text-gray-400">
            © {{ new Date().getFullYear() }} UPA Bahasa POLBAN. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.8s ease-out;
}
</style>
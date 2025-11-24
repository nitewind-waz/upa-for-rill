<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <!-- Header dengan Glassmorphism Effect -->
    <header class="sticky top-0 z-50 backdrop-blur-lg bg-white/80 border-b border-blue-100 shadow-sm">
      <nav class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
          <!-- Logo & Brand -->
          <div class="flex items-center gap-3 group cursor-pointer">
            <div class="relative">
              <Avatar 
                icon="pi pi-comments" 
                size="large" 
                shape="circle"
                class="bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:scale-105" 
              />
              <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
            </div>
            <div>
              <span class="text-xl font-bold bg-gradient-to-r from-blue-700 to-blue-900 bg-clip-text text-transparent">
                UPA Bahasa
              </span>
              <p class="text-xs text-blue-600 font-medium">Politeknik Negeri Bandung</p>
            </div>
          </div>

          <!-- Navigation Menu -->
          <div class="hidden md:flex items-center gap-2">
            <a
              v-for="item in navItems"
              :key="item.label"
              :href="item.url"
              class="px-5 py-2.5 rounded-xl font-medium transition-all duration-300 relative overflow-hidden group"
              :class="item.active
                ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-200'
                : 'text-gray-700 hover:bg-blue-50'"
            >
              <span class="relative z-10">{{ item.label }}</span>
              <div 
                v-if="!item.active"
                class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
              ></div>
              <span 
                v-if="!item.active"
                class="absolute inset-0 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"
              >
                {{ item.label }}
              </span>
            </a>
          </div>

          <!-- Mobile Menu Button -->
          <Button 
            icon="pi pi-bars" 
            class="md:hidden" 
            text 
            rounded 
            @click="mobileMenuVisible = true"
          />
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-6 py-8">
      <slot />
    </main>

    <!-- Floating Action Button dengan Tooltip -->
    <div class="fixed bottom-8 right-8 z-40">
      <div class="relative group">
        <Button
          icon="pi pi-comments"
          rounded
          size="large"
          severity="info"
          class="shadow-2xl shadow-blue-300 hover:shadow-3xl hover:scale-110 transition-all duration-300 bg-gradient-to-br from-blue-600 to-blue-800 border-none w-16 h-16"
          @mouseenter="contactVisible = true"
          @mouseleave="contactVisible = false"
        />
        
        <!-- Contact Card -->
        <Transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 translate-y-4"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 translate-y-4"
        >
          <Card 
            v-if="contactVisible"
            class="absolute bottom-20 right-0 w-80 shadow-2xl"
            @mouseenter="contactVisible = true"
            @mouseleave="contactVisible = false"
          >
            <template #header>
              <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 text-white">
                <h3 class="text-lg font-bold flex items-center gap-2">
                  <i class="pi pi-phone"></i>
                  Hubungi Kami
                </h3>
                <p class="text-sm text-blue-100 mt-1">Kami siap membantu Anda</p>
              </div>
            </template>
            <template #content>
              <div class="space-y-4">
                <a 
                  href="mailto:upa.bahasa@polban.ac.id"
                  class="flex items-center gap-3 p-3 rounded-lg hover:bg-blue-50 transition-colors group"
                >
                  <div class="bg-blue-100 p-2 rounded-lg group-hover:bg-blue-200 transition-colors">
                    <i class="pi pi-envelope text-blue-700 text-lg"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-xs text-gray-500 font-medium">Email</p>
                    <p class="text-sm text-gray-800 font-semibold">upa.bahasa@polban.ac.id</p>
                  </div>
                </a>

                <a 
                  href="https://wa.me/6281234567890"
                  target="_blank"
                  class="flex items-center gap-3 p-3 rounded-lg hover:bg-green-50 transition-colors group"
                >
                  <div class="bg-green-100 p-2 rounded-lg group-hover:bg-green-200 transition-colors">
                    <i class="pi pi-whatsapp text-green-600 text-lg"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-xs text-gray-500 font-medium">WhatsApp</p>
                    <p class="text-sm text-gray-800 font-semibold">+62 812 3456 7890</p>
                  </div>
                </a>

                <a 
                  href="https://instagram.com/upabahasa_polban"
                  target="_blank"
                  class="flex items-center gap-3 p-3 rounded-lg hover:bg-pink-50 transition-colors group"
                >
                  <div class="bg-pink-100 p-2 rounded-lg group-hover:bg-pink-200 transition-colors">
                    <i class="pi pi-instagram text-pink-600 text-lg"></i>
                  </div>
                  <div class="flex-1">
                    <p class="text-xs text-gray-500 font-medium">Instagram</p>
                    <p class="text-sm text-gray-800 font-semibold">@upabahasa_polban</p>
                  </div>
                </a>
              </div>
            </template>
          </Card>
        </Transition>
      </div>
    </div>

    <!-- Modern Footer -->
    <footer class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 text-white py-12 mt-16 relative overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-300 rounded-full blur-3xl"></div>
      </div>

      <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-3 gap-8">
          <!-- About Section -->
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <Avatar 
                icon="pi pi-comments" 
                size="large" 
                shape="circle"
                class="bg-white text-blue-800 shadow-lg" 
              />
              <div>
                <h3 class="text-xl font-bold">UPA Bahasa</h3>
                <p class="text-blue-200 text-sm">POLBAN</p>
              </div>
            </div>
            <p class="text-blue-100 text-sm leading-relaxed">
              Unit Pelaksana Administrasi Bahasa Politeknik Negeri Bandung, 
              mendukung pengembangan kemampuan bahasa mahasiswa.
            </p>
          </div>

          <!-- Quick Links -->
          <div class="space-y-4">
            <h4 class="text-lg font-bold">Quick Links</h4>
            <div class="grid grid-cols-2 gap-2">
              <a 
                v-for="item in navItems" 
                :key="item.label"
                :href="item.url"
                class="text-blue-100 hover:text-white transition-colors text-sm hover:translate-x-1 transform duration-200 flex items-center gap-2"
              >
                <i class="pi pi-angle-right text-xs"></i>
                {{ item.label }}
              </a>
            </div>
          </div>

          <!-- Contact Info -->
          <div class="space-y-4">
            <h4 class="text-lg font-bold">Kontak & Lokasi</h4>
            <div class="space-y-3 text-sm">
              <div class="flex items-start gap-3">
                <i class="pi pi-map-marker text-blue-300 mt-1"></i>
                <p class="text-blue-100">
                  Jl. Gegerkalong Hilir, Ciwaruga<br/>
                  Bandung, Jawa Barat
                </p>
              </div>
              <div class="flex items-center gap-3">
                <i class="pi pi-envelope text-blue-300"></i>
                <a href="mailto:upa.bahasa@polban.ac.id" class="text-blue-100 hover:text-white transition-colors">
                  upa.bahasa@polban.ac.id
                </a>
              </div>
              <div class="flex items-center gap-3">
                <i class="pi pi-instagram text-blue-300"></i>
                <a href="https://instagram.com/upabahasa_polban" target="_blank" class="text-blue-100 hover:text-white transition-colors">
                  @upabahasa_polban
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Copyright -->
        <Divider class="my-6 border-blue-700" />
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-blue-200">
          <p>© 2025 UPA Bahasa Politeknik Negeri Bandung. All rights reserved.</p>
          <div class="flex gap-4">
            <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
          </div>
        </div>
      </div>
    </footer>

    <!-- Mobile Menu Sidebar -->
    <Sidebar v-model:visible="mobileMenuVisible" position="right">
      <template #header>
        <div class="flex items-center gap-3">
          <Avatar 
            icon="pi pi-comments" 
            size="large" 
            class="bg-gradient-to-br from-blue-600 to-blue-800 text-white" 
          />
          <div>
            <h3 class="font-bold text-blue-900">UPA Bahasa</h3>
            <p class="text-xs text-blue-600">POLBAN</p>
          </div>
        </div>
      </template>

      <div class="space-y-2">
        <a
          v-for="item in navItems"
          :key="item.label"
          :href="item.url"
          class="block px-4 py-3 rounded-lg font-medium transition-all"
          :class="item.active
            ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white'
            : 'text-gray-700 hover:bg-blue-50'"
          @click="mobileMenuVisible = false"
        >
          {{ item.label }}
        </a>
      </div>
    </Sidebar>
  </div>
</template>

<script setup>
import { ref, computed} from 'vue'
import {Link, usePage} from '@inertiajs/vue3'
import Avatar from 'primevue/avatar'
import Button from 'primevue/button'
import Card from 'primevue/card'
import Divider from 'primevue/divider'
import Sidebar from 'primevue/sidebar'

const Page = usePage();

const navItems = ref([
  { label: 'Home', url: '/', active: false },
  { label: 'Kursus', url: '/kursus', active: false },
  { label: 'Pembelajaran', url: '/mahasiswa/pembelajaran', active: false },
  { label: 'Jadwal EPT', url: '/jadwal', active: false },
  { label: 'Hasil EPT', url: '/hasil', active: false },
])

const currentUrl = Page.url

navItems.value = navItems.value.map(item => ({
  ...item,
  active: currentUrl === item.url
}))

const contactVisible = ref(false)
const mobileMenuVisible = ref(false)
</script>

<style scoped>
/* Custom scrollbar untuk sidebar */
:deep(.p-sidebar-content) {
  scrollbar-width: thin;
  scrollbar-color: #3b82f6 #e5e7eb;
}

:deep(.p-sidebar-content::-webkit-scrollbar) {
  width: 6px;
}

:deep(.p-sidebar-content::-webkit-scrollbar-track) {
  background: #e5e7eb;
}

:deep(.p-sidebar-content::-webkit-scrollbar-thumb) {
  background: #3b82f6;
  border-radius: 3px;
}
</style>
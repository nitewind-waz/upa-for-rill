<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Button from 'primevue/button'
import Sidebar from 'primevue/sidebar'

const page = usePage()

const navItems = ref([
  { label: 'Home', url: '/' },
  { label: 'Kursus', url: '/course' },
  { label: 'Pembelajaran', url: '/pembelajaran' },
  { label: 'Jadwal EPT', url: '/jadwal' },
  { label: 'Hasil EPT', url: '/hasil' },
])

const contactVisible = ref(false)
const mobileMenuVisible = ref(false)

const handleImageError = (e) => { 
  e.target.src = 'https://via.placeholder.com/150?text=LOGO' 
}

const isActive = (url) => {
  if (url === '/') {
    return page.url === '/'
  }
  return page.url.startsWith(url)
}
</script>

<template>
  <div class="min-h-screen flex flex-col bg-slate-50 relative overflow-x-hidden font-sans">
    
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:30px_30px] opacity-[0.2]"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-blue-200/40 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-5%] right-[-5%] w-[500px] h-[500px] bg-orange-100/50 rounded-full blur-[100px] animate-pulse delay-1000"></div>
        <div class="absolute top-[30%] right-[-15%] w-[400px] h-[400px] bg-indigo-200/30 rounded-full blur-[120px] animate-pulse delay-700"></div>
        <div class="absolute top-20 left-[-50px] w-96 h-96 border border-slate-200 rounded-full opacity-60"></div>
        <div class="absolute bottom-40 right-10 w-32 h-32 border-2 border-blue-100 rounded-full opacity-50 border-dashed"></div>
    </div>

    <header class="sticky top-0 z-50 bg-blue-600/95 backdrop-blur-md border-b border-blue-500 shadow-lg transition-all duration-300">
      <nav class="container mx-auto px-6 py-3 relative">
        
        <div class="flex justify-between items-center">
          
          <div class="flex items-center gap-3 group cursor-pointer z-20" @click="$inertia.visit('/')">
            <div class="relative transition-transform duration-300 group-hover:scale-105">
              <img 
                src="/images/logo-polban.png" 
                alt="Logo Polban" 
                class="h-10 w-auto object-contain filter drop-shadow-sm"
                @error="handleImageError"
              />
            </div>
            <div class="flex flex-col">
              <div class="flex items-baseline gap-1 text-xl font-bold tracking-wide">
                <span class="text-white drop-shadow-sm">UPA</span>
                <span class="text-orange-300 drop-shadow-sm">BAHASA</span>
              </div>
              <span class="text-[10px] text-blue-100 font-medium uppercase tracking-widest">Politeknik Negeri Bandung</span>
            </div>
          </div>

          <div class="hidden md:flex items-center gap-1 ml-auto">
            <a
              v-for="item in navItems"
              :key="item.label"
              :href="item.url"
              class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 relative group overflow-hidden whitespace-nowrap"
              :class="isActive(item.url)
                ? 'text-white bg-white/10 shadow-sm font-semibold' /* ACTIVE: Sama kayak hover (transparan putih) */
                : 'text-blue-100 hover:text-white hover:bg-white/10'"
            >
              {{ item.label }}
              
              <div 
                v-if="isActive(item.url)" 
                class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-0.5 bg-orange-400 shadow-[0_0_8px_rgba(251,146,60,0.8)] rounded-full"
              ></div>
            </a>
          </div>

          <div class="md:hidden z-20">
             <Button 
              icon="pi pi-bars" 
              class="!text-white hover:!bg-white/10" 
              text rounded 
              @click="mobileMenuVisible = true"
            />
          </div>

        </div>
      </nav>
    </header>

    <main class="flex-1 relative z-10">
      <slot />
    </main>

    <div class="fixed bottom-8 right-8 z-40">
      <div 
        class="relative group"
        @mouseenter="contactVisible = true"
        @mouseleave="contactVisible = false"
      >
        <Button
          icon="pi pi-comments" rounded size="large"
          class="!bg-blue-600 !border-blue-500 shadow-xl group-hover:scale-110 transition-all duration-300 !w-14 !h-14"
        />

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div 
              v-if="contactVisible" 
              class="absolute bottom-0 right-16 mb-2 w-64 bg-white rounded-xl shadow-2xl p-4 border border-slate-100"
            >
                <h4 class="font-bold text-slate-800 text-center mb-4">Hubungi Kami</h4>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-2 bg-blue-50 text-blue-800 rounded-lg">
                        <i class="pi pi-envelope text-lg"></i>
                        <span class="font-medium text-sm">upabahasa@polban.ac.id</span>
                    </div>
                    <div class="flex items-center gap-3 p-2 bg-pink-50 text-pink-800 rounded-lg">
                        <i class="pi pi-instagram text-lg"></i>
                        <span class="font-medium text-sm">@upabahasa.polban</span>
                    </div>
                    <div class="flex items-center gap-3 p-2 bg-green-50 text-green-800 rounded-lg">
                        <i class="pi pi-whatsapp text-lg"></i>
                        <span class="font-medium text-sm">0812-3456-7890</span>
                    </div>
                </div>
            </div>
        </transition>
      </div>
    </div>

    <footer class="bg-slate-900 text-white py-12 mt-auto border-t border-slate-800 relative z-10">
       <div class="container mx-auto px-6 relative">
          <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
          
          <div class="flex flex-col md:flex-row justify-between items-start gap-8 relative z-10 mb-8 pb-8 border-b border-slate-700">
            <div class="flex-shrink-0">
               <div class="flex items-baseline gap-1 text-2xl font-bold tracking-wide mb-2">
                 <span class="text-white drop-shadow-sm">UPA</span>
                 <span class="text-orange-300 drop-shadow-sm">BAHASA</span>
               </div>
               <p class="text-sm text-slate-400 max-w-sm">Pusat layanan bahasa unggulan untuk mendukung civitas akademika Politeknik Negeri Bandung.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 md:gap-12 flex-grow">
               <div>
                  <h4 class="font-bold text-slate-200 mb-4">Informasi Kontak</h4>
                  <ul class="space-y-3 text-slate-400 text-sm">
                     <li class="flex items-center gap-3">
                        <i class="pi pi-envelope text-blue-400"></i> 
                        <a href="mailto:upabahasa@polban.ac.id" class="hover:text-white transition-colors">upabahasa@polban.ac.id</a>
                     </li>
                     <li class="flex items-center gap-3">
                        <i class="pi pi-instagram text-pink-400"></i> 
                        <a href="https://instagram.com/upabahasa.polban" target="_blank" class="hover:text-white transition-colors">@upabahasa.polban</a>
                     </li>
                     <li class="flex items-center gap-3">
                        <i class="pi pi-whatsapp text-green-400"></i> 
                        <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-white transition-colors">0812-3456-7890</a>
                     </li>
                  </ul>
               </div>
               
               <div>
                  <h4 class="font-bold text-slate-200 mb-4">Akses Cepat</h4>
                  <ul class="space-y-3 text-slate-400 text-sm">
                     <li v-for="item in navItems" :key="item.label">
                        <a :href="item.url" class="hover:text-white transition-colors">{{ item.label }}</a>
                     </li>
                  </ul>
               </div>

               <div>
                  <h4 class="font-bold text-slate-200 mb-4">Lokasi</h4>
                  <p class="text-sm text-slate-400 leading-relaxed">Gedung H, Lantai 2,<br>Politeknik Negeri Bandung<br>Jl. Gegerkalong Hilir, Bandung</p>
               </div>
            </div>
          </div>

          <p class="text-center text-slate-400 text-sm relative z-10">© {{ new Date().getFullYear() }} UPA Bahasa Politeknik Negeri Bandung. All rights reserved.</p>
       </div>
    </footer>

    <Sidebar v-model:visible="mobileMenuVisible" position="right" class="!bg-white">
      <template #header>
        <div class="flex items-center gap-3 border-b border-gray-100 pb-4 w-full">
           <img src="/images/logo-polban.png" alt="Logo" class="h-8 w-auto" />
           <div class="flex flex-col">
              <div class="font-bold text-lg">
                 <span class="text-blue-700">UPA</span> 
                 <span class="text-orange-500">BAHASA</span>
              </div>
           </div>
        </div>
      </template>

      <div class="flex flex-col gap-2 mt-2">
        <a
          v-for="item in navItems"
          :key="item.label"
          :href="item.url"
          class="px-4 py-3 rounded-lg font-medium text-sm transition-all flex items-center justify-between"
          :class="isActive(item.url)
            ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600'
            : 'text-gray-600 hover:bg-gray-50 hover:text-blue-600'"
        >
          {{ item.label }}
          <i v-if="isActive(item.url)" class="pi pi-check text-xs"></i>
        </a>
      </div>
    </Sidebar>

  </div>
</template>
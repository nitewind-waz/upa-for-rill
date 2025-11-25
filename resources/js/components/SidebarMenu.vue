<template>
  <div>
    <div class="lg:hidden fixed top-4 left-4 z-50">
      <Button 
        icon="pi pi-bars" 
        @click="visible = true" 
        severity="secondary"
        outlined
        class="!bg-white !shadow-sm"
        rounded
      />
    </div>

    <Sidebar 
      v-model:visible="visible" 
      class="!w-72 !border-r-0"
      :pt="{
        header: { class: '!hidden' }, // Sembunyikan header default PrimeVue biar kita custom sendiri
        content: { class: '!p-0' }    // Reset padding content
      }"
    >
      <div class="flex flex-col h-full bg-white">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center text-white font-bold">U</div>
            <span class="text-xl font-bold text-slate-900">UPA<span class="text-orange-500">Bahasa</span></span>
          </div>
          <Button icon="pi pi-times" text rounded severity="secondary" @click="visible = false" />
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
          <template v-for="item in menuItems" :key="item.route">
            <Link 
              :href="item.route" 
              class="flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200 group"
              :class="isActive(item.route) 
                ? 'bg-blue-50 text-blue-700 font-semibold' 
                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
              @click="visible = false"
            >
              <i :class="[item.icon, isActive(item.route) ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600']" class="text-lg"></i>
              <span class="text-sm font-medium">{{ item.label }}</span>
            </Link>
          </template>
        </div>

            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
              <div class="flex items-center gap-3">
                <Avatar :label="props.user?.name ? props.user.name.charAt(0).toUpperCase() : 'A'" class="bg-blue-100 text-blue-700" shape="circle" />
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-semibold text-slate-900 truncate">{{ props.user?.name || 'Admin User' }}</p>
                  <p class="text-xs text-slate-500 truncate">{{ props.user?.email || 'admin@upabahasa.ac.id' }}</p>
                </div>
              </div>
            </div>      </div>
    </Sidebar>

    <div class="hidden lg:flex fixed left-0 top-0 h-screen w-72 bg-white border-r border-slate-200 z-40 flex-col">
      
      <div class="h-20 flex items-center px-6 border-b border-slate-100">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm shadow-blue-200">
            <i class="pi pi-book text-white text-sm"></i>
          </div>
          <div>
            <h2 class="text-lg font-bold text-slate-900 leading-none">
              UPA<span class="text-orange-500">Bahasa</span>
            </h2>
            <p class="text-[10px] font-medium text-slate-400 mt-1 tracking-wider uppercase">Admin Dashboard</p>
          </div>
        </div>
      </div>

      <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 custom-scrollbar">
        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 px-2">Menu Utama</div>
        
        <template v-for="item in menuItems" :key="item.route">
          <Link 
            :href="item.route" 
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group relative"
            :class="isActive(item.route) 
              ? 'bg-blue-50 text-blue-700 font-semibold' 
              : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
          >
            <div v-if="isActive(item.route)" class="absolute left-0 top-1/2 -translate-y-1/2 h-6 w-1 bg-blue-600 rounded-r-full"></div>

            <i :class="[item.icon, isActive(item.route) ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600']" class="text-lg"></i>
            <span class="text-sm font-medium">{{ item.label }}</span>
            
            <i v-if="isActive(item.route)" class="pi pi-angle-right ml-auto text-xs text-blue-400"></i>
          </Link>
        </template>

        <div class="my-4 border-t border-slate-100"></div>

        <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 px-2">Lainnya</div>
        
        <Link 
          href="/settings" 
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group text-slate-600 hover:bg-slate-50 hover:text-slate-900"
          :class="{ 'bg-blue-50 text-blue-700 font-semibold': isActive('/settings') }"
        >
           <i class="pi pi-cog text-lg text-slate-400 group-hover:text-slate-600"></i>
           <span class="text-sm font-medium">Settings</span>
        </Link>
      </div>

          <div class="p-4 border-t border-slate-200 bg-slate-50/50">
              <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-white hover:shadow-sm transition-all cursor-pointer border border-transparent hover:border-slate-200">
                <Avatar
                  :label="props.user?.name ? props.user.name.charAt(0).toUpperCase() : 'A'"
                  class="bg-blue-600 text-white shadow-sm shadow-blue-200"
                  shape="circle"
                />
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-bold text-slate-800 truncate">{{ props.user?.name || 'Admin User' }}</p>
                  <p class="text-xs text-slate-500 truncate">{{ props.user?.email || 'admin@upabahasa.ac.id' }}</p>
                </div>
                <Link
                  href="/admin/logout"
                  method="post"
                  as="button"
                  class="cursor-pointer"
                >
                  <i class="pi pi-sign-out text-slate-400 hover:text-red-500 transition-colors"></i>
                </Link>
              </div>
            </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Sidebar from 'primevue/sidebar'; // Gunakan 'primevue/drawer' jika sudah full v4
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';

const page = usePage();
const visible = ref(false);

const props = defineProps({
    user: Object, // Define the user prop
});

// Logic untuk mengecek active state (support nested routes basic)
const isActive = (path: string): boolean => {
  if (path === '/home' && page.url === '/home') return true;
  if (path !== '/home' && page.url.startsWith(path)) return true;
  return false;
};

const menuItems = [
  { label: 'Dashboard', icon: 'pi pi-home', route: '/admin/dashboard' },
  { label: 'Jadwal', icon: 'pi pi-calendar', route: '/admin/jadwal' },
  { label: 'Nilai EPT', icon: 'pi pi-chart-bar', route: '/admin/ept' }, // Disesuaikan dengan route sebelumnya
  { label: 'Kelola Kursus', icon: 'pi pi-book', route: '/admin/course' },
  { label: 'Kelola Pembelajaran', icon: 'pi pi-book', route: '/admin/pembelajaran' },
  { label: 'Kelola Berita', icon: 'pi pi-megaphone', route: '/admin/berita' },
  { label: 'Kelola Acara', icon: 'pi pi-calendar-plus', route: '/admin/acara' },
];
</script>

<style scoped>
/* Scrollbar tipis agar tidak merusak UI */
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #e2e8f0;
  border-radius: 20px;
}
</style>
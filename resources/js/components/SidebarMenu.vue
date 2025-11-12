<template>
  <aside class="hidden lg:flex flex-col h-screen p-3 sticky top-0 w-64">
    <div class="flex flex-col h-full text-white">
      
      <div class="text-center py-4 mb-2">
        <h2 class="text-2xl font-bold">
          <span class="text-white">UPA</span>
          <span class="text-orange-500">Bahasa</span>
        </h2>
      </div>

      <nav class="flex flex-col space-y-2 flex-1">
        <Link
          v-for="item in menuItems"
          :key="item.label"
          :href="item.route"
          class="flex items-center justify-center gap-3 py-3 rounded-xl transition-all duration-300"
          :class="isActive(item.route) 
            ? 'bg-white text-[#2B3B89] font-semibold' 
            : 'text-blue-200 hover:bg-white/10 hover:text-white'"
        >
          <i :class="item.icon" class="text-lg w-6 text-center"></i>
          <span class="text-sm truncate">{{ item.label }}</span>
        </Link>
      </nav>

      <Divider class="my-3 border-blue-700/50" />
      
      <Link 
        href="/settings" 
        class="flex items-center justify-center gap-3 py-3 rounded-xl transition-all duration-300 text-blue-200 hover:bg-white/10 hover:text-white"
        :class="{ 'bg-white/10 text-white': isActive('/settings') }"
      >
        <i class="pi pi-cog text-lg w-6 text-center"></i>
        <span class="text-sm truncate">Settings</span>
      </Link>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { sidebarVisible } from '@/composables/useLayout';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Divider from 'primevue/divider';
import Sidebar from 'primevue/sidebar';

interface MenuItem {
  label: string;
  icon: string;
  route: string;
}

const page = usePage();
const isActive = (path: string): boolean => page.url.startsWith(path);

// DIKONFIRMASI: Menu item ini sudah sesuai dengan screenshot Anda
const menuItems: MenuItem[] = [
  { label: 'Home', icon: 'pi pi-home', route: '/home' },
  { label: 'Mahasiswa', icon: 'pi pi-users', route: '/mahasiswa' },
  { label: 'Jadwal', icon: 'pi pi-calendar', route: '/jadwal' },
  { label: 'Nilai EPT', icon: 'pi pi-chart-bar', route: '/nilai-ept' },
  { label: 'Kursus', icon: 'pi pi-book', route: '/kursus' },
  { label: 'Berita', icon: 'pi pi-globe', route: '/berita' },
  { label: 'Event', icon: 'pi pi-ticket', route: '/event' },
];
</script>
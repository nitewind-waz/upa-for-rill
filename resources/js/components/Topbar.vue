<template>
  <header class="flex items-center justify-between p-4 h-16 bg-white border-b border-slate-200">
    
    <div class="flex items-center gap-4">
      <Button 
        icon="pi pi-bars" 
        @click="sidebarVisible = true"
        text
        rounded
        class="lg:hidden text-gray-600 hover:bg-gray-100"
        aria-label="Open sidebar"
      />
      <h1 class="text-xl font-bold text-gray-800 hidden sm:block">
          Dashboard
      </h1>
    </div>

    <div class="flex items-center gap-3">
       <Button 
          icon="pi pi-bell" 
          text 
          rounded 
          severity="secondary" 
          class="text-gray-600 hover:bg-gray-100" 
        />
       <Avatar 
          image="https://i.pravatar.cc/40" 
          shape="circle" 
          class="ring-2 ring-gray-200"
        />
    </div>
  </header>
</template>

<script setup lang="ts">
// Impor state global, pastikan path-nya benar
import { sidebarVisible } from '@/composables/useLayout'; 
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
</script>
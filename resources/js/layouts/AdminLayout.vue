<template>
  <div class="min-h-screen bg-slate-50 text-slate-900">
    
    <SidebarMenu /> 

    <div class="lg:ml-72 min-h-screen">
      <main class="p-6 lg:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import SidebarMenu from '@/components/SidebarMenu.vue';
import Topbar from '@/components/Topbar.vue';

const props = defineProps({
  title: {
    type: String,
    default: 'Admin'
  }
})
</script>
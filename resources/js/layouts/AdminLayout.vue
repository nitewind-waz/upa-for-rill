<template>
  <div class="flex min-h-screen bg-[#2B3B89]">
    
    <SidebarMenu /> 

    <div class="flex-1 flex flex-col h-screen overflow-y-auto">
      
      <div class="p-3 flex-1">
        
        <div class="flex-1 flex flex-col bg-white rounded-2xl shadow-lg shadow-slate-300/50 h-full overflow-hidden">
          
          <Topbar /> 
          
          <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
            <slot />
          </main>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import SidebarMenu from '@/components/SidebarMenu.vue';
import Topbar from '@/components/Topbar.vue';
</script>
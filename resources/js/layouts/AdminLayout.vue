<script setup lang="ts">
import SidebarMenu from '@/components/SidebarMenu.vue';
import Topbar from '@/components/Topbar.vue';
import { usePage } from '@inertiajs/vue3'; // Import usePage

const page = usePage(); // Get page props
const authUser = page.props.auth.user; // Access authenticated user

const props = defineProps({
  title: {
    type: String,
    default: 'Admin'
  }
})
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-900">

    <SidebarMenu :user="authUser" /> <!-- Pass user to SidebarMenu -->

    <div class="lg:ml-72 min-h-screen">
      <main class="p-6 lg:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>
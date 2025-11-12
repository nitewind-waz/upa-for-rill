<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <DashboardCard
      v-for="n in 3"
      :key="n"
      :title="`Card ${n}`"
      :value="(n * 1243).toLocaleString()"
    />
  </div>
</template>

<script setup>
import DashboardCard from '@/components/DashboardCard.vue'
</script>

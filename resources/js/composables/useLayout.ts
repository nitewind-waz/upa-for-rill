// src/composables/useLayout.ts
import { ref, type Ref } from 'vue';

// Beri tipe Ref<boolean> pada state
export const sidebarVisible: Ref<boolean> = ref(false);
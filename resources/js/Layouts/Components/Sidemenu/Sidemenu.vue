<template>
  <div class="min-w-fit relative">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-gray-900/30 z-40 lg:z-auto transition-opacity duration-200"
      :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true"></div>

    <!-- Sidebar -->
    <div id="sidebar" ref="sidebar" class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-hidden 
      shrink-0 transition-all duration-200 ease-in-out 
      rounded-tr-2xl shadow-lg bg-gradient-to-b from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-800 dark:via-blue-900/20 dark:to-purple-900/20 border border-blue-100 dark:border-gray-700"
      :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-64',
        sidebarExpanded ? 'w-64' : 'w-24'
      ]">
      
      <!-- Subtle Background Accent -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-30">
        <div class="absolute -top-20 -left-20 w-40 h-40 bg-blue-200 dark:bg-blue-700 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-2xl"></div>
        <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-purple-200 dark:bg-purple-700 rounded-full mix-blend-multiply dark:mix-blend-soft-light filter blur-2xl"></div>
      </div>

      <div class="relative z-10 h-full flex flex-col">
      
      <!-- Sidebar header - Sticky -->
      <div class="sticky top-0 bg-gradient-to-b from-blue-50 via-indigo-50 to-purple-50 dark:from-gray-800 dark:via-blue-900/20 dark:to-purple-900/20 z-20 p-4 pb-0">
      <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
          <!-- Close button (mobile) -->
          <Button class="lg:hidden" severity="secondary" icon="pi pi-arrow-left" variant="text" rounded
            @click.stop="$emit('close-sidebar')" aria-controls="sidebar" :aria-expanded="sidebarOpen" />
          
          <!-- Collapse/Expand button (desktop) -->
          <Button 
            class="hidden lg:block" 
            severity="secondary" 
            :icon="sidebarExpanded ? 'pi pi-angle-double-left' : 'pi pi-angle-double-right'" 
            variant="text" 
            rounded
            size="small"
            @click.stop="toggleSidebar"
            v-tooltip.right="sidebarExpanded ? 'Tutup Sidebar' : 'Buka Sidebar'"
          />
        </div>
        <!-- Logo -->
        <Link :href="route('dashboard')">
          <div class="flex gap-2 items-center">
            <img src="/images/logo.png" alt="app-logo" class="h-12 flex-none" />
            <div class="text-xs font-semibold transition-all duration-300 overflow-hidden whitespace-nowrap"
              :class="sidebarExpanded ? 'opacity-100 max-w-[200px]' : 'opacity-0 max-w-0'">
              <div>Dinas Pendidikan dan</div>
              <div>Kebudayaan Kota Bontang</div>
            </div>
          </div>
        </Link>
      </div>

      <div>
        <slot name="sidebar-header" />
      </div>
      </div>
      
      <!-- Main Content - Scrollable -->
      <div class="flex-1 overflow-y-scroll lg:overflow-y-auto no-scrollbar px-4">

      <!-- Links -->
      <div class="p-1 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <!-- Pages group -->
        <div>
          <nav class="flex-1 overflow-y-scroll no-scrollbar">
            <ul>
              <li v-for="menuItem in sideMenuItemData">
                <SidemenuItem 
                  :label="menuItem.label" 
                  :sparator="menuItem.separator" 
                  :icon="menuItem.icon"
                  :url="menuItem.url" 
                  :items="menuItem.items" 
                  :collapsed="!sidebarExpanded"
                  v-if="can(menuItem.permissions)" 
                />
              </li>
            </ul>
          </nav>
        </div>
      </div>
      </div>
      
      <!-- Footer Gradient -->
      <div class="mt-auto p-4 bg-gradient-to-r from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-900">
        <div class="text-center text-white transition-all duration-300 overflow-hidden"
          :class="sidebarExpanded ? 'opacity-100 max-h-10' : 'opacity-0 max-h-0'">
          <p class="text-xs opacity-80">© {{ currentYear }} Disdikbud Bontang</p>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'

import SidemenuItem from './SidemenuItem.vue';
import { sideMenuItemData } from '@/Core/Configs/sidemenu-item';
import { Link } from '@inertiajs/vue3';
import { can } from '@/Core/Utils/permission-check';

const props = defineProps({
  sidebarOpen: {
    type: Boolean,
    default: false,
  },
});
const emit = defineEmits();

const trigger = ref(null)
const sidebar = ref(null)

const sidebarExpanded = ref(localStorage.getItem('sidebar-expanded') !== 'false')
const currentYear = new Date().getFullYear()

const toggleSidebar = () => {
  sidebarExpanded.value = !sidebarExpanded.value
}

const clickHandler = ({ target }) => {
  if (!sidebar.value || !trigger.value) return
  if (
    !props.sidebarOpen ||
    sidebar.value.contains(target) ||
    trigger.value.contains(target)
  ) return
  emit('close-sidebar')
}

// close if the esc key is pressed
const keyHandler = ({ keyCode }) => {
  if (!props.sidebarOpen || keyCode !== 27) return
  emit('close-sidebar')
}

onMounted(() => {
  document.addEventListener('click', clickHandler)
  document.addEventListener('keydown', keyHandler)
})

onUnmounted(() => {
  document.removeEventListener('click', clickHandler)
  document.removeEventListener('keydown', keyHandler)
})

watch(sidebarExpanded, () => {
  localStorage.setItem('sidebar-expanded', sidebarExpanded.value)
  if (sidebarExpanded.value) {
    document.querySelector('body').classList.add('sidebar-expanded')
  } else {
    document.querySelector('body').classList.remove('sidebar-expanded')
  }
})
</script>
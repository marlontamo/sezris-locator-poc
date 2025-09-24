<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue'
import type { BreadcrumbItemType } from '@/types'
import { usePage } from '@inertiajs/vue3'
import { watch, ref } from 'vue'

interface Props {
  breadcrumbs?: BreadcrumbItemType[]
}

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
})

const page = usePage()
const visibleFlash = ref<{ success?: string; error?: string }>({})

// 👀 Watch for flash messages coming from backend
watch(
  () => page.props.flash,
  (newVal) => {
    if (newVal?.success || newVal?.error) {
      visibleFlash.value = newVal
      setTimeout(() => (visibleFlash.value = {}), 3000) // auto-hide after 3s
    }
  },
  { immediate: true, deep: true }
)
</script>

<template>
  <AppSidebarLayout :breadcrumbs="breadcrumbs">
    <!-- ✅ Flash Messages -->
    <div v-if="visibleFlash.success" class="mb-4 px-4 py-2 rounded bg-green-500 text-white">
      {{ visibleFlash.success }}
    </div>
    <div v-if="visibleFlash.error" class="mb-4 px-4 py-2 rounded bg-red-500 text-white">
      {{ visibleFlash.error }}
    </div>

    <!-- Page Content -->
    <slot />
  </AppSidebarLayout>
</template>

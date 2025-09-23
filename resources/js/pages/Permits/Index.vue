<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
  permits: {
    type: Array,
    default: () => []
  }
});

const permits = ref(props.permits);
</script>

<template>
  <AppLayout>
    <Head title="Permits" />

    <div class="p-6 max-w-6xl mx-auto space-y-6">

      <!-- Header remains same size -->
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">All Permits</h1>
        <a
          href="/permits/create"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Create Permit
        </a>
      </div>

      <!-- Table Container -->
      <div class="border rounded shadow overflow-x-auto w-full">
        <table class="min-w-[1000px] text-left border-collapse text-base">
          <thead>
            <tr class="bg-gray-100">
              <th class="border px-6 py-4">Title</th>
              <th class="border px-6 py-4">Type</th>
              <th class="border px-6 py-4">Status</th>
              <th class="border px-6 py-4">Created By</th>
              <th class="border px-6 py-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="permit in permits" :key="permit?.id" class="hover:bg-gray-50">
              <td class="border px-6 py-4">{{ permit?.title || '-' }}</td>
              <td class="border px-6 py-4">{{ permit?.permit_type || '-' }}</td>
              <td class="border px-6 py-4">{{ permit?.status || '-' }}</td>
              <td class="border px-6 py-4">{{ permit?.user?.name || 'N/A' }}</td>
              <td class="border px-6 py-4">
                <a :href="`/permits/${permit.id}`" class="text-blue-600 hover:underline">
                  View
                </a>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="permits.length === 0">
              <td colspan="5" class="text-center py-4 text-gray-500">No permits found</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </AppLayout>
</template>

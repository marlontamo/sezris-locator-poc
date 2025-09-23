<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
  permit: Object
});

const approvals = ref(props.permit.approvals || []);
const uploads = ref(props.permit.uploads || []);
</script>

<template>
  <AppLayout>
    <Head title="Permit Details" />

    <div class="p-6 max-w-5xl mx-auto space-y-6">

      <!-- Permit Info -->
      <div class="border rounded shadow p-4">
        <h2 class="text-2xl font-bold mb-4">{{ permit.title || 'N/A' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
          <div><strong>Description:</strong> {{ permit.description || '-' }}</div>
          <div><strong>Type:</strong> {{ permit.permit_type || '-' }}</div>
          <div><strong>Status:</strong> {{ permit.status || '-' }}</div>
          <div><strong>Created By:</strong> {{ permit.user?.name || 'N/A' }}</div>
        </div>
      </div>

      <!-- Approvals Table -->
      <div class="border rounded shadow p-4 overflow-x-auto">
        <h3 class="text-lg font-semibold mb-2">Approvals</h3>
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-100">
              <th class="border px-4 py-2">Step</th>
              <th class="border px-4 py-2">Approver</th>
              <th class="border px-4 py-2">Status</th>
              <th class="border px-4 py-2">Remarks</th>
              <th class="border px-4 py-2">Acted At</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="approval in approvals" :key="approval.id" class="hover:bg-gray-50">
              <td class="border px-4 py-2">{{ approval.step || '-' }}</td>
              <td class="border px-4 py-2">{{ approval.approver?.name || 'N/A' }}</td>
              <td class="border px-4 py-2">{{ approval.status || '-' }}</td>
              <td class="border px-4 py-2">{{ approval.remarks || '-' }}</td>
              <td class="border px-4 py-2">{{ approval.acted_at || '-' }}</td>
            </tr>
            <tr v-if="approvals.length === 0">
              <td colspan="5" class="text-center py-4 text-gray-500">No approvals yet</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Uploaded Files Table -->
      <div class="border rounded shadow p-4 overflow-x-auto">
        <h3 class="text-lg font-semibold mb-2">Uploaded Files</h3>
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-100">
              <th class="border px-4 py-2">File Name</th>
              <th class="border px-4 py-2">Size</th>
              <th class="border px-4 py-2">Download</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="file in uploads" :key="file.id" class="hover:bg-gray-50">
              <td class="border px-4 py-2">{{ file.file_name || 'N/A' }}</td>
              <td class="border px-4 py-2">{{ file.file_size ? Math.round(file.file_size / 1024) + ' KB' : '-' }}</td>
              <td class="border px-4 py-2">
                <a
                  v-if="file.file_path"
                  :href="`/storage/${file.file_path}`"
                  target="_blank"
                  class="text-blue-600 hover:underline"
                >
                  Download
                </a>
                <span v-else>-</span>
              </td>
            </tr>
            <tr v-if="uploads.length === 0">
              <td colspan="3" class="text-center py-4 text-gray-500">No files uploaded</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </AppLayout>
</template>

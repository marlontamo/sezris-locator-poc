<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/inertia-vue3'

const props = defineProps({
  permit: Object
})

const approvals = ref(props.permit?.approvals || [])
const uploads = ref(props.permit?.uploads || [])

/**
 * Handles approval or rejection
 * @param approvalId - the ID of the approval
 * @param action - 'approve' | 'reject'
 */
function actApproval(approvalId: number, action: 'approve' | 'reject') {
  const url = `/approvals/${approvalId}/${action}` // /approvals/123/approve or /reject

  router.post(url, {}, {
    onSuccess: (page) => {
      // Update local approvals array after server response
      const updatedApproval = page.props.value?.permit.approvals.find(a => a.id === approvalId)
      if (updatedApproval) {
        const index = approvals.value.findIndex(a => a.id === approvalId)
        approvals.value[index] = updatedApproval
      }
    },
    onError: (errors) => {
      console.error(`Failed to ${action} approval`, errors)
    }
  })
}
</script>

<template>
  <AppLayout>
    <Head title="Permit Details" />

    <div class="p-6 max-w-5xl mx-auto space-y-6">

      <!-- Permit Info -->
      <div class="border rounded shadow p-4">
        <h2 class="text-2xl font-bold mb-4">{{ permit?.title || 'Permit' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
          <div><strong>Description:</strong> {{ permit?.description || '-' }}</div>
          <div><strong>Type:</strong> {{ permit?.permit_type || '-' }}</div>
          <div><strong>Status:</strong> {{ permit?.status || '-' }}</div>
          <div><strong>Created By:</strong> {{ permit?.user?.name || 'N/A' }}</div>
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
              <th class="border px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="approval in approvals" :key="approval.id" class="hover:bg-gray-50">
              <td class="border px-4 py-2">{{ approval?.step || '-' }}</td>
              <td class="border px-4 py-2">{{ approval?.approver?.name || 'N/A' }}</td>
              <td class="border px-4 py-2">{{ approval?.status || '-' }}</td>
              <td class="border px-4 py-2">{{ approval?.remarks || '-' }}</td>
              <td class="border px-4 py-2">{{ approval?.acted_at || '-' }}</td>
              <td class="border px-4 py-2 flex gap-2">
                <button
                  @click="actApproval(approval.id, 'approve')"
                  class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700"
                  :disabled="approval.status === 'approved' || approval.status === 'rejected'"
                >
                  Approve
                </button>
                <button
                  @click="actApproval(approval.id, 'reject')"
                  class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"
                  :disabled="approval.status === 'approved' || approval.status === 'rejected'"
                >
                  Reject
                </button>
              </td>
            </tr>
            <tr v-if="approvals.length === 0">
              <td colspan="6" class="text-center py-4 text-gray-500">No approvals yet</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Uploaded Files Table -->
      <div class="border rounded shadow p-4 overflow-x-auto">
        <h3 class="text-lg font-semibold mb-2">Uploaded Requirements</h3>
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
            <tr v-if="uploads?.length === 0">
              <td colspan="3" class="text-center py-4 text-gray-500">No files uploaded</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </AppLayout>
</template>

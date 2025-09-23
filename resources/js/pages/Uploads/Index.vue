<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, usePage, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const { props } = usePage()
const uploads = props.uploads?.data || []
const flash = props.flash || {}
const filters = ref(props.filters || {})

// 🔎 reactive search text
const search = ref(filters.value.search || '')

// debounce search so it won’t spam the server
let timeout = null
watch(search, (value) => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    router.get(route('uploads.index'), { search: value }, { preserveState: true, replace: true })
  }, 400)
})

function destroy(id) {
  if (confirm('Are you sure you want to delete this file?')) {
    router.delete(route('uploads.destroy', id))
  }
}
</script>

<template>
  <AppLayout>
    <Head title="Uploads" />

    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Uploaded Files
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

          <!-- Flash Success -->
          <div v-if="flash.success" class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ flash.success }}
          </div>

          <!-- Search -->
          <div class="mb-4">
            <input
              v-model="search"
              type="text"
              placeholder="Search files..."
              class="border rounded p-2 w-full"
            />
          </div>

          <!-- Table -->
          <table class="w-full border-collapse border">
            <thead>
              <tr class="bg-gray-100 text-left">
                <th class="p-2 border">File Name</th>
                <th class="p-2 border">Uploaded By</th>
                <th class="p-2 border">Uploaded At</th>
                <th class="p-2 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="upload in uploads" :key="upload.id" class="border-t">
                <td class="p-2">{{ upload.file_name }}</td>
                <td class="p-2">{{ upload.user?.name || 'N/A' }}</td>
                <td class="p-2">{{ upload.created_at }}</td>
                <td class="p-2 flex gap-2">
                  <!-- View -->
                  <a
                    :href="route('uploads.view', upload.id)"
                    target="_blank"
                    class="px-2 py-1 bg-blue-500 text-white rounded text-sm"
                  >
                    View
                  </a>

                  <!-- Download -->
                  <a
                    :href="route('uploads.download', upload.id)"
                    class="px-2 py-1 bg-green-500 text-white rounded text-sm"
                  >
                    Download
                  </a>

                  <!-- Delete -->
                  <button
                    @click="destroy(upload.id)"
                    class="px-2 py-1 bg-red-500 text-white rounded text-sm"
                  >
                    Delete
                  </button>
                </td>
              </tr>

              <tr v-if="!uploads.length">
                <td colspan="4" class="text-center p-4 text-gray-500">
                  No files uploaded yet.
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="mt-4 flex gap-2">
            <Link
              v-for="link in props.uploads.links"
              :key="link.label"
              :href="link.url || ''"
              v-html="link.label"
              class="px-3 py-1 border rounded"
              :class="{
                'bg-blue-500 text-white': link.active,
                'text-gray-500 cursor-not-allowed': !link.url
              }"
            />
          </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>

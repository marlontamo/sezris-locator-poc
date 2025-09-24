<template>
  <div class="flex flex-col items-start gap-2">
    <!-- File input -->
    <input
      id="file-upload"
      type="file"
      class="hidden"
      @change="handleFileChange"
    />

    <!-- Buttons row -->
    <div class="flex gap-2">
      <label
        for="file-upload"
        class="cursor-pointer px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Select File
      </label>

      <button
        @click="uploadFile"
        :disabled="!selectedFile || uploading"
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
      >
        {{ uploading ? 'Uploading...' : 'Upload File' }}
      </button>
    </div>

    <!-- Selected file name -->
    <p v-if="fileName" class="text-gray-700">Selected: {{ fileName }}</p>

    <!-- Upload progress bar -->
    <div v-if="uploadProgress > 0" class="w-full bg-gray-200 rounded h-2 mt-1">
      <div
        class="bg-blue-600 h-2 rounded"
        :style="{ width: uploadProgress + '%' }"
      ></div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  uploadUrl: { type: String, required: true },
  permitId: { type: Number, required: true }
})

const fileName = ref('')
const selectedFile = ref(null)
const uploadProgress = ref(0)
const uploading = ref(false)

function handleFileChange(event) {
  const file = event.target.files[0]
  if (file) {
    fileName.value = file.name
    selectedFile.value = file
  }
}

function uploadFile() {
  if (!selectedFile.value) return

  uploading.value = true

  const formData = new FormData()
  formData.append('file', selectedFile.value)
  formData.append('permit_id', props.permitId) // ✅ attach permit_id

  router.post(props.uploadUrl, formData, {
    forceFormData: true,
    onProgress: (progress) => {
      uploadProgress.value = Math.round(progress.percentage ?? 0)
    },
    onSuccess: () => {
      fileName.value = ''
      selectedFile.value = null
      uploadProgress.value = 0
    },
    onFinish: () => {
      uploading.value = false
    }
  })
}
</script>

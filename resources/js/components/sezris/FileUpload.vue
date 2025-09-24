<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Input from '../ui/input/Input.vue'
import Label from '../ui/label/Label.vue'
import { Button } from '@/components/ui/button'

const props = defineProps({
  uploadUrl: { type: String, required: true },
  permitId: { type: Number, required: true }
})

const selectedFile = ref<File | null>(null)
const fileName = ref('')
const uploadProgress = ref(0)
const uploading = ref(false)

function handleFileChange(event: Event) {
  const file = (event.target as HTMLInputElement).files?.[0]
  if (file) {
    selectedFile.value = file
    fileName.value = file.name
  }
}

function uploadFile() {
  if (!selectedFile.value) return

  uploading.value = true
  const formData = new FormData()
  formData.append('file', selectedFile.value)
  formData.append('permit_id', props.permitId.toString())

  router.post(props.uploadUrl, formData, {
    forceFormData: true,
    onProgress: (progress) => {
      uploadProgress.value = Math.round(progress?.percentage ?? 0)
    },
    onSuccess: () => {
      selectedFile.value = null
      fileName.value = ''
      uploadProgress.value = 0
    },
    onFinish: () => {
      uploading.value = false
    }
  })
}
</script>

<template>
  <div class="flex flex-col gap-2 w-full max-w-md">

    <!-- Hidden file input -->
    <Input
      id="file-upload"
      type="file"
      class="hidden"
      @change="handleFileChange"
    />

    <!-- File upload controls -->
    <div class="inline-flex w-full rounded-md border border-gray-300 overflow-hidden">
      <Label
        for="file-upload"
        class="cursor-pointer flex-1 text-center px-2 py-1 bg-gray-50 hover:bg-gray-100"
      >
        Select File
      </Label>
      <Button
        class="flex-1 px-2 py-1"
        @click="uploadFile"
        :disabled="!selectedFile || uploading"
      >
        {{ uploading ? 'Uploading...' : 'Upload' }}
      </Button>
    </div>

    <!-- Selected file name -->
    <p v-if="fileName" class="text-gray-700 text-sm truncate">{{ fileName }}</p>

    <!-- Upload progress -->
    <div v-if="uploadProgress > 0" class="w-full bg-gray-200 rounded h-2 mt-1 overflow-hidden">
      <div class="bg-blue-600 h-2 rounded" :style="{ width: uploadProgress + '%' }"></div>
    </div>

  </div>
</template>

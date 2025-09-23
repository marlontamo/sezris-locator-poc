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
      <!-- Select File label -->
      <Label
        for="file-upload"
        class="cursor-pointer px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Select File
      </Label>

      <!-- Upload button -->
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
import { ref } from 'vue';
import Label from './ui/label/Label.vue';
import axios from 'axios';

const props = defineProps({
  uploadUrl: { type: String, required: true }
});

const fileName = ref('');
const selectedFile = ref(null);
const uploadProgress = ref(0);
const uploading = ref(false);

function handleFileChange(event) {
  const file = event.target.files[0];
  if (file) {
    fileName.value = file.name;
    selectedFile.value = file;
  }
}

async function uploadFile() {
  if (!selectedFile.value) return;

  uploading.value = true;
  const formData = new FormData();
  formData.append('file', selectedFile.value);

  try {
    const response = await axios.post(props.uploadUrl, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
      onUploadProgress: progressEvent => {
        uploadProgress.value = Math.round(
          (progressEvent.loaded * 100) / progressEvent.total
        );
      }
    });

    console.log('Upload successful:', response.data);
    fileName.value = '';
    selectedFile.value = null;
    uploadProgress.value = 0;
  } catch (error) {
    console.error('Upload failed:', error);
  } finally {
    uploading.value = false;
  }
}
</script>

<script setup>
import { ref } from 'vue'
import Applayout from '@/layouts/AppLayout.vue'
import axios from 'axios'

const file = ref(null)
const errors = ref({})
const success = ref(false)
const processing = ref(false)

function submit() {
  processing.value = true
  success.value = false
  errors.value = {}

  const formData = new FormData()
  formData.append('file', file.value)

  axios.post('/uploads', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  })
    .then(() => {
      success.value = true
      file.value = null
    })
    .catch((err) => {
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors || {}
      }
    })
    .finally(() => {
      processing.value = false
    })
}
</script>

<template>
  <Applayout>
    <!-- Header slot -->
    <template #header>
      <h2 class="text-xl font-semibold leading-tight">Upload File</h2>
    </template>

    <!-- Main content -->
    <div class="max-w-lg mx-auto mt-6 p-6 bg-white rounded shadow">
      <!-- Success -->
      <div v-if="success" class="bg-green-100 text-green-800 p-3 rounded mb-4">
        File uploaded successfully!
      </div>

      <!-- Errors -->
      <div v-if="errors.file" class="text-red-600 mb-2">
        {{ errors.file[0] }}
      </div>

      <form @submit.prevent="submit">
        <input
          type="file"
          @change="file = $event.target.files[0]"
          class="mb-4 border rounded px-3 py-2 w-full"
        />

        <button
          type="submit"
          class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700"
          :disabled="processing"
        >
          <span v-if="processing">Uploading...</span>
          <span v-else>Upload</span>
        </button>
      </form>
    </div>
  </Applayout>
</template>


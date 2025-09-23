<script setup>
import { useForm, router } from '@inertiajs/vue3'

const form = useForm({
  file: null
})

const handleFileChange = (e) => {
  form.file = e.target.files[0]
}

const submit = () => {
  form.post(route('uploads.store'), {
    forceFormData: true,
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>

<template>
  <div class="max-w-lg mx-auto mt-6 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Upload File</h2>

    <!-- Success message -->
    <div
      v-if="$page.props.flash.success"
      class="mb-4 p-2 rounded bg-green-100 text-green-700"
    >
      {{ $page.props.flash.success }}
    </div>

    <form @submit.prevent="submit" enctype="multipart/form-data">
      <!-- File input -->
      <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Select File</label>
        <input
          type="file"
          @change="handleFileChange"
          class="block w-full border rounded p-2"
        />
        <div v-if="form.errors.file" class="text-red-500 text-sm mt-1">
          {{ form.errors.file }}
        </div>
      </div>

      <!-- Submit -->
      <button
        type="submit"
        :disabled="form.processing"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        <span v-if="form.processing">Uploading...</span>
        <span v-else>Upload</span>
      </button>
    </form>
  </div>
</template>

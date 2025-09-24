<script setup>
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  permit_type: ''
})

function submit() {
  form.post('/permits')
}
</script>

<template>
  <AppLayout>
    <Head title="Create Permit" />
    <div class="p-4 max-w-3xl mx-auto">
      <h1 class="text-2xl font-bold mb-4">Step 1: Create Permit</h1>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block mb-1 font-medium">Permit Type</label>
          <select
    v-model="form.permit_type"
    class="w-full border px-3 py-2 rounded text-gray-800">
            <option value="" disabled>Select a permit type</option>
            <option value="Bring In">Bring In</option>
            <option value="Bring Out">Bring Out</option>
            <option value="GatePass">GatePass</option>
          </select>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="px-4 py-2 bg-blue-600 text-white rounded"
        >
          {{ form.processing ? 'Creating...' : 'Create Permit' }}
        </button>
      </form>
    </div>
  </AppLayout>
</template>

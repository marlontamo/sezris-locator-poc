<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
  title: '',
  description: '',
  permit_type: ''
});

function submit() {
  form.post('/permits', {
    onSuccess: () => form.reset(),
  });
}
</script>

<template>
  <AppLayout>
    <Head title="Create Permit" />
      <div class="p-4 max-w-3xl mx-auto w-full">
  <h1 class="text-2xl font-bold mb-4">Create Permit</h1>

  <form @submit.prevent="submit" class="space-y-4">
    <!-- Title Field -->
    <div>
      <label class="block mb-1 font-medium">Title</label>
      <input
        v-model="form.title"
        type="text"
        class="w-full border px-3 py-2 rounded"
      />
      <span class="text-red-500 text-sm" v-if="form.errors.title">{{ form.errors.title }}</span>
    </div>

    <!-- Description Field -->
    <div>
      <label class="block mb-1 font-medium">Description</label>
      <textarea
        v-model="form.description"
        class="w-full border px-3 py-2 rounded"
      ></textarea>
      <span class="text-red-500 text-sm" v-if="form.errors.description">{{ form.errors.description }}</span>
    </div>

    <!-- Permit Type Dropdown -->
    <div>
      <label class="block mb-1 font-medium">Permit Type</label>
      <select
        v-model="form.permit_type"
        class="w-full border px-3 py-2 rounded"
      >
        <option value="" disabled>Select a permit type</option>
        <option value="Permit to Bring In">Permit to Bring In</option>
        <option value="Permit to Bring Out">Permit to Bring Out</option>
        <option value="GatePass">GatePass</option>
      </select>
      <span class="text-red-500 text-sm" v-if="form.errors.permit_type">{{ form.errors.permit_type }}</span>
    </div>

    <!-- Submit Button -->
    <button
      type="submit"
      :disabled="form.processing"
      class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
    >
      {{ form.processing ? 'Submitting...' : 'Create Permit' }}
    </button>
  </form>
</div>

 </AppLayout>
</template>

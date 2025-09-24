<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import Input from '../ui/input/Input.vue'
import Label from '../ui/label/Label.vue'
import { Button } from "@/components/ui/button"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select" 
import FileUpload from '../FileUpload.vue'


const form = useForm({
  title: '',
  description: '',
  permit_type: ''
})

// Options for the Select
const permitOptions = [
  { label: 'Permit to Bring In', value: 'Permit to Bring In' },
  { label: 'Permit to Bring Out', value: 'Permit to Bring Out' },
  { label: 'GatePass', value: 'GatePass' },
]

function submit() {
  form.post('/permits', {
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <Head title="Create Permit" />

  <div class="p-4 max-w-3xl mx-auto w-[3/12]">
    <h1 class="text-2xl font-bold mb-4">Create Permit</h1>

    <form @submit.prevent="submit" class="space-y-4">

      <!-- Title Field -->
      <div>
        <Label>Title</Label>
        <Input v-model="form.title" placeholder="Enter permit title" />
        <span class="text-red-500 text-sm" v-if="form.errors.title">{{ form.errors.title }}</span>
      </div>

      <!-- Description Field -->
      <div>
        <Label>Description</Label>
        <Input v-model="form.description" placeholder="Enter description" />
        <span class="text-red-500 text-sm" v-if="form.errors.description">{{ form.errors.description }}</span>
      </div>

      <!-- Permit Type Dropdown using Starter Kit Select -->
      <div>
        <Label>Permit Type</Label>
        <Select>
    <SelectTrigger class="w-full">
      <SelectValue placeholder="Permit Type" />
    </SelectTrigger>
    <SelectContent>
      <SelectGroup>
        <SelectLabel>Permit Types</SelectLabel>
        <SelectItem value="GatePass">
          GatePass
        </SelectItem>
        <SelectItem value="Permit-to-bring-In">
         Permit to Bring-In
        </SelectItem>
        <SelectItem value="Permit-to-bring-out">
          Permit to Bring-Out
        </SelectItem>
      </SelectGroup>
    </SelectContent>
  </Select>
        <span class=" w-full text-red-500 text-sm" v-if="form.errors.permit_type">{{ form.errors.permit_type }}</span>
      </div>

      <!-- Submit Button -->
      <Button
        type="submit"
        :disabled="form.processing"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
      >
        {{ form.processing ? 'Submitting...' : 'Create Permit' }}
      </Button><FileUpload/>
    </form>
    
  </div>

</template>

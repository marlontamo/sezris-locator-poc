<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps<{
  title: string;
  description?: string;
  value: string | number;
  cardBg?: string; // Tailwind background for card
  href?: string;   // URL to navigate on click
}>();

function handleClick() {
  if (props.href) router.visit(props.href);
}
</script>

<template>
  <div
    :class="['p-4 rounded-xl shadow cursor-pointer flex items-center gap-4 transition hover:shadow-lg', cardBg || 'bg-white']"
    @click="handleClick"
  >
    <slot name="icon" />
    <div class="flex flex-col">
      <div class="text-xl font-bold">{{ value }}</div>
      <div class="text-sm text-gray-600">{{ description || title }}</div>
    </div>
  </div>
</template>

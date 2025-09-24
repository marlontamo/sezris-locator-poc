<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import FileUpload from '@/components/FileUpload.vue';


const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Requirements',
    href: '/Requirement',
  },
];
const props = defineProps({
  permit: Object
})
</script>

<template>
    <Head title="Requirement"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                   <PlaceholderPattern/>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern/>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                   <PlaceholderPattern/>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <h1 class="text-2xl font-bold">
        Upload Requirements for Permit #{{ props.permit?.id }}
      </h1>
      <p class="text-gray-600">Permit Type: {{ props.permit?.permit_type }}</p>
                <FileUpload :upload-url="`/uploads`" :permit-id="permit?.id" />
            </div>
        </div>
    </AppLayout>
</template>
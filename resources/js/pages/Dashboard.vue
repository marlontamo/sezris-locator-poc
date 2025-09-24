<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import LocatorCard from '@/components/sezris/Locator-card.vue';
import { ShoppingCart, Users, CheckCircle, XCircle } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <div class="relative  overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                   <LocatorCard
      title="Active"
      description=""
      value="0"
      chartBg="bg-blue-100"
      cardBg="bg-blue-50"
      href="/uploads"
    >
    <template #icon>
    <ShoppingCart class="h-8 w-8 text-blue-600"/>
  </template>
                   </LocatorCard>
                </div>
                <div class="relative  overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                   <LocatorCard
      title="On-Process"
      description=""
      value="4"
      chartBg="bg-green-100"
      cardBg="bg-green-50"
      href="/permits/create"
    >
     <template #icon>
    <Users class="h-8 w-8 text-green-600"/>
  </template>
                   </LocatorCard>
                </div>
                <div class="relative  overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                   <LocatorCard
                title='Approved'
                value='4'
                cardBg='bg-green-50'
                href='/permits/1'
                >
                <template #icon>
                    <CheckCircle class='h-8 w-8 text-green-600' />
                </template>
            </LocatorCard>
             </div>
                <div class="relative overflow-hidden rounded-xl border border-sidebar-border opacity-70 dark:border-sidebar-border">
                   <LocatorCard
                    title='Rejected'
                    value='4'
                    cardBg='bg-red-50'
                    href='/permits'
                    >
                    <template #icon>
                        <XCircle class='h-8 w-8 text-red-600' />
                    </template>
                    </LocatorCard>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border  opacity-70 md:min-h-min dark:border-sidebar-border">
                 <PlaceholderPattern/>
            </div>
        </div>
    </AppLayout>
</template>

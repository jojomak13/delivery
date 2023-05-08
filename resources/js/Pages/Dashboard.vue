<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";

const { t } = useI18n()

defineProps(['stats'])

/**
 * bg-pink-600
 * bg-purple-600
 * bg-yellow-500
 * bg-green-500
 */
</script>

<template>
    <Head :title="t('app.dashboard')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.dashboard') }}
            </h2>
        </template>

        <ul role="list" class="mt-3 grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-4">
            <li v-for="project in stats" :key="project.name" class="col-span-1 flex rounded-md shadow-sm">
                <div
                    :class="[project.bgColor, 'flex w-16 flex-shrink-0 items-center justify-center rounded-l-md text-sm font-medium text-white']">
                    {{ project.initials }}</div>
                <div
                    class="flex flex-1 items-center justify-between truncate rounded-r-md border-b border-r border-t border-gray-200 bg-white">
                    <div class="flex-1 truncate px-4 py-2 text-sm">
                        <span class="font-medium text-gray-900 hover:text-gray-600">{{ t(project.name) }}</span>
                        <p class="text-gray-500">{{ project.count }} {{ t('app.record') }}</p>
                    </div>
                </div>
            </li>
        </ul>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, Link as InertiaLink } from '@inertiajs/inertia-vue3';
import { useI18n } from "vue-i18n";
import Pagination from '@/Components/Pagination.vue'

const { t } = useI18n()

defineProps(['stats', 'sellers'])

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
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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

            <div class="bg-white p-5 mt-5 rounded-md shadow-sm">
                <h2 class="text-xl">{{ t('app.seller.unapproved') }}</h2>
                <div class="mt-8 flex flex-col">
                    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ t('app.name') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.email') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.phone') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.store.title') }}</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <template v-if="sellers.data.length">
                                    <tr v-for="seller in sellers.data" :key="seller.id">
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ seller.name }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ seller.email }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ seller.phone }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ seller?.my_store?.name ?? '---' }}</div>
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <InertiaLink :href="route('admin.sellers.edit', seller)" class="text-indigo-600 hover:text-indigo-900">
                                                {{ t('app.edit') }}
                                            </InertiaLink> |
                                            <button @click="deleteSeller(seller)" class="text-red-600 hover:text-red-900">
                                                {{ t('app.delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="5" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">
                                            <div class="text-gray-900">{{ t('app.no_records') }}</div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="my-5">
                    <Pagination :links="sellers.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

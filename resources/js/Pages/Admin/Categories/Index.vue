<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, Link as InertiaLink } from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import Link from '@/Components/Link.vue'
import pagination from '@/Components/Pagination.vue'
import { Inertia } from '@inertiajs/inertia';

const { t } = useI18n()

defineProps(['categories'])

const deleteCategory = (category) => {
    if(!confirm(t('app.sure'))) return

    Inertia.delete(route('admin.categories.destroy', category))
}
</script>

<template>
    <Head :title="t('app.category.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('app.category.title') }}
                </h2>

                <Link type="primary" :href="route('admin.categories.create')">{{ t('app.category.create') }}</Link>
            </div>
        </template>

        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ t('app.name') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.type') }}</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template v-if="categories.data.length">
                                <tr v-for="category in categories.data" :key="category.id">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full" :src="category.image_url" :alt="category.name" />
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ category.name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <span v-if="category.type === 'store'" class="inline-flex items-center rounded-full bg-purple-100 px-3 py-0.5 text-sm font-medium text-purple-800">{{ t('app.store.title') }}</span>
                                        <span v-else class="inline-flex items-center rounded-full bg-blue-100 px-3 py-0.5 text-sm font-medium text-blue-800">{{ t('app.product.title_single') }}</span>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <InertiaLink :href="route('admin.categories.edit', category)" class="text-indigo-600 hover:text-indigo-900">
                                            {{ t('app.edit') }}
                                        </InertiaLink> |
                                        <button @click="deleteCategory(category)" class="text-red-600 hover:text-red-900">
                                            {{ t('app.delete') }}
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="3" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">
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
                <pagination :links="categories.links" />
            </div>

        </div>
    </AuthenticatedLayout>
</template>

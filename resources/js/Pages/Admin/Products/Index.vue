<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, Link as InertiaLink } from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import pagination from '@/Components/Pagination.vue'
import { Inertia } from '@inertiajs/inertia';

const { t } = useI18n()

defineProps(['products'])

const deleteProduct = (product) => {
    if(!confirm(t('app.sure'))) return

    Inertia.delete(route('admin.products.destroy', product))
}
</script>

<template>
    <Head :title="t('app.product.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('app.product.title') }}
                </h2>
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
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.status') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.category.title_single') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.store.title') }}</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template v-if="products.data.length">
                                <tr v-for="product in products.data" :key="product.id">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full" :src="product.image_url" :alt="product.name" />
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ product.name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <span v-if="product.approved" class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ t('app.active') }}</span>
                                        <span v-else class="inline-flex rounded-full bg-yellow-100 px-2 text-xs font-semibold leading-5 text-yellow-800">{{ t('app.disabled') }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ product.category.name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ product.store.name }}</div>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <InertiaLink :href="route('admin.products.edit', product)" class="text-indigo-600 hover:text-indigo-900">
                                            {{ t('app.edit') }}
                                        </InertiaLink> |
                                        <button @click="deleteProduct(product)" class="text-red-600 hover:text-red-900">
                                            {{ t('app.delete') }}
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="6" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">
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
                <pagination :links="products.links" />
            </div>

        </div>
    </AuthenticatedLayout>
</template>

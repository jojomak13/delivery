<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link as InertiaLink } from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import Link from '@/Components/Link.vue'
import pagination from '@/Components/Pagination.vue'

const { t } = useI18n()

defineProps(['orders'])
</script>

<template>
    <Head :title="t('app.order.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('app.order.title') }}
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ t('app.customer') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.sub_price') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.discount_price') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.total_price') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.branch.single') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.status') }}</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.created_at') }}</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template v-if="orders.data.length">
                                <tr v-for="order in orders.data" :key="order.id">
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ order.user.name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ order.sub_price }} {{ t('app.ru') }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ order.discount_price }} {{ t('app.ru') }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ order.total_price }} {{ t('app.ru') }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ order.branch.name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">{{ t('app.' + order.status) }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ new Date(order.created_at).toDateString() }}</div>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <Link :href="route('seller.orders.show', order)" class="text-indigo-600 hover:text-indigo-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </Link>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="8" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">
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
                <pagination :links="orders.links" />
            </div>

        </div>
    </AuthenticatedLayout>
</template>

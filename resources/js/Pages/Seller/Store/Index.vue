<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link as InertiaLink } from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import Link from "@/Components/Link.vue";
import {Inertia} from "@inertiajs/inertia";

defineProps(['store', 'categories'])

const { t } = useI18n()

const deleteBranch = (branch) => {
    if(!confirm(t('app.sure'))) return

    Inertia.delete(route('seller.branches.destroy', branch));
}
</script>

<template>
    <Head :title="t('app.store.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <template v-if="store">
                    <img class="h-12 w-12 overflow-hidden rounded-full bg-gray-100" :src="store.logo_url">
                </template>
                <template v-else>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ t('app.store.title') }}
                    </h2>
                </template>

                <template v-if="store">
                    <Link type="primary" :href="route('seller.store.edit')">{{ t('app.store.update') }}</Link>
                </template>
                <template v-else>
                    <Link type="primary" :href="route('seller.store.create')">{{ t('app.store.create') }}</Link>
                </template>
            </div>
        </template>

        <div class="grid grid-cols-5 gap-5">
            <!-- Start Branches -->
            <div class="col-span-3">
                <div class="flex justify-between mb-5">
                    <h2 class="text-xl font-semibold">{{ t('app.branch.title') }}</h2>
                    <Link type="primary" :href="route('seller.branches.create')">{{ t('app.branch.create') }}</Link>
                </div>
                <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <li v-for="branch in store.branches" :key="branch.id" class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
                        <div class="flex w-full items-center justify-between space-x-6 p-6">
                            <div class="flex-1 truncate">
                                <div class="flex items-center space-x-3">
                                    <h3 class="truncate text-sm font-medium text-gray-900">{{ branch.name }}</h3>
                                    <span class="inline-block flex-shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">{{ branch.delivery_period }} {{ t('app.minutes') }}</span>
                                </div>
                                <p class="mt-1 truncate text-sm text-gray-500">{{ branch.address }}</p>
                            </div>
                            <div>
                                <h6 class="font-medium text-gray-900">{{ t('app.order.title') }}</h6>
                                <span class="font-semibold text-3xl text-indigo-600">456</span>
                            </div>
                        </div>
                        <div>
                            <div class="-mt-px flex divide-x divide-gray-200">
                                <div class="flex w-0 flex-1">
                                    <InertiaLink  :href="route('seller.branches.edit', branch)" class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center rounded-bl-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                                        {{ t('app.edit') }}
                                    </InertiaLink>
                                </div>
                                <div class="-ml-px flex w-0 flex-1">
                                    <button @click="deleteBranch(branch)" class="relative inline-flex w-0 flex-1 items-center justify-center rounded-br-lg border border-transparent py-4 text-sm font-medium text-gray-700 hover:text-gray-500">
                                        {{ t('app.delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- End Branches -->

            <div class="col-span-2">
                <div class="flex justify-between mb-5">
                    <h2 class="text-xl font-semibold">{{ t('app.category.title') }}</h2>
                    <Link type="primary" :href="route('seller.categories.create')">{{ t('app.category.create') }}</Link>
                </div>
                <ul class="h-full overflow-y-scroll">
                    <li v-for="cat in categories.data" :key="cat.id" class="bg-white p-5 rounded shadow mb-2 flex justify-between items-center">
                        <span class="text-xl">{{ cat.name }}</span>
                        <InertiaLink :href="route('seller.products.index', {category: cat.id})" class="rounded border border-transparent bg-indigo-100 px-2.5 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </InertiaLink>
                    </li>
                </ul>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

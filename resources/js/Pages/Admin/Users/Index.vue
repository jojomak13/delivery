<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import { Head, Link as InertiaLink } from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import Link from '@/Components/Link.vue'
import Pagination from '@/Components/Pagination.vue'
import { Inertia } from '@inertiajs/inertia';

const { t } = useI18n()

defineProps(['users'])

const deleteUser = (user) => {
    if(!confirm(t('app.sure'))) return

    Inertia.delete(route('admin.users.destroy', user))
}

const restoreUser = (user) => {
    if(!confirm(t('app.sure'))) return

    Inertia.post(route('admin.users.restore', user))
}
</script>

<template>
    <Head :title="t('app.user.title')" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('app.user.title') }}
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
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.email') }}</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template v-if="users.data.length">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                        <div class="text-gray-900">{{ user.name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <div class="text-gray-900">{{ user.email }}</div>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <InertiaLink :href="route('admin.users.edit', user)" class="text-indigo-600 hover:text-indigo-900">
                                            {{ t('app.edit') }}
                                        </InertiaLink> |
                                        <button v-if="user.deleted_at" @click="restoreUser(user)" class="text-red-600 hover:text-red-900">
                                            {{ t('app.restore') }}
                                        </button>
                                        <button v-else @click="deleteUser(user)" class="text-red-600 hover:text-red-900">
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
                <Pagination :links="users.links" />
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import {Inertia} from '@inertiajs/inertia';

const { t } = useI18n()

const currentLang = computed(() => usePage().props.value.locale);

const showingNavigationDropdown = ref(false);

const changeLocale = () => {
    Inertia.post(route('locale'), {}, {
        onSuccess(){
            window.location.reload()
        }
    })
}
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('admin.home')">
                            <ApplicationLogo class="block h-9 w-auto" />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavLink :href="route('admin.home')" :active="route().current('admin.home')">
                            {{ t('app.dashboard') }}
                        </NavLink>
                        <NavLink :href="route('admin.sellers.index')" :active="route().current('admin.sellers.index')">
                            {{ t('app.seller.title') }}
                        </NavLink>
                        <NavLink :href="route('admin.categories.index')" :active="route().current('admin.categories.index')">
                            {{ t('app.category.title') }}
                        </NavLink>
                        <NavLink :href="route('admin.products.index')" :active="route().current('admin.products.index')">
                            {{ t('app.product.title') }}
                        </NavLink>
                        <NavLink :href="route('admin.bundles.index')" :active="route().current('admin.bundles.index')">
                            {{ t('app.bundle.title') }}
                        </NavLink>
                        <NavLink :href="route('admin.codes.index')" :active="route().current('admin.codes.index')">
                            {{ t('app.code.title') }}
                        </NavLink>
                        <NavLink :href="route('admin.admins.index')" :active="route().current('admin.admins.index')">
                            {{ t('app.admin.title') }}
                        </NavLink>
                        <NavLink :href="route('admin.users.index')" :active="route().current('admin.users.index')">
                            {{ t('app.user.title') }}
                        </NavLink>
                    </div>

                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative">
                        <button @click="changeLocale" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                            {{ currentLang === 'en' ? 'RU' : 'EN' }}
                        </button>
                    </div>

                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('admin.logout')" method="post" as="button">
                                    {{ t('app.logout') }}
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink :href="route('seller.home')" :active="route().current('seller.home')">
                    {{ t('app.dashboard') }}
                </ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('admin.logout')" method="post" as="button">
                        {{ t('app.logout') }}
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
</template>

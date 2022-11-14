<script setup>
import {Link} from '@inertiajs/inertia-vue3'

defineProps(['type'])

const types = {
    primary: 'bg-gray-800 hover:bg-gray-700 active:bg-gray-900 focus:border-gray-900 focus:shadow-outline-gray',
    success: 'bg-green-800 hover:bg-green-700 active:bg-green-900 focus:border-green-900 focus:shadow-outline-green',
    indigo: 'bg-indigo-800 hover:bg-indigo-700 active:bg-indigo-900 focus:border-indigo-900 focus:shadow-outline-indigo',
}
</script>

<template>
    <Link class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none  transition ease-in-out duration-150" :class="types[type]" >
        <slot />
    </Link>
</template>


<script setup>
    import {useI18n} from "vue-i18n";

    defineProps(['title'])

    const { t } = useI18n()
</script>

<template>
    <div class="rounded-md bg-yellow-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-yellow-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 font-semibold">{{ t('app.attention') }}</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>{{ title }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

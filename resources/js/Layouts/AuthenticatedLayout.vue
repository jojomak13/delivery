<script setup>
import Alert from "@/Components/Alert.vue";
import Navbar from "@/Components/Navbar.vue";
import {useI18n} from "vue-i18n";

const { t } = useI18n()
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <Navbar />

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <template v-if="!$page.props.auth.user.approved">
                            <alert class="mb-5" :title="t('app.not_approved')"/>
                        </template>
                        <slot />
                    </div>
                </div>
            </main>

        </div>
    </div>
</template>

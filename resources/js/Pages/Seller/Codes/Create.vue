<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { t } = useI18n()

const form  = useForm({
    name: '',
    limit: '',
    amount: '',
    end_at: '',
})

const save = () => {
    form.post(route('seller.codes.store'))
}
</script>

<template>
    <Head :title="t('app.code.create')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.code.create') }}
            </h2>
        </template>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form @submit.prevent="save">
                    <div class="md:grid md:grid-cols-2 md:gap-2">
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="name" :value="t('app.name')" />
                            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="limit" :value="t('app.limit')" />
                            <TextInput id="limit" type="number" min="1" class="mt-1 block w-full" v-model="form.limit"/>
                            <InputError class="mt-2" :message="form.errors.limit" />
                        </div>
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="amount" :value="t('app.amount')" />
                            <TextInput id="amount" type="number" min="1" step=".1" class="mt-1 block w-full" v-model="form.amount"/>
                            <InputError class="mt-2" :message="form.errors.amount" />
                        </div>
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="end_at" :value="t('app.end_date')" />
                            <TextInput id="end_at" type="date" class="mt-1 block w-full" v-model="form.end_at"/>
                            <InputError class="mt-2" :message="form.errors.end_at" />
                        </div>
                    </div>
                    <div class="text-right">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ t('app.save') }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

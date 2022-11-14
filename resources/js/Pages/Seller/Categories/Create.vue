<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import 'vue-select/dist/vue-select.css';
import VSelect from 'vue-select'
import { Inertia } from '@inertiajs/inertia';

defineProps(['categories'])

const { t } = useI18n()

const form  = useForm({
    name: ''
})

const save = () => {
    form.post(route('seller.categories.store'))
}

const search = (search, loading) => {
    loading(true)
    if(search){
        Inertia.get(route('seller.categories.create'), {search}, {
            preserveState: true,
            replace: true,
            onSuccess: () => loading(false)
        })
    } else {
        loading(false)
    }
}
</script>

<template>
    <Head :title="t('app.category.create')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.category.create') }}
            </h2>
        </template>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <form @submit.prevent="save">
                    <input type="hidden" v-model="form.name">
                    <div class="md:grid md:grid-cols-2 md:gap-2">
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="name" :value="t('app.name')" />
                            <v-select taggable v-model="form.name" @search="search" label="name" :options="categories"></v-select>
                            <InputError class="mt-2" :message="form.errors.name" />
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

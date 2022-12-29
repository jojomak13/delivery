<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { t } = useI18n()

const form  = useForm({
    name: '',
    image: '',
    type: '',
    description: ''
})

const save = () => {
    form.post(route('admin.categories.store'))
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
                    <div class="md:grid md:grid-cols-2 md:gap-2">
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="name" :value="t('app.name')" />
                            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="image" :value="t('app.image')" />
                            <TextInput id="image" type="file" class="mt-1 block w-full" @input="form.image = $event.target.files[0]" />
                            <InputError class="mt-2" :message="form.errors.image" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="type" :value="t('app.type')" />
                            <SelectInput id="type" class="mt-1 block w-full" v-model="form.type">
                                <option value="">{{ t('app.select_option') }}</option>
                                <option value="store">{{ t('app.store.title') }}</option>
                                <option value="product">{{ t('app.product.title_single') }}</option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.type" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="description" :value="t('app.description')" />
                            <TextInput id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                            <InputError class="mt-2" :message="form.errors.description" />
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

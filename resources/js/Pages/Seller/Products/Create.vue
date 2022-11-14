<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import SelectInput from "@/Components/SelectInput.vue";

const { t } = useI18n()

defineProps(['categories'])

const form  = useForm({
    name: '',
    image: '',
    price: 0,
    category_id: '',
    description: '',
    available: true
})

const save = () => {
   form.post(route('seller.products.store'))
}
</script>

<template>
    <Head :title="t('app.product.create')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.product.create') }}
            </h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                            <InputLabel for="price" :value="t('app.price')" />
                            <TextInput id="price" type="number"  step=".1" min="0" class="mt-1 block w-full" v-model="form.price" />
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="category_id" :value="t('app.category.title_single')" />
                            <SelectInput id="category_id" class="mt-1 block w-full" v-model="form.category_id">
                                <option value="">{{ t('app.select_option') }}</option>
                                <option v-for="option in categories" :key="option.id" :value="option.id">{{ option.name }}</option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.category_id" />
                        </div>

                        <div class="md:col-span-2 mb-4">
                            <InputLabel for="description" :value="t('app.description')" />
                            <TextArea id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                       <div class="md:col-span-1 mb-4">
                            <InputLabel for="available" :value="t('app.available')" />
                            <SelectInput id="available" class="mt-1 block w-full" v-model="form.available">
                                <option value="true">{{ t('app.yes') }}</option>
                                <option value="false">{{ t('app.no') }}</option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.available" />
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

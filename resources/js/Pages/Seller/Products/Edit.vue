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
import 'vue-select/dist/vue-select.css';
import VSelect from 'vue-select'

const { t } = useI18n()

const {product} = defineProps(['product', 'categories', 'extras', 'types'])

const form  = useForm({
    _method: 'patch',
    name: product.name,
    image: '',
    size: product.size,
    extra: product.extras.map(el => el.id),
    types: product.types.map(el => el.id),
    category_id: product.category_id,
    description: product.description,
    available: product.available ? 'true' : 'false'
})

const save = () => {
   form.post(route('seller.products.update', product))
}

const newSize = () => {
    form.size.push({id: new Date().getTime(), name: '', price: 0})
}

const removeSize = (id) => {
    form.size = form.size.filter(el => el.id !== id)
}
</script>

<template>
    <Head :title="t('app.product.update')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.product.update') }}
            </h2>
        </template>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

            <div class="col-span-2 bg-white shadow-sm sm:rounded-lg">
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
                                <span class="text-gray-500 text-sm">{{ $t('app.input_file_hint') }}</span>
                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                            <div class="md:col-span-1 mb-4">
                                <InputLabel for="category_id" :value="t('app.category.title_single')" />
                                <SelectInput id="category_id" class="mt-1 block w-full" v-model="form.category_id">
                                    <option value="">{{ t('app.select_option') }}</option>
                                    <option v-for="option in categories" :key="option.id" :value="option.id">{{ option.name }}</option>
                                </SelectInput>
                                <InputError class="mt-2" :message="form.errors.category_id" />
                            </div>

                            <div class="md:col-span-1 mb-4">
                                <InputLabel for="extra" :value="t('app.extra.title')" />
                                <v-select multiple v-model="form.extra" :reduce="option => option.id" label="name" :options="extras"></v-select>
                                <InputError class="mt-2" :message="form.errors.extra" />
                            </div>

                            <div class="md:col-span-2 mb-4">
                                <InputLabel for="description" :value="t('app.description')" />
                                <TextArea id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                                <span class="text-gray-500 text-sm">{{ $t('app.textarea_hint') }}</span>
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

                            <div class="md:col-span-1 mb-4">
                                <InputLabel for="types" :value="t('app.types')" />
                                <v-select multiple v-model="form.types" :reduce="option => option.id" label="name" :options="types"></v-select>
                                <InputError class="mt-2" :message="form.errors.types" />
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

            <div class="col-span-2 md:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl">{{ t('app.size') }}</h2>
                    <PrimaryButton type="button" @click="newSize">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </PrimaryButton>
                </div>
                <div class="flex space-x-2 items-center mb-2" v-for="item in form.size" :key="item.id">
                    <div>
                        <InputLabel for="description" :value="t('app.size')" />
                        <TextInput type="text" class="mt-1 block w-full" v-model="item.name" />
                    </div>
                    <div>
                        <InputLabel for="description" :value="t('app.price')" />
                        <TextInput type="number"  step=".1" min="0" class="mt-1 block w-full" v-model="item.price" />
                    </div>
                    <button type="button" class="mt-5 text-red-400" @click="removeSize(item.id)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                </div>
                <InputError class="mt-2" :message="form.errors.size" />
            </div>

        </div>
    </AuthenticatedLayout>
</template>

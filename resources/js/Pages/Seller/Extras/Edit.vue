<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { t } = useI18n()

const { extra } = defineProps(['extra'])

const form  = useForm({
    _method: 'patch',
    name: extra.name,
    price: extra.price,
    image: '',
})

const save = () => {
    form.post(route('seller.extras.update', extra))
}
</script>

<template>
    <Head :title="t('app.extra.update')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.extra.update') }}
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
                            <InputLabel for="price" :value="t('app.price')" />
                            <TextInput id="price" type="number" step="0.1" min="0" class="mt-1 block w-full" v-model="form.price"/>
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="image" :value="t('app.image')" />
                            <TextInput id="image" type="file" class="mt-1 block w-full" @input="form.image = $event.target.files[0]" />
                            <InputError class="mt-2" :message="form.errors.image" />
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

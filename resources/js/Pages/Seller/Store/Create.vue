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

defineProps(['types'])

const form  = useForm({
    name: '',
    logo: '',
    type_id: '',
    description: '',
    from: '',
    to: ''
})

const save = () => {
   form.post(route('seller.store.store'))
}
</script>

<template>
    <Head :title="t('app.store.create')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.store.create') }}
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
                            <InputLabel for="logo" :value="t('app.logo')" />
                            <TextInput id="logo" type="file" class="mt-1 block w-full" @input="form.logo = $event.target.files[0]" />
                            <span class="text-gray-500 text-sm">{{ $t('app.input_file_hint') }}</span>
                            <InputError class="mt-2" :message="form.errors.logo" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="type_id" :value="t('app.type')" />
                            <SelectInput id="type_id" class="mt-1 block w-full" v-model="form.type_id">
                                <option value="">{{ t('app.select_option') }}</option>
                                <option v-for="option in types" :key="option.id" :value="option.id">{{ option.name }}</option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.type_id" />
                        </div>

                        <div class="md:col-span-2 mb-4">
                            <InputLabel for="description" :value="t('app.description')" />
                            <TextArea id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                            <span class="text-gray-500 text-sm">{{ $t('app.textarea_hint') }}</span>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="col-span-full border-b mb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ t('app.working_time') }}</h3>
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="from" :value="t('app.from')" />
                            <TextInput id="from" type="time" class="mt-1 block w-full" v-model="form.from" />
                            <InputError class="mt-2" :message="form.errors.from" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="to" :value="t('app.to')" />
                            <TextInput id="to" type="time" class="mt-1 block w-full" v-model="form.to" />
                            <InputError class="mt-2" :message="form.errors.to" />
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

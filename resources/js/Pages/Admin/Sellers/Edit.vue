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

const { seller } = defineProps(['seller'])

const form  = useForm({
    name: seller.name,
    email: seller.email,
    phone: seller.phone,
    approved: seller.approved,
})

const save = () => {
    form.patch(route('admin.sellers.update', seller))
}
</script>

<template>
    <Head :title="t('app.seller.update')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.seller.update') }}
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
                            <InputLabel for="email" :value="t('app.email')" />
                            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email"/>
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="phone" :value="t('app.phone')" />
                            <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone"/>
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="approved" :value="t('app.status')" />
                            <SelectInput id="approved" class="mt-1 block w-full" v-model="form.approved">
                                <option value="1">{{ t('app.active') }}</option>
                                <option value="0">{{ t('app.disabled') }}</option>
                            </SelectInput>
                            <InputError class="mt-2" :message="form.errors.approved" />
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

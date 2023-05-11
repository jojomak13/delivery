<script setup>
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { t } = useI18n()

const { user } = defineProps(['user'])

const form  = useForm({
    name: user.name,
    email: user.email,
    password: '',
    password_confirmation: '',
})

const save = () => {
    form.patch(route('admin.users.update', user))
}
</script>

<template>
    <Head :title="t('app.user.update')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.user.update') }}
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
                            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="password" :value="t('app.password')" />
                            <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        
                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="password_confirmation" :value="t('app.confirm_password')" />
                            <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
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

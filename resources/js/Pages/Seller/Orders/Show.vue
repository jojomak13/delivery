<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { t } = useI18n()

const { order } = defineProps(['order'])

const form  = useForm({
    status: order.status,
});

const save = () => {
    form.patch(route('seller.orders.update', order))
}
</script>

<template>
    <Head :title="t('app.order.show')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.order.show') }}
            </h2>
        </template>

        <form @submit.prevent="save">
            <div class="md:grid md:grid-cols-2 md:gap-2">
                <div class="md:col-span-1 mb-4">
                    <InputLabel for="status" :value="t('app.status')" />
                    <SelectInput id="status" class="mt-1 block w-full" v-model="form.status">
                        <option value="pending">{{ t('app.pending') }}</option>
                        <option value="in-progress">{{ t('app.inprogress') }}</option>
                        <option value="delivered">{{ t('app.delivered') }}</option>
                        <option value="completed">{{ t('app.completed') }}</option>
                        <option value="canceled">{{ t('app.canceled') }}</option>
                    </SelectInput>
                </div>
                
                <div class="md:col-span-1 mb-4 mt-7">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">{{ t('app.save') }}</PrimaryButton>
                </div>
            </div>
        </form>

        <div class="mb-5">
            <div class="flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ t('app.name') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.price') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.size') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ t('app.quantity') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <template v-for="item in order.items" :key="item.id">
                                    <tr v-if="item.cartable_type === 'App\\Models\\Bundle'" v-for="subItem in item.options.products">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full" :src="subItem.image" :alt="subItem.name" />
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900">{{ subItem.name }}</div>
                                                    <div class="text-gray-500">{{ item.name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ subItem.price }} {{ t('app.ru') }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ subItem.size }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">1</div>
                                        </td>
                                    </tr>
                                    <tr v-else>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full" :src="item.image" :alt="item.name" />
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900">{{ item.name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ item.price }} {{ t('app.ru') }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ item.options.size }}</div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            <div class="text-gray-900">{{ item.quantity }}</div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg mb-5">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-5 border-b">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ t('app.address') }}</h2>
                </div>
                <div class="md:grid md:grid-cols-2 md:gap-2">
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="customer" :value="t('app.customer')" />
                        <TextInput disabled id="customer" type="text" class="mt-1 block w-full" :value="order.user.name" />
                    </div>
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="street" :value="t('app.street')" />
                        <TextInput disabled id="street" type="text" class="mt-1 block w-full" :value="order.location.street" />
                    </div>
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="floor_number" :value="t('app.floor_number')" />
                        <TextInput disabled id="floor_number" type="text" class="mt-1 block w-full" :value="order.location.floor_number" />
                    </div>
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="phone_number" :value="t('app.phone_number')" />
                        <TextInput disabled id="phone_number" type="text" class="mt-1 block w-full" :value="order.location.phone_number" />
                    </div>
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="building_type" :value="t('app.building_type')" />
                        <TextInput disabled id="building_type" type="text" class="mt-1 block w-full" :value="order.location.building_type" />
                    </div>
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="apartment_number" :value="t('app.apartment_number')" />
                        <TextInput disabled id="apartment_number" type="text" class="mt-1 block w-full" :value="order.location.apartment_number" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="mb-5 border-b">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">{{ t('app.pricing') }}</h2>
                </div>
                <div class="md:grid md:grid-cols-2 md:gap-2">
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="sub_price" :value="t('app.sub_price')" />
                        <TextInput disabled id="sub_price" type="text" class="mt-1 block w-full" :value="order.sub_price" />
                    </div>
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="delivery_price" :value="t('app.delivery_cost')" />
                        <TextInput disabled id="delivery_price" type="text" class="mt-1 block w-full" :value="order.delivery_price" />
                    </div>
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="discount_price" :value="t('app.discount_price')" />
                        <TextInput disabled id="discount_price" type="text" class="mt-1 block w-full" :value="order.discount_price" />
                    </div>
                    <div class="md:col-span-1 mb-4">
                        <InputLabel for="total_price" :value="t('app.total_price')" />
                        <TextInput disabled id="total_price" type="text" class="mt-1 block w-full" :value="order.total_price" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

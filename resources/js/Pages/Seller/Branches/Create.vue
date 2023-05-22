<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useI18n} from "vue-i18n";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextArea from "@/Components/TextArea.vue";
import {onMounted} from "vue";

const { t } = useI18n()

const form  = useForm({
    name: '',
    phone: '',
    delivery_cost: '',
    delivery_period: '',
    delivery_distance: '',
    address: '',
    location: {
        lat: '',
        long: ''
    }
})

const save = () => {
    form.post(route('seller.branches.store'))
}

const loadMap = () => {
    ymaps.ready(() => {
        const map = new ymaps.Map("map", {
            center: [form.location.lat, form.location.long],
            zoom: 10
        });

        let marker = new ymaps.Placemark(map.getCenter(), {}, {
            draggable: true
        });

        map.geoObjects.add(marker);

        marker.events.add("dragend", function (e) {
            let target = e.get("target");
            let coords = target.geometry.getCoordinates();

            form.location.lat = coords[0];
            form.location.long = coords[1];
        });
    });
}

onMounted(() => {
    navigator.geolocation.getCurrentPosition((position) => {
        form.location.lat = position.coords.latitude
        form.location.long = position.coords.longitude
        loadMap()
    });
})
</script>

<template>
    <Head :title="t('app.branch.create')" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('app.branch.create') }}
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
                            <InputLabel for="phone" :value="t('app.phone')" />
                            <TextInput id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="delivery_cost" :value="t('app.delivery_cost')" />
                            <TextInput id="delivery_cost" type="number" step=".1" min="0" class="mt-1 block w-full" v-model="form.delivery_cost" />
                            <InputError class="mt-2" :message="form.errors.delivery_cost" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="delivery_period" :value="t('app.delivery_period')" />
                            <TextInput id="delivery_period" type="number" min="0" class="mt-1 block w-full" v-model="form.delivery_period" />
                            <InputError class="mt-2" :message="form.errors.delivery_period" />
                        </div>

                        <div class="md:col-span-1 mb-4">
                            <InputLabel for="delivery_distance" :value="t('app.delivery_distance')" />
                            <TextInput id="delivery_distance" type="number" min="0" class="mt-1 block w-full" v-model="form.delivery_distance" />
                            <InputError class="mt-2" :message="form.errors.delivery_distance" />
                        </div>

                        <div class="md:col-span-2 mb-4">
                            <InputLabel for="address" :value="t('app.address')" />
                            <TextArea id="address" type="text" class="mt-1 block w-full" v-model="form.address" />
                            <span class="text-gray-500 text-sm">{{ $t('app.textarea_hint') }}</span>
                            <InputError class="mt-2" :message="form.errors.address" />
                        </div>
                        <div class="md:col-span-2 mb-4">
                            <div id="map" class="w-full" style="height: 400px;"></div>
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

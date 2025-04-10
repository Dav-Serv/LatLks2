<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import ButtonLink from '@/Components/ButtonLink.vue';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const tab1 = ref(true);
const tab2 = ref(false);
const tab3 = ref(false);

const props = defineProps({
    title: String,
    route_url: String,
    form_type: String,
    model: Object,
    tables: Array,
    products: Array,
    level:{
        type: String,
        default: 'user',
    }
});

const form = useForm({
    _method: props.form_type,
    name: props.model?.name,
    table_id: props.model?.table_id,
    guests: props.model?.guests,
    product_id: props.model?.table_id,
    count: props.model?.count,
});

const createReservation = () => {
    form.post(props.route_url, {
        errorBag: 'createReservation',
        preserveScroll: true,
        onSuccess: () => true,
    });
};

// Fungsi untuk memformat harga
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR', // Format IDR (Rupiah)
    }).format(price);
};

onMounted(() => {
    tab1.value = true;
});
</script>

<template>
    <AppLayout :title="title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ButtonLink :href="route('orders.index')">Back</ButtonLink>
                <form @submit.prevent="createReservation" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mt-4">
                    <div  v-if="tab1" class="mb-7">
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Name </label>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            />
                            <p class="text-sm text-red-600" v-if="form.errors.name" v-html="form.errors.name" />
                        </div>
                        <ButtonLink type="submit" color="blue" @click="tab1 = false; tab2 = true; tab3 = false;">
                            Next >>
                        </ButtonLink>
                    </div>

                    <div  v-if="tab2" class="mb-7">
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Table </label>
                            <select v-model="form.table_id" id="table" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                <option value="" disabled>Pilih Table</option>
                                <option v-for="item in tables" :key="item.id" :value="item.id">
                                    Name : {{ item.name }}, Limit : {{ item.limit }}
                                </option>
                            </select>
                            <p class="text-sm text-red-600" v-if="form.errors.table_id" v-html="form.errors.table_id" />
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Guests </label>
                            <input
                                id="guests"
                                v-model="form.guests"
                                type="text"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            />
                            <p class="text-sm text-red-600" v-if="form.errors.guests" v-html="form.errors.guests" />
                        </div>
                        <ButtonLink class="mr-3" type="button" color="blue" @click="tab1 = true; tab2 = false; tab3 = false;">
                            << Back
                        </ButtonLink>
                        <ButtonLink type="submit" color="blue" @click="tab1 = false; tab2 = false; tab3 = true;">
                            Next >>
                        </ButtonLink>
                    </div>

                    <div  v-if="tab3" class="mb-7">
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Product </label>
                            <select v-model="form.product_id" id="product" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                <option value="" disabled>Pilih Product</option>
                                <option v-for="item in products" :key="item.id" :value="item.id">
                                    Name : {{ item.name }}, Price : {{ formatPrice(item.price) }}, Stock : {{ item.stock }}
                                </option>
                            </select>
                            <p class="text-sm text-red-600" v-if="form.errors.product_id" v-html="form.errors.product_id" />
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Count </label>
                            <input
                                id="count"
                                v-model="form.count"
                                type="text"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            />
                            <p class="text-sm text-red-600" v-if="form.errors.count" v-html="form.errors.count" />
                        </div>

                        <div>

                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <ButtonLink class="mr-3" type="button" color="blue" @click="tab1 = false; tab2 = true; tab3 = false;">
                            << Back
                        </ButtonLink>

                        <ButtonLink :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit" color="blue">
                            Save
                        </ButtonLink>
                    </div>
                    </div>

                    
                </form>
            </div>
        </div>
    </AppLayout>
</template>
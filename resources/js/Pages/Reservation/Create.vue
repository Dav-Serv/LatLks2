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

const props = defineProps({
    title: String,
    route_url: String,
    form_type: String,
    model: Object,
    tables: Array,
    level:{
        type: String,
        default: 'user',
    }
});

const form = useForm({
    _method: props.form_type,
    name: props.model?.name,
    email: props.model?.email,
    telephone: props.model?.telephone,
    guests: props.model?.guests,
    date: props.model?.date,
    table_id: props.model?.table_id,
    payment_id: props.model?.payment_id,
});

const createReservation = () => {
    form.post(props.route_url, {
        errorBag: 'createReservation',
        preserveScroll: true,
        onSuccess: () => true,
    });
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
                <div v-if="level == 'admin'"><ButtonLink :href="route('reservations.index')">Back</ButtonLink></div>
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
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Email </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            />
                            <p class="text-sm text-red-600" v-if="form.errors.email" v-html="form.errors.email" />
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Telephone </label>
                            <input
                                id="telephone"
                                v-model="form.telephone"
                                type="text"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            />
                            <p class="text-sm text-red-600" v-if="form.errors.telephone" v-html="form.errors.telephone" />
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Guests </label>
                            <input
                                id="guests"
                                v-model="form.guests"
                                type="number"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            />
                            <p class="text-sm text-red-600" v-if="form.errors.guests" v-html="form.errors.guests" />
                        </div>
                    </div>

                    <div class="mb-7" >
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Date </label>
                            <!--<input
                                id="date"
                                v-model="form.date"
                                type="datetime-local"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                            />-->
                            <VueDatePicker  id="date" type="datetime" v-model="form.date" />
                            <p class="text-sm text-red-600" v-if="form.errors.date" v-html="form.errors.date" />
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Table</label>
                            <select v-model="form.table_id" id="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full">
                                <option value="" disabled>Pilih Table</option>
                                <option v-for="item in tables" :key="item.id" :value="item.id">
                                    {{ item.name }}
                                </option>
                            </select>
                            <p class="text-sm text-red-600" v-if="form.errors.table_id" v-html="form.errors.table_id" />
                        </div>
                    </div>

                    <div>
                        <ActionMessage :on="form.recentlySuccessful" class="me-3">
                            Saved.
                        </ActionMessage>

                        <ButtonLink :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit" color="blue">
                            Save
                        </ButtonLink>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
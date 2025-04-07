<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import ButtonLink from '@/Components/ButtonLink.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    models: {
        type: Object,
        default: {}
    },
    reser: {
        type: Object,
        default: {}
    },
    level:{
        type: String,
        default: 'user'
    }
});

const form = useForm({_method: "DELETE"});

const deleteReservation = (id) => {
    if (confirm('Are you sure you want to delete this post?')) {
        form.post(route('reservations.destroy', id), {
            errorBag: 'deleteReservation',
            preserveScroll: true,
        });
    }
}

// Fungsi untuk memformat harga
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR', // Format IDR (Rupiah)
    }).format(price);
};
</script>

<template>
    <AppLayout title="Reservation">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reservation</h2>
        </template>

        <div class="py-12">
            <div class="container mx-auto sm:px-6 lg:px-8">
                <ButtonLink :href="route('reservations.create')">Add Reservation</ButtonLink>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-4">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Id</th>
                                    <th scope="col" class="px-6 py-3">Name User</th>
                                    <th scope="col" class="px-6 py-3">Name Buyer</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Telephone</th>
                                    <th scope="col" class="px-6 py-3">Guests</th>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Table</th>
                                    <th scope="col" class="px-6 py-3">Dp</th>
                                    <th scope="col" class="px-6 py-3" width="20%">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in models.data" :key="item.id" class="bg-white border-b border-gray-200">
                                    <td class="px-6 py-4">{{ item.id }}</td>
                                    <td class="px-6 py-4">{{ item.user?.name }}</td>
                                    <td class="px-6 py-4">{{ item.name }}</td>
                                    <td class="px-6 py-4">{{ item.email }}</td>
                                    <td class="px-6 py-4">{{ item.telephone }}</td>
                                    <td class="px-6 py-4">{{ item.guests }}</td>
                                    <td class="px-6 py-4">{{ item.date }}</td>
                                    <td class="px-6 py-4">{{ item.table?.name }}</td>
                                    <td class="px-6 py-4">{{ formatPrice(item.table?.price) }}</td>
                                    <td class="px-6 py-4">
                                        <ButtonLink :href="route('reservations.show', item.id)" color="yellow">Detail</ButtonLink>&nbsp;
                                        <ButtonLink :href="route('reservations.edit', item.id)" color="blue">Edit</ButtonLink>&nbsp;
                                        <ButtonLink @click="deleteReservation(item.id)" color="red">Delete</ButtonLink>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination :links="models.links"/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
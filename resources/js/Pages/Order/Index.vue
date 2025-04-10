<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import ButtonLink from '@/Components/ButtonLink.vue';
import Pagination from '@/Components/Pagination.vue';
import Green from '@/Components/IndicatorGreen.vue';
import Red from '@/Components/IndicatorRed.vue';

const props = defineProps({
    models: {
        type: Object,
        default: {}
    },
    order: {
        type: Object,
        default: {}
    },
    level:{
        type: String,
        default: 'user'
    }
});

const form = useForm({_method: "DELETE"});

const deleteOrder = (id) => {
    if (confirm('Are you sure you want to delete this order?')) {
        form.post(route('orders.destroy', id), {
            errorBag: 'deleteOrder',
            preserveScroll: true,
        });
    }
}

// untuk selesai
const form2 = useForm({_method: "POST"});

const finishedOrder = (id) => {
    if (confirm('Are you sure you want to finished this order?')) {
        form2.post(route('orders.finished', id), {
            errorBag: 'finishedOrder',
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
    <AppLayout title="Order">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Order</h2>
        </template>

        <div class="py-12">
            <div class="container mx-auto sm:px-6 lg:px-8">
                <ButtonLink :href="route('orders.create')">Add Order</ButtonLink>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-4">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Id</th>
                                    <th scope="col" class="px-6 py-3">Name User</th>
                                    <th scope="col" class="px-6 py-3">Name Buyer</th>
                                    <th scope="col" class="px-6 py-3">Table</th>
                                    <th scope="col" class="px-6 py-3">Guests</th>
                                    <th scope="col" class="px-6 py-3">Product</th>
                                    <th scope="col" class="px-6 py-3">Count</th>
                                    <th scope="col" class="px-6 py-3">Subtotal</th>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                    <th scope="col" class="px-6 py-3">Payment</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3" width="10%">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in models.data" :key="item.id" class="bg-white border-b border-gray-200">
                                    <td class="px-6 py-4">{{ item.id }}</td>
                                    <td class="px-6 py-4">{{ item.user?.name }}</td>
                                    <td class="px-6 py-4">{{ item.name }}</td>
                                    <td class="px-6 py-4">{{ item.table?.name }}</td>
                                    <td class="px-6 py-4">{{ item.guests }}</td>
                                    <td class="px-6 py-4">{{ item.product?.name }}</td>
                                    <td class="px-6 py-4">{{ item.count }}</td>
                                    <td class="px-6 py-4">{{ formatPrice(item.subtotal) }}</td>
                                    <td class="px-6 py-4">{{ item.date }}</td>
                                    <td class="px-6 py-4">{{ item.payGate }}</td>
                                    <Green v-if="item.status_pay == 'Paid'">
                                        <td class="px-6 py-4">{{ item.status_pay }}</td>
                                    </Green>
                                    <Red v-if="item.status_pay == 'Unpaid'">
                                        <td class="px-6 py-4">{{ item.status_pay }}</td>
                                    </Red>
                                    <td class="px-6 py-4">
                                        <ButtonLink :href="route('orders.show', item.id)" color="yellow" class="mb-2">Detail</ButtonLink>&nbsp;
                                        <ButtonLink v-if="item.status_order == 'Unfinished' && item.table?.status == 'inactive' && item.status_pay == 'Paid'"  @click="finishedOrder(item.id)" color="blue" class="mb-2">Finished</ButtonLink>&nbsp;
                                        <ButtonLink v-if="item.status_pay == 'Unpaid'" :href="route('orders.edit', item.id)" color="blue" class="mb-2">Edit</ButtonLink>&nbsp;
                                        <ButtonLink v-if="item.status_pay == 'Unpaid'" @click="deleteOrder(item.id)" color="red" class="mb-2">Delete</ButtonLink>
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
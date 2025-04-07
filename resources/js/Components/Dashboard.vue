<!-- script vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import ButtonLink from '@/Components/ButtonLink.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    name:{
        type: String,
        default: null
    },
    models:{
        type: Object,
        default: {}
    },
    level:{
        type: String,
        default: 'user',
    }
});

// Fungsi untuk memformat harga
const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR', // Format IDR (Rupiah)
    }).format(price);
};
</script>

<!-- script css -->
<style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 50px;
    }

    .grid-item {
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
</style>

<template>
    <div  class="" >
        <div v-if="level == 'admin'" class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="mt-8 text-2xl font-medium text-gray-900 text-center">
                Admin [<strong>{{ name }}</strong>] Welcome to Dashboard Admin Restaurant Dava!
            </h1>

            <p class="mt-6 text-gray-500 leading-relaxed">
                Ini adalah halaman Admin dan anda bisa mengelola restoran ini. <br>
                Di sini Admin bisa mengelola kategori, mengelola barang,mengelola reservasi, 
                mengelola pembelian, mengelola pembayaran, dan mengelola laporan transaksi. <br>
               <h2 class="text-center"><strong>~SELAMAT BEKERJA ADMIN RESTORAN DAVA~</strong></h2> 
            </p>
        </div>

        <div v-else-if="level == 'user'" class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="mt-8 text-2xl font-medium text-gray-900 text-center">
                Pelanggan [<strong>{{ name }}</strong>] Welcome to Dashboard Pelanggan Restaurant Dava!
            </h1>

            <p class="mt-6 text-gray-500 leading-relaxed">
                Ini adalah halaman Admin dan anda bisa mengelola restoran ini. <br>
                Di sini Admin bisa mengelola kategori, mengelola barang,mengelola reservasi, 
                mengelola pembelian, mengelola pembayaran, dan mengelola laporan transaksi. <br>
            <h2 class="text-center"><strong>~SELAMAT BEKERJA ADMIN RESTORAN DAVA~</strong></h2> 
            </p>
        </div>

        
    </div>
</template>

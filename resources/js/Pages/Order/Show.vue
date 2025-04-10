<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonLink from '@/Components/ButtonLink.vue';
import { useForm } from '@inertiajs/vue3';  // Impor useForm jika diperlukan untuk form atau proses lain

// Properti untuk menerima data dari controller
const props = defineProps({
  models: {
    type: Object,  // pastikan models yang dikirim adalah objek produk
    default: () => ({})  // default objek kosong jika tidak ada data
  },
});

const PrintInvoice = () => {
  window.print();
};

const form_pay = useForm({
    _method: 'POST',
});

const createOrder = () => {
    form_pay.post(route('orders.reqpay', { order : props.models.id}), {
        errorBag: 'createOrder',
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
</script>

<template>
    <AppLayout title="Reservation">
      <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reservation</h2>
        </template>
        <div class="invoice-container">
          <ButtonLink :href="route('orders.index')">Back</ButtonLink>
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-4">
          <div class="invoice-header">
            <h1>Invoice Order #{{ models.id }}</h1>
            <p>{{ models.created_at }}</p>
            <p>{{ models.name }}</p>
            <p>{{ models.user?.email }}</p>
          </div>
      
          <div class="invoice-items">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:text-gray-400">
                <tr>
                  <th>Name Table</th>
                  <th>Guests</th>
                  <th>Name Product</th>
                  <th>Price</th>
                  <th>Count</th>
                  <th>Subtotal</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{{ models.table?.name }}</td>
                  <td>{{ models.guests }}</td>
                  <td>{{ models.product?.name }}</td>
                  <td>{{ formatPrice(models.product?.price) }}</td>
                  <td>{{ models.count }}</td>
                  <td>{{ formatPrice(models.subtotal) }}</td>
                  <td>{{ models.date }}</td>
                </tr>
              </tbody>
            </table>
          </div>
      
          <!-- <div class="invoice-total">
            <p>Total: {{ totalAmount | currency }}</p>
          </div> -->
          <div>
            <iframe v-if="models?.requestpay?.invoice_url" :src="models?.requestpay?.invoice_url" style="width:100%;height:500px;"></iframe>
            <div v-if="models?.status_pay == 'Unpaid'">
              <button @click="createOrder">Pay</button>
            </div>
            <div v-if="models?.status_pay == 'Paid'">
              <button @click="PrintInvoice">Print Invoice</button>
            </div>
          </div>
        </div>
          
        </div>
  </AppLayout>
  </template>

<style scoped>
.invoice-container {
  max-width: 90%;
  margin: auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.invoice-header {
  text-align: center;
  margin-bottom: 20px;
}

.invoice-items table {
  width: 100%;
  border-collapse: collapse;
}

.invoice-items th,
.invoice-items td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.invoice-items th {
  background-color: #f4f4f4;
}

.invoice-total {
  text-align: right;
  font-size: 1.2em;
  margin-top: 20px;
}

button {
  display: block;
  width: 100%;
  padding: 10px;
  margin-top: 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}
</style>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ButtonLink from '@/Components/ButtonLink.vue';
import { useForm } from '@inertiajs/vue3';  // Impor useForm jika diperlukan untuk form atau proses lain

// Properti untuk menerima data dari controller
const props = defineProps({
  models: {
    type: Object,  // pastikan models yang dikirim adalah objek produk
    default: () => ({})  // default objek kosong jika tidak ada data
  }
});

const form_pay = useForm({
    _method: 'POST',
});

const createReservation = () => {
    form_pay.post(route('reservations.reqpay', { reservation : props.models.id}), {
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
</script>

<template>
    <div class="invoice-container">
      <div class="invoice-header">
        <h1>Invoice Reservation #{{ models.id }}</h1>
        <p>{{ models.created_at }}</p>
        <p>{{ models.name }}</p>
        <p>{{ models.email }} | {{ models.telephone }}</p>
      </div>
  
      <div class="invoice-items">
        <table>
          <thead>
            <tr>
              <th>Name Table</th>
              <th>Price</th>
              <th>Guests</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{ models.table?.name }}</td>
              <td>{{ formatPrice(models.table?.price) }}</td>
              <td>{{ models.guests }}</td>
              <td>{{ models.date }}</td>
              <!-- <td>{{ (item.price * item.quantity) | currency }}</td> -->
            </tr>
          </tbody>
        </table>
      </div>
  
      <!-- <div class="invoice-total">
        <p>Total: {{ totalAmount | currency }}</p>
      </div> -->
  
      <button @click="createReservation">Print Invoice</button>
    </div>
  </template>

<style scoped>
.invoice-container {
  max-width: 600px;
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

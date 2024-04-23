<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref} from 'vue';
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";

const quantity = ref(null);
const unit_cost = ref(null);
const selling_price = ref(null);
const sales = ref([]);
const product = ref(null);
const coffees = ref([]);

function getCoffees() {
    axios.get('/get-coffees').then(response => {
        if (response.data.data.coffees) {
            coffees.value = response.data.data.coffees;
        }
    })
}

function getSales() {
    axios.get('/get-sales').then(response => {
        if (response.data.data.sales) {
            sales.value = response.data.data.sales;
        }
    })
}

function getSellingPrice() {
    if (product.value && quantity.value && unit_cost.value) {
        const data = {
            'product': product.value,
            'quantity': quantity.value,
            'unit_cost': unit_cost.value
        };
        axios.post('/sales/get-selling-price', data).then(response => {
            if (response.data.data.selling_price) {
                selling_price.value = response.data.data.selling_price;
            }
        })
    }
}

function recordSale() {
    if (quantity.value && unit_cost.value && selling_price.value) {
        const data = {
            'product': product.value,
            'quantity': quantity.value,
            'unit_cost': unit_cost.value,
            'selling_price': selling_price.value
        };
        axios.post('/sales', data).then(response => {
            console.log('done');
            resetValues();
            getSales();
        })
    }
}

function resetValues(){
    product.value = null;
    quantity.value = null;
    unit_cost.value = null;
    selling_price.value = null;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('default', {dateStyle: 'long'}).format(date);
}

getCoffees();
getSales();
</script>

<template>
    <Head title="Sales"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New &#9749; Sales</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <!-- Sale Record Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="flex py-6 px-6">
                        <div class="px-3">
                            <InputLabel for="">Product</InputLabel>
                            <select
                                v-model="product"
                                @change="getSellingPrice"
                                class="mt-1 block w-full border-gray-300
                            focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select Product</option>
                                <option v-for="coffee in coffees" :value="coffee.id">{{coffee.name}}</option>
                            </select>
                        </div>
                        <div class="px-3">
                            <InputLabel for="quantity">Quantity</InputLabel>
                            <TextInput
                                id="quantity"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="quantity"
                                @keyup="getSellingPrice"
                                required
                                autofocus
                                autocomplete="name"
                            />
                        </div>
                        <div class="px-3">
                            <InputLabel for="unit_cost">Unit Cost (£)</InputLabel>
                            <TextInput
                                id="unit_cost"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="unit_cost"
                                @keyup="getSellingPrice"
                                required
                                autocomplete="unit cost"
                            />
                        </div>
                        <div class="px-3">
                            <InputLabel>Selling Price</InputLabel>
                            <div class="mt-1 w-48 py-2 font-semibold">
                                £<span>{{ selling_price }}</span>
                            </div>
                        </div>
                        <div class="px-3">
                            <InputLabel>&nbsp;</InputLabel>
                            <PrimaryButton
                                @click.prevent="recordSale"
                                class="mt-1 block w-full">
                                Record Sale
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
                <!-- Sale Record Form -->

                <!-- Sale Listing -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="block py-6 px-6">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="text-left p-4">
                                    Product
                                </th>
                                <th scope="col" class="text-left p-4">
                                    Quantity
                                </th>
                                <th scope="col" class="text-left p-4">
                                    Unit Cost
                                </th>
                                <th scope="col" class="text-left p-4">
                                    Selling Price
                                </th>
                                <th scope="col" class="text-left p-4">
                                    Sold At
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="sale in sales"
                                :key="sale.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            >
                                <td class="text-left p-4">
                                    {{sale.name}}
                                </td>
                                <td class="text-left p-4">
                                    {{sale.quantity}}
                                </td>
                                <td class="text-left p-4">
                                    {{sale.unit_cost}}
                                </td>
                                <td class="text-left p-4">
                                    £{{sale.selling_price}}
                                </td>
                                <td class="text-left p-4">
                                    {{ formatDate(sale.created_at) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Sale Listing -->

            </div>
        </div>
    </AuthenticatedLayout>
</template>

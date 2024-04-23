<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref} from 'vue';
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const quantity = ref(null);
const unit_cost = ref(null);
const selling_price = ref(null);
const sales = ref([]);

function getSales() {
    axios.get('/get-sales').then(response => {
        if (response.data.data.sales) {
            sales.value = response.data.data.sales;
        }
    })
}

function getSellingPrice() {
    if (quantity.value && unit_cost.value) {
        const data = {
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
    quantity.value = null;
    unit_cost.value = null;
    selling_price.value = null;
}

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
                            <label for="">Quantity</label>
                            <TextInput
                                id="name"
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
                            <label for="">Unit Cost (£)</label>
                            <TextInput
                                id="name"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="unit_cost"
                                @keyup="getSellingPrice"
                                required
                                autocomplete="unit cost"
                            />
                        </div>
                        <div class="px-3">
                            <label for="">Selling Price</label>
                            <div class="mt-1 w-48 py-2 font-semibold">
                                £<span>{{ selling_price }}</span>
                            </div>
                        </div>
                        <div class="px-3">
                            <label for="">&nbsp;</label>
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
                                    Quantity
                                </th>
                                <th scope="col" class="text-left p-4">
                                    Unit Cost
                                </th>
                                <th scope="col" class="text-left p-4">
                                    Selling Price
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="sale in sales"
                                :key="sale.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            >
                                <td class="text-center p-4">
                                    {{sale.quantity}}
                                </td>
                                <td class="text-center p-4">
                                    {{sale.unit_cost}}
                                </td>
                                <td class="text-center p-4">
                                    £{{sale.selling_price}}
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

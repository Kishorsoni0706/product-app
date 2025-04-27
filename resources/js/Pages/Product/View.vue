<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    product: Object
});
</script>

<template>
    <Head :title="props.product.name" />
    <AuthenticatedLayout>
        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Card Layout -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="lg:flex">
                       
                        <!-- Product Details -->
                        <div class="lg:w-2/3 p-6 space-y-6">
                            <!-- Product Title -->
                            <h1 class="text-3xl font-semibold text-gray-800">{{ props.product.name }}</h1>

                            <!-- Product Description -->
                            <p class="text-gray-600 text-lg">{{ props.product.description }}</p>

                            <!-- Product Price -->
                            <p class="text-2xl text-green-600 font-bold">₹{{ props.product.price }}</p>

                            <!-- Product Category -->
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Category: {{ props.product.category }}</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

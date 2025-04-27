<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import * as Yup from 'yup'; // Import Yup
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import { Transition } from 'vue';

const form = useForm({
    name: '',
    price: '',
    description: '',
    category: ''
});

// Define the validation schema using Yup
const validationSchema = Yup.object({
    name: Yup.string().required("Name is required"),
    price: Yup.number().required("Price is required").positive("Price must be positive").typeError("Price must be a number"),
    description: Yup.string().optional(),
    category: Yup.string().required("Category is required")
});

// Validate the form before submission
const submit = async () => {
    try {
        await validationSchema.validate(form.data, { abortEarly: false }); // Validate form data

        // If validation passes, submit the form
        form.post(route("products.store"));
    } catch (err) {
        // If validation fails, handle the errors (Yup will return an array of errors)
        form.errors.clear();

        err.inner.forEach(error => {
            form.errors.set(error.path, error.message); // Set the error messages in the form
        });
    }
};
</script>

<template>
    <Head title="Create Blog" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Product</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <section>
                    <form @submit.prevent="submit" class="mt-6 space-y-6">
                        <div>
                            <InputLabel for="name" value="Name" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="price" value="Price" />
                            <TextInput
                                id="price"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.price"
                            />
                            <InputError class="mt-2" :message="form.errors.price" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Description" />
                            <TextArea
                                id="description"
                                class="mt-1 block w-full"
                                v-model="form.description"
                            />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div>
                            <InputLabel for="category" value="Category" />
                            <TextInput
                                id="category"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.category"
                            />
                            <InputError class="mt-2" :message="form.errors.category" />
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                            </Transition>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

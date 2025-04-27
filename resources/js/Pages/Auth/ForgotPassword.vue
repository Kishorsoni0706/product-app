<script setup>
import { reactive } from 'vue';
import * as yup from 'yup';
import { Head, useForm } from '@inertiajs/vue3';

import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

// Props
defineProps({
    status: String,
});

// Form data
const form = useForm({
    email: '',
});

// Reactive error holder
const errors = reactive({
    email: '',
});

// Yup schema
const schema = yup.object({
    email: yup.string().required('Email is required').email('Must be a valid email address'),
});

// Validation function
const validate = async () => {
    try {
        await schema.validate(form, { abortEarly: false });
        errors.email = '';
        return true;
    } catch (err) {
        errors.email = '';
        if (err.inner) {
            err.inner.forEach(e => {
                if (e.path === 'email') {
                    errors.email = e.message;
                }
            });
        }
        return false;
    }
};

// Submit function
const submit = async () => {
    const isValid = await validate();
    if (!isValid) return;

    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <h1 class="text-2xl font-bold text-center mb-6">Forgot Password</h1>

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset
            link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="errors.email || form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

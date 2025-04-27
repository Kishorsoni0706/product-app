<script setup>
import { reactive } from 'vue';
import * as yup from 'yup';
import { useForm, Head, Link } from '@inertiajs/vue3';

import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

// Reactive form data
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Validation schema with yup
const schema = yup.object({
    name: yup.string().required('Name is required'),
    email: yup.string().required('Email is required').email('Must be a valid email'),
    password: yup.string().required('Password is required').min(6, 'Minimum 6 characters'),
    password_confirmation: yup
        .string()
        .required('Please confirm your password')
        .oneOf([yup.ref('password')], 'Passwords must match'),
});

// Custom reactive errors for client-side validation
const errors = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Validate using schema
const validate = async () => {
    try {
        await schema.validate(form, { abortEarly: false });
        // Clear previous errors
        Object.keys(errors).forEach(key => errors[key] = '');
        return true;
    } catch (validationError) {
        // Reset all error messages
        Object.keys(errors).forEach(key => errors[key] = '');
        validationError.inner.forEach(err => {
            if (errors[err.path] !== undefined) {
                errors[err.path] = err.message;
            }
        });
        return false;
    }
};

// Submit function
const submit = async () => {
    const isValid = await validate();
    if (!isValid) return;

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <h1 class="text-2xl font-bold text-center mb-6">Register</h1>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    class="mt-1 block w-full"
                    v-model="form.name"
                />
                <InputError class="mt-2" :message="errors.name || form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                />
                <InputError class="mt-2" :message="errors.email || form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                />
                <InputError class="mt-2" :message="errors.password || form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                />
                <InputError class="mt-2" :message="errors.password_confirmation || form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                >
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

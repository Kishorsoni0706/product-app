<script setup>
import { reactive } from 'vue';
import * as yup from 'yup';
import { useForm, Head } from '@inertiajs/vue3';

import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

// Props
const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

// Inertia form
const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

// Reactive error state for yup validation
const errors = reactive({
    email: '',
    password: '',
    password_confirmation: '',
});

// Yup schema
const schema = yup.object({
    email: yup.string().required('Email is required').email('Must be a valid email address'),
    password: yup.string().required('Password is required').min(6, 'Minimum 6 characters'),
    password_confirmation: yup
        .string()
        .required('Please confirm your password')
        .oneOf([yup.ref('password')], 'Passwords must match'),
});

// Validate before submission
const validate = async () => {
    try {
        await schema.validate(form, { abortEarly: false });
        Object.keys(errors).forEach(key => errors[key] = '');
        return true;
    } catch (err) {
        Object.keys(errors).forEach(key => errors[key] = '');
        err.inner.forEach(e => {
            if (errors[e.path] !== undefined) {
                errors[e.path] = e.message;
            }
        });
        return false;
    }
};

// Submit handler
const submit = async () => {
    const isValid = await validate();
    if (!isValid) return;

    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <h1 class="text-2xl font-bold text-center mb-6">Reset Password</h1>

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

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    autocomplete="new-password"
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
                    autocomplete="new-password"
                />
                <InputError
                    class="mt-2"
                    :message="errors.password_confirmation || form.errors.password_confirmation"
                />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

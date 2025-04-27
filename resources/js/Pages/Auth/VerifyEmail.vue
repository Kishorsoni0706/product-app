<script setup>
import { reactive, computed } from 'vue';
import * as yup from 'yup';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: String,
});

// Inertia form
const form = useForm({});

// Reactive error state
const errors = reactive({});

// Yup schema (empty now, add fields here if needed later)
const schema = yup.object({});

// Optional validation (for consistency or future use)
const validate = async () => {
    try {
        await schema.validate(form, { abortEarly: false });
        return true;
    } catch (err) {
        err.inner?.forEach(e => {
            if (errors[e.path] !== undefined) {
                errors[e.path] = e.message;
            }
        });
        return false;
    }
};

const submit = async () => {
    const isValid = await validate(); // always true for now
    if (!isValid) return;

    form.post(route('verification.send'));
};

// UI flag
const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <h1 class="text-2xl font-bold text-center mb-6">Email Verification</h1>

        <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
            we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </div>

        <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
            A new verification link has been sent to the email address you provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Resend Verification Email
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Log Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>

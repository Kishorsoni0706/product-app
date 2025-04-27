<script setup>
import { reactive, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import * as yup from 'yup'
import { Head, Link } from '@inertiajs/vue3'

import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import Checkbox from '@/Components/Checkbox.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

// Yup validation schema
const schema = yup.object({
    email: yup.string().required('Email is required').email('Invalid email'),
    password: yup.string().required('Password is required').min(6, 'Minimum 6 characters'),
})

const errors = reactive({
    email: '',
    password: ''
})

const validate = async () => {
    try {
        await schema.validate(form, { abortEarly: false })
        errors.email = ''
        errors.password = ''
        return true
    } catch (err) {
        if (err.inner) {
            errors.email = ''
            errors.password = ''
            err.inner.forEach(e => {
                errors[e.path] = e.message
            })
        }
        return false
    }
}

const submit = async () => {
    const isValid = await validate()
    if (!isValid) return

    form.post(route('login'), {
        onFinish: () => form.reset('password')
    })
}
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <h1 class="text-2xl font-bold text-center mb-6">Login</h1>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="form.email" class="mt-1 block w-full" />
                <InputError :message="errors.email || form.errors.email" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password" value="Password" />
                <TextInput id="password" type="password" v-model="form.password" class="mt-1 block w-full" />
                <InputError :message="errors.password || form.errors.password" class="mt-2" />
            </div>

            <div class="flex items-center">
                <Checkbox name="remember" v-model:checked="form.remember" />
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </div>

            <div class="flex items-center justify-between">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Button, TextField, PasswordInput } from '@idds/vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'),{
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <form @submit.prevent="submit" class="space-y-4 lg:space-y-6 p-8 max-w-lg mx-auto bg-white shadow-sm rounded-xl w-full">
            
            <div class="flex flex-col items-center gap-2 mb-6">
                <div class="h-20 w-20 bg-gray-200 rounded-full flex items-center justify-center mb-2">
                    <span class="text-xs text-gray-500">Logo</span>
                </div>
                <div class="space-y-2 text-center">
                    <h1 class="text-2xl font-bold">Login DPMD</h1>
                    <p class="text-sm text-gray-500">Silakan login untuk mengelola konten website</p>
                </div>
            </div>

            <div v-if="status" class="mb-2 p-4 rounded-md bg-blue-50 border border-blue-200 flex items-start gap-3">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-blue-800 leading-tight">
                    {{ status }}
                </p>
            </div>

            <div class="flex flex-col gap-4 w-full">
                <TextField 
                    label="Email" 
                    v-model="form.email" 
                    type="email"
                    placeholder="Masukkan email admin..." 
                    :error="form.errors.email"
                />
                
                <div class="flex flex-col gap-2 w-full">
                    <PasswordInput 
                        label="Password" 
                        v-model="form.password" 
                        placeholder="Masukkan password..." 
                        :error="form.errors.password"
                    />
                </div>
            </div>

            <div class="flex flex-col gap-3 md:gap-4 w-full pt-4">
                <Button hierarchy="primary" type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Memproses...' : 'Masuk' }}
                </Button>
            </div>
        </form>
    </div>
</template>
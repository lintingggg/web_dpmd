<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Button, TextField, PasswordInput } from '@idds/vue';

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
                    <Button hierarchy="link" class="self-start text-xs">Lupa Password?</Button>
                </div>
            </div>

            <div class="flex flex-col gap-3 md:gap-4 w-full pt-4">
                <Button hierarchy="primary" type="submit" :disabled="form.processing">
                    Masuk
                </Button>
            </div>
        </form>
    </div>
</template>
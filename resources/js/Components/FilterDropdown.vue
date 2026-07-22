<script setup>
const model = defineModel({ default: '' });

defineProps({
    label: {
        type: String,
        default: 'Filter'
    },
    placeholder: {
        type: String,
        default: 'Pilih salah satu...'
    },
    options: {
        type: Array,
        required: true,
    }
});
</script>

<template>
    <div class="w-full max-w-[250px]" style="font-family: 'Plus Jakarta Sans', sans-serif;">
        <label v-if="label" style="display: block; font-size: 13px; font-weight: 500; color: #373f50; margin-bottom: 6px;">
            {{ label }}
        </label>
        <div class="relative">
            <select
                v-model="model"
                style="
                    width: 100%;
                    height: 44px;
                    padding: 0 40px 0 14px;
                    background: #ffffff;
                    border: 1.5px solid #e3e5e7;
                    border-radius: 16px;
                    font-size: 14px;
                    font-weight: 500;
                    color: #0f172a;
                    outline: none;
                    cursor: pointer;
                    appearance: none;
                    -webkit-appearance: none;
                    transition: border-color 0.2s ease;
                    box-shadow: 0 1px 3px rgba(15,23,42,0.06);
                    font-family: 'Plus Jakarta Sans', sans-serif;
                "
                @focus="$event.target.style.borderColor = '#0f172a'"
                @blur="$event.target.style.borderColor = '#e3e5e7'"
            >
                <option value="" disabled>{{ placeholder }}</option>
                <option v-for="opt in options" :key="opt.value ?? opt" :value="opt.value ?? opt">
                    {{ opt.label ?? opt }}
                </option>
            </select>
            <span class="material-symbols-outlined pointer-events-none" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 18px; color: #9499a3;">expand_more</span>
        </div>
    </div>
</template>
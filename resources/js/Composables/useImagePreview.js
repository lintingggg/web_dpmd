import { ref, onUnmounted } from 'vue';

export function useImagePreview(initialUrl = '') {
    const previewUrl = ref(initialUrl);

    const updatePreview = (file) => {
        if (!file) return;
        
        if (previewUrl.value && previewUrl.value.startsWith('blob:')) {
            URL.revokeObjectURL(previewUrl.value);
        }
        
        previewUrl.value = URL.createObjectURL(file);
    };

    onUnmounted(() => {
        if (previewUrl.value && previewUrl.value.startsWith('blob:')) {
            URL.revokeObjectURL(previewUrl.value);
        }
    });

    return {
        previewUrl,
        updatePreview
    };
}

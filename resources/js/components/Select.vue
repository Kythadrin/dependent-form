<template>
    <div class="relative">
        <label :for="id" class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>
        <div class="relative">
            <select
                :id="id"
                :value="modelValue"
                :disabled="disabled"
                :class="[inputClass, { 'bg-gray-200': disabled }]"
                @change="$emit('update:modelValue', $event.target.value)"
            >
                <option value="" disabled>{{ placeholder }}</option>
                <option v-for="option in options" :key="option" :value="option">{{ option }}</option>
            </select>
            <div v-if="loading" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="loader w-6 h-6 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
        <div v-if="errors.length">
            <p v-for="error in errors" :key="error" class="text-sm text-red-500 mt-2">{{ error }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: String,
        label: String,
        modelValue: String,
        options: {
            type: Array,
            default: () => [],
        },
        errors: {
            type: Array,
            default: () => [],
        },
        disabled: Boolean,
        loading: Boolean,
        placeholder: {
            type: String,
            default: "Select an option",
        },
        inputClass: {
            type: String,
            default: "w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300",
        },
    },
};
</script>

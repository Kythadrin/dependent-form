<template>
    <div>
        <label :for="id" class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>
        <input
            :id="id"
            :value="modelValue"
            :type="type"
            :class="inputClass"
            @input="$emit('update:modelValue', $event.target.value)"
        />
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
        type: {
            type: String,
            default: "text",
        },
        errors: {
            type: Array,
            default: () => [],
        },
        inputClass: {
            type: String,
            default: "w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300",
        },
    },
};
</script>

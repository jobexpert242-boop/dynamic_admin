<script setup>
import Vue3Select from "vue3-select";
import "vue3-select/dist/vue3-select.css";
import { computed } from "vue";

const props = defineProps({
    modelValue: [String, Number, Array],
    options: Array,
    label: String,
    error: String,
    multiple: Boolean,
    placeholder: String,
});

const emit = defineEmits(["update:modelValue"]);

// Normalize options to { label, value }
const formattedOptions = computed(() =>
    props.options.map((option) => {
        if (option.label && option.value) return option;
        if (option.title && option.id)
            return { label: option.title, value: option.id };
        return { label: option, value: option };
    })
);

// Convert raw value(s) to full object(s) for display
const selectedOptions = computed(() => {
    if (props.multiple) {
        return formattedOptions.value.filter(
            (opt) =>
                Array.isArray(props.modelValue) &&
                props.modelValue.includes(opt.value)
        );
    } else {
        return (
            formattedOptions.value.find(
                (opt) => opt.value === props.modelValue
            ) ?? null
        );
    }
});

// Emit only raw value(s)
function update(value) {
    if (props.multiple) {
        emit(
            "update:modelValue",
            value.map((item) => item.value)
        );
    } else {
        emit("update:modelValue", value?.value ?? null);
    }
}
</script>

<template>
    <div class="space-y-1">
        <label v-if="label" class="form-label font-robo">{{ label }}</label>
        <Vue3Select
            :options="formattedOptions"
            :multiple="multiple"
            :modelValue="selectedOptions"
            :placeholder="placeholder"
            @update:modelValue="update"
            :close-on-select="!multiple"
            class="w-full"
            searchable
            clearable
            :class="{ 'multi-select': multiple }"
        />

        <div v-if="error" class="text-sm text-red-600">{{ error }}</div>
    </div>
</template>

<style>
.multi-select .vs__selected {
    font-size: 0.75rem;
    padding: 2px 6px;
    margin: 2px;
}
.multi-select .vs__selected-options {
    gap: 4px;
    padding: 2px 4px;
}

/*
<SelectInput
    v-model="form.parent_id"
    :options="allMenus"
    label="Parent Menu"
    placeholder="Select Parent Menu..."
    :error="form.errors.parent_id"
/>
<!-- Multiple select -->
<SelectInput
    v-model="form.permission_class"
    :options="allPermissions"
    label="Permissions"
    multiple
    placeholder="Select permissions..."
    :error="form.errors.permission_class"
/>
*/
</style>

<template>
    <v-container>
        <h1 class="text-h4 mb-4">Create User</h1>

        <v-alert v-if="store.successMessage" type="success" dismissible>
            {{ store.successMessage }}
        </v-alert>
        <v-alert v-if="store.errorMessage" type="error" dismissible>
            {{ store.errorMessage }}
        </v-alert>

        <!-- Validation Switch -->
        <v-switch v-model="enableValidation" label="Enable Validation"></v-switch>

        <!-- Get Token Button -->
        <v-btn color="secondary" :loading="store.loading" @click="getToken()" class="mb-4">
            Get Token
        </v-btn>

        <!-- Show Token if available -->
        <v-text-field
            v-if="store.token"
            v-model="store.token"
            label="Registration Token"
            readonly
        ></v-text-field>

        <v-form ref="form" @submit.prevent="submitForm">
            <v-text-field
                v-model="user.name"
                label="Full Name"
                :rules="enableValidation ? [rules.required, rules.min2, rules.max60] : []"
                :error-messages="errors.name"
            ></v-text-field>

            <v-text-field
                v-model="user.email"
                label="Email"
                type="email"
                :rules="enableValidation ? [rules.required, rules.email] : []"
                :error-messages="errors.email"
            ></v-text-field>

            <v-text-field
                v-model="user.password"
                label="Password"
                type="password"
                :rules="enableValidation ? [rules.required, rules.min2, rules.max60] : []"
                :error-messages="errors.password"/>

            <v-text-field
                v-model="user.phone"
                label="Phone (+380XXXXXXXXX)"
                :rules="enableValidation ? [rules.required, rules.phone] : []"
                :error-messages="errors.phone"
            ></v-text-field>

            <v-select
                v-model="user.position_id"
                label="Select Position"
                :items="positions"
                item-title="name"
                item-value="id"
                :rules="enableValidation ? [rules.required] : []"
                :error-messages="errors.position_id"
            ></v-select>

            <v-file-input
                label="Upload Photo"
                accept="image/jpeg"
                show-size
                @change="handleFileUpload"
                :rules="enableValidation ? [rules.required] : []"
                :error-messages="errors.photo"
            ></v-file-input>

            <v-btn type="submit" color="primary" :loading="store.loading">Submit</v-btn>
        </v-form>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useUserStore } from '@/stores/userStore';
import axios from 'axios';

const store = useUserStore();
const form = ref(null);
const positions = ref([]);
const enableValidation = ref(false);
const token = ref(null);
const tokenLoading = ref(false);
const errors = ref({}); // Store API validation errors

const user = ref({
    name: '',
    email: '',
    phone: '',
    position_id: null,
    photo: null,
});

// Validation rules
const rules = {
    required: (v) => !!v || 'This field is required',
    min2: (v) => (v?.length >= 2) || 'Must be at least 2 characters',
    max60: (v) => (v?.length <= 60) || 'Cannot exceed 60 characters',
    email: (v) => /.+@.+\..+/.test(v) || 'Must be a valid email',
    phone: (v) => /^\+380\d{9}$/.test(v) || 'Phone must be in +380XXXXXXXXX format',
};

const handleFileUpload = (event) => {
    user.value.photo = event.target.files[0];
};

const getToken = async () => {
    tokenLoading.value = true;
    token.value = await store.fetchToken();
    tokenLoading.value = false;
};

const submitForm = async () => {
    errors.value = {}; // Reset errors

    if (enableValidation.value) {
        const { valid } = await form.value.validate();
        if (!valid) return;
    }

    const formData = new FormData();
    Object.entries(user.value).forEach(([key, value]) => {
        formData.append(key, value);
    });

    store.useToken(token.value);

    console.log('Form data:', formData);
    try {
        await store.createUser(formData);
    } catch (error) {
        console.log('Failed to create user:', error);
        if (error.response?.data?.fails) {
            errors.value = error.response.data.fails;
        }
    }
};

onMounted(async () => {
    try {
        const response = await axios.get('/api/v1/positions');
        positions.value = response.data.positions;
    } catch (error) {
        console.error('Failed to fetch positions:', error);
    }
});
</script>

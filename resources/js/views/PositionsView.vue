<template>
    <v-container>
        <h1 class="text-h4 mb-4">Available Positions</h1>

        <v-row v-if="store.loading">
            <v-col v-for="n in 4" :key="n" cols="12" sm="6" md="4">
                <v-skeleton-loader type="card"></v-skeleton-loader>
            </v-col>
        </v-row>

        <v-row v-else>
            <v-col v-for="position in store.positions" :key="position.id" cols="12" sm="6" md="4">
                <v-card class="pa-3" color="grey-darken-4">
                    <v-card-title>{{ position.name }}</v-card-title>
                    <v-card-subtitle>ID: {{ position.id }}</v-card-subtitle>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { onMounted } from 'vue';
import { usePositionsStore } from '@/stores/positionStore';

const store = usePositionsStore();

onMounted(() => {
    store.fetchPositions();
});
</script>

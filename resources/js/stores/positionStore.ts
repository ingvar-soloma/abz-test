import { defineStore } from 'pinia';
import axios from 'axios';

export const usePositionsStore = defineStore('positions', {
    state: () => ({
        positions: [],
        loading: false,
    }),
    actions: {
        async fetchPositions() {
            this.loading = true;
            try {
                const response = await axios.get('/api/v1/positions');
                this.positions = response.data.positions;
            } catch (error) {
                console.error('Failed to fetch positions:', error);
            } finally {
                this.loading = false;
            }
        },
    },
});

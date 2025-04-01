import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', {
    state: () => ({
        users: [],
        page: 1,
        totalPages: 1,
        loading: false,
    }),
    actions: {
        async fetchUsers() {
            if (this.loading || this.page > this.totalPages) return;

            this.loading = true;
            try {
                const response = await axios.get(`/api/v1/users?page=${this.page}`);
                if (response.data.success) {
                    this.users.push(...response.data.users); // Append new users
                    this.totalPages = response.data.total_pages;
                    this.page++;
                }
            } catch (error) {
                console.error("Error fetching users:", error);
            } finally {
                this.loading = false;
            }
        },
        async createUser(userData) {
            this.loading = true;
            this.successMessage = '';
            this.errorMessage = '';

            try {
                await axios.post('/api/v1/users', userData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });
                this.successMessage = 'User successfully created!';
            } catch (error) {
                this.errorMessage = error.response?.data?.message || 'Something went wrong!';
            } finally {
                this.loading = false;
            }
        },
    }
});

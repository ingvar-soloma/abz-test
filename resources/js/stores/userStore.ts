import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', {
    state: () => ({
        users: [],
        page: 1,
        totalPages: 1,
        loading: false,
        token: null, // Store token here
        successMessage: '',
        errorMessage: '',
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

        async fetchToken(): Promise<string> {
            this.loading = true;
            try {
                const response = await axios.get('/api/v1/token');
                this.token = response.data.token;
            } catch (error) {
                console.error("Failed to fetch token:", error);
            } finally {
                this.loading = false;
            }

            return this.token;
        },

        useToken(token: string) {
            this.token = token;
        },

        async createUser(userData) {
            // if (!this.token) {
            //     this.errorMessage = 'Token is missing. Please get a token first.';
            //     throw new Error('Token is required');
            // }

            this.loading = true;
            this.successMessage = '';
            this.errorMessage = '';

            try {
                await axios.post('/api/v1/users', userData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${this.token}`,
                    },
                });
                this.successMessage = 'User successfully created!';
            } catch (error) {
                if (error.response?.data?.fails) {
                    throw error;
                }
                this.errorMessage = error.response?.data?.message || 'Something went wrong!';
            } finally {
                this.loading = false;
            }
        },
    }
});

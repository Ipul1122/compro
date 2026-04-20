// src/stores/category.js
import { defineStore } from 'pinia';
import Api from '../api';

export const useCategoryStore = defineStore('category', {
    state: () => ({
        categories: [],
        pagination: {},
        loading: false,
    }),
    actions: {
        async fetchCategories(page = 1, search = '') {
            this.loading = true;
            try {
                const response = await Api.get(`/admin/categories?page=${page}&search=${search}`);
                this.categories = response.data.data;
                this.pagination = response.data.pagination;
            } catch (error) {
                console.error("Gagal mengambil data kategori", error);
            } finally {
                this.loading = false;
            }
        },
        async storeCategory(data) {
            try {
                const response = await Api.post('/admin/categories', data);
                // Refetch the categories after successful creation
                await this.fetchCategories(1);
                return response;
            } catch (error) {
                throw error;
            }
        },
        async updateCategory(id, data) {
            return await Api.put(`/admin/categories/${id}`, data);
        },
        async destroyCategory(id) {
            return await Api.delete(`/admin/categories/${id}`);
        },
        async getCategoryDetail(id) {
            this.loading = true;
            try {
                const response = await Api.get(`/admin/categories/${id}`);
                return response.data.data;
            } catch (error) {
                console.error("Gagal mengambil detail kategori", error);
                throw error;
            } finally {
                this.loading = false;
            }
        },
    }
});
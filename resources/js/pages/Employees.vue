<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs = [
    {
        title: 'Employees Management',
        href: '/karyawan',
    },
];

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const sortField = ref(props.filters.sortField || 'id');
const sortOrder = ref(props.filters.sortOrder || 'asc');
const selectedUser = ref(null);
const showModal = ref(false);

const changeSort = (field) => {
    if (sortField.value === field) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortOrder.value = 'asc';
    }
    fetchUsers();
};

const fetchUsers = () => {
    router.get(
        '/employees',
        {
            search: search.value,
            sortField: sortField.value,
            sortOrder: sortOrder.value,
        },
        { preserveState: true },
    );
};

const openModal = (user) => {
    selectedUser.value = user;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedUser.value = null;
};

const editUser = (user) => {
    router.get(`/employees/${user.id}/edit`);
};

const deleteUser = (user) => {
    if (confirm(`Are you sure you want to delete ${user.name}?`)) {
        router.delete(`/employees/${user.id}`);
    }
};
</script>

<template>
    <Head title="Employees Managements" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="rounded-lg bg-white p-6 shadow-md">
                <div class="mb-4">
                    <input
                        v-model="search"
                        @input="fetchUsers"
                        type="text"
                        placeholder="Search users..."
                        class="w-full rounded border px-3 py-1 text-sm"
                    />
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2">Actions</th>
                                <th class="cursor-pointer p-2" @click="changeSort('id')">
                                    ID <span v-if="sortField === 'id'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                </th>
                                <th class="cursor-pointer p-2" @click="changeSort('name')">
                                    Name <span v-if="sortField === 'name'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                </th>
                                <th class="cursor-pointer p-2" @click="changeSort('email')">
                                    Email <span v-if="sortField === 'email'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                </th>
                                <th class="cursor-pointer p-2" @click="changeSort('created_at')">
                                    Created At <span v-if="sortField === 'created_at'">{{ sortOrder === 'asc' ? '↑' : '↓' }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users.data" :key="user.id" class="cursor-pointer border-t hover:bg-gray-100" @click="openModal(user)">
                                <td class="p-2">
                                    <button @click.stop="editUser(user)" class="mr-2 rounded bg-blue-500 px-2 py-1 text-xs text-white">Edit</button>
                                    <button @click.stop="deleteUser(user)" class="rounded bg-red-500 px-2 py-1 text-xs text-white">Delete</button>
                                </td>
                                <td class="p-2">{{ user.id }}</td>
                                <td class="p-2">{{ user.name }}</td>
                                <td class="p-2">{{ user.email }}</td>
                                <td class="p-2">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-between text-sm">
                    <button
                        @click="router.get(users.prev_page_url, {}, { preserveState: true })"
                        :disabled="!users.prev_page_url"
                        class="rounded bg-gray-300 px-3 py-1 disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <span class="px-3 py-1">Page {{ users.current_page }} of {{ users.last_page }}</span>
                    <button
                        @click="router.get(users.next_page_url, {}, { preserveState: true })"
                        :disabled="!users.next_page_url"
                        class="rounded bg-gray-300 px-3 py-1 disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>

    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="w-80 rounded-lg bg-white p-4 text-sm shadow-lg">
            <h2 class="mb-2 text-lg font-bold">Employee Details</h2>
            <p><strong>ID:</strong> {{ selectedUser.id }}</p>
            <p><strong>Name:</strong> {{ selectedUser.name }}</p>
            <p><strong>Email:</strong> {{ selectedUser.email }}</p>
            <p><strong>Created At:</strong> {{ new Date(selectedUser.created_at).toLocaleDateString() }}</p>
            <button @click="closeModal" class="mt-3 rounded bg-red-500 px-3 py-1 text-white">Close</button>
        </div>
    </div>
</template>

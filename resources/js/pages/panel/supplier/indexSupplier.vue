<template>
    <Head title="supplier"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <TableSupplier
                    :supplier-list="principal.supplierList"
                    :supplier-paginate="principal.paginacion"
                    @page-change="handlePageChange"
                    @open-modal="getIdSupplier"
                    @open-modal-delete="openDeleteModal"
                    :loading="principal.loading"
                />
                <EditSupplier
                    :supplier-data="principal.supplierData"
                    :modal="principal.stateModal.update"
                    @emit-close="closeModal"
                    @update-supplier="emitUpdateSupplier"
                />
                <DeleteSupplier
                    :modal="principal.stateModal.delete"
                    :supplier-id="principal.idSupplier"
                    @close-modal="closeModalDelete"
                    @delete-supplier="emitDeleteSupplier"
                />
            </div>
        </div>
    </AppLayout>
</template>
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import TableSupplier from './components/tableSupplier.vue';
import { SupplierUpdateRequest } from './interface/Supplier';
import DeleteSupplier from './components/deleteSupplier.vue';
import EditSupplier from './components/editSupplier.vue';
import { useSupplier } from '@/composables/useSupplier';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
{
        title: 'crear proveedor',
        href: '/panel/suppliers/create',
    },
    {
        title: 'Exportar',
        href: '/panel/suppliers/export',
    },
    {
        title: 'proveedores',
        href: '/panel/suppliers',
    },
];

onMounted(() => {
    loadingSuppliers();
});

const {principal,loadingSuppliers, getSupplierById, updateSupplier, deleteSupplier} = useSupplier();

// get pagination
const handlePageChange = (page: number) => {
    console.log(page);
    loadingSuppliers(page);
};
// get supplier by id for edit
const getIdSupplier = (id: number) => {
    getSupplierById(id);
};
// close modal
const closeModal = (open: boolean) => {
    principal.stateModal.update = open;
};
// close modal delete
const closeModalDelete = (open: boolean) => {
    principal.stateModal.delete = open;
};

// update supplier
const emitUpdateSupplier = (supplier: SupplierUpdateRequest, id_supplier: number) => {
    updateSupplier(id_supplier, supplier);
};

// delete supplier
const openDeleteModal = (supplierId: number) => {
    principal.stateModal.delete = true;
    principal.idSupplier = supplierId;
    console.log('Eliminar proveedor con ID:', supplierId);
};
// delete supplier
const emitDeleteSupplier = (supplierId: number) => {
    deleteSupplier(supplierId);
};
</script>
<style lang="css" scoped></style>
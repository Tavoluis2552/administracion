<template>
    <Head title="clientType"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <TableClientType 
                    :client-type-list="principal.clientTypeList"
                    :client-type-paginate="principal.paginacion"
                    @page-change="handlePageChange"
                    @open-modal="getIdClientType"
                    @open-modal-delete="openDeleteModal"
                    :loading="principal.loading"
                />
                <EditClientType
                    :client-type-data="principal.clientTypeData"
                    :modal="principal.stateModal.update"
                    @emit-close="closeModal"
                    @update-client-type="emitUpdateClientType"
                />
                <DeleteClientType
                    :modal="principal.stateModal.delete"
                    :client-type-id="principal.idClientType"
                    @close-modal="closeModalDelete"
                    @delete-client-type="emitDeleteClientType"
                />
            </div>
        </div>
    </AppLayout>
</template>
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem } from '@/types';
import { useClientType } from '@/composables/useClientType';
import TableClientType from './components/tableClientType.vue';
import DeleteClientType from './components/deleteClientType.vue';
import { onMounted } from 'vue';
import { ClientTypeUpdateRequest } from './interface/ClientType';
import EditClientType from './components/editClientType.vue';

const breadcrumbs: BreadcrumbItem[] = [
{
        title: 'Crear tipo de cliente',
        href: '/panel/clientTypes/create',
    },
    {
        title: 'Exportar',
        href: '/panel/clientTypes/export',
    },
    {
        title: 'Tipos de cliente',
        href: '/panel/clientTypes',
    },
];

onMounted(() => {
    loadingClientTypes();
});

const {principal, loadingClientTypes, getClientTypeById, updateClientType, deleteClientType} = useClientType();

// get pagination
const handlePageChange = (page: number) => {
    console.log(page);
    loadingClientTypes(page);
};

// get clientType by id for edit
const getIdClientType = (id: number) => {
    getClientTypeById(id);
};

// close modal
const closeModal = (open: boolean) => {
    principal.stateModal.update = open;
};

// close modal delete
const closeModalDelete = (open: boolean) => {
    principal.stateModal.delete = open;
};

//update clientType
const emitUpdateClientType = (clientType: ClientTypeUpdateRequest, id_clientType: number) => {
    updateClientType(id_clientType, clientType);
};

// delete clientType
const openDeleteModal = (clientTypeid: number) => {
    principal.stateModal.delete = true;
    principal.idClientType = clientTypeid;
    console.log('Eliminar tipo de cliente con ID:', clientTypeid);
};

// delete clientType
const emitDeleteClientType = (clientTypeId: number) => {
    deleteClientType(clientTypeId);
};
</script>
<style lang="css" scoped></style>
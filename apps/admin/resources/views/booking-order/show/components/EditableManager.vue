<script setup lang="ts">

import { computed, onMounted } from 'vue'

import EditableSelect from 'gts-components/Editable/EditableSelect'

import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { useAdministratorGetAPI } from '~api/administrator'

const { data: administrators, execute: fetchAdministrators } = useAdministratorGetAPI()

const orderStore = useOrderStore()

const managerId = computed(() => orderStore.bookingManagerId)
const selectOptions = computed(
  () => administrators.value?.map(({ id, presentation }) => ({ value: id, label: presentation })) || [],
)

onMounted(() => fetchAdministrators())

</script>

<template>
  <EditableSelect
    id="booking-manager"
    :value="managerId"
    :items="selectOptions"
    required
    :show-empty-item="false"
    @change="orderStore.updateManager"
  />
</template>

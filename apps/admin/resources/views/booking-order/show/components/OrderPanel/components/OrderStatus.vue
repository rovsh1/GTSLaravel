<script setup lang="ts">
import { computed } from 'vue'

import StatusSelect from '~resources/views/booking/shared/components/StatusSelect.vue'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { OrderStatusResponse } from '~api/order/models'
import { OrderAvailableActionsResponse } from '~api/order/status'

const orderStore = useOrderStore()

const order = computed(() => orderStore.order)
const statuses = computed<OrderStatusResponse[] | null>(() => orderStore.statuses)
const availableActions = computed<OrderAvailableActionsResponse | null>(() => orderStore.availableActions)
const isAvailableActionsFetching = computed<boolean>(() => orderStore.isAvailableActionsFetching)
const isStatusUpdateFetching = computed(() => orderStore.isStatusUpdateFetching)

const handleStatusChange = async (value: number) => {
  await orderStore.changeStatus(value)
}

</script>

<template>
  <StatusSelect
    v-if="order && statuses"
    v-model="order.status"
    :statuses="statuses"
    :available-statuses="availableActions?.statuses || null"
    :is-loading="isStatusUpdateFetching || isAvailableActionsFetching"
    @change="handleStatusChange"
  />
</template>

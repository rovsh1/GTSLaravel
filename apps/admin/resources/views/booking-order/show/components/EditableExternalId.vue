<script setup lang="ts">

import { computed } from 'vue'

import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import EditableTextInput from '~components/Editable/EditableTextInput.vue'

const orderStore = useOrderStore()

const externalId = computed(() => orderStore.order?.externalId || undefined)

</script>

<template>
  <EditableTextInput
    type="text"
    :value="externalId"
    :can-edit="true"
    empty-value="<Пусто>"
    @change="orderStore.updateExternalId"
  />
</template>

<script setup lang="ts">

import { computed } from 'vue'

import EditableTextInput from 'gts-components/Editable/EditableTextInput'

import { useOrderStore } from '~resources/views/booking-order/show/store/order'

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

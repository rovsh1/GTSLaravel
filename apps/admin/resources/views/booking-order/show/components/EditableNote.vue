<script setup lang="ts">

import { computed } from 'vue'

import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import EditableTextarea from '~components/Editable/EditableTextarea.vue'

const orderStore = useOrderStore()

const note = computed(() => orderStore.order?.note || undefined)

</script>

<template>
  <EditableTextarea
    :value="note"
    :can-edit="true"
    empty-value="<Пусто>"
    @change="orderStore.updateNote"
  />
</template>

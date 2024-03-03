<script setup lang="ts">

import { computed } from 'vue'

import EditableTextarea from 'gts-components/Editable/EditableTextarea'

import { useBookingStore } from '~resources/views/booking/shared/store/booking'

const bookingStore = useBookingStore()

const note = computed(() => bookingStore.booking?.note || undefined)

</script>

<template>
  <EditableTextarea
    :value="note"
    :can-edit="true"
    empty-value="<Пусто>"
    @change="bookingStore.updateNote"
  />
</template>

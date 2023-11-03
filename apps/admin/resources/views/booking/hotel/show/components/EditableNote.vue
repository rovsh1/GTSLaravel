<script setup lang="ts">

import { computed } from 'vue'

import { useBookingStore } from '~resources/views/booking/hotel/show/store/booking'

import EditableTextarea from '~components/Editable/EditableTextarea.vue'

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

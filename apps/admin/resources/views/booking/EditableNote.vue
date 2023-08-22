<script setup lang="ts">

import { computed } from 'vue'

import { useBookingStore } from '~resources/views/hotel-booking/show/store/booking'

import EditableTextarea from '~components/Editable/EditableTextarea.vue'

const bookingStore = useBookingStore()

const canEdit = computed(() => Boolean(bookingStore.availableActions?.isEditable))
const note = computed(() => bookingStore.booking?.note || undefined)

</script>

<template>
  <EditableTextarea
    :value="note"
    :can-edit="canEdit"
    empty-value="<Пусто>"
    @change="bookingStore.updateNote"
  />
</template>

<script setup lang="ts">

import { computed, onMounted } from 'vue'

import { useBookingStore } from '~resources/views/service-booking/show/store/booking'

import { useAdministratorGetAPI } from '~api/administrator'

import EditableSelect from '~components/Editable/EditableSelect.vue'

const { data: administrators, execute: fetchAdministrators } = useAdministratorGetAPI()

const bookingStore = useBookingStore()

const managerId = computed(() => bookingStore.bookingManagerId)
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
    @change="bookingStore.updateManager"
  />
</template>

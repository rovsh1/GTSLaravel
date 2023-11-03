import { computed, onMounted, ref } from 'vue'

import { useAdministratorGetAPI } from '~api/administrator'
import { updateManager as executeUpdateManager } from '~api/booking/hotel'

export const useBookingManager = (bookingId: number, managerId: number) => {
  const bookingManagerId = ref(managerId)

  const { data: administrators, execute: fetchAdministrators } = useAdministratorGetAPI()

  const selectOptions = computed(
    () => administrators.value?.map(({ id, presentation }) => ({ value: id, label: presentation })) || [],
  )

  const updateManager = async (id: number) => {
    await executeUpdateManager({ bookingID: bookingId, managerId: id })
    bookingManagerId.value = Number(id)
  }

  onMounted(() => fetchAdministrators())

  return {
    managerId: bookingManagerId,
    managerSelectOptions: selectOptions,
    updateManager,
  }
}

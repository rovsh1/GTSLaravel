<script setup lang="ts">

import { computed, MaybeRef, onMounted, ref, unref, watch } from 'vue'

import { z } from 'zod'

import CarModal from '~resources/views/booking/components/CarModal.vue'
import CarsTable from '~resources/views/booking/components/CarsTable.vue'
import InfoBlock from '~resources/views/booking/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/components/InfoBlock/InfoBlockTitle.vue'
import { CarFormData } from '~resources/views/booking/lib/data-types'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import { useBookingStore } from '~resources/views/service-booking/show/store/booking'

import { BookingDetails } from '~api/booking/service'
import { addBookingCar, updateBookingCar } from '~api/booking/service/cars'
import { Car, useGetSupplierCarsAPI } from '~api/supplier/cars'

import { formatDateToAPIDate, parseAPIDateToJSDate } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'

import EditableDateInput from '~components/Editable/EditableDateInput.vue'
import EditableTextInput from '~components/Editable/EditableTextInput.vue'
import IconButton from '~components/IconButton.vue'

const { bookingID } = requestInitialData('view-initial-data-service-booking', z.object({
  bookingID: z.number(),
}))

const bookingStore = useBookingStore()

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const bookingDetails = computed<BookingDetails | null>(() => bookingStore.booking?.details || null)

const availableCars = ref<Car[]>([])

const getDefaultCarForm = () => ({})
const carForm = ref<Partial<CarFormData>>(getDefaultCarForm())

const modalSettings = {
  add: {
    title: 'Добавление автомобиля',
    handler: async (request: MaybeRef<Required<CarFormData>>) => {
      const preparedRequest = unref(request)
      const payload = { bookingID, ...preparedRequest, babyCount: 0 }
      await addBookingCar(payload)
      await bookingStore.fetchBooking()
    },
  },
  edit: {
    title: 'Редактирование автомобиля',
    handler: async (request: MaybeRef<Required<CarFormData>>) => {
      const preparedRequest = unref(request)
      const payload = { bookingID, ...preparedRequest, babyCount: 0 }
      await updateBookingCar(payload)
      await bookingStore.fetchBooking()
    },
  },
}

const {
  isOpened: isCarModalOpened,
  isLoading: isCarModalLoading,
  title: carModalTitle,
  openAdd: openAddCarModal,
  openEdit: openEditCarModal,
  editableObject: editableCar,
  close: closeCarModal,
  submit: submitCarModal,
} = useEditableModal<Required<CarFormData>, Required<CarFormData>, Partial<CarFormData>>(modalSettings)

const handleChangeDetails = async (field: string, value: any) => {
  await bookingStore.updateDetails(field, value)
}

const getAvailableCars = async () => {
  const supplierId = bookingStore.booking?.details?.serviceInfo.supplierId
  if (!supplierId) {
    availableCars.value = []
  } else {
    const { data: supplierCars, execute: fetchSupplierCars } = useGetSupplierCarsAPI({ supplierId })
    await fetchSupplierCars()
    availableCars.value = supplierCars.value || []
  }
}

watch(bookingDetails, async () => {
  await getAvailableCars()
})

onMounted(async () => {
  await getAvailableCars()
})

</script>

<template>
  {{ availableCars }}
  <div class="d-flex flex-row gap-4">
    <InfoBlock>
      <template #header>
        <InfoBlockTitle title="Параметры размещения" />
      </template>
      <table class="table-params">
        <tbody>
          <tr>
            <th>Номер рейса</th>
            <td>
              <EditableTextInput
                :value="bookingDetails?.flightNumber"
                :can-edit="isEditableStatus"
                type="text"
                @change="value => handleChangeDetails('flightNumber', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Дата вылета</th>
            <td>
              <EditableDateInput
                :value="bookingDetails?.departureDate
                  ? parseAPIDateToJSDate(bookingDetails?.departureDate) : undefined"
                :can-edit="isEditableStatus"
                @change="value => handleChangeDetails('departureDate', value ? formatDateToAPIDate(value) : null)"
              />
            </td>
          </tr>
          <tr>
            <th>Время вылета</th>
            <td>
              <EditableTextInput
                :value="''"
                :can-edit="isEditableStatus"
                type="time"
                @change="value => handleChangeDetails('', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Город вылета</th>
            <td>
              <EditableTextInput
                :value="''"
                :can-edit="isEditableStatus"
                type="text"
                @change="value => handleChangeDetails('', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Табличка для встречи</th>
            <td>
              <EditableTextInput
                :value="''"
                :can-edit="isEditableStatus"
                type="text"
                @change="value => handleChangeDetails('', value)"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </InfoBlock>

    <InfoBlock>
      <template #header>
        <div class="d-flex gap-1">
          <InfoBlockTitle title="Список автомобилей" />
          <IconButton v-if="isEditableStatus" icon="add" @click="openAddCarModal" />
        </div>
      </template>
      <CarsTable
        :can-edit="isEditableStatus"
        :booking-cars="bookingDetails?.carBids || []"
        @edit="(car) => { }"
        @delete="(car) => { }"
      />
      <CarModal
        :title-text="carModalTitle"
        :opened="isCarModalOpened"
        :is-fetching="isCarModalLoading"
        :form-data="carForm"
        :available-cars="availableCars"
        @close="closeCarModal"
        @submit="submitCarModal"
        @clear="carForm = getDefaultCarForm()"
      />
    </InfoBlock>
  </div>
</template>

<script setup lang="ts">
import { computed, defineAsyncComponent, onMounted, reactive, ref, shallowRef } from 'vue'

import { z } from 'zod'

import { toPascalCase } from '~resources/js/libs/strings'
import ErrorComponent from '~resources/views/booking/components/ErrorComponent.vue'
import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'
import { DetailsFormData } from '~resources/views/supplier/service/form/components/details/lib/types'

import { useGetBookingDetailsTypesAPI } from '~api/booking/service'
import { createService, updateService } from '~api/supplier/service'

import { requestInitialData } from '~lib/initial-data'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'
import OverlayLoading from '~components/OverlayLoading.vue'

const { service, cancelUrl, supplier } = requestInitialData('view-initial-data-supplier-service', z.object({
  supplier: z.object({
    id: z.number(),
    name: z.string(),
  }),
  service: z.object({
    id: z.number(),
    title: z.string(),
    type: z.number(),
    data: z.object({
      airportId: z.number().optional(),
      cityId: z.number().optional(),
      fromCityId: z.number().optional(),
      toCityId: z.number().optional(),
      returnTripIncluded: z.boolean().optional(),
      railwayStationId: z.number().optional(),
    }),
  }).nullable(),
  cancelUrl: z.string(),
}))

type ServiceFormData = {
  title: string
  type: number | undefined
  details: DetailsFormData | undefined
}

const detailsComponent = shallowRef()

const isSubmittingRequest = ref<boolean>(false)

const { data: BookingDetailsTypes, execute: fetchBookingDetailsTypes } = useGetBookingDetailsTypesAPI()

const serviceTypesOptions = ref<SelectOption[]>([])

const serviceFormData = reactive<ServiceFormData>({
  title: '',
  type: undefined,
  details: undefined,
})

const isValidForm = computed(() => !!serviceFormData.title && !!serviceFormData.type && !!serviceFormData.details)

const setDetailsComponentByServiceType = (typeId: number | undefined) => {
  serviceFormData.type = typeId
  const currentServiceType = BookingDetailsTypes.value?.find((type) => type.id === typeId)
  if (!currentServiceType) {
    detailsComponent.value = undefined
    return
  }
  const ComponentName = toPascalCase(currentServiceType.system_name)
  detailsComponent.value = defineAsyncComponent({
    loader: () => import(`./components/details/${ComponentName}.vue`),
    loadingComponent: OverlayLoading,
    errorComponent: ErrorComponent,
  })
}

const handleSubmitForm = async () => {
  if (!isValidForm.value) return
  isSubmittingRequest.value = true
  const payloadData = {
    supplierId: supplier.id,
    title: serviceFormData.title,
    type: serviceFormData.type as number,
    data: serviceFormData.details as DetailsFormData,
  }
  if (!service) {
    await createService({
      ...payloadData,
    })
  } else {
    await updateService({
      serviceId: service.id,
      ...payloadData,
    })
  }
  isSubmittingRequest.value = false
}

onMounted(async () => {
  await fetchBookingDetailsTypes()
  serviceTypesOptions.value = mapEntitiesToSelectOptions(BookingDetailsTypes.value?.map((type) => ({
    id: type.id,
    name: type.display_name,
  })) || [])
  if (service) {
    serviceFormData.title = service.title
    serviceFormData.type = service.type
    serviceFormData.details = service.data
    setDetailsComponentByServiceType(service.type)
  }
})

</script>

<template>
  <div class="form-group position-relative">
    <OverlayLoading v-if="isSubmittingRequest" />
    <div class="row form-field field-text field-title field-required">
      <label for="form_data_title" class="col-sm-5 col-form-label">Название</label>
      <div class="col-sm-7 d-flex align-items-center">
        <input id="form_data_title" v-model="serviceFormData.title" type="text" class="form-control" required>
      </div>
    </div>
    <div class="row form-field field-bookingservicetype field-type field-required">
      <label for="form_data_type" class="col-sm-5 col-form-label">Тип услуги</label>
      <div class="col-sm-7 d-flex align-items-center">
        <BootstrapSelectBase
          id="form_data_type"
          :options="serviceTypesOptions"
          :value="serviceFormData.type"
          required
          @input="(value: any, event: any) => {
            serviceFormData.details = undefined
            setDetailsComponentByServiceType(value as number)
          }"
        />
      </div>
    </div>
    <div class="position-relative">
      <component
        :is="detailsComponent"
        v-if="detailsComponent"
        :value="service && service.type === serviceFormData.type ? service.data : undefined"
        @form-completed="(value: DetailsFormData | undefined) => {
          serviceFormData.details = value
        }"
      />
    </div>
    <div class="form-buttons">
      <BootstrapButton
        label="Сохранить"
        :disabled="!isValidForm || isSubmittingRequest"
        severity="primary"
        @click="handleSubmitForm"
      />
      <a :href="cancelUrl" :disabled="isSubmittingRequest" class="btn btn-cancel">Отмена</a>
      <div class="spacer" />
      <BootstrapButton
        v-if="!!service"
        :disabled="isSubmittingRequest"
        label="Удалить"
        start-icon="delete"
        variant="outline"
        severity="link"
        @click="() => {}"
      />
    </div>
  </div>
</template>

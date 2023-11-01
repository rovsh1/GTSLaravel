<script setup lang="ts">
import { computed, defineAsyncComponent, onMounted, reactive, ref, shallowRef } from 'vue'

import { z } from 'zod'

import { toPascalCase } from '~resources/js/libs/strings'
import ErrorComponent from '~resources/views/booking/components/ErrorComponent.vue'
import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'
import { DetailsFormData } from '~resources/views/supplier/service/form/components/details/lib/types'

import { useGetBookingDetailsTypesAPI } from '~api/booking/service'

import { requestInitialData } from '~lib/initial-data'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'
import OverlayLoading from '~components/OverlayLoading.vue'

const { supplier, cancelUrl } = requestInitialData('view-initial-data-supplier-service', z.object({
  supplier: z.object({
    id: z.number(),
    name: z.string(),
  }),
  cancelUrl: z.string(),
}))

type ServiceFormData = {
  name: string
  type: number | undefined
  details: DetailsFormData | undefined
}

const detailsComponent = shallowRef()

const { data: BookingDetailsTypes, execute: fetchBookingDetailsTypes } = useGetBookingDetailsTypesAPI()

const serviceTypesOptions = ref<SelectOption[]>([])

const serviceFormData = reactive<ServiceFormData>({
  name: '',
  type: undefined,
  details: undefined,
})

const isValidForm = computed(() => !!serviceFormData.name && !!serviceFormData.type && !!serviceFormData.details)

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

onMounted(async () => {
  await fetchBookingDetailsTypes()
  serviceTypesOptions.value = mapEntitiesToSelectOptions(BookingDetailsTypes.value?.map((type) => ({
    id: type.id,
    name: type.display_name,
  })) || [])
})

</script>

<template>
  <div class="form-group">
    <div class="row form-field field-text field-title field-required">
      <label for="form_data_title" class="col-sm-5 col-form-label">Название</label>
      <div class="col-sm-7 d-flex align-items-center">
        <input id="form_data_title" v-model="serviceFormData.name" type="text" class="form-control" required>
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
        @form-completed="(value: DetailsFormData) => {
          serviceFormData.details = value
        }"
      />
    </div>
    <div class="form-buttons">
      <button type="button" class="btn btn-primary" :disabled="!isValidForm">Сохранить</button>
      <a :href="cancelUrl" class="btn btn-cancel">Отмена</a>
      <div class="spacer" />
    </div>
  </div>
</template>

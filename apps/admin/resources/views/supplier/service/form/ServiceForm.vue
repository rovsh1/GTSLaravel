<script setup lang="ts">
import { computed, defineAsyncComponent, onMounted, reactive, ref, shallowRef } from 'vue'

import { z } from 'zod'

import { toPascalCase } from '~resources/js/libs/strings'
import ErrorComponent from '~resources/views/booking/shared/components/ErrorComponent.vue'
import { mapEntitiesToSelectOptions } from '~resources/views/booking/shared/lib/constants'
import { DetailsFormData } from '~resources/views/supplier/service/form/components/details/lib/types'

import { useGetBookingDetailsTypesAPI } from '~api/booking/service'
import { deleteService } from '~api/supplier/service'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import { SelectOption } from '~components/Bootstrap/lib'
import OverlayLoading from '~components/OverlayLoading.vue'
import SelectComponent from '~components/SelectComponent.vue'

function intTransformator(value: any) {
  return parseInt(value, 10)
}

function boolTransformator(value: any) {
  return !!value
}

const { service, cancelUrl, supplier, titles } = requestInitialData(z.object({
  supplier: z.object({
    id: z.number(),
    name: z.string(),
  }),
  service: z.object({
    id: z.number(),
    title: z.string(),
    type: z.number().transform(intTransformator),
    data: z.object({
      airportId: z.union([z.string().transform(intTransformator), z.number()]).optional(),
      cityId: z.union([z.string().transform(intTransformator), z.number()]).optional(),
      fromCityId: z.union([z.string().transform(intTransformator), z.number()]).optional(),
      toCityId: z.union([z.string().transform(intTransformator), z.number()]).optional(),
      returnTripIncluded: z.union([z.string().transform(boolTransformator), z.boolean()]).optional(),
      railwayStationId: z.union([z.string().transform(intTransformator), z.number()]).optional(),
    }).nullable(),
  }).nullable(),
  cancelUrl: z.string(),
  titles: z.object({
    ru: z.string(),
    en: z.string().nullable(),
    uz: z.string().nullable(),
  }).nullable(),
}))

type TitleLocles = {
  ru: string
  en: string
  uz: string
}

type ServiceFormData = {
  titles: TitleLocles
  type: number | undefined
  details: DetailsFormData | undefined
}

const detailsComponent = shallowRef()

const isSubmittingRequest = ref<boolean>(false)

const { data: BookingDetailsTypes, execute: fetchBookingDetailsTypes } = useGetBookingDetailsTypesAPI()

const serviceTypesOptions = ref<SelectOption[]>([])

const serviceFormData = reactive<ServiceFormData>({
  titles: {
    ru: '',
    en: '',
    uz: '',
  },
  type: undefined,
  details: undefined,
})

const isValidForm = computed(() => !!serviceFormData.titles.ru
&& !!serviceFormData.titles.en
&& !!serviceFormData.titles.uz
&& !!serviceFormData.type
&& !!serviceFormData.details)

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

const setFormMethod = (): string => (service ? 'put' : 'post')

const setFormAction = (): string => (service ? `/supplier/${supplier.id}/services/${service.id}`
  : `/supplier/${supplier.id}/services`)

const handleDeleteAction = async () => {
  if (!service) return
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить услугу?', 'btn-danger')
  if (!isConfirmed) return
  isSubmittingRequest.value = true
  if (isConfirmed) {
    toggleLoading()
    const response = await deleteService({
      supplierId: supplier.id,
      serviceId: service.id,
    })
    if (response && response.data.value) {
      const { url, action } = response.data.value
      if (action === 'redirect') { window.location.href = url }
    }
    isSubmittingRequest.value = false
    toggleClose()
  }
}

onMounted(async () => {
  await fetchBookingDetailsTypes()
  serviceTypesOptions.value = mapEntitiesToSelectOptions(BookingDetailsTypes.value?.map((type) => ({
    id: type.id,
    name: type.display_name,
  })) || [])
  if (service) {
    serviceFormData.titles.ru = titles?.ru || ''
    serviceFormData.titles.en = titles?.en || ''
    serviceFormData.titles.uz = titles?.uz || ''
    serviceFormData.type = service.type
    serviceFormData.details = service.data || undefined
    setDetailsComponentByServiceType(service.type)
  }
})

</script>

<template>
  <form :action="setFormAction()" method="post" enctype="multipart/form-data">
    <div class="form-group position-relative">
      <input type="hidden" name="_method" :value="setFormMethod()" />
      <input type="hidden" name="data[supplier_id]" :value="supplier.id" />
      <div class="row form-field field-text field-title field-required">
        <label for="form_data_title" class="col-sm-5 col-form-label">Название</label>
        <div class="col-sm-7 d-flex align-items-center">
          <div class="field-locale-inputs-wrapper">
            <div class="field-locale-input-wrapper">
              <img src="/images/flag/ru.svg" alt="ru">
              <input
                id="form_data_title_ru"
                v-model="serviceFormData.titles.ru"
                type="text"
                name="data[title][ru]"
                required
                class="field-locale form-control"
              >
            </div>
            <div class="field-locale-input-wrapper">
              <img src="/images/flag/en.svg" alt="en">
              <input
                id="form_data_title_en"
                v-model="serviceFormData.titles.en"
                type="text"
                name="data[title][en]"
                required
                class="field-locale form-control"
              >
            </div>
            <div class="field-locale-input-wrapper">
              <img src="/images/flag/uz.svg" alt="uz">
              <input
                id="form_data_title_uz"
                v-model="serviceFormData.titles.uz"
                type="text"
                name="data[title][uz]"
                required
                class="field-locale form-control"
              >
            </div>
          </div>
        </div>
      </div>
      <div class="row form-field field-bookingservicetype field-type field-required">
        <label for="form_data_type" class="col-sm-5 col-form-label">Тип услуги</label>
        <div class="col-sm-7 d-flex align-items-center">
          <SelectComponent
            name="data[type]"
            :options="serviceTypesOptions"
            :value="serviceFormData.type"
            required
            @change="(value) => {
              serviceFormData.details = undefined
              setDetailsComponentByServiceType(value ? Number(value) : value)
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
        <button type="submit" class="btn btn-primary" :disabled="!isValidForm">Сохранить</button>
        <a :href="cancelUrl" :disabled="isSubmittingRequest" class="btn btn-cancel">Отмена</a>
        <div class="spacer" />
        <BootstrapButton
          v-if="!!service"
          label="Удалить"
          start-icon="delete"
          variant="outline"
          severity="link"
          @click="handleDeleteAction"
        />
      </div>
    </div>
  </form>
</template>

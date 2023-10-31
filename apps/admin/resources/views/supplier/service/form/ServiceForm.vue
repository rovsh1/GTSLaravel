<script setup lang="ts">
import { computed, defineAsyncComponent, onMounted, reactive, ref, shallowRef } from 'vue'

import { toPascalCase } from '~resources/js/libs/strings'
import { mapEntitiesToSelectOptions } from '~resources/views/booking/lib/constants'

import { useGetBookingDetailsTypesAPI } from '~api/booking/service'

import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'

const detailsComponent = shallowRef()

const { data: BookingDetailsTypes, execute: fetchBookingDetailsTypes } = useGetBookingDetailsTypesAPI()

const serviceTypesOptions = ref<SelectOption[]>([])

const serviceFormData = reactive<any>({
  name: '',
  type: 1,
  details: null,
})

const isValidForm = computed(() => !!serviceFormData.name && !!serviceFormData.type && !!serviceFormData.details)

const setDetailsComponentByServiceType = (typeId: number | undefined) => {
  const currentServiceType = BookingDetailsTypes.value?.find((type) => type.id === typeId)
  if (!currentServiceType) {
    detailsComponent.value = undefined
    return
  }
  const ComponentName = toPascalCase(currentServiceType.system_name)
  detailsComponent.value = defineAsyncComponent({
    loader: () => import(`./components/details/${ComponentName}.vue`),
    errorComponent: undefined,
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
            serviceFormData.details = null
            setDetailsComponentByServiceType(value as number)
          }"
        />
      </div>
    </div>
    <component
      :is="detailsComponent"
      v-if="detailsComponent"
      @get-details-form-data="(value: any) => {
        serviceFormData.details = value
      }"
    />
    <div class="form-buttons">
      <button type="button" class="btn btn-primary" :disabled="!isValidForm">Сохранить</button>
      <a href="#" class="btn btn-cancel">Отмена</a>
      <div class="spacer" />
    </div>
  </div>
</template>

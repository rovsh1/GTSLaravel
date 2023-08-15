<script setup lang="ts">

import { computed, nextTick, reactive, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { storeToRefs } from 'pinia'

import { useCityStore } from '~resources/store/city'
import { useCurrencyStore } from '~resources/store/currency'
import ManagerSelect from '~resources/views/hotel-booking/form/components/ManagerSelect.vue'
import { statusOptions } from '~resources/views/hotel-booking/form/lib/constants'
import {
  clientTypeOptions,
  genderOptions,
  mapEntitiesToSelectOptions, residentTypeOptions,
} from '~resources/views/hotel-booking/show/lib/constants'

import { useApplicationEventBus } from '~lib/event-bus'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import BootstrapTabs from '~components/Bootstrap/BootstrapTabs/BootstrapTabs.vue'
import BootstrapTabsLink from '~components/Bootstrap/BootstrapTabs/components/BootstrapTabsLink.vue'
import BootstrapTabsTabContent from '~components/Bootstrap/BootstrapTabs/components/BootstrapTabsTabContent.vue'
import { TabItem } from '~components/Bootstrap/BootstrapTabs/types'
import { showToast } from '~components/Bootstrap/BootstrapToast'
import { SelectOption } from '~components/Bootstrap/lib'
import IconButton from '~components/IconButton.vue'
import OverlayLoading from '~components/OverlayLoading.vue'
import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const legalIndustryOptions = mapEntitiesToSelectOptions([
  { id: 1, name: 'Турагенство' },
  { id: 2, name: 'Туристическая компания' },
])

const legalTypeOptions = mapEntitiesToSelectOptions([
  { id: 1, name: 'OTA' },
  { id: 2, name: 'TA' },
  { id: 3, name: 'TO' },
])

const clientCitySelect2 = ref()
const priceTypesSelect2 = ref()
const clientManagerSelect2 = ref()

const { cities } = storeToRefs(useCityStore())
const cityOptions = computed(() => {
  const citiesOptions: any[] | null = cities.value ? cities.value : []
  return citiesOptions.map(
    (entity) => ({ value: entity.id, label: entity.name, group: entity.country_name }),
  ) as SelectOption[]
})

const { currencies } = storeToRefs(useCurrencyStore())
const currencyOptions = computed(() => (currencies.value ? mapEntitiesToSelectOptions(currencies.value) : []))

const [isOpened, toggleModal] = useToggle()

const waitCreatingClient = ref(false)

const tabsItems = ref<TabItem[]>([{
  name: 'basic-details',
  title: '1. Основные данные',
  isActive: true,
  isRequired: false,
  isDisabled: false,
}, {
  name: 'legal-details',
  title: '2. Данные физ. лица',
  value: 1,
  isActive: false,
  isRequired: false,
  isDisabled: true,
}, {
  name: 'physical-details',
  title: '2. Данные юр. лица',
  value: 2,
  isActive: false,
  isRequired: true,
  isDisabled: true,
}])

const basicData = reactive({
  name: '',
  type: 1,
  cityId: 0,
  status: 0,
  currency: 0,
  managerId: 0,
  priceTypes: [],
})

const legalEntityData = reactive({
  legalName: '',
  legalIndustry: 0,
  legalType: 0,
  legalAddress: '',
  legalBik: '',
  legalBankCity: '',
  legalInn: '',
  legalOkpoCode: '',
  legalCorrAccount: '',
  legalKpp: '',
  legalBankName: '',
  legalCurrentAccount: '',
})

const physicalEntityData = reactive({
  physicalGender: 0,
})

const getActiveTab = computed(() => {
  const activeTab = tabsItems.value.filter((tab) => tab.isActive)
  if (activeTab && activeTab.length) {
    return activeTab[0].name
  }
  return null
})

const isValidData = ($event: any, value: any): boolean => {
  let isValid = false
  if (Array.isArray(value)) {
    isValid = value.length > 0
  } else {
    isValid = !!value
  }
  if (isValid) {
    if ($event) {
      $event.target.classList.remove('is-invalid')
    }
    return true
  } if ($event) {
    $event.target.classList.add('is-invalid')
  }
  return false
}

const validateBaseDataForm = computed(() => (isValidData(null, basicData.name)
  && isValidData(null, basicData.type) && isValidData(null, basicData.cityId)
  && isValidData(null, basicData.currency) && isValidData(null, basicData.priceTypes)))

const validateLegalDataForm = computed(() => (isValidData(null, legalEntityData.legalType)
  && isValidData(null, legalEntityData.legalAddress)))

const switchTab = (currentTab: TabItem) => {
  if (currentTab.isDisabled && !validateBaseDataForm.value) return
  tabsItems.value.forEach((tabItem) => {
    const a = tabItem
    a.isActive = false
  })
  const b = currentTab
  b.isActive = true
}

const eventBus = useApplicationEventBus()

const resetForm = () => {
  basicData.name = ''
  basicData.type = 0
  basicData.cityId = 0
  basicData.status = 0
  basicData.currency = 0
  basicData.managerId = 0
  basicData.priceTypes = []
  legalEntityData.legalName = ''
  legalEntityData.legalIndustry = 0
  legalEntityData.legalType = 0
  legalEntityData.legalAddress = ''
  legalEntityData.legalBik = ''
  legalEntityData.legalBankCity = ''
  legalEntityData.legalInn = ''
  legalEntityData.legalOkpoCode = ''
  legalEntityData.legalCorrAccount = ''
  legalEntityData.legalKpp = ''
  legalEntityData.legalBankName = ''
  legalEntityData.legalCurrentAccount = ''
  physicalEntityData.physicalGender = 0
  clientCitySelect2.value.clearComponentValue()
  priceTypesSelect2.value.clearComponentValue()
  clientManagerSelect2.value.clearManagerComponentValue()
  nextTick(() => {
    $('.select2-hidden-accessible').removeClass('is-invalid')
  })
}

const onModalSubmit = async () => {
  if ((basicData.type === 1 && !validateBaseDataForm.value) || (basicData.type === 2 && !validateLegalDataForm.value)) {
    return
  }
  waitCreatingClient.value = true
  setTimeout(() => {
    showToast({ title: 'Клиент успешно создан' })
    waitCreatingClient.value = false
  }, 2000)

  console.log(basicData)
  console.log(legalEntityData)
  console.log(physicalEntityData)

  const clientId = 123
  eventBus.emit('client-created', { clientId })
  resetForm()
  toggleModal()
}

</script>

<template>
  <BaseDialog :opened="isOpened as boolean" @close="toggleModal(false)" @submit="onModalSubmit">
    <OverlayLoading v-if="waitCreatingClient" />
    <template #title>Создать клиента</template>
    <BootstrapTabs>
      <template #links>
        <template v-for="tab in tabsItems" :key="tab.name">
          <BootstrapTabsLink
            v-if="tab.value ? (((tab.value === 1 && !basicData.type) || basicData.type === tab.value) ? true : false) : true"
            :tab-name="tab.name"
            :is-active="tab.isActive"
            :is-required="tab.isRequired"
            :is-disabled="tab.isDisabled && !validateBaseDataForm"
            @click.prevent="switchTab(tab)"
          >
            {{ tab.title }}
          </BootstrapTabsLink>
        </template>
      </template>
      <template #tabs>
        <BootstrapTabsTabContent tab-name="basic-details" :is-active="tabsItems[0].isActive">
          <form ref="clientGeneralForm" class="tab-content">
            <div class="col-md-12 mt-2">
              <div class="field-required">
                <label class="form-label" for="name">ФИО или название компании</label>
                <input
                  id="name"
                  v-model="basicData.name"
                  class="form-control"
                  required
                  @blur="isValidData($event, basicData.name)"
                  @input="isValidData($event, basicData.name)"
                >
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <BootstrapSelectBase
                id="type"
                label="Тип"
                :options="clientTypeOptions"
                :value="basicData.type"
                required
                @blur="isValidData($event, basicData.type)"
                @input="value => basicData.type = Number(value)"
              />
            </div>

            <div class="col-md-12 mt-2 city-wrapper" style="position: relative;">
              <Select2BaseSelect
                id="client-city"
                ref="clientCitySelect2"
                label="Город"
                :options="cityOptions"
                :value="basicData.cityId"
                :parent="'.city-wrapper'"
                :enable-tags="true"
                required
                :show-empty-item="false"
                @blur="isValidData($event, basicData.cityId)"
                @input="value => basicData.cityId = Number(value)"
              />
            </div>

            <div class="col-md-12 mt-2">
              <BootstrapSelectBase
                id="status"
                label="Статус"
                :options="statusOptions"
                :value="basicData.status"
                :show-empty-item="false"
                @input="value => basicData.status = Number(value)"
              />
            </div>

            <div class="col-md-12 mt-2">
              <BootstrapSelectBase
                id="currency"
                label="Валюта"
                :options="currencyOptions"
                :value="basicData.currency"
                required
                @blur="isValidData($event, basicData.currency)"
                @input="value => basicData.currency = Number(value)"
              />
            </div>

            <div class="col-md-12 mt-2 price-types-wrapper" style="position: relative;">
              <Select2BaseSelect
                id="price-types"
                ref="priceTypesSelect2"
                label="Тариф"
                :options="residentTypeOptions"
                :value="basicData.priceTypes"
                :parent="'.price-types-wrapper'"
                :enable-multiple="true"
                required
                :show-empty-item="false"
                @blur="isValidData($event, basicData.priceTypes)"
                @input="value => basicData.priceTypes = value"
              />
            </div>

            <div class="col-md-12 mt-2 manager-wrapper" style="position: relative;">
              <ManagerSelect
                ref="clientManagerSelect2"
                :value="basicData.managerId"
                parent=".manager-wrapper"
                @input="value => basicData.managerId = value"
              />
            </div>
          </form>
        </BootstrapTabsTabContent>
        <BootstrapTabsTabContent v-if="basicData.type == 2" tab-name="legal-details" :is-active="tabsItems[2].isActive">
          <form ref="clientLegalForm" class="tab-content">
            <div class="col-md-12 mt-2">
              <label for="legal-name">Наименование</label>
              <input id="legal-name" v-model="legalEntityData.legalName" class="form-control">
            </div>

            <div class="col-md-12 mt-2">
              <BootstrapSelectBase
                id="industry"
                label="Индустрия"
                :options="legalIndustryOptions"
                :value="legalEntityData.legalIndustry"
                @input="value => legalEntityData.legalIndustry = Number(value)"
              />
            </div>

            <div class="col-md-12 mt-2">
              <BootstrapSelectBase
                id="legal-type"
                label="Тип"
                :options="legalTypeOptions"
                :value="legalEntityData.legalType"
                required
                @blur="isValidData($event, legalEntityData.legalType)"
                @input="value => legalEntityData.legalType = Number(value)"
              />
            </div>

            <div class="col-md-12 mt-2 pb-3 border-bottom">
              <div class="field-required">
                <label for="legal-address">Адрес</label>
                <input
                  id="legal-address"
                  v-model="legalEntityData.legalAddress"
                  required
                  class="form-control"
                  @blur="isValidData($event, legalEntityData.legalAddress)"
                  @input="isValidData($event, legalEntityData.legalAddress)"
                >
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-bik">БИК</label>
                <input id="legal-bik" v-model="legalEntityData.legalBik" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-bank-city">Город банка</label>
                <input id="legal-bank-city" v-model="legalEntityData.legalBankCity" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-inn">ИНН</label>
                <input id="legal-inn" v-model="legalEntityData.legalInn" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-okpo-code">Код ОКПО</label>
                <input id="legal-okpo-code" v-model="legalEntityData.legalOkpoCode" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-corr-acc">Корреспондентский счет</label>
                <input id="legal-corr-acc" v-model="legalEntityData.legalCorrAccount" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-kpp">КПП</label>
                <input id="legal-kpp" v-model="legalEntityData.legalKpp" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-bank-name">Наименование банка</label>
                <input id="legal-bank-name" v-model="legalEntityData.legalBankName" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-current-acc">Рассчетный счет</label>
                <input id="legal-current-acc" v-model="legalEntityData.legalCurrentAccount" class="form-control">
              </div>
            </div>
          </form>
        </BootstrapTabsTabContent>
        <BootstrapTabsTabContent
          v-else-if="basicData.type == 1"
          tab-name="physical-details"
          :is-active="tabsItems[1].isActive"
        >
          <form ref="clientPhysicalForm" class="tab-content">
            <div class="col-md-12 mt-2">
              <BootstrapSelectBase
                id="gender"
                label="Пол"
                :options="genderOptions"
                :value="physicalEntityData.physicalGender"
                @input="value => physicalEntityData.physicalGender = Number(value)"
              />
            </div>
          </form>
        </BootstrapTabsTabContent>
      </template>
    </BootstrapTabs>
    <template #actions-start>
      <button class="btn btn-default" type="button" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button
        v-if="getActiveTab === 'basic-details'"
        :disabled="!validateBaseDataForm"
        class="btn btn-primary"
        type="button"
        @click="switchTab(tabsItems[basicData.type])"
      >
        Далее
      </button>
      <button
        v-else
        :disabled="(basicData.type == 1 && !validateBaseDataForm) || (basicData.type == 2 && !validateLegalDataForm)"
        class="btn btn-primary"
        type="button"
        @click="onModalSubmit"
      >
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" @click="toggleModal(false)">Отмена</button>
    </template>
  </BaseDialog>

  <IconButton class="btn btn-add" icon="add" @click="toggleModal">
    Создать
  </IconButton>
</template>

<style scoped lang="scss">
.error {
  border: 1px solid red;
}

a.has-required-fields::after {
  content: " *";
  color: #DC493A;
}
</style>

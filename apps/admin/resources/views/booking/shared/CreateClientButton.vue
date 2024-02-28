<script setup lang="ts">

import { computed, nextTick, onMounted, reactive, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { useApplicationEventBus } from 'gts-common/helpers/event-bus'
import { storeToRefs } from 'pinia'

import { tabsItemsSettings } from '~resources/views/booking/hotel/form/lib/composables'
import { statusOptions } from '~resources/views/booking/hotel/form/lib/constants'
import { BasicFormData, LegalEntityFormData, PhysicalEntityFormData } from '~resources/views/booking/hotel/form/lib/types'
import ManagerSelect from '~resources/views/booking/shared/components/ManagerSelect.vue'
import {
  clientTypeOptions,
  genderOptions,
  mapEntitiesToSelectOptions, residentTypeOptions,
} from '~resources/views/booking/shared/lib/constants'

import { createClient, useIndustryListAPI } from '~api/client'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapTabs from '~components/Bootstrap/BootstrapTabs/BootstrapTabs.vue'
import BootstrapTabsLink from '~components/Bootstrap/BootstrapTabs/components/BootstrapTabsLink.vue'
import BootstrapTabsTabContent from '~components/Bootstrap/BootstrapTabs/components/BootstrapTabsTabContent.vue'
import { TabItem } from '~components/Bootstrap/BootstrapTabs/types'
import { showToast } from '~components/Bootstrap/BootstrapToast'
import { SelectOption } from '~components/Bootstrap/lib'
import IconButton from '~components/IconButton.vue'
import OverlayLoading from '~components/OverlayLoading.vue'
import SelectComponent from '~components/SelectComponent.vue'

import { useCityStore } from '~stores/city'
import { useCountryStore } from '~stores/countries'
import { useCurrencyStore } from '~stores/currency'
import { useMarkupGroupStore } from '~stores/markup-group'

import { isDataValid } from '~helpers/form'

const { cities } = storeToRefs(useCityStore())
const cityOptions = computed(() => {
  const citiesOptions = cities.value ? cities.value : []
  return citiesOptions.map(
    (entity) => ({ value: entity.id, label: entity.name, group: entity.country_name }),
  ) as SelectOption[]
})

const { countries } = storeToRefs(useCountryStore())
const countryOptions = computed(() => {
  const countriesOptions = countries.value ? countries.value : []
  return countriesOptions.map(
    (entity) => ({ value: entity.id, label: entity.name }),
  ) as SelectOption[]
})

const { markupGroup } = storeToRefs(useMarkupGroupStore())
const markupGroupOptions = computed(() => {
  const markupGroupsOptions: any[] | null = markupGroup.value ? markupGroup.value : []
  return markupGroupsOptions.map(
    (entity) => ({ value: entity.id, label: entity.name }),
  ) as SelectOption[]
})

const { currencies } = storeToRefs(useCurrencyStore())
const currencyOptions = computed<SelectOption[]>(() => {
  if (!currencies.value) {
    return []
  }
  return currencies.value.map((currency) => ({ value: currency.code_char, label: currency.name }))
})

const { data: industries, execute: fetchIndustries } = useIndustryListAPI()
const legalIndustryOptions = computed(() => (industries.value ? mapEntitiesToSelectOptions(industries.value) : []))

const [isOpened, toggleModal] = useToggle()

const waitCreatingClient = ref(false)

const tabsItems = ref<TabItem[]>(tabsItemsSettings)

const basicData = reactive<BasicFormData>({
  name: '',
  type: 2,
  status: 1,
  currency: null,
  managerId: null,
  residency: null,
  markupGroupId: null,
})

const legalEntityData = reactive<LegalEntityFormData>({
  cityId: null,
  name: null,
  industry: null,
  address: '',
  bik: null,
  bankCity: null,
  inn: null,
  okpoCode: null,
  corrAccount: null,
  kpp: null,
  bankName: null,
  currentAccount: null,
})

const physicalEntityData = reactive<PhysicalEntityFormData>({
  countryId: null,
  gender: null,
})

const getActiveTab = computed(() => {
  const activeTab = tabsItems.value.filter((tab) => tab.isActive)
  if (activeTab && activeTab.length) {
    return activeTab[0].name
  }
  return null
})

const validateBaseDataForm = computed(() => (isDataValid(null, basicData.name)
  && isDataValid(null, basicData.type)
  && isDataValid(null, basicData.currency) && isDataValid(null, basicData.residency)
  && isDataValid(null, basicData.markupGroupId)))

const validateLegalDataForm = computed(() => (isDataValid(null, legalEntityData.name)
  && isDataValid(null, legalEntityData.address) && isDataValid(null, legalEntityData.cityId)))

const validatePhysicalDataForm = computed(() => (isDataValid(null, physicalEntityData.countryId)))

const isFormValid = (): boolean => {
  switch (basicData.type) {
    case 1:
      return !!(validateBaseDataForm.value && validatePhysicalDataForm.value)
    case 2:
      return !!(validateBaseDataForm.value && validateLegalDataForm.value)
    default:
      return false
  }
}

const switchTab = (currentTab: TabItem) => {
  if (currentTab.isDisabled && !validateBaseDataForm.value) return
  tabsItems.value.forEach((tabItem) => {
    const tabItemDuplicate = tabItem
    tabItemDuplicate.isActive = false
  })
  const currentTabDuplicate = currentTab
  currentTabDuplicate.isActive = true
}

const showTabByClientType = (clientType: number | null, tabType: number | undefined): boolean => {
  if (!clientType && tabType && tabType === 1) {
    return true
  }
  if (tabType) {
    if (tabType && tabType === clientType) {
      return true
    }
    return false
  }
  return true
}

const resetForm = () => {
  basicData.name = ''
  basicData.type = 2
  basicData.status = 1
  basicData.currency = null
  basicData.managerId = null
  basicData.residency = null
  basicData.markupGroupId = null
  legalEntityData.cityId = null
  legalEntityData.name = null
  legalEntityData.industry = null
  legalEntityData.address = ''
  legalEntityData.bik = null
  legalEntityData.bankCity = null
  legalEntityData.inn = null
  legalEntityData.okpoCode = null
  legalEntityData.corrAccount = null
  legalEntityData.kpp = null
  legalEntityData.bankName = null
  legalEntityData.currentAccount = null
  physicalEntityData.gender = null
  physicalEntityData.countryId = null
  switchTab(tabsItems.value[0])
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
}

const getClientDataByType = (type: number | null): any => {
  if (type === 1) {
    const physicalClientData: any = {
      ...basicData,
      physical: { ...physicalEntityData },
    }
    return physicalClientData
  } if (type === 2) {
    const legalClientData: any = {
      ...basicData,
      legal: { ...legalEntityData },
    }
    return legalClientData
  }
  return null
}

const eventBus = useApplicationEventBus()

const onModalSubmit = async () => {
  if (!isFormValid() || waitCreatingClient.value) {
    return
  }
  const clientData: any = getClientDataByType(basicData.type)
  if (!clientData) return

  waitCreatingClient.value = true
  const { data: newClient } = await createClient(clientData)
  showToast({ title: 'Клиент успешно создан' })
  waitCreatingClient.value = false

  eventBus.emit('client-created', { clientId: newClient.value?.id })
  resetForm()
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
    toggleModal()
  })
}

onMounted(() => {
  fetchIndustries()
})

</script>

<template>
  <BaseDialog :opened="isOpened as boolean" @keydown.enter="onModalSubmit" @close="toggleModal(false)">
    <OverlayLoading v-if="waitCreatingClient" />
    <template #title>Создать клиента</template>
    <BootstrapTabs>
      <template #links>
        <template v-for="tab in tabsItems" :key="tab.name">
          <BootstrapTabsLink
            v-if="showTabByClientType(basicData.type, tab.value)"
            :tab-name="tab.name"
            :is-active="tab.isActive"
            :is-required="tab.isRequired"
            :is-disabled="tab.isDisabled && (!validateBaseDataForm || (basicData.type === 2 && !legalEntityData.cityId))"
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
              <SelectComponent
                v-if="isOpened"
                :options="clientTypeOptions"
                required
                label="Тип"
                :returned-empty-value="null"
                :value="basicData.type"
                @change="(value, event) => {
                  basicData.type = Number(value)
                  isDataValid(event, value)
                }"
              />
            </div>

            <div class="col-md-12 mt-2">
              <div class="field-required">
                <label class="form-label" for="name">ФИО или название компании</label>
                <input
                  id="name"
                  v-model="basicData.name"
                  class="form-control"
                  required
                  @blur="isDataValid($event.target, basicData.name)"
                  @input="isDataValid($event.target, basicData.name)"
                >
              </div>
            </div>

            <div v-if="basicData.type === 1" class="col-md-12 mt-2">
              <SelectComponent
                v-if="isOpened"
                :options="genderOptions"
                label="Пол"
                :returned-empty-value="null"
                :value="physicalEntityData.gender"
                @change="(value, event) => {
                  physicalEntityData.gender = value ? Number(value) : value
                }"
              />
            </div>

            <div v-if="basicData.type === 1" class="col-md-12 mt-2 country-wrapper">
              <SelectComponent
                v-if="isOpened"
                :options="countryOptions"
                required
                label="Страна (гражданство)"
                :returned-empty-value="null"
                :value="physicalEntityData.countryId"
                :enable-tags="true"
                @change="(value, event) => {
                  physicalEntityData.countryId = Number(value)
                  isDataValid(event, value)
                }"
              />
            </div>

            <div v-if="basicData.type === 2" class="col-md-12 mt-2 city-wrapper">
              <SelectComponent
                v-if="isOpened"
                :options="cityOptions"
                required
                label="Город"
                :returned-empty-value="null"
                :value="legalEntityData.cityId"
                :enable-tags="true"
                @change="(value, event) => {
                  legalEntityData.cityId = Number(value)
                  isDataValid(event, value)
                }"
              />
            </div>

            <div class="col-md-12 mt-2">
              <SelectComponent
                v-if="isOpened"
                :options="statusOptions"
                label="Статус"
                :returned-empty-value="null"
                :value="basicData.status"
                @change="(value, event) => {
                  basicData.status = value ? Number(value) : value
                }"
              />
            </div>

            <div class="col-md-12 mt-2">
              <SelectComponent
                v-if="isOpened"
                :options="currencyOptions"
                required
                label="Валюта"
                :returned-empty-value="null"
                :value="basicData.currency"
                @change="(value, event) => {
                  basicData.currency = value as string
                  isDataValid(event, value)
                }"
              />
            </div>

            <div class="col-md-12 mt-2 price-types-wrapper">
              <SelectComponent
                v-if="isOpened"
                :options="residentTypeOptions"
                required
                label="Тариф"
                :returned-empty-value="null"
                :value="basicData.residency"
                @change="(value, event) => {
                  basicData.residency = value ? Number(value) : value
                  isDataValid(event, value)
                }"
              />
            </div>

            <div class="col-md-12 mt-2">
              <SelectComponent
                v-if="isOpened"
                :options="markupGroupOptions"
                required
                label="Группа наценки"
                :returned-empty-value="null"
                :value="basicData.markupGroupId"
                @change="(value, event) => {
                  basicData.markupGroupId = value ? Number(value) : value
                  isDataValid(event, value)
                }"
              />
            </div>

            <div class="col-md-12 mt-2 manager-wrapper">
              <ManagerSelect
                :value="basicData.managerId"
                @input="(value: any) => basicData.managerId = value"
              />
            </div>
          </form>
        </BootstrapTabsTabContent>
        <BootstrapTabsTabContent v-if="basicData.type == 2" tab-name="legal-details" :is-active="tabsItems[1].isActive">
          <form ref="clientLegalForm" class="tab-content">
            <div class="col-md-12 mt-2">
              <div class="field-required">
                <label for="legal-name">Наименование</label>
                <input
                  id="legal-name"
                  v-model="legalEntityData.name"
                  class="form-control"
                  required
                  @blur="isDataValid($event.target, legalEntityData.name)"
                  @input="isDataValid($event.target, legalEntityData.name)"
                >
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <SelectComponent
                v-if="isOpened"
                :options="legalIndustryOptions"
                label="Индустрия"
                :returned-empty-value="null"
                :value="legalEntityData.industry"
                @change="(value, event) => {
                  legalEntityData.industry = value ? Number(value) : value
                }"
              />
            </div>

            <div class="col-md-12 mt-2 pb-3 border-bottom">
              <div class="field-required">
                <label for="legal-address">Адрес</label>
                <input
                  id="legal-address"
                  v-model="legalEntityData.address"
                  required
                  class="form-control"
                  @blur="isDataValid($event.target, legalEntityData.address)"
                  @input="isDataValid($event.target, legalEntityData.address)"
                >
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-bik">БИК</label>
                <input id="legal-bik" v-model="legalEntityData.bik" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-bank-city">Город банка</label>
                <input id="legal-bank-city" v-model="legalEntityData.bankCity" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-inn">ИНН</label>
                <input id="legal-inn" v-model="legalEntityData.inn" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-okpo-code">Код ОКПО</label>
                <input id="legal-okpo-code" v-model="legalEntityData.okpoCode" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-corr-acc">Корреспондентский счет</label>
                <input id="legal-corr-acc" v-model="legalEntityData.corrAccount" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-kpp">КПП</label>
                <input id="legal-kpp" v-model="legalEntityData.kpp" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-bank-name">Наименование банка</label>
                <input id="legal-bank-name" v-model="legalEntityData.bankName" class="form-control">
              </div>
            </div>

            <div class="col-md-12 mt-2">
              <div>
                <label for="legal-current-acc">Рассчетный счет</label>
                <input id="legal-current-acc" v-model="legalEntityData.currentAccount" class="form-control">
              </div>
            </div>
          </form>
        </BootstrapTabsTabContent>
      </template>
    </BootstrapTabs>
    <template #actions-start>
      <button class="btn btn-default" type="button" :disabled="waitCreatingClient" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button
        v-if="getActiveTab === 'basic-details' && basicData.type === 2"
        :disabled="!validateBaseDataForm || !legalEntityData.cityId"
        class="btn btn-primary"
        type="button"
        @click="switchTab(tabsItems[basicData.type ? 1 : 0])"
      >
        Далее
      </button>
      <button
        v-else
        :disabled="(basicData.type == 1 && (!validateBaseDataForm || !validatePhysicalDataForm))
          || (basicData.type == 2 && !validateLegalDataForm) || waitCreatingClient"
        class="btn btn-primary"
        type="button"
        @click="onModalSubmit"
      >
        Сохранить
      </button>
      <button
        class="btn btn-cancel"
        type="button"
        :disabled="waitCreatingClient"
        @click="toggleModal(false)"
      >
        Отмена
      </button>
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

.country-wrapper,
.city-wrapper,
.price-types-wrapper,
.manager-wrapper {
  position: relative;
}
</style>

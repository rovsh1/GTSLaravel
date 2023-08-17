import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api/index'

import { getNullableRef } from '~lib/vue'

export interface Client {
  id: number
  name: string
  type: number
  is_legal: boolean
}

export interface Industry {
  id: number
  name: string
}

interface CreateClientPayload {
  name: string
  type: number
  cityId: number
  currency: number
  priceTypes: string[]
  status?: number
  managerId?: number
}

interface CreatePhysicalClientPayload extends CreateClientPayload {
  physical: {
    gender?: number
  }
}

interface CreateLegalClientPayload extends CreateClientPayload {
  legal: {
    name?: string
    industry?: number
    type: number
    address: string
    bik?: string
    bankCity?: string
    inn?: string
    okpoCode?: string
    corrAccount?: string
    kpp?: string
    bankName?: string
    currentAccount?: string
  }
}

export const createClient = (props: MaybeRef<CreatePhysicalClientPayload | CreateLegalClientPayload>) =>
  useAdminAPI(
    props,
    () => '/client/create/dialog',
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<CreateClientPayload, any>(
        props,
        (payload: CreateClientPayload): any => payload,
      ),
    )), 'application/json')
    .json<Client>()

export const useIndustryListAPI = () =>
  useAdminAPI({ }, () => '/client/industry/list')
    .get()
    .json<Industry[]>()

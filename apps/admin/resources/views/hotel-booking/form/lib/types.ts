export interface LegalEntityFormData {
  legalName: string
  legalIndustry: number
  legalType: number
  legalAddress: string
  legalBik: string
  legalBankCity: string
  legalInn: string
  legalOkpoCode: string
  legalCorrAccount: string
  legalKpp: string
  legalBankName: string
  legalCurrentAccount: string
}

export interface PhysicalEntityFormData {
  physicalGender: number
}

export interface BasicFormData {
  name: string
  type: number
  cityId: number
  status: number
  currency: number
  managerId: number
  priceTypes: string[]
}

export type ClientFormDataUnion = Omit<BasicFormData, 'priceTypes'> & {
  priceTypes: number[]
  legalEntityData: LegalEntityFormData | null
  physicalEntityData: PhysicalEntityFormData | null
}

export type ClientFormData = Partial<ClientFormDataUnion>

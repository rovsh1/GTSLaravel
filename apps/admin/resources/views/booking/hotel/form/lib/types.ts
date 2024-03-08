export interface LegalEntityFormData {
  cityId: number | null
  name: string | null
  industry: number | null
  address: string
  bik: string | null
  bankCity: string | null
  inn: string | null
  okpoCode: string | null
  corrAccount: string | null
  kpp: string | null
  bankName: string | null
  currentAccount: string | null
}

export interface PhysicalEntityFormData {
  countryId: number | null
  gender: number | null
}

export interface BasicFormData {
  name: string
  type: number | null
  status: number | null
  currency: string | null
  language: string
  managerId: number | null
  residency: number | null
  markupGroupId: number | null
}

export interface CreatePhysicalClient extends BasicFormData {
  physical: PhysicalEntityFormData
}

export interface CreateLegalClient extends BasicFormData {
  legal: LegalEntityFormData
}

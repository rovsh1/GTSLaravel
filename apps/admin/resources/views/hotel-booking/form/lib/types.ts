export interface LegalEntityFormData {
  name: string | null
  industry: number | null
  type: number | null
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
  gender: number | null
}

export interface BasicFormData {
  name: string
  type: number | null
  cityId: number | null
  status: number | null
  currency: number | null
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

export type Select2Option = {
  id: string | number | ''
  text: string
}

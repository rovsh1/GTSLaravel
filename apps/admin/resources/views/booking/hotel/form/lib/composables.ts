export interface TabItem {
  name: string
  value?: number
  title: string
  isActive: boolean
  isRequired: boolean
  isDisabled: boolean
}

export const tabsItemsSettings: TabItem[] = [{
  name: 'basic-details',
  title: '1. Основные данные',
  isActive: true,
  isRequired: false,
  isDisabled: false,
}, {
  name: 'physical-details',
  title: '2. Данные юр. лица',
  value: 2,
  isActive: false,
  isRequired: true,
  isDisabled: true,
}]

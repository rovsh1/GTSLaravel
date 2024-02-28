import { TabItem } from '~components/Bootstrap/BootstrapTabs/types'

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

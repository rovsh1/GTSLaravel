import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { useMarkupGroupListAPI } from '~api/markup-group'

export const useMarkupGroupStore = defineStore('markup-group', () => {
  const { data: markupGroup, execute: fetchMarkupGroup } = useMarkupGroupListAPI()
  onMounted(() => fetchMarkupGroup())
  return {
    markupGroup,
  }
})

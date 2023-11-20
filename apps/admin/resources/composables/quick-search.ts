import { useUrlSearchParams } from '@vueuse/core'

export interface UseSearchReturn {
  quickSearch: string | undefined
  isEmpty: boolean
}

export const useQuickSearch = (): UseSearchReturn => {
  const query = useUrlSearchParams('history')

  const quickSearch = query.quicksearch as string | undefined
  const isEmpty = quickSearch ? quickSearch.trim().length === 0 : true

  return {
    quickSearch,
    isEmpty,
  }
}

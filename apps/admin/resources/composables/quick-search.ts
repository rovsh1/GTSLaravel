import { useUrlSearchParams } from '@vueuse/core'

export interface UseSearchReturn {
  quickSearch: string | undefined
}

export const useQuickSearch = (): UseSearchReturn => {
  const query = useUrlSearchParams('history')

  return {
    quickSearch: query.quicksearch as string | undefined,
  }
}

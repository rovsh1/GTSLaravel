import { onMounted, onUnmounted, ref } from 'vue'

export type MenuPosition = {
  date: Date
  dayKey: string
  roomTypeID: number
}

export const useDayMenu = () => {
  const dayMenuRef = ref<HTMLElement | null>(null)
  const dayMenuPosition = ref<MenuPosition | null>(null)

  const openDayMenu = (params: { trigger: HTMLElement } & MenuPosition) => {
    const {
      trigger,
      date,
      dayKey,
      roomTypeID,
    } = params
    dayMenuRef.value = trigger
    dayMenuPosition.value = {
      date,
      dayKey,
      roomTypeID,
    }
  }

  const closeDayMenu = () => {
    dayMenuRef.value = null
    dayMenuPosition.value = null
  }

  onMounted(() => {
    window.addEventListener('scroll', closeDayMenu)
  })

  onUnmounted(() => {
    window.removeEventListener('scroll', closeDayMenu)
  })

  return {
    dayMenuRef,
    dayMenuPosition,
    openDayMenu,
    closeDayMenu,
  }
}

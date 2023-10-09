import { onMounted, onUnmounted, ref } from 'vue'

export type MenuPosition = {
  // date: Date
  dayKey: string
  roomTypeID: number
}

export type ElementTrigger = {
  trigger: HTMLElement
}

export type MenuParams = ElementTrigger & MenuPosition

export const useDayMenu = () => {
  const dayMenuRef = ref<HTMLElement | null>(null)
  const dayMenuPosition = ref<MenuPosition | null>(null)

  const openDayMenu = (params: MenuParams) => {
    const {
      trigger,
      // date,
      dayKey,
      roomTypeID,
    } = params as MenuPosition & ElementTrigger
    dayMenuRef.value = trigger
    dayMenuPosition.value = {
      // date,
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

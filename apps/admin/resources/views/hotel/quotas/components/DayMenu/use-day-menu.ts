import { onMounted, onUnmounted, ref } from 'vue'

export type MenuPosition = {
  dayKey: string
  roomTypeID: number
}

export const useDayMenu = () => {
  const menuRef = ref<HTMLElement | null>(null)
  const menuPosition = ref<MenuPosition | null>(null)

  const openDayMenu = (params: { trigger: HTMLElement } & MenuPosition) => {
    const {
      trigger,
      dayKey,
      roomTypeID,
    } = params
    menuRef.value = trigger
    menuPosition.value = {
      dayKey,
      roomTypeID,
    }
  }

  const closeDayMenu = () => {
    menuRef.value = null
    menuPosition.value = null
  }

  onMounted(() => {
    window.addEventListener('scroll', closeDayMenu)
  })

  onUnmounted(() => {
    window.removeEventListener('scroll', closeDayMenu)
  })

  return {
    menuRef,
    menuPosition,
    openDayMenu,
    closeDayMenu,
  }
}

class ApplicationEventBus {
  // eslint-disable-next-line class-methods-use-this
  emit(event: string, payload?: any): void {
    document.dispatchEvent(
      new CustomEvent(event, { detail: payload }),
    )
  }

  // eslint-disable-next-line class-methods-use-this
  on(event: string, callback: (payload: any | undefined) => void): void {
    document.addEventListener(event, (e: CustomEventInit) => callback(e.detail))
  }
}

export const useApplicationEventBus = () => new ApplicationEventBus()

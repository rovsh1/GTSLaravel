export {}

declare global {
    interface Window {
        get_meta_content: (name: string, parse: boolean = false) => string | null
        get_url_parameter: (name: string, url: string) => string | null
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        WindowDialog: (options: any) => void
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        MessageConfirm: (message: string, title: string, fn: any) => void
        MessageBox: (message: string, title: string, type: string) => void
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        google: any
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        is_function: (value: any) => boolean
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        in_array: (value: any, array: any) => boolean
    }
}

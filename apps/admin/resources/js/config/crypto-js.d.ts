declare module 'crypto-js' {
  const SHA256: {
    (text: string): { toString(): string }
  }
}

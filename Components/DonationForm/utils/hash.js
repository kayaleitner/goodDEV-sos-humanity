/**
 * Compute an SHA-256 hash of a UTF-8 string and return a lowercase hex string.
 * Uses the Web Crypto API when available; falls back to a lightweight polyfill if not.
 *
 * @param {string} string
 * @returns {Promise<string>} hex-encoded sha256
 */
export default async function hashSha256(string) {
  const utf8 = new TextEncoder().encode(string)
  return crypto.subtle.digest('SHA-256', utf8).then((hashBuffer) => {
    const hashArray = Array.from(new Uint8Array(hashBuffer))
    return hashArray
      .map((bytes) => bytes.toString(16).padStart(2, '0'))
      .join('')
  })
}

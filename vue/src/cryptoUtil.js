import CryptoJS from 'crypto-js';

const secretKey = 'ONeiros93232';

export function encrypt(plaintext) {
  return CryptoJS.AES.encrypt(plaintext, secretKey).toString();
}

export function decrypt(ciphertext) {
  const bytes = CryptoJS.AES.decrypt(ciphertext, secretKey);
  return bytes.toString(CryptoJS.enc.Utf8);
}

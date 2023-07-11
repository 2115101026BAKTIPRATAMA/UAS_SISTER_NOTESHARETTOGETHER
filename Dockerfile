# Menggunakan image base node.js yang sesuai dengan versi yang Anda butuhkan
FROM node:14

# Menetapkan direktori kerja di dalam container
WORKDIR /app

# Menyalin file package.json dan package-lock.json ke direktori kerja
COPY package*.json ./

# Menjalankan perintah npm install untuk menginstal dependensi
RUN npm install

# Menyalin seluruh kode sumber aplikasi Anda ke direktori kerja
COPY . .

# Menjalankan perintah untuk memulai webservice API
CMD ["npm", "start"]
